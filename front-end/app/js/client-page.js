$(".client-name__link-name").on("click", function(e) {
	e.preventDefault();
	$(".head__client-menu").fadeIn();
});


if($(".editing__input ").attr("value") == undefined) {
	$(".editing__icon").css("fill", "#0073C0");
} else {
	$(".editing__icon").css("fill", "#00CDE2");
}



$(".orders-name-list__link").on("click", function(e) {
	e.preventDefault();

	var itemArray = $(".orders-name-list__item"); 

	for(var i = 0; i < itemArray.length; i++) {
		$(itemArray).removeClass("orders-name-list__item_active");
	}

	var current = $(this),
		item = current.closest(".orders-name-list__item"),
		itemIndex = item.index(),
		anotherListItem = $('.order-table-list__item');

	item.addClass("orders-name-list__item_active");

	anotherListItem.removeClass("order-table-list__item_active")
							.css("display", "none");
	anotherListItem.eq(itemIndex).fadeToggle();
});

var orderStatus = $(".order-status");
for(var i = 0; i < orderStatus.length; i++) {
	var statusNum = $(orderStatus[i]).data("status");
	switch(statusNum) {
	  case 1: 
	    $(orderStatus[i]).css("color", "#0073C0");
	    break;
	  case 2:  
	    $(orderStatus[i]).css("color", "gray");
	    break;
	  case 3:  
	    $(orderStatus[i]).css("color", "blue");
	  break;
	  case 4:  
	    $(orderStatus[i]).css("color", "green");
	  break;
	  case 5:  
	    $(orderStatus[i]).css("color", "red");
	  break;

	  default:
	    break;
	}
}

// popup client-page
$(".my-feedback-info__button").on("click", function() {
	$(".comment-edit").fadeIn();
});

$(".my-notary-order-info__button-feedback").on("click", function() {
	$(".comment-write").fadeIn();
});
$(".comment__exit-button").on("click", function() {
		$(this).closest(".comment-write").hide();
		$(this).closest(".comment-edit").hide();
});
// end__popup client-page

// DOCUMENTS
	// select file
	function fileInfo () {
	      var doc = document.getElementById('document-add__file');
	      for(var i = 0; i < doc.files.length; i++) {
	      	var  name = doc.files.item(i).name,
	      	  size = doc.files.item(i).size; 

	      	 if(size >= 0 && size<=1024) {
		      	  size = Math.round(doc.files.item(0).size) + " " + "б";       	
		      } else if(size >= 1024 && size<=1024000) {
		      	  size = Math.round(doc.files.item(0).size/1024) + " " + "Кб";       	
		      } else if(size >= 1024000 ) {
		      	  size =  Math.round(doc.files.item(0).size/1024000) + " " + "Мб";       	
		      }

		    var list  = $(".document-add-list").append(
		      	"<li class='document-add-list__item'>" + 
		            "<div class='document-add-list__name-wrap'>" + 
		                "<span class='document-add-list__name'>" + name.slice(0, name.length-4) + "</span>" +
		                "<img src='/img/client-page/remove.png' alt='del' class='document-add-list__icon'>" +
		            "</div>" + 
		            "<p class='document-add-list__size'>" + size + " " + name.slice(-4) + "</p>" +
		        "</li>"
	     	);
	    }	

	};

	$("#document-add__file").change(function() {
		fileInfo();
		$(".document-add-list__icon").on("click", function() {
		var a = $(this).closest(".document-add-list__item").remove();
		$(".document-add-buttons>form")[0].reset();
	});
	});
	// end__select lile

	$(".document-add-list__icon").on("click", function() {
		var a = $(this).closest(".document-add-list__item").remove();
	});

	// function hoverChangeText(hoverElemen, target) {
	// 	var oldText;
	// 	$(hoverElemen).mouseover(function() {
	// 	oldText = $(this).find(target).attr("value");
	// 	$(this).find(target).attr("value", "Удалить");

	// 	});

	// 	$(hoverElemen).mouseout(function() {
	// 		$(this).find(target).attr("value", oldText);
	// 	}); 

	// 	$(target).on("click", function() {
	// 		$(this).closest(hoverElemen).hide();
	// 	});
	// }
	// hoverChangeText(".document-show-list__item", ".document-show-list__input");


	$(".document-full-slider-cansel__cansel").on("click", function() {
		$(".document-full").hide();
		$(".document-full-slider__image").remove();
		$("#document-slider").remove();
	});

	function downloadLink() {
		var currentImageSRC = $(".document-full").find(".slick-current").attr("src");
		$(".document-full").find(".document-full-slider-cansel__link").attr("href", currentImageSRC);
	}

	$(".document-show-list__item").on("click", function() {
		// $("body").css("overflow", "hidden");
		$(".document-full").fadeIn();
		// add slider for document-full
		var  imageSRC = $(this).find(".document-show-list-image__image").attr("src");
		var docList = $(".document-show-list-image__image");

		$(".document-full-slider-wrap").append("<div id='document-slider'></div>");
		for(var i = 0; i < docList.length; i++) {
			var docItemSRC = $(docList[i]).attr("src");
			$("#document-slider").append(
	            "<img src='" + docItemSRC + "' alt='document' class='document-full-slider__image'>"
			);
		}
		$("#document-slider").addClass("document-full-slider");
		$('.document-full-slider').slick({
		   slidesToShow: 1,
		   slidesToScroll: 1,
		   prevArrow: '.document-full-slider-nav__prev',
		   nextArrow: '.document-full-slider-nav__next',
		   dots: false
	 
		});
		// end__add slider for document-full

		$('.document-full-slider').find(".slick-current").attr("src", imageSRC);
		downloadLink();
		
		$(".document-full-slider-nav__prev").on("click", function() {
			downloadLink();
		});
		$(".document-full-slider-nav__next").on("click", function() {
			downloadLink();
		});

		$(".document-full-slider-zoom__plus").on("click", function() {
			$(this).closest(".document-full-slider-wrap").find(".slick-current")
			.css("transform", "scale(1.3)");
		});

		$(".document-full-slider-zoom__minus").on("click", function() {
			$(this).closest(".document-full-slider-wrap").find(".slick-current")
			.css("transform", "scale(1)");
		});
	});	
// END__DOCUMENTS

