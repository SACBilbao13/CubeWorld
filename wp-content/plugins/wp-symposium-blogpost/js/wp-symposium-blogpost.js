jQuery(document).ready(function() {

	if (__wps__.q == '&amp;') {
		__wps__.q = '&';
	}

	jQuery('#showmore_blogpost').live('click', function() {
		
		
		jQuery('#showmore_blogpost').html("<img src='"+__wps__.images_url+"/busy.gif' />");
		
		var start = parseInt(jQuery("#symposium_blogpost_start").html());
		var page_length = parseInt(jQuery('#symposium_blogpost_page_length').html());
		start=start+page_length;

		jQuery.ajax({
			url: __wps__.plugins+'/wp-symposium-blogpost/ajax/wp-symposium-blogpost_functions.php',
			type: "POST",
			data: ({
				action:"getPosts",
				uid1:__wps__.current_user_page,
				start:start,
				page_length:page_length
			}),
		    dataType: "html",
			async: true,
			success: function(str){				
				jQuery('#symposium_blogpost_start').html(start);
				jQuery('#showmore_blogpost').remove();
				jQuery(str).appendTo('#symposium_blogpost').hide().slideDown("slow");
			  	if (str != "OK") {
					//alert(str);
				}
			},
			error: function(err){
				alert("BlogPosts: error #"+err);
			}	
   		});
		
   	});		

});
