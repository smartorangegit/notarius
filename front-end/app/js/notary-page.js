
// notary video
$(".notary-video-ref-wrap__button").on("click", function() {
	var refer = $(this).siblings(".notary-video-ref-wrap__input").val();
	var videoBlock = $(this).closest(".notary-video-container")
						   .find(".notary-video-list-wrap");
	videoBlock.find(".notary-video-list-content").fadeOut().remove();

	videoBlock.append(
		"<div class='notary-video-list-content'>" + 
            "<div class='notary-video-list__video-wrap'>" + 
                "<div class='video_wrapper video_wrapper_full js-videoWrapper notary-video-list__video'>" + 
                    "<iframe class='videoIframe js-videoIframe' src='' frameborder='0' allowTransparency='true' allowfullscreen data-src=" + refer + "autoplay=1&modestbranding=1&rel=0&hl=ru&showinfo=0&color=white'></iframe>" + 
                    "<button class='videoPoster js-videoPoster'><svg class='notary-video-list__icon'><use xlink:href='#youtube'></use></svg></button>" +
                "</div>" + 
                  "<p class='notary-video-list__name'>Video name</p>" + 
                "</div>" + 
                "<div class='notary-video-list-del'>" + 
                  "<img src='img/notary-page/del-icon.png' alt='del' class='notary-video-list-del__icon'>" + 
                  "<p class='notary-video-list-del__text'>удалить</p>" + 
            "</div>" + 
        "</div>"
	);

	$(".notary-video-list-del").on("click", function() {
		$(this).closest(".notary-video-list-wrap").find(".notary-video-list-content").remove();
	});
});

$(".notary-video-list-del").on("click", function() {
	$(this).closest(".notary-video-list-wrap").find(".notary-video-list-content").remove();
});
// notary end__video

$(".notary-page-services-table__icon").on("click", function() {
  $(this).closest(".notary-page-services-table__row").remove();
});
