
function plusMinus(el) {
	if(el.hasClass("search-title__show_plus")) {
		el.removeClass("search-title__show_plus");
		el.addClass("search-title__show_minus");
	} else if(el.hasClass("search-title__show_minus")) {
		el.removeClass("search-title__show_minus");
		el.addClass("search-title__show_plus");
	}
}


$(".search-title").on("click", function() {
	var show = $(this).find(".search-title__show");
	plusMinus(show);
	var tag = $(this).closest(".search-tag");
	if(tag.hasClass("search-subway")) {
		tag.removeClass("search-tag_padding");
	} else {
		tag.toggleClass("search-tag_padding");
	}
	tag.find(".search-items-wrap").toggle();
});

$(".search-items__show").on("click", function() {
	plusMinus($(this));
	$(this).closest(".search-items__item").find(".search-items-droplist").toggle();
});


$(".filter-meta__button").on("click", function() {
	$(this).closest(".filter-meta").find(".filter-meta-button-list__item").remove();
});

$(".search-tag__image").on("click", function() {
	$(this).closest(".search-items-wrap").css("display", "none");
	$(this).siblings(".search-subway__input-info").attr("value", " ");
	$(".metro-map__label").removeClass("metro-map__label_active");

	var plus = $(this).closest(".search-tag").find(".search-title__show");
	plus.removeClass("search-title__show_minus");
	plus.addClass("search-title__show_plus");
	console.log(plus);

});

$(".filter-meta-button-list__icon").on("click", function() {
	$(this).closest(".filter-meta-button-list__item").remove();

	var oldUrl = window.location.href;
    var address = oldUrl.slice(0, oldUrl.indexOf('?'));
    var data = $(this).data('filter');

    var getParams = oldUrl.slice(oldUrl.indexOf('?')).split('&');
    var sub = getParams.shift().substring(1);
    getParams.unshift(sub);

    var newParams = getParams.reduce(function(first, second, i) {
        if(second.indexOf(data)===-1) {
            if(i !== getParams.length - 1) {
                first += second + '&';
            } else {
                first += second;
            }
            return first;
        } else {
            return first;
        }
    }, '?');

    window.location.href = address + newParams;
});

// metro filter
$(".search-title__subway").on("click", function() {
	if($("body").width() < 768) {
		$(".metro-map-wrap").hide();
		$(".metro-map-wrap_mobile").fadeIn();
	} else {
		$(".metro-map-wrap_mobile").hide();
		$(".metro-map-wrap").fadeIn();
	}
});

$(".metro-map__icon").on("click", function(e) {
	$(this).closest(".metro-map__label").removeClass("metro-map__label_active");
});

$(".metro-map__text").on("click", function() {
	$(".metro-map__label").removeClass("metro-map__label_active");
	$(this).closest(".metro-map__label").addClass("metro-map__label_active");
});

$(".metro-map__close").on("click", function() {
	$(this).closest(".metro-map-wrap").css("display", "none");
	$(this).closest(".metro-map-wrap_mobile").css("display", "none");
});

$(".metro-map-button__send").on("click", function() {
	$(".metro-map-wrap").css("display", "none");
	$(".metro-map-wrap_mobile").css("display", "none");

	var subwayActive = $(".metro-map__label_active"),
		id = subwayActive.attr("id"),
		text = subwayActive.find(".metro-map__text").html();

	$(".search-subway__input").text(text);
	$(".search-subway__input-info").attr("value", id);

	$(".search-subway__input").closest(".search-items-wrap").css("display", "block");
});

$(".metro-map-button__clear").on("click", function() {
	$(".metro-map__label").removeClass("metro-map__label_active");
});
// end__metro filter
