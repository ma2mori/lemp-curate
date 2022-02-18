<?php

namespace Classes;

use Classes\ModelClass;

class FrontClass extends ModelClass
{

	public $_css_path = '/temp/css/';
	public $_js_path = '/temp/js/';

	public function __construct()
	{
		parent::__construct();
		$this->_topic_id     = isset($_GET['id']) ? (int)$_GET['id'] : null;
		$this->_category_id  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
		$this->_tag_id       = isset($_GET['tag_id']) ? (int)$_GET['tag_id'] : null;
		$this->_comment_id   = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : null;
		$this->_comment_body = isset($_POST['comment_body']) ? $_POST['comment_body'] : null;
		$this->_route_name   = basename($_SERVER['PHP_SELF'], '.php');
		$this->setStyleSheet($this->_route_name);
		$this->setJavascript($this->_route_name);
	}

	/**
	 * @return array $list
	 */
	public function getIndexList($current_min_num = 1, $limit = 20)
	{
		$m = new ModelClass;
		$list = [];
		$sql = "
		SELECT
			a.id as id,
			a.topic_id as topic_id,
			a.category_id as category_id,
			a.title as title,
			a.img_src as img_src,
			a.created_at as created_at,
			a.updated_at as updated_at,
			a.deleted_at as deleted_at,
			count(ct.topic_id) as content_num,
			GROUP_CONCAT(DISTINCT tag.name SEPARATOR ',') AS tag
		FROM
		 articles as a
		LEFT OUTER JOIN 
			contents AS ct ON a.topic_id = ct.topic_id
		INNER JOIN article_to_tag as map ON a.topic_id = map.topic_id
		INNER JOIN tags as tag ON map.tag_id = tag.id
		WHERE
		";

		if ($this->_category_id && gettype($this->_category_id) == 'integer') {
			$sql .= " category_id = " . $this->_category_id . " AND ";
		}
		if ($this->_tag_id && gettype($this->_tag_id) == 'integer') {
			$sql .= " tag_id = " . $this->_tag_id . " AND ";
		}

		$sql .= "
			a.deleted_at IS NULL
		GROUP BY
			a.topic_id
		ORDER BY
		 created_at DESC
		";

		$list['item_num'] = count($m->_pdo->query($sql)->fetchAll());
		$list['page_num'] = ceil($list['item_num'] / $limit);

		$sql .= " LIMIT " . $limit . ' OFFSET ' . $current_min_num;
		$stmt = $m->_pdo->prepare($sql);
		$stmt->execute();
		$list['data'] = $stmt->fetchAll();

		return $list;
	}

	/**
	 * @return array $list
	 */
	public function getAllArticleIds()
	{
		$m = new ModelClass;
		$list = [];
		$sql = "
		SELECT
			a.topic_id as topic_id
		FROM
			articles as a
		WHERE
			a.deleted_at IS NULL
		";

		$base = $m->_pdo->query($sql)->fetchAll();
		foreach ($base as $val) {
			$list[] = $val['topic_id'];
		}
		return $list;
	}

	/**
	 * @param string $category_id
	 * @return array $list
	 */
	public function getRelationArticleList($category_id)
	{
		$m = new ModelClass;
		$list = [];
		$sql = "
		SELECT
			a.id as id,
			a.topic_id as topic_id,
			a.category_id as category_id,
			a.title as title,
			a.img_src as img_src,
			a.created_at as created_at,
			a.updated_at as updated_at,
			a.deleted_at as deleted_at
		FROM
			articles as a
		WHERE
			a.category_id = " . $category_id . " AND
			deleted_at IS NULL
		";

		$base = $m->_pdo->query($sql)->fetchAll();
		foreach ($base as $val) {
			if ($val['topic_id'] != $this->_topic_id) {
				$list[] = $val;
			}
		}

		(shuffle($list));
		array_splice($list, 5);

		return $list;
	}

