<?php

namespace Classes;

use Classes\ModelClass;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

class GetContentsClass extends ModelClass
{
	public function __construct()
	{
		parent::__construct();
		$this->_get_contents_val = $this->_model_val . 'get_contents ' . $_ENV['TEST_ENV'];

		$this->_articles_fillable = [
			'topic_id',
			'category_id',
			'title',
			'img_src',
			'created_at',
			'updated_at',
			'deleted_at',
		];

		$this->_contents_fillable = [
			'topic_id',
			'content_id',
			'content_body',
			'anchor',
			'come_lv',
			'created_at',
			'updated_at',
			'deleted_at',
		];
	}

	/**
	 * @param string $url
	 * @return object $dom
	 */
	private function htmlConvertObject($url)
	{
		$options = new Options();
		$options->setEnforceEncoding('utf8');
		$dom = new Dom();
		$dom->loadFromUrl($url, $options);
		return $dom;
	}

	/**
	 * @param string $str
	 * @return string $anchor_id
	 */
	private function getAnchorId($str)
	{
		$start     = mb_strpos($str, '&gt;&gt;') + 8;
		$end       = mb_strpos($str, '</span>');
		$anchor_id = mb_substr($str, $start, $end - $start);
		return $anchor_id;
	}

	/**
	 * @return array $topic_id list
	 */
	public function gettedTopicIds()
	{
		$m = new ModelClass;
		$list = [];
		$sql = "
		SELECT
		 topic_id
		FROM
		 articles
		WHERE
		 deleted_at IS NULL
		";
		$ids = $m->_pdo->query($sql)->fetchAll();
		foreach ($ids as $val) {
			$list[] = $val['topic_id'];
		}

		return $list;
	}

	/**
	 * @return array $tag_name list
	 */
	public function getRegisteredTagsName()
	{
		$m = new ModelClass;
		$registered_tags = [];
		$sql = "
			SELECT
				name
			FROM
				tags
			WHERE
				deleted_at IS NULL
		";
		$tags = $m->_pdo->query($sql)->fetchAll();
		foreach ($tags as $val) {
			$registered_tags[] = $val['name'];
		}

		return $registered_tags;
	}


	/**
	 * @param array $match_tags
	 * @return array $tag_id list
	 */
	public function getRegisteredTagsId($match_tags)
	{
		$m = new ModelClass;
		$registered_tags = [];
		$sql = "
			SELECT
				id
			FROM
				tags
			WHERE
				name = :name AND
				deleted_at IS NULL
		";
		foreach ($match_tags as $name) {
			$stmt = $m->_pdo->prepare($sql);
			$stmt->bindValue(":name", $name);
			$stmt->execute();
			$tag_arr = $stmt->fetchAll();
			$registered_tags[] = $tag_arr[0]['id'];
		}

		return $registered_tags;
	}


	/**
	 * @param string $url
	 * @return array $opt_datas
	 */
	public function getArticleList($category_id)
	{
		$url_list  = URL_LIST;
		$url       = $url_list[$category_id];
		$obj       = $this->htmlConvertObject($url);
		$articles  = $obj->find('.main > .topic-list-wrap > .topic-list > li > a');
		$opt_datas = [];

		$getted_topic_ids = $this->gettedTopicIds();

		foreach ($articles as $article) {
			$topic_id     = substr(str_replace('/topics/', '', $article->getAttribute('href')), 0, -1);
			$title        = $article->find('.title')->text;
			$contents_cnt = str_replace('コメント', '', $article->find('.comment span')->nextSibling()->text);
			$img_src      = $article->find('img')->getAttribute('data-src');
			if ($contents_cnt >= 100 && !in_array($topic_id, $getted_topic_ids)) {
				$opt_datas[] = [
					'topic_id' => $topic_id,
					'category_id' => $category_id,
					'title' => $title,
					'img_src' => $img_src,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => null,
					'deleted_at' => null,
				];
			}
		}

		array_splice($opt_datas, 1);

		return $opt_datas[0];
	}

	/**
	 * @param string $topic_id
	 * @return array $opt_datas
	 */
	public function getContentsList($topic_id)
	{
		$url       = 'https://girlschannel.net/topics/' . $topic_id;
		$obj       = $this->htmlConvertObject($url);
		$contents  = $obj->find('.topic-comment .comment-item');
		$opt_datas = [];

		foreach ($contents as $comment) {
			$come_id = str_replace('comment', '', $comment->getAttribute('id'));
			$come_lv = str_replace('body lv', '', $comment->find('.body')->getAttribute('class'));
			$body    = $comment->find('.body')->innerHtml;
			$anchor  = str_contains($body, 'anchor') ? $this->getAnchorId($body) : 0;
			$opt_datas[$come_id] = [
				'topic_id' => $topic_id,
				'content_id' => $come_id,
				'content_body' => htmlspecialchars($body, ENT_QUOTES, "UTF-8"),
				'anchor' => $anchor,
				'come_lv' => $come_lv,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => null,
				'deleted_at' => null,
			];
		}

		return $opt_datas;
	}


	/**
	 * @return int $target_id
	 */
	public function getCategoryId()
	{

		$now_i      = date('i');
		$target_id  = "";

		switch ($now_i) {

			case $now_i < 10:
				$target_id = 1;
				break;

			case $now_i >= 10 && $now_i < 20:
				$target_id = 2;
				break;

			case $now_i >= 20 && $now_i < 30:
				$target_id = 3;
				break;

			case $now_i >= 30 && $now_i < 40:
				$target_id = 4;
				break;

			case $now_i >= 40 && $now_i < 50:
				$target_id = 5;
				break;

			case $now_i >= 50 && $now_i < 60:
				$target_id = 6;
				break;

			default:
				$target_id = 2;
				break;
		}

		return $target_id;
	}
}
