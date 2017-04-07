var ACME = {
	/** 
	 * 
	 *
	 * ACME
	 * 
	 * @author Jeremy Heminger c/o Geographics inbox@geomail.info
	 * @version 1.0.0
	 * 
	 * KeyBindings
	 * Agendas and Minutes
	 * Ajax
	 * 
	 */

//--------------------------------------


	/** 
	 * 
	 * KeyBindings
	 * 
	 * @function KeyBindings  
	 *  
	 */
	KeyBindings: function($) {
		// on change display selected meeting 
		$('#agandmin_container').on('change','.meetings',function(){
			// get the selected option
			var t = $(this).val();
			$('.block-acme-agenda-minutes').fadeOut('fast',function(){
				// 
				if(typeof ACME.AgAndMin.meetings[t] === 'undefined') {
					$('.block-acme-agenda-minutes').fadeIn('fast');
				}else{
					ACME.AgAndMin.meetings[t].fadeIn('fast');
				}
			});
		});
		$('#agandmin_container').on('change','.years',function(){
			ACME.AgAndMin.getByYear($,$(this).val());
		});
	},
	/** 
	 * 
	 *
	 * Agendas and Minutes
	 * 
	 * @function init 
	 *  	@param {Object} jQuery
	 * @function getByYear
	 *		@param {Object} jQuery
	 *		@param {String}
	 * @function getAttr
	 *		@param {Objecy} DOM Object
	 *
	 */
	AgAndMin: {
		meetings:[],
		init: function($){
			var obj = [];
			// loop the tables and add the attributes to an array
			// easier to reference later
			$('.block-acme-agenda-minutes').each(function(){
				var title = $(this).find('table').find('th').first().html();
				obj.push(title);
				var o = ACME.AgAndMin.getAttr($(this).find('table'));
				$(this).data('type_machine_name',o.tmn);
				$(this).data('category_field',o.cf);
				$(this).data('date_field',o.df);
				ACME.AgAndMin.meetings.push($(this));
			});
			// build the select tags based on the data
			var div = document.createElement('div');
				$(div).attr('id','agandmin_container');
				var left = document.createElement('div');
					$(left).attr('class','col-sm-3');
					var select = document.createElement('select');
						$(select).attr('class','meetings');
						var option = document.createElement('option');
								$(option).html('-- CHOOSE COMMITTEE --');
								$(option).attr('value','');
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
						$(select).attr('class','years');
						var option = document.createElement('option');
							$(option).html('-- CHOOSE YEAR --');
							$(option).attr('value','');
						$(select).append(option);
						var p = ACME.Ajax.getData('getYears',{});
						ACME.Ajax.dataResult(p,function(data){
							$.each(data.message,function(k,v) {
								var o = document.createElement('option');
									$(o).attr('value',v);
									$(o).html(v);
								$(select).append(o);
							});
						});
					$(right).append(select);
				$(div).append(right);
			$(div).insertAfter('article.meetings-agendas-and-minutes-pag');
		},
		getByYear: function($,year) {

			var m = $('#agandmin_container .meetings').val();
			// if chk == '' then meeting is not a factor
			if('' == m) m = false;
			// get the selected meeting year
			var t = $('#agandmin_container .years').val();

			$.logThis('m = '+m);

			var tid = m;
			if(!m)tid = 0;
			// get a node to reference for variables necessary to get the data
			var $t = ACME.AgAndMin.meetings[tid] ? ACME.AgAndMin.meetings[tid] : false;
			// make the Ajax call
			var p = ACME.Ajax.getData('getByYear',{
				year:((year == '') ? false : year),
				type:$t.data('type_machine_name'),
				dfield:$t.data('date_field'),
				meeting:m
			});
			// return the data (callback)
			$('.block-acme-agenda-minutes').fadeOut('fast',function(){
				$('.block-acme-agenda-minutes').find('tbody').html('<tr><td colspan="4">There are no meetings for this time period.</td></tr>');
				ACME.Ajax.dataResult(p,function(data){
					// if no meeting selected then we want to loop all the meetings to update
					if(!m) {
						// loop the results
						$.each(data.message,function(k,v){
							// if the key matches an existing node
							if(typeof ACME.AgAndMin.meetings[k] !== 'undefined') {
								// update the nodes value
								ACME.AgAndMin.meetings[k].find('tbody').html(v);	
							}
						});
						$('.block-acme-agenda-minutes').fadeIn('fast');	
					}else{
						// if a specific meeting was selected
						// update it
						$t.find('tbody').html(data.message);
						$t.fadeIn('fast');
					}
				});
			});
		},
		getAttr: function($t) {
			var r = {
				tmn:'',cf:''
			}
			var cl = $t.attr('class').split(/\s+/);

			var l = cl.length;
			for(var i = 0; i<l; i++) {
				if(cl[i].indexOf('type_machine_name-') > -1) {
					r.tmn = cl[i].replace('type_machine_name-','');
				}
				if(cl[i].indexOf('category_field') > -1) {
					r.cf = cl[i].replace('category_field-','');
				}
				if(cl[i].indexOf('date_field') > -1) {
					r.df = cl[i].replace('date_field-','');
				}
			}
			return r;
		}
	},
	/** 
	 * 
	 * Ajax
	 * 
	 * @function init 
	 *  	@param {String} ajaxurl
	 *  	@param {Function} callback
	 * @function getData 
	 *  	@param {String} method
	 *  	@param {Object} data
	 *  	@param {string} url
	 *  	@return {Object} 
	 * @function dataResult 
	 *  	@param {Object} p
	 *  	@param {Function} callback
	 *  	@param {Function} ecallback
	 *  	@return {Void} 
	 */
	Ajax: {
		ajaxurl:'',
		init: function(ajaxurl, callback){
			this.ajaxurl = ajaxurl;
			if(typeof callback === 'function') {
				callback();
			}
		},
		getData: function(method,data,url){
	        if (typeof url === 'undefined') {
	            url = this.ajaxurl+'/'+method;

	        }
	        jQuery.logThis('send ajax '+url);
	        var post = {
	            method  :method,
	            data    :data
	        };
	        return jQuery.ajax({
	            url         :url,
	            data        :post,
	            type        :"post",
	            dataType    :"json"
	        });
	    },
	    dataResult: function(p,callback,ecallback) {
	        p.done( function(data){
	        	data = data[0];
	            if(data.success == 1) {
	                if (typeof callback === "function") {
	                    callback(data);
	                }
	            } else {
	                if (typeof data.errors !== 'undefined') {
	                    var errors = "";
	                    jQuery.each(data.errors,function(k,v){
	                        errors+=v+"\n";
	                    });
	                    if (typeof ecallback === "function") {
	                        ecallback(errors);
	                    }else{
	                        alert("An error occured "+errors);
	                    }
	                }else{
	                    if (typeof ecallback === "function") {
	                        ecallback(data.message);
	                    }else{
	                        alert("An error occured "+data.message);
	                    }
	                }
	            } 
	        });
	        p.fail( function(xhr, ajaxOptions, thrownError){
	            var error_text = 'An Error occurred...';
	            if ( typeof xhr !== 'undefined') {
	                jQuery.logThis('xhr error '+xhr.status);
	            }
	            if ( typeof thrownError !== 'undefined') {
	                jQuery.logThis('thrownError '+thrownError);
	            }
	            alert(error_text); 
	        });
	    }
	}
}