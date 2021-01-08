var items = []; //array of names from database
var current = 0;

function next() { //increments current and sets the div to the correct data.
    if (current < items.length - 1) {
        current++;
    }
    setContent();
}
function prev() {//decrements current and sets the div to the correct data.
    if (current > 0) {
        current--;
    }
    setContent();
}
function setContent() { //sets the div to the correct data.
    $.getJSON( "responseJSON.php", function( data ) {
        $("#jsonAJAXContent").html(items[current]);
    });
}

$(document).ready(function(){ 
    $.getJSON( "responseJSON.php", function( data ) { //pushes all data to items array
        $.each( data.reverse(), function( key, val ) {
            items.push( "<div id=" + key + ">" + "<p>Category: " + val[0] + "</p>" +
                "<p>Weight: " + val[1] + "</p> </div>" +
                "<p>Date: " + val[2] + "</p> </div>" 
            );
        });
    });
    setContent();
});