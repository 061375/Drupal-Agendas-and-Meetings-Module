var ACME={KeyBindings:function(a){a("#agandmin_container").on("change",".meetings",function(){var b=a(this).val();a(".block-acme-agenda-minutes").fadeOut("fast",function(){"undefined"==typeof ACME.AgAndMin.meetings[b]?a(".block-acme-agenda-minutes").fadeIn("fast"):ACME.AgAndMin.meetings[b].fadeIn("fast")})}),a("#agandmin_container").on("change",".years",function(){ACME.AgAndMin.getByYear(a,a(this).val())})},AgAndMin:{meetings:[],init:function(a){var b=[];a(".block-acme-agenda-minutes").each(function(){var c=a(this).find("table").find("th").first().html();b.push(c);var d=ACME.AgAndMin.getAttr(a(this).find("table"));a(this).data("type_machine_name",d.tmn),a(this).data("category_field",d.cf),a(this).data("date_field",d.df),ACME.AgAndMin.meetings.push(a(this))});var c=document.createElement("div");a(c).attr("id","agandmin_container");var d=document.createElement("div");a(d).attr("class","col-sm-3");var e=document.createElement("select");a(e).attr("class","meetings");var f=document.createElement("option");a(f).html("-- CHOOSE COMMITTEE --"),a(f).attr("value",""),a(e).append(f),a.each(b,function(b,c){var d=document.createElement("option");a(d).attr("value",b),a(d).html(c),a(e).append(d)}),a(d).append(e),a(c).append(d);var g=document.createElement("div");a(g).attr("class","col-sm-3");var e=document.createElement("select");a(e).attr("class","years");var f=document.createElement("option");a(f).html("-- CHOOSE YEAR --"),a(f).attr("value",""),a(e).append(f);var h=ACME.Ajax.getData("getYears",{});ACME.Ajax.dataResult(h,function(b){a.each(b.message,function(b,c){var d=document.createElement("option");a(d).attr("value",c),a(d).html(c),a(e).append(d)})}),a(g).append(e),a(c).append(g),a(c).insertAfter("article.meetings-agendas-and-minutes-pag")},getByYear:function(a,b){var c=a("#agandmin_container .meetings").val();""==c&&(c=!1);a("#agandmin_container .years").val();a.logThis("m = "+c);var d=c;c||(d=0);var e=ACME.AgAndMin.meetings[d]?ACME.AgAndMin.meetings[d]:!1,f=ACME.Ajax.getData("getByYear",{year:""==b?!1:b,type:e.data("type_machine_name"),dfield:e.data("date_field"),meeting:c});a(".block-acme-agenda-minutes").fadeOut("fast",function(){a(".block-acme-agenda-minutes").find("tbody").html('<tr><td colspan="4">There are no meetings for this time period.</td></tr>'),ACME.Ajax.dataResult(f,function(b){c?(e.find("tbody").html(b.message),e.fadeIn("fast")):(a.each(b.message,function(a,b){"undefined"!=typeof ACME.AgAndMin.meetings[a]&&ACME.AgAndMin.meetings[a].find("tbody").html(b)}),a(".block-acme-agenda-minutes").fadeIn("fast"))})})},getAttr:function(a){for(var b={tmn:"",cf:""},c=a.attr("class").split(/\s+/),d=c.length,e=0;d>e;e++)c[e].indexOf("type_machine_name-")>-1&&(b.tmn=c[e].replace("type_machine_name-","")),c[e].indexOf("category_field")>-1&&(b.cf=c[e].replace("category_field-","")),c[e].indexOf("date_field")>-1&&(b.df=c[e].replace("date_field-",""));return b}},Ajax:{ajaxurl:"",init:function(a,b){this.ajaxurl=a,"function"==typeof b&&b()},getData:function(a,b,c){"undefined"==typeof c&&(c=this.ajaxurl+"/"+a),jQuery.logThis("send ajax "+c);var d={method:a,data:b};return jQuery.ajax({url:c,data:d,type:"post",dataType:"json"})},dataResult:function(a,b,c){a.done(function(a){if(a=a[0],1==a.success)"function"==typeof b&&b(a);else if("undefined"!=typeof a.errors){var d="";jQuery.each(a.errors,function(a,b){d+=b+"\n"}),"function"==typeof c?c(d):alert("An error occured "+d)}else"function"==typeof c?c(a.message):alert("An error occured "+a.message)}),a.fail(function(a,b,c){var d="An Error occurred...";"undefined"!=typeof a&&jQuery.logThis("xhr error "+a.status),"undefined"!=typeof c&&jQuery.logThis("thrownError "+c),alert(d)})}}},ajaxpath="/acme/ajax",show_log=!0;!function(a){a.logThis=function(a){show_log&&void 0!==window.console&&console.log(a)}}(jQuery),function(a,b,c,d){var e=a;"undefined"!=typeof e(".block-agendas-meetings-agenda-minutes")&&d.Ajax.init("/acme/ajax",function(){d.AgAndMin.init(e)}),d.KeyBindings(e),e.logThis("acme module js loaded")}(jQuery,Drupal,drupalSettings,ACME);