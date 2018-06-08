// pagination

if($(".pagination-num__item").length > 4) {
	$($(".pagination-num__item")[$(".pagination-num__item").length-1]).addClass("pagination-dot-left");
}

if($($(".pagination-num__item")[0]).hasClass("pagination-num__item_active")) {
	$(".pagination__nav_prev").css("opacity", "0");
} else {
	$(".pagination__nav_prev").css("opacity", "1");
}


var myNotaryPagItem = $(".my-notary-pagination-num").find(".pagination-num__item"); 
if(myNotaryPagItem.length > 3) {
	$(myNotaryPagItem[myNotaryPagItem.length-1]).addClass("pagination-dot-left");
}

function addPaginNum(listItem, paginWrap, quantyti) {
	var itemsNum = $(listItem).length;
	var num = Math.ceil(itemsNum/quantyti);

	for(var i = 1; i < num; i++) {
		var num = i+1;

		$(paginWrap).append(
			"<li class='pagination-num__item'>" + 
                num + 
            "</li>"
		);
	}
}
addPaginNum(".shares-list__item", ".shares-pagination-num", 5);
addPaginNum(".my-feedback-list__item", ".my-feedback-pagination-num", 4);
addPaginNum(".my-notary-list__item", ".my-notary-pagination-num", 4);
addPaginNum(".feedback-list__item", ".feedback-pagination-num", 4);

function resetPaginationStyle(contentItems, numList) {
	contentItems.css("display", "none");
	numList.removeClass("pagination-num__item_active");
	numList.removeClass("pagination-dot-left pagination-dot-right")
				.css("display", "none");
}

function paginationNumDisplaying(numIndex, numList, pageNum) {
	if(numIndex >= 3) {
		$(numList[numIndex]).addClass("pagination-dot-left");
	}
	for(var i = numIndex; i < numIndex+2; i++) {
		$(numList[i]).css("display", "block");
	}	
	$(numList[numList.length - 1]).addClass("pagination-dot-left");
}

function contentItemsDisplaying(pageNum, num, contentItems) {
	var firstElement = pageNum*num-num,
		lastElement = pageNum*num;

	if(pageNum == 1) {
		for(var i = 0; i < lastElement; i++) {
			$(contentItems[i]).fadeIn().css("display", "flex");
		}
		
	} else if(pageNum == 2) {
		for(var i = num; i < lastElement; i++) {
			$(contentItems[i]).fadeIn().css("display", "flex");
		}

	} else {
		for(var i = firstElement; i < lastElement; i++) {
			$(contentItems[i]).fadeIn().css("display", "flex");
		}

	}
}

function removeDotFromPaginItem(pageNum, numList) {
	if(pageNum >= numList.length){
		$(numList[numList.length-2]).css("display", "block")
						.addClass("pagination-dot-left");
		$(numList[numList.length-1]).removeClass("pagination-dot-left pagination-dot-right");
	}

	if(pageNum == numList.length-1){
		$(numList[numList.length-1]).removeClass("pagination-dot-left pagination-dot-right");
	}

	if(numList.length < 5) {
		$(numList[numList.length-2]).removeClass("pagination-dot-left pagination-dot-right");
	}

	$(numList[1]).removeClass("pagination-dot-left pagination-dot-right");
	$(numList[2]).removeClass("pagination-dot-left pagination-dot-right");

	// hide prev-button
	if($($(".pagination-num__item")[0]).hasClass("pagination-num__item_active")) {
		$(".pagination__nav_prev").css("opacity", "0");
	} else {
		$(".pagination__nav_prev").css("opacity", "1");
	}
}




function paginationNumHandler(element, items, num) {
		var	wrap = element.closest(".pagination-wrap-js"),
			contentItems = wrap.find(items); 
	
		var	numIndex = element.index(),
			numList = wrap.find(".pagination-num__item");

		var pageNum = +element.html();

		resetPaginationStyle(contentItems, numList);				
		
		paginationNumDisplaying(numIndex, numList);
		element.addClass("pagination-num__item_active");

		contentItemsDisplaying(pageNum, num, contentItems);

		removeDotFromPaginItem(pageNum, numList);	
};

