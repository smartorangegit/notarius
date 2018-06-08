
// services-tab
$(".card-services-name-list__link").on("click", function(e) {
	e.preventDefault();

	$(".card-services-name-list__link").removeClass("card-services-name-list__link_active");

	var current = $(this),
		item = current.closest(".card-services-name-list__item"),
		itemIndex = item.index(),
		subspeciesItem = $('.card-services-price-wrap__item');

	current.addClass("card-services-name-list__link_active");

	subspeciesItem.removeClass("card-services-price-wrap__item_active")
							.css("display", "none");
	subspeciesItem.eq(itemIndex).fadeToggle();
});
// end__services-tab

// video
$(".card-video-slider__image").on("click", function() {

	var video = $(this).siblings(".card-video-slider__video")[0];

	$(this).hide();

	if(video.paused) {
		video.play();
		video.setAttribute("controls", "");

	} else {
		video.pause();
		// video.removeAttribute("controls", "");
	}
});
// end__video


//start
function rating(ratingWrap) {
	var starNumber = ratingWrap.data("star")/5,
		starProc = starNumber*100;

	starProc = starProc.toString();
	ratingWrap.find(".star__color").css("width", starProc + "%");
}

rating($(".card-top__rating"));


Array.prototype.forEach.call($('.feedback-list__item'), function(item) {
	rating($(item));
})
//end__start 
