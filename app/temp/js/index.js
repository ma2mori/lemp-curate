$(function(){

	var active_tsb = $('.category-tabs li.active').index();
	if(active_tsb >= 4){
		console.log(active_tsb);
		$('.category-tabs ul').scrollLeft(1000);
	}

});