	/**
	 * @param array $topic_ids
	 * @return array $list
	 */
	public function getRecommendArticleList($topic_ids)
	{
		$m = new ModelClass;
		$list = [];
		$sql = "
			SELECT
				*
			FROM
				articles
			WHERE
				topic_id = :topic_id
		";
		foreach($topic_ids as $topic_id){
			$stmt = $m->_pdo->prepare($sql);
			$stmt->bindValue(":topic_id", $topic_id);
			$stmt->execute();
			$list[] = $stmt->fetchAll()[0];
		}
		return $list;
	}

	/**
	 * @param string $topic_id
	 * @return array $match_tags
	 */
	public function getMatchTags($topic_id)
	{
		$m = new ModelClass;
		$match_tags = [];
		$sql = "
			SELECT
				map.tag_id as id
			FROM
				article_to_tag as map
			WHERE
				map.topic_id = :topic_id
		";
		$stmt = $m->_pdo->prepare($sql);
		$stmt->bindValue(":topic_id", $topic_id);
		$stmt->execute();
		$tag_arr = $stmt->fetchAll();

		foreach ($tag_arr as $tag) {
			$match_tags[] = $tag['id'];
		}

		return $match_tags;
	}

	/**
	 * @param string $tag_id
	 * @return string $tag_name
	 */
	public function getTagName($tag_id)
	{
		$m = new ModelClass;
		$sql = "
			SELECT
				tag.name as name
			FROM
				tags as tag
			WHERE
				tag.id = " . $tag_id . "
		";
		$tag_info = $m->_pdo->query($sql)->fetchAll();
		return $tag_info[0]['name'];
	}

