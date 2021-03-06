
/**
 * Auto initialiaze material components
 */
$( window ).on( "load", function() 
{ 
    M.AutoInit();
 });

(function($){
    $(function(){

        $('.sidenav').sidenav();

    }); // end of document ready
})(jQuery); // end of jQuery name space

/**
 * Collapsable initialiasation
 */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, 'accordion');
  });

$(document) .ready(function(){
    $('select').formSelect();
});

$(document) .ready(function(){
    $('input#input_text, textarea#inputDescription').characterCounter();
});

$('.dropdown-toggle').dropdown()

$('.dropdown-trigger').dropdown();

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems, options);
});
        