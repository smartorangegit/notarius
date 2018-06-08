

$(".select-menu-list__item").on("click", function() {
	var current = $(this),
		currentText = $(this).text().trim(),
		id = current.attr("id"),
		selected = current.closest(".notary-page-services")
						  .find(".notary-page-services-selected-list");

	var selectedItem = "<li class='notary-page-services-selected-list__item'>" +
				  "<input type='text' class='notary-page-services-selected-droplist__input' style='display: none' value='" + id + "'>" +  
		          "<div class='notary-page-services-selected-list__item-content'>" + 
		             "<img src='/img/notary-page/remove.png' alt='remove' class='notary-page-services-selected-list__image'/>" +
		               "<p class='notary-page-services-selected-list__text'>" + currentText + "</p>" + 
		          "</div>" + 
	          "</li>";

	selected.append(selectedItem);

	$(".notary-page-services-selected-list__image").on("click", function() {
		$(this).closest(".notary-page-services-selected-list__item").remove();
	});
});
