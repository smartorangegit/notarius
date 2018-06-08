
// fixed menu
	$(window).scroll(function () {
		if ($(this).scrollTop() > 0) {
			$('.header-wrap').css({
				'position': 'fixed',
				'width': '100%',
				'transition': 'all 0.3s',
				'top': '0',
				'left': '0',
				'z-index': '101',
				'box-sizing': 'border-box',
				'background-color': 'rgba(63, 207, 213, 0.9)',
			});
		} else {
			$('.header-wrap').css({
				'position': 'static',
				'z-index': '0',
				'padding': '0',
				'background-color': 'rgba(0,0,0, 0.3)',
			});
		}
	});
// end__fixed-menu


$(".how-notary-reg__text_blue").on("click", function() {
	$(".sign-in").fadeToggle();
});

// header
$(".header__button-enter").on('click', function () {
	$(".sign-in").fadeToggle();
	$(".log-in").fadeOut();		
});

$(".header__button-question").on('click', function () {
	$(".popup-form").fadeIn();	
});
//////////
$(".sign-in__button").on("click", function() {
	$(".sign-in__button").removeClass("sign-in__button_active");
	$(this).addClass("sign-in__button_active");

	if($(".sign-in-user__button").hasClass("sign-in__button_active")) {
		$(".sign-in-notary-form-wrap").css("display", "none");
		$(".sign-in-user-form-wrap").fadeIn();
	} else if($(".sign-in-notary__button").hasClass("sign-in__button_active")) {
		$(".sign-in-user-form-wrap").css("display", "none");
		$(".sign-in-notary-form-wrap").fadeIn();
	}
});

$(".sign-in-regist__link").on("click", function(e) {
	e.preventDefault();
	$(".sign-in").css("display", "none");
	$(".log-in").fadeIn();
});
//////////
$(".menu-drop__link").on("click", function(e) {
	// e.preventDefault();
	$(".menu-drop__link").removeClass("menu-drop__link_active");
	$(this).addClass("menu-drop__link_active");
});
$('#mnr-js').click(function() {
    location.replace("/notary/users/register/");
});
// end__header

//form-category
$(".form__select-wrap").on("click", function() {
	var wrap = $(this).closest(".form-wrap");
	wrap.find(".select-menu")
		.css("display", "flex");
});

$(".form__anchor").on("click", function(e) {
	e.preventDefault();
	var wrap = $(this).closest(".form-wrap");
	var scrollValue = wrap.offset().top - 150;
	$('body,html').animate({scrollTop: scrollValue}, 800);
});

$(".select-menu-list__item").on("click", function(e) {
	$(".select-menu-list__item").removeClass("select-menu-list__item_active");

	var current = $(this),
		currentText = $(this).text().trim(),
		id = current.attr("id"),
		formWrapper = current.closest(".form-wrap"),
		selectInfoInput = formWrapper.find(".form__select-info"),
		selectTextinput = formWrapper.find(".form__select");

	current.addClass("select-menu-list__item_active")
		   .siblings().removeClass("select-menu-list__item_active");

	selectInfoInput.attr("value", " ");
	selectTextinput.attr("value", " ");

	selectInfoInput.attr("value", id);
	selectTextinput.attr("value", currentText);

	current.closest(".form-select-menu").css("display", "none");

});

$(".popup-form__icon").on("click", function() {
	$(this).closest(".popup-form").fadeOut();
	$(this).closest(".shares-popup-form").fadeOut();
});


//end__form-category 



// categories
$(".categories-type__item").on("click", function(e) {
	e.preventDefault();

	$(".categories-type__icon").css("fill", "#fff"); //до svg елемента не додається і не видаєляється динамічно назва класу, тому довелось робити через метод .css();
	$(".categories-type__link").removeClass("categories-type__link_active");

	var current = $(this),
		categoriesTypeLink = current.find(".categories-type__link"),
		categoriesTypeIcon = current.find(".categories-type__icon"),
		categoriesTypeItemIndex = current.index(),
		categoriesSubspeciesItem = $('.categories-subspecies-wrap__item');

	categoriesTypeLink.addClass("categories-type__link_active");
	categoriesTypeIcon.css("fill", "#333");

	categoriesSubspeciesItem.removeClass("categories-subspecies-wrap__item_active")
							.css("display", "none");
	categoriesSubspeciesItem.eq(categoriesTypeItemIndex).fadeToggle();
});
// end__categories



// questions
$(".questions-list__item").on("click", function() {
	var triangle = $(this).find(".questions-list__triangle"),
		heading = $(this).find(".questions-list__heading"),
		questionsDescript = $(this).find($(".questions-list__descript"));

	$(".questions-list__descript").not(questionsDescript).slideUp();

	$(".questions-list__heading").removeClass("questions-list__heading_active");
	$(".questions-list__triangle").removeClass("questions-list__triangle_active");

	triangle.toggleClass("questions-list__triangle_active");
	heading.toggleClass('questions-list__heading_active');
	questionsDescript.slideToggle();
});
// end__questions


