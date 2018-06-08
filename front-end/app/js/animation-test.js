
var animationArr = ["fade", "slide-left", "slide-right", "bounce", "slide-bottom"];

function animationOnScroll(animation) { 
    var elements = document.querySelectorAll(".animation");
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
