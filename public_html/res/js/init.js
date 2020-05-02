
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
        