$(".how-notary-reg-form__text_blue").on("click", function() {
	$(".how-notary-popup-wrap").fadeIn();
	$(".how-notary-popup").css("display", "block");
});



// close popup
function popupClose(block) {
	$(document).mouseup(function (e){
		var div = block; 
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			div.hide();
		}
	});
};
function globalPopupClose(block) {
	$(document).mouseup(function (e){
		var div = block; 
		if (div.has(e.target).length === 0) {
			div.hide();
		}
	});
};
popupClose($(".sign-in"));
popupClose($(".log-in"));
popupClose($(".form-select-menu"));
popupClose($(".head__client-menu"));
globalPopupClose($(".how-notary-popup-wrap"));
globalPopupClose($(".comment-edit"));
globalPopupClose($(".comment-write"));
globalPopupClose($(".popup-form"));
globalPopupClose($(".shares-popup-form"));
globalPopupClose($(".metro-map-wrap"));


// end__close popup


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
});
Array.prototype.forEach.call($('.shares-list__item'), function(item) {
	rating($(item));
});
Array.prototype.forEach.call($('.my-notary-list__item'), function(item) {
	rating($(item));
});
Array.prototype.forEach.call($('.my-feedback-notary-rating'), function(item) {
	rating($(item));
});
Array.prototype.forEach.call($('.my-feedback-info-rating'), function(item) {
	rating($(item));
});
Array.prototype.forEach.call($('.filter-notary-list__rating'), function(item) {
	rating($(item));
});

function starSelect(el) {
	var item = el.closest(".dynamic-star__item"),
		indexItem = item.index();

	var itemArray = el.closest(".dynamic-star").find(".dynamic-star__icon");

	itemArray.css("fill", "#fff");
	
	for(var i =0; i <= indexItem; i++) {
		$(itemArray[i]).css("fill", "#00CDE2");
	}
}

$(".dynamic-star__icon").on("click", function () {
		starSelect($(this));
		var input =  $(this).siblings(".dynamic-star__input");
		var itemValue = input.attr("value");

		input.attr("checked", "checked");
		$(this).closest(".dynamic-star-wrap").find(".dynamic-star__input-info")
											 .attr("value", itemValue);
});

function starSelectDataAttr() {
	var starList = $(".dynamic-star");
	for(var i = 0; i < starList.length; i++) {
		var dataValue = $(starList[i]).data("feedback-star"),
			starItem = $(starList[i]).find(".dynamic-star__icon");

		starItem.css("fill", "#fff");
	
		for(var j =0; j < dataValue; j++) {
			$(starItem[j]).css("fill", "#00CDE2");
		}
	}
}
starSelectDataAttr();

//end__start 

// show full comment text
function showCommentFull(current, textBlock) {
	var text = current.siblings(textBlock);
	text.toggleClass("my-feedback__text_active");

	if(text.hasClass("my-feedback__text_active")) {
		current.css({
			"transform": "rotate(-90deg)",
			"fill": "#000"
		});
	} else {
		current.css({
			"transform": "rotate(90deg)",
			"fill": "#6FCCE0"
		});
	}
}
$(".my-feedback__text-icon").on("click", function() {
	showCommentFull($(this), ".my-feedback__text");
	
});
$(".feedback-list-text__icon").on("click", function() {
	showCommentFull($(this), ".feedback-list-text__comment");
	
});


function hideCommentTextIcon(container, text, icon) {
	var item = $(container);

	for(var i = 0; i < item.length; i++) {
		var text = $(item[i]).children(text),
			icon = $(item[i]).children(".my-feedback__text-icon");

		if(text.height() < 115) {
			icon.css("display", "none");
		}
	}


};
hideCommentTextIcon(".my-feedback-info-container", ".my-feedback__text");
hideCommentTextIcon(".feedback-list-text", ".feedback-list-text__comment");
// end__show full comment text


//plugin
$('.form__date').pickadate({
	format: 'yyyy-mm-dd',
	formatSubmit: 'yyyy-mm-dd'
});
$('.editing-date__input').pickadate({
	format: 'yyyy-mm-dd',
	formatSubmit: 'yyyy-mm-dd',
	selectYears: true,
  	selectMonths: true
});
$('.form__time').pickatime({
	format: 'HH:i',
	min: [8,0],
  	max: [20,0]
});
$('.work-time-min').pickatime({
	format: 'HH:i',
	min: [8,0],
  	max: [20,0]
});
$('.work-time-max').pickatime({
	format: 'HH:i',
	min: [8,0],
  	max: [20,0]
});


$(".form__phone").mask("+38 (099) 999-9999");
$("#editing-phone").mask("+38 (099) 999-9999");
// $(".call-input__input").mask("+38( 0 9 9 )  9 9 9  -  9 9 9 9");

$('.workers-slider').slick({
	   autoplay: true,
	   autoplaySpeed: 2000,
	   slidesToShow: 2,
	   slidesToScroll: 2,
	   prevArrow: '.workers-slider-nav__prev',
	   nextArrow: '.workers-slider-nav__next',
	   dots: false,
	   responsive: [
	    {
	      breakpoint: 1200,
	      settings: {
	        slidesToShow: 1,
	        slidesToScroll: 1,
	      }
	    },
	    {
	      breakpoint: 767,
	      settings: {
	        slidesToShow: 1,
	        slidesToScroll: 1,
	        arrows: false,
	        dots: true
	      }
	    }
	   ]
 
});

$('.share-slider').slick({
	   autoplay: true,
	   autoplaySpeed: 1500,
	   prevArrow: '.share-slider-nav__prev',
	   nextArrow: '.share-slider-nav__next',
	   dots: true,
	   responsive: [
	    {
	      breakpoint: 767,
	      settings: {
	        arrows: false
	      }
	    }
	   ]
 
});

$('.services-slider').slick({
	   autoplay: true,
	   arrows: false,
	   autoplaySpeed: 2000,
	   dots: true
});

// $('.card-video-slider').slick({
// 	   autoplay: false,
// 	   autoplaySpeed: 5000,
// 	   slidesToShow: 3,
// 	   slidesToScroll: 1,
// 	   prevArrow: '.card-video-slider-nav__prev',
// 	   nextArrow: '.card-video-slider-nav__next',
// 	   dots: false
 
// });
// end__plugin

// video
$(document).on('click','.js-videoPoster',function(e) {
  //отменяем стандартное действие button
  e.preventDefault();
  var poster = $(this);
  // ищем родителя ближайшего по классу
  var wrapper = poster.closest('.js-videoWrapper');
  videoPlay(wrapper);
});

//вопроизводим видео, при этом скрывая постер
function videoPlay(wrapper) {
  var iframe = wrapper.find('.js-videoIframe');
  // Берем ссылку видео из data
  var src = iframe.data('src');
  // скрываем постер
  wrapper.addClass('videoWrapperActive');
  // подставляем в src параметр из data
  iframe.attr('src',src);
}
// end-video


// adaptive
$(".hamburger-open").on("click", function() {
	$("body").css("overflow", "hidden");
	$(".container").css("width", "100%");
	$(".header").css("padding", "0");
	$(this).css("opacity", "0");
	$(".hamburger-cansel").css("display", "block");
	$(".hamburger-content").css("display", "flex");
});

$(".hamburger-cansel").on("click", function() {

	if($("body").width() < 900) {
		$(".container").css("width", "98%");
		$("body").css("overflow", "visible");
		$(this).css("display", "none");
		$(".hamburger-open").css("opacity", "1");
		$(".hamburger-content").css("display", "none");
	} else {
		$(".container").css("width", "83.4%");
		$("body").css("overflow", "visible");
		$(".header").css("padding", "0 3%");
		$(this).css("display", "none");
		$(".hamburger-open").css("opacity", "1");
		$(".hamburger-content").css("display", "none");
	}
});

$(".feedback-list__more").on("click", function() {
	$(".feedback-list__item").css("display", "flex");
	$(this).css("display", "none");
});

if($("body").width() < 1000) {
	$(".document-show-list__input").val("удалить");
}

$(".filter-mobile-open__button").on("click", function() {
	$(".sidebar").toggle();
});


if($("body").width() < 600) {
	if($(".filter-meta-button-list__item").length == 0) {
		$(".filter-meta").css("display", "none");
	} else {
		$(".filter-meta").css("display", "block");
	}

	$(".filter-meta__button").on("click", function() {
		$(".filter-meta").css("display", "none");
	});
}
// end__adaptive

// scroll animation

var animationArr = ["fade", "slide-top", "slide-left", "slide-right", "bounce", "slide-bottom"];

function animationOnScroll(animation) { 
    var elements = document.querySelectorAll(".animation-js");
    var arrElement = [];

    for(var i = 0; i < elements.length; i++) {
        arrElement.push(elements[i]);
    }

    var arrTop = arrElement.map(function(item) {
        return item.getBoundingClientRect().top;
    });

    window.onscroll = function() {
        var windowScroll = window.pageYOffset;

        for(var j = 0; j < arrElement.length; j++ ) {
            var difference = arrTop[j] - windowScroll;

            if(difference < 600) {

                for (var k = 0; k < animation.length; k++) {
                    if(arrElement[j].classList.contains(animation[k])) {
                        arrElement[j].classList.add(animation[k] + "-in");
                    }
                }
            }
        }
    }
}

animationOnScroll(animationArr); 
// end__scroll animation

