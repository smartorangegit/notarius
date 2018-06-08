function confirmDelete(){
    if ( confirm("Delete this item?") ){
        return true;
    } else {
        return false;
    }
}
// $(".dropdown-toggle.one").click(function(e){
//     e.preventDefault();
//     //console.log('test');
//     $(".dropdown-menu.one").css("display","block");
// });
// $(".dropdown-toggle.two").click(function(e){
//     e.preventDefault();
//     //console.log('test');
//     $(".dropdown-menu.two").css("display","block");
// });
$(document).keyup(function(e) {
    if (e.keyCode === 27){
        $(".dropdown-menu").css("display","none");
    }
});


// dashboard_status_color
function statusColor() {
	var option = $('.option');

    for(var i = 0; i < option.length; i++) {
        var row = option[i].closest(".row100").children;
        for(var j = 0; j < row.length; j++) {
            if(row[j].classList.contains("deal")) {
                var deal =row[j];
                break; 
        	}
    	}

        var circle = deal.children[0].children[0];
        var value = option[i].innerHTML;

        switch (value) {
        case "Новая":
            circle.style.background="yellow";
            break;
        case "Обработана":
            circle.style.background="#54a5d3";
            break;
        case "Назначено":
            circle.style.background="#41ba7b";
            break;
        case "Состоялась":
            circle.style.background="gray";
            break;
        case "Отмена":
            circle.style.background="#f97f7f";
            break;
        default:
            circle.style.background="fff";
            break;
        }
    }
}
// END__dashboard_status_color



// $('.drop-menu-item-hide').on('click', function () {
//     var a = $(this).find('.swanky_wrapper__content');
//     a.toggleClass('toggle-display-js');
//     $(this).toggleClass('toggle-height-js');
// });
