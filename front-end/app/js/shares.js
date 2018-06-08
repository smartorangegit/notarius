//shares-form
function selectMenuShow(current, target) {
	current.closest(".shares-form-container")
			.find(target)
			.fadeToggle()
			.css("display", "flex");
};

function inputSelect(current, wrapper, inputInfo, input) {
	var id = current.attr("id");

	//input meta data 
	var	selectInfoInput = current.closest(wrapper)
					  .find(inputInfo)
	selectInfoInput.attr("value", " ");
	selectInfoInput.attr("value", id);

	//input text for user 
	var	selectTextinput = current.closest(wrapper)
					  .find(input);

	var second = current.html().trim();
	selectTextinput.attr("value", " ");
	selectTextinput.attr("value", second);

	$(".form-select-menu").css("display", "none");	
}



$(".shares-form__offer").on("click", function() {
	selectMenuShow($(this), ".shares-offer-select-menu");
});

$(".shares-form__district").on("click", function() {
	selectMenuShow($(this), ".shares-district-select-menu");
});


$(".select-menu-list__item").on("click", function() {

	inputSelect($(this), ".shares-form__offer-wrap", ".shares-form__offer-info", ".shares-form__offer");

	inputSelect($(this), ".shares-form__district-wrap", ".shares-form__district-info", ".shares-form__district");

});
//end__shares-form


//shares-popup-form
$(".shares-list-notary__button").on("click", function() {
	var current = $(this),
		item = current.closest(".shares-list__item"),
		inputList = item.find(".shares-list__meta-input"),
		popup = $(".shares-popup-form"),
		blockForInsert = popup.find(".form__select-wrap"),
		text = item.find(".shares-list-title__name").text();

	blockForInsert.find(".shares-list__meta-input").remove();	

	for(var i = 0; i < inputList.length; i++) {
		blockForInsert.append($(inputList[i]).clone());
	}

	blockForInsert.find(".form__select").val(text);
	blockForInsert.off("click");

	popup.fadeIn();
});
//end__shares-form

