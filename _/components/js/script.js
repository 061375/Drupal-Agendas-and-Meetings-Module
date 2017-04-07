/********

********/

var ajaxpath = '/acme/ajax';

var show_log = true;

// plugins
(function ( $ ) {
    $.logThis = function(m) {
        if (show_log) {
            if( (window['console'] !== undefined) ){
                console.log( m );
            }
        }
    }
}( jQuery ));

// initialize all the classes
(function ( jQuery, Drupal, drupalSettings, ACME) {
    var $ = jQuery;
    if(typeof $('.block-agendas-meetings-agenda-minutes') !== 'undefined') {
      ACME.Ajax.init('/acme/ajax',function(){
        ACME.AgAndMin.init($);
      });
    }
    ACME.KeyBindings($);
    $.logThis('acme module js loaded'); 
})(jQuery, Drupal, drupalSettings, ACME);
