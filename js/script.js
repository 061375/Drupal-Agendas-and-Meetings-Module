/**
 * 
 *
 *
 */
var show_log = true;
(function ( $ ) {
    $.logThis = function(m) {
        if (show_log) {
            if( (window['console'] !== undefined) ){
                console.log( m );
            }
        }
    }
}( jQuery ));
(function ( jQuery, Drupal, drupalSettings) {
	var $ = jQuery;
		if(typeof $('.block-msrc-agenda-minutes') !== 'undefined') {
			Drupal.AjaxCommands.prototype.getAgAndMinYears = function(ajax, response, status) {
				var years = response.years ? response.years : '';
				$(response.selector).getAgAndMinYears(years);
			}
			var obj = [];
			$('.block-msrc-agenda-minutes').each(function(){
				obj.push($(this).find('table').find('th').first().html());
			});
			console.log(obj);
			var div = document.createElement('div');
				$(div).attr('id','agandmin_container');
				var left = document.createElement('div');
					$(left).attr('class','col-sm-3');
					var select = document.createElement('select');
						$(select).attr('class','meetings');
						var option = document.createElement('option');
								$(option).html('-- CHOOSE COMMITTEE --');
							$(select).append(option);
						$.each(obj,function(k,v){
							var option = document.createElement('option');
								$(option).attr('value',k);
								$(option).html(v);
							$(select).append(option);
						});
					$(left).append(select);
				$(div).append(left);
				var right = document.createElement('div');
					$(right).attr('class','col-sm-3');
					var select = document.createElement('select');
						$(select).attr('class','meetings');
						Drupal.AjaxCommands.getAgAndMinYears();		
					$(right).append(select);
				$(div).append(right);
			$(div).insertAfter('article.meetings-agendas-and-minutes-pag');
		}
		/* Function.init($); */
		$.logThis('msrc module js loaded');
})(jQuery, Drupal, drupalSettings);