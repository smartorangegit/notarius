// $( "button" ).click(function() {
//     $("#userMainForm").show( "slow" );
//     $( "button" ).hide("slow");
// });
function myFunction() {
    var txt;
    var r = confirm("Изменить данные?");
    if (r == true) {
        document.getElementById("confirm").value = "1";
    } else {
        txt = "You pressed Cancel!";
        document.getElementById("confirm").value = "0";
    }

}
function func2() {
    $( "h3.fillsUpd" ).hide("slow");
}
setTimeout(func2, 2000);