	/**
	 * @return array $tag_list
	 */
	public function getTagList()
	{
		$m = new ModelClass;
		$sql = "
		SELECT
			t.tag_id,
			count(*) as count
		FROM
			article_to_tag as t
		GROUP BY
			t.tag_id
		ORDER BY
			count DESC
		";
		$stmt = $m->_pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	/**
	 * @param array $datas
	 * @return array $opt_datas
	 */
	private function optDetailList($datas)
	{

		foreach ($datas as $d) {
			if ($d['anchor']) {
				$datas[$d['anchor'] - 1]['res_anchor_flg'] = true;
			}
		}
		foreach ($datas as $d) {
			if ($d['anchor'] && !isset($d['res_anchor_flg'])) {
				$datas[$d['anchor'] - 1]['res_anchors'][] = $d;
			}
		}

		$opt_datas = [];
		foreach ($datas as $d) {
			$remove_flg = isset($d['res_anchor_flg']) && !isset($d['res_anchors']) ? true : false;
			if ($d['content_id'] == 1 || !$remove_flg) {
				$opt_datas[] = $d;
			}
		}

		$opt_datas2 = [];
		foreach ($opt_datas as $d) {
			$remove_flg = !isset($d['res_anchors']) && ($d['come_lv'] == 3 || $d['come_lv'] == 4) ? true : false;
			if ($d['content_id'] == 1 || !$remove_flg) {
				$opt_datas2[] = $d;
			}
		}

		$opt_datas3 = [];
		foreach ($opt_datas2 as $d) {
			$remove_flg = $d['anchor'] ? true : false;
			if ($d['content_id'] == 1 || !$remove_flg) {
				$opt_datas3[] = $d;
			}
		}

		return $opt_datas3;
	}

	/**
	 * @return array $list
	 */
	public function getDetailInfo()
	{

		$m = new ModelClass;
		$sql = "
		SELECT
		 *
		FROM
		 articles
		WHERE
		 topic_id = :topic_id AND
		 deleted_at IS NULL
		";

		$stmt = $m->_pdo->prepare($sql);
		$stmt->bindValue(":topic_id", $this->_topic_id);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/**
	 * @return array $datas
	 */
	public function getDetailList()
	{

		$m = new ModelClass;

		$sql = "
		SELECT
		 *
		FROM
		 contents
		WHERE
		 topic_id = :topic_id AND
		 deleted_at IS NULL
		";

		$stmt = $m->_pdo->prepare($sql);
		$stmt->bindValue(":topic_id", $this->_topic_id);
		$stmt->execute();

		$datas = $stmt->fetchAll();

		return $this->optDetailList($datas);
	}

	/**
	 * @return array $datas
	 */
	public function getCommentList()
	{
		$m = new ModelClass;

		$sql = "
		SELECT
		 *
		FROM
		 comments
		WHERE
		 topic_id = :topic_id AND
		 deleted_at IS NULL
		";

		$stmt = $m->_pdo->prepare($sql);
		$stmt->bindValue(":topic_id", $this->_topic_id);
		$stmt->execute();

		$datas = $stmt->fetchAll();

		return $datas;
	}

	public function storeComment()
	{
		$m = new ModelClass;
		$colums = [
			'comment_id',
			'n_user_id',
			'topic_id',
			'body',
		];
		$sql = $this->create($colums, 'comments');
		$params = [
			'comment_id' => $this->_comment_id,
			'n_user_id' => $this->createId(),
			'topic_id' => $this->_topic_id,
			'body' => $this->_comment_body,
		];
		$this->postParams($sql, $params);
	}

	public function addArticleView()
	{
		$m = new ModelClass;
		$user_id = $this->createId();
		$columns = [
			'topic_id',
			'n_user_id',
			'total_view',
		];
		$values = [
			'topic_id' => $this->_topic_id,
			'n_user_id' => $user_id,
			'total_view' => 1,
		];
		$sql = $m->create($columns, 'article_views');
		$m->postParams($sql, $values);
	}

	/**
	 * @return bool $bool
	 */
	public function checkArticleView()
	{
		$m = new ModelClass;
		$user_id = $this->createId();
		$sql = "
		SELECT
			*
		FROM
			article_views
		WHERE
			topic_id = '" . $this->_topic_id . "' AND
			n_user_id = '" . $user_id . "'
		";
		$stmt = $m->_pdo->prepare($sql);
		$stmt->execute();
		$datas = $stmt->fetchAll();
		return count($datas) ? true : false;
	}

	/**
	 * @param int $limit
	 * @param bool $daily
	 * @return string $free
	 */
	public function createId($limit = 10, $daily = false, $free = '')
	{
		$daily_md5 = ($daily) ? md5(date('Y-m-d')) : '';
		$free_md5 = ($free) ? md5($free) : md5($_SERVER['REMOTE_ADDR']);
		$id = substr(base64_encode($daily_md5 . $free_md5), 0, $limit);
		return $id;
	}

	public function setStyleSheet($name)
	{
		$css = [];
		$css[] = $this->_css_path . 'app.css';

		switch ($name) {

			case 'index':
				$css[] = $this->_css_path . 'index.css';
				break;

			case 'detail':
				$css[] = $this->_css_path . 'detail.css';
				break;

			default:
				break;
		}

		$this->_css = $css;
		return $this->_css;
	}

	public function getStyleSheet()
	{
		return $this->_css;
	}

	public function setJavascript($name)
	{
		$js = [];
		$js[] = 'https://cdn.jsdelivr.net/npm/lazysizes@5.2.2/lazysizes-umd.min.js';

		switch ($name) {

			case 'index':
				$js[] = $this->_js_path . 'index.js';
				break;

			default:
				break;
		}

		$this->_js = $js;
		return $this->_js;
	}

	public function getJavascript()
	{
		return $this->_js;
	}

	public function getAssets()
	{
		foreach ($this->getStyleSheet() as $val) {
			echo '<link rel="stylesheet" href="' . $val . '?date=' . date('YmdHis') . '" async>';
			// echo '<link rel="stylesheet" href="' . $val. '" async>';
		}
		foreach ($this->getJavascript() as $val) {
			echo '<script defer src="' . $val . '?date=' . date('YmdHis') . '" ></script>';
			// echo '<script defer src="'.$val.'"></script>';
		}
	}
}