function paginPrevHandler(element, list, num) {
	var	wrap = element.closest(".pagination-wrap-js"),
		contentItems = wrap.find(list),
		numList = wrap.find(".pagination-num__item");
		numIndex = element.closest(".pagination").find(".pagination-num__item_active").index();
	
	resetPaginationStyle(contentItems, numList);		

	paginationNumDisplaying(numIndex-2, numList);
	$(element.closest(".pagination").find(".pagination-num__item")[numIndex-1])
								   .addClass("pagination-num__item_active");

	// content items displaying
	var pageNum = numIndex,
				firstElement = pageNum*num-num,
				lastElement = pageNum*num;

	if(pageNum <= 0) {
		for(var i = 0; i < num; i++) {
			$(contentItems[i]).fadeIn().css("display", "flex");
		}

		$(numList[0]).addClass("pagination-num__item_active");
	} else {
		for(var i = firstElement; i < lastElement; i++) {
			$(contentItems[i]).fadeIn().css("display", "flex");
		}
	}
	//end__content items displaying

	removeDotFromPaginItem(pageNum, numList);
}

function paginNextHandler(element, list, num) {
	var	wrap = element.closest(".pagination-wrap-js"),
			contentItems = wrap.find(list),
			numList = wrap.find(".pagination-num__item");
			numIndex = element.closest(".pagination").find(".pagination-num__item_active").index(),
			pageNum = numIndex+2;

	resetPaginationStyle(contentItems, numList);				

	paginationNumDisplaying(numIndex, numList);
	$(element.closest(".pagination").find(".pagination-num__item")[numIndex+1])
							   .addClass("pagination-num__item_active");

	contentItemsDisplaying(pageNum, num, contentItems);

	// on last pagination num item displaying only remnants from content items
	if(pageNum >= numList.length) {
		if(contentItems.length%num == 0) {
			for(var i = contentItems.length - num; i < contentItems.length; i++) {
				$(contentItems[i]).fadeIn().css("display", "flex");
			}			
		} else {
			for(var i = contentItems.length - (contentItems.length%num); i < contentItems.length; i++) {
				$(contentItems[i]).fadeIn().css("display", "flex");
			}			
		}

		$(numList[numList.length - 1]).addClass("pagination-num__item_active");
		$(numList[numList.length - 2]).css("display", "block");
	}

	removeDotFromPaginItem(pageNum, numList);
}


$(".pagination-num__item").on("click", function() {
	paginationNumHandler($(this), $(".feedback-list__item"), 4);
	paginationNumHandler($(this), $(".shares-list__item"), 5);
	paginationNumHandler($(this), $(".my-notary-list__item"), 4);
	paginationNumHandler($(this), $(".my-feedback-list__item"), 4);
});



$(".shares-pagination__nav_next").on("click", function(e) {
	e.preventDefault();
	paginNextHandler($(this), ".shares-list__item", 5);
});
$(".shares-pagination__nav_prev").on("click", function(e) {
	e.preventDefault();
	paginPrevHandler($(this), ".shares-list__item", 5);
});


$(".my-feedback-pagination__nav_next").on("click", function(e) {
	e.preventDefault();
	paginNextHandler($(this), ".my-feedback-list__item", 4);
});

$(".my-feedback-pagination__nav_prev").on("click", function(e) {
	e.preventDefault();
	paginPrevHandler($(this), ".my-feedback-list__item", 4);
});


$(".my-notary-pagination__nav_next").on("click", function(e) {
	e.preventDefault();
	paginNextHandler($(this), ".my-notary-list__item", 4);
});
$(".my-notary-pagination__nav_prev").on("click", function(e) {
	e.preventDefault();
	paginPrevHandler($(this), ".my-notary-list__item", 4);
});


$(".feedback-pagination__nav_next").on("click", function(e) {
	e.preventDefault();
	paginNextHandler($(this), ".feedback-list__item", 4);
});
$(".feedback-pagination__nav_prev").on("click", function(e) {
	e.preventDefault();
	paginPrevHandler($(this), ".feedback-list__item", 4);
});
// end__pagination

