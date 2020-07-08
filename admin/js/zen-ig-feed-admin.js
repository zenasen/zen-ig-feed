(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function() {

		/*-- PUBLIC FUNCTION --*/
		function init_tabs(){
			if($( "#zenigfeed_plugin_dashboard").length>0){
				$( "#zenigfeed_plugin_dashboard" ).tabs({active: 0});
			}
		}
		init_tabs();


		// selected source
		var selected_source_fun = {
			get_method:function(){
				var selected_source = $("#zenigfeed_plugin_dashboard #select_source_method").val();
				return selected_source;
			},
			set_method:function(value){
				$("#zenigfeed_plugin_dashboard #select_source_method").val(value);
			},
			show_method_source_by_select:function(){
				var selected_source = $("#zenigfeed_plugin_dashboard #select_source_method").val();
				console.log(selected_source);
				$("#zenigfeed_plugin_dashboard .source_option").removeClass("active");
				$("#zenigfeed_plugin_dashboard #"+selected_source).addClass("active");

				var data_container = $('.data-ig-container');
	 			data_container.find(".list-item-ig").remove();
			}
		}
		$("#zenigfeed_plugin_dashboard #select_source_method").change(function(){
			selected_source_fun.show_method_source_by_select();

			display_from_metabox("load");
		});
		// selected source end
		

		function create_el(type, value, label){
	 		var el = "";
	 		if(typeof value === 'undefined'){
	 			return;
	 		}
	 		if(type == "a"){
	 			el = '<p>'+label+' : <a href="'+value+'" target="_blank">'+label+'</a></p>';
	 		}
	 		if(type == "p"){
	 			el = '<p>'+label+' : '+value+'</p>';
	 		}
	 		if(type == "img"){
	 			el = '<img src="'+value+'">';
	 		}
	 		return el;
	 	}

	 	function display_from_metabox(v_selected_source){
	 		if($("#metabox_data_ig").length < 1){
	 			return;
	 		}
	 		var data_str = $("#metabox_data_ig").val();
	 		if(data_str.length < 20){
	 			return;
	 		}
	 		var data_obj = JSON.parse(data_str);
	 		console.log(data_obj);
	 		var data_id = data_obj.user_id;
	 		var data_token = data_obj.token;
	 		var data_url = data_obj.ig_url;
	 		var data_method = data_obj.source_method;

	 		var data_media_obj = data_obj.media_list;
	 		$('input[name="ig_id"]').val(data_id);
	 		$('input[name="access_token"]').val(data_token);
	 		$('input[name="ig_url"]').val(data_url);
	 		var data_resource = data_obj.source_method;
	 		

	 		var data_container = $('.data-ig-container');
	 		data_container.find(".list-item-ig").remove();;

	 		if(v_selected_source){
	 			var selected_method = selected_source_fun.get_method();
	 			if(data_method != selected_method)	{
	 				return
	 			}
	 		}else{
	 			selected_source_fun.set_method(data_method);
	 			selected_source_fun.show_method_source_by_select();
	 		}

	 		
	 		
	 		var counter = 0;
	 		$.each(data_media_obj, function() {
	 			var media_n = $(this)[0];
	 			counter+=1;

	 		  //copy ori item 
	 		  var data_list_origin = $("#list_item_ig_origin").clone();
	 		  var item_class = data_list_origin.attr("xclass");
	 		  data_list_origin.removeAttr("id");
	 		  data_list_origin.removeAttr("style");
	 		  data_list_origin.addClass(item_class);
	 		  
	 		  //create el
	 		  var type = media_n.media_type;
	 		  var el_image = create_el("img", media_n.media_url,'');
	 		  var el_thumbnail_url = create_el("a", media_n.thumbnail_url,'thumbnail_url');
	 		  if(type == "VIDEO"){
	 		  	el_image = create_el("img", media_n.thumbnail_url,'');  
	 		  	el_thumbnail_url = create_el("a", media_n.thumbnail_url,'thumbnail_url');
	 		  }
	 		  var el_counter = create_el("p", counter,'no');
	 		  var el_id = create_el("p", media_n.id,'id');
	 		  var el_type = create_el("p", media_n.media_type,'media_type');
	 		  var el_media_link = create_el("a", media_n.media_url,'media_url');
	 		  var el_permalink = create_el("a", media_n.permalink,'permalink');
	 		  
	 		  
	 		  //append new el
	 		  data_list_origin.find(".img-wrapper").append(el_image);
	 		  data_list_origin.find(".text-wrapper").append(el_counter, el_id, el_type, el_media_link, el_permalink, el_thumbnail_url);
	 		  
	 		  data_container.append(data_list_origin);
	 		  
	 		});
	 	}
	 	display_from_metabox();

	 	$("button#display_ig").click(function(e){
	 		e.preventDefault();
	 		display_from_metabox();
	 	});

	 	$("button#load_ig").click(function(e){
	 		e.preventDefault();
	 		var this_btn = $(this);
	 		var user_id = $('input[name="ig_id"]').val();
	 		var user_token = $('input[name="access_token"]').val();
	 		var ig_url = $('input[name="ig_url"]').val();
	 		var source_method = selected_source_fun.get_method();
	 		var data = {
	 		 	'action': 'zenigfeed_load_ig2',
	 		 	'user_id': user_id,
		      	'token': user_token,
		      	'ig_url': ig_url,
	 		 	"source_method":source_method,
		    };

	 		
	 		
	 		this_btn.addClass("loading");

	 		if(source_method == "tabs_source_m1"){
	 			data.action = 'zenigfeed_load_ig';

		 		var j_ajax = jQuery.post(ajax_object.ajax_url, data,  function(response) {
		 			console.log(response)
		 			var data_json_str = response;
		 			$("#metabox_data_ig").val(data_json_str);
		 			display_from_metabox();
		 			this_btn.removeClass("loading"); 
		 
		 		});

		 		j_ajax.fail(function(data) {
		 			this_btn.removeClass("loading"); 
		 		});
	 		}
	 		if(source_method == "tabs_source_m2"){
	 			
	 			var j_ajax = jQuery.post(ajax_object.ajax_url, data,  function(response) {
		 			console.log(response)
		 			var data_json_str = response;
		 			$("#metabox_data_ig").val(data_json_str);
		 			display_from_metabox();
		 			this_btn.removeClass("loading"); 
		 
		 		});

		 		j_ajax.fail(function(data) {
		 			this_btn.removeClass("loading"); 
		 		});
	 		}

	 		console.log(data);

	 	});
		/*-- PUBLIC FUNCTION END --*/

	});


})( jQuery );
