jQuery(document).ready(function() {

	
	/* Set global javascript variables */

	var show_js_errors = false; // Show errors returned from .ajax calls
	// Force current user ID if using permalinks
	if (jQuery('#__wps__current_user_page').length) {
		__wps__.current_user_page = jQuery('#__wps__current_user_page').html();
	}

/*
	   +------------------------------------------------------------------------------------------+
	   |                                          SHARED                                          |
	   +------------------------------------------------------------------------------------------+
	*/

	// Show/hide a div (with passed ID)
	jQuery(".symposium_expand").click(function() {
		jQuery(this).hide();
		jQuery(this).next(".expand_this").slideDown("slow");
	});

	// Get translated strings
	var symposium_clear = __wps__.clear;
	var symposium_update = __wps__.update;
	var symposium_cancel = __wps__.cancel;
	var write_a_comment = __wps__.write_a_comment;
	var btn_add = __wps__.add;
	var show_original = __wps__.show_original;
	var add_a_comment = __wps__.add_a_comment;
	var pleasewait = __wps__.pleasewait;
	var saving = __wps__.saving;
	var more = __wps__.more;
	var browseforfile = __wps__.browseforfile;
	var attachfile = __wps__.attachfile;
	var attachimage = __wps__.attachimage;
	var whatsup = __wps__.whatsup;
	var symposium_next = __wps__.next;
	var symposium_likes = __wps__.likes;
	var symposium_dislikes = __wps__.dislikes;
	var areyousure = __wps__.areyousure;

	// Centre in screen
	jQuery.fn.inmiddle = function() {
		this.css("position", "absolute");
		this.css("top", (jQuery(window).height() - this.height()) / 2 + jQuery(window).scrollTop() + "px");
		this.css("left", (jQuery(window).width() - this.width()) / 2 + jQuery(window).scrollLeft() + "px");
		return this;
	}

	// Check if really want to delete	    
	jQuery(".delete").click(function() {
		var answer = confirm(areyousure);
		return answer // answer is a boolean
	});
	jQuery(".deletebutton").live('click', function() {
		var answer = confirm(areyousure);
		return answer // answer is a boolean
	});

	// Global dialog
	jQuery('.symposium-dialog').live('click', function() {
		var id = jQuery(this).attr("rel");
		var title = jQuery('#' + id).attr("title");
		var str = '<div style="width:100%; text-align:center">' + jQuery('#' + id).html() + '</div>';
		jQuery("#dialog").html(str).dialog({
			title: title,
			width: 800,
			height: 500,
			modal: true,
			buttons: {
				"OK": function() {
					jQuery("#dialog").dialog('close');
				}
			}
		});
	});

/*
	   +------------------------------------------------------------------------------------------+
	   |                                       WIDGETS (AJAX)                                     |
	   +------------------------------------------------------------------------------------------+
	*/

	if (jQuery("#symposium_summary_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "symposium_summary_Widget",
				show_loggedout: jQuery("#symposium_summary_Widget_show_loggedout").html(),
				form: jQuery("#symposium_summary_Widget_form").html(),
				login_url: jQuery("#symposium_summary_Widget_login_url").html(),
				show_avatar: jQuery("#symposium_summary_Widget_show_avatar").html(),
				login_username: jQuery("#symposium_summary_Widget_login_username").html(),
				login_password: jQuery("#symposium_summary_Widget_login_password").html(),
				login_remember_me: jQuery("#symposium_summary_Widget_login_remember_me").html(),
				login_button: jQuery("#symposium_summary_Widget_login_button").html(),
				login_forgot: jQuery("#symposium_summary_Widget_login_forgot").html(),
				login_register: jQuery("#symposium_summary_Widget_login_register").html(),
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#symposium_summary_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#symposium_friends_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "symposium_friends_Widget",
				count: jQuery("#symposium_friends_count").html(),
				desc: jQuery("#symposium_friends_desc").html(),
				mode: jQuery("#symposium_friends_mode").html(),
				show_light: jQuery("#symposium_friends_show_light").html(),
				show_mail: jQuery("#symposium_friends_show_mail").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#symposium_friends_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#__wps__Forumexperts_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "Forumexperts_Widget",
				cat_id: jQuery("#__wps__Forumexperts_Widget_cat_id").html(),
				cat_id_exclude: jQuery("#__wps__Forumexperts_Widget_cat_id_exclude").html(),
				timescale: jQuery("#__wps__Forumexperts_Widget_timescale").html(),
				postcount: jQuery("#__wps__Forumexperts_Widget_postcount").html(),
				groups: jQuery("#__wps__Forumexperts_Widget_groups").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#__wps__Forumexperts_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#__wps__Forumnoanswer_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "Forumnoanswer_Widget",
				preview: jQuery("#__wps__Forumnoanswer_Widget_preview").html(),
				cat_id: jQuery("#__wps__Forumnoanswer_Widget_cat_id").html(),
				cat_id_exclude: jQuery("#__wps__Forumnoanswer_Widget_cat_id_exclude").html(),
				timescale: jQuery("#__wps__Forumnoanswer_Widget_timescale").html(),
				postcount: jQuery("#__wps__Forumnoanswer_Widget_postcount").html(),
				groups: jQuery("#__wps__Forumnoanswer_Widget_groups").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#__wps__Forumnoanswer_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#__wps__recent_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "recent_Widget",
				count: jQuery("#__wps__recent_Widget_count").html(),
				desc: jQuery("#__wps__recent_Widget_desc").html(),
				show_light: jQuery("#__wps__recent_Widget_show_light").html(),
				show_mail: jQuery("#__wps__recent_Widget_show_mail").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#__wps__recent_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#symposium_members_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "members_Widget",
				count: jQuery("#symposium_members_Widget_count").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#symposium_members_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#__wps__friends_status_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "friends_status_Widget",
				postcount: jQuery("#__wps__friends_status_postcount").html(),
				preview: jQuery("#__wps__friends_status_preview").html(),
				forum: jQuery("#__wps__friends_status_forum").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#__wps__friends_status_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#symposium_Recentactivity_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "Recentactivity_Widget",
				postcount: jQuery("#symposium_Recentactivity_Widget_postcount").html(),
				preview: jQuery("#symposium_Recentactivity_Widget_preview").html(),
				forum: jQuery("#symposium_Recentactivity_Widget_forum").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#symposium_Recentactivity_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#__wps__Forumrecentposts_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/widget_functions.php",
			data: ({
				action: "__wps__Forumrecentposts_Widget",
				postcount: jQuery("#__wps__Forumrecentposts_Widget_postcount").html(),
				preview: jQuery("#__wps__Forumrecentposts_Widget_preview").html(),
				cat_id: jQuery("#__wps__Forumrecentposts_Widget_cat_id").html(),
				show_replies: jQuery("#__wps__Forumrecentposts_Widget_show_replies").html(),
				incl_cat: jQuery("#__wps__Forumrecentposts_Widget_incl_cat").html(),
				incl_parent: jQuery("#__wps__Forumrecentposts_Widget_incl_parent").html(),
				just_own: jQuery("#__wps__Forumrecentposts_Widget_just_own").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#__wps__Forumrecentposts_Widget").html(str);
				}
			}
		});
	}

	if (jQuery("#symposium_Gallery_Widget").length) {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			data: ({
				action: "Gallery_Widget",
				albumcount: jQuery("#symposium_Gallery_Widget_albumcount").html()
			}),
			type: "POST",
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				} else {
					jQuery("#symposium_Gallery_Widget").html(str);
				}
			}
		});
	}

/*
	   +------------------------------------------------------------------------------------------+
	   |                                        WIDGET: LOGIN                                     |
	   +------------------------------------------------------------------------------------------+
	*/

	if (jQuery("#symposium-widget-login").length) {
		jQuery("#symposium_widget_login_button").click(function() {

			jQuery(".__wps__pleasewait").inmiddle().show();

			var show_form = '';
			if (jQuery("#symposium-widget-login").length) {
				show_form = 'on';
			}

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/widget_functions.php",
				type: "POST",
				data: ({
					action: "doLogin",
					username: jQuery("#symposium_widget_login").val(),
					password: jQuery("#symposium_widget_password").val(),
					show_form: show_form,
					login_url: jQuery("#symposium_widget_login_button").attr("title")
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str.substring(0, 4) == 'FAIL') {
						jQuery(".__wps__pleasewait").hide();
						alert('Login Failed');
					} else {
						window.location.href = str;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
		});
	}

	jQuery('#symposium_widget_password').keypress(function(event) {
		if (event.which == 13) {

			var show_form = '';
			if (jQuery("#symposium-widget-login").length) {
				show_form = 'on';
			}

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/widget_functions.php",
				type: "POST",
				data: ({
					action: "doLogin",
					username: jQuery("#symposium_widget_login").val(),
					password: jQuery("#symposium_widget_password").val(),
					show_form: show_form,
					login_url: jQuery("#symposium_widget_login_button").attr("title")
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str.substring(0, 4) == 'FAIL') {
						jQuery(".__wps__pleasewait").hide();
						alert('Login Failed');
					} else {
						window.location.href = str;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
		}
	})


/*
	   +------------------------------------------------------------------------------------------+
	   |                                     MEMBER DIRECTORY                                     |
	   +------------------------------------------------------------------------------------------+
	*/



	// Show advanced search
	jQuery('#symposium_show_advanced').live('click', function() {
		jQuery('#symposium_advanced_search').toggle();
		jQuery('#symposium_show_advanced').hide();
	});

	// Show mail link on friend hover
	jQuery('.members_row').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".mail_icon").show();
		} else {
			jQuery(this).find(".mail_icon").hide();
		}
	});

	jQuery('#symposium_member').live('keydown', function(e) {
		var keyCode = e.keyCode || e.which;

		if (keyCode == 9 || keyCode == 27) {
			jQuery('#symposium_member_list').hide();
		}

	});

	// Order by
	jQuery('#symposium_members_orderby').change(function() {
		jQuery("#symposium_directory_start").html(0);
		symposium_do_member_search(true);
	});

	jQuery('#members_go_button').live('click', function() {
		jQuery("#symposium_directory_start").html(0);
		symposium_do_member_search(true);
	});
	jQuery('#symposium_member').live('keypress', function(e) {
		if (e.keyCode == 13) {
			jQuery("#symposium_directory_start").html(0);
			symposium_do_member_search(true);
		}
	});

	// Search
	jQuery('#showmore_directory').live('click', function() {
		symposium_do_member_search(false);
	});

	function symposium_do_member_search(clear) {
		
		if (__wps__.dir_full_ver) {
			jQuery('#symposium_members_orderby_div').hide();
		}

		if (clear) {
			jQuery('#__wps__members').html("<img src='" + __wps__.images_url + "/busy.gif' />");
		} else {
			jQuery('#showmore_directory_div').html("<br /><img src='" + __wps__.images_url + "/busy.gif' />");
		}

		// check for extended fields
		if (jQuery(".symposium_extended_search").length && jQuery("#symposium_advanced_search").css("display") != 'none') {
			var extended = new Array();
			jQuery(".symposium_extended_search").each(function(index) {
				var eid = jQuery(this).attr("id");
				switch (jQuery(this).attr("rel")) {
				case 'list':
					var value = jQuery(this).val();
					break;
				case 'checkbox':
					if (jQuery(this).is(":checked")) {
						var value = 'on';
					} else {
						var value = '';
					};
					break;
				}
				extended.push(jQuery(this).attr("rel") + '|' + eid + '|' + value);
			});
		} else {
			var extended = '';
		}

		var page_length = jQuery('#symposium_directory_page_length').html();
		var start = jQuery("#symposium_directory_start").html();

		var friends = '';
		if (jQuery("#symposium_member_friends").is(":checked")) {
			var friends = 'on';
		};

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/members_functions.php",
			type: "POST",
			data: ({
				action: "getMembers",
				friends: friends,
				start: start,
				orderby: jQuery('#symposium_members_orderby').val(),
				page_length: jQuery('#symposium_directory_page_length').html(),
				term: jQuery('#symposium_member').val(),
				extended: extended,
				roles: jQuery('#__wps__directory_roles').html()
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				if (__wps__.debug == 'on') {
					alert(str);
				}

				if (clear) {
					jQuery("#__wps__members").html('');
				}
				jQuery('#showmore_directory_div').remove();
				var new_start = parseFloat(start) + parseFloat(page_length) + 1;
				jQuery("#symposium_directory_start").html(new_start);

				if (start == 0) {
					jQuery('#__wps__members').html(str);
				} else {
					jQuery(str).appendTo('#__wps__members').hide().slideDown("slow");
				}

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});
	}

	// Autocomplete
	if (jQuery("input#symposium_member").length) {

		jQuery("input#symposium_member").autocomplete({
			source: function(request,response) {
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/members_functions.php",
					data: {
						term: request.term,
						roles: jQuery('#__wps__directory_roles').html()
					},
					dataType: "json",
					success: function(data) {
						response(data);
					}
				});				
			},
			minLength: 3,
			focus: function(event, ui) {
				jQuery("input#symposium_member").val(ui.item.name);
				return false;
			},
			select: function(event, ui) {
				jQuery(".__wps__pleasewait").inmiddle().show().delay(3000).fadeOut("slow");
				var q = symposium_q(__wps__.profile_url);
				window.location.href = __wps__.profile_url + q + 'uid=' + ui.item.value;
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			var group = "<a>";
			group += "<div style='height:40px; overflow:hidden'>";
			group += "<div style=\'float:left; background-color:#fff; margin-right: 8px; width:40px; height:40px; \'>";
			group += item.avatar;
			group += "</div>";
			group += "<div>" + item.name + "</div>";
			var sep = (item.city != '' && item.country != '') ? ', ' : '';
			group += "<div style='font-size:80%'>" + item.city + sep + item.country + "</div>";
			group += "<br style='clear:both' />";
			group += "</div>";
			group += "</a>";
			return jQuery("<li></li>").data("item.autocomplete", item).append(group).appendTo(ul);
		};
	}



/*
	   +------------------------------------------------------------------------------------------+
	   |                                           MAIL                                           |
	   +------------------------------------------------------------------------------------------+
	*/
	// Switch tabs
	jQuery("#symposium_compose_tab").live('click', function() {

		jQuery('#symposium_compose_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

		// Clear the form
		jQuery('#mail_recipient_id').html('');
		jQuery('#compose_recipient_name').val('');
		jQuery('#compose_subject').val('');
		jQuery('#compose_text').val('');
		jQuery('#compose_previous').val('');
		jQuery("#mail_sent_message").hide();
		jQuery("#compose_form").show();

		jQuery("#compose_form").show();
		jQuery("#mail-main-div #mailbox").hide();

	});
	jQuery("#symposium_inbox_tab").live('click', function() {

		jQuery('#symposium_compose_tab').removeClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

		jQuery("#compose_form").hide();
		jQuery("#mail-main-div #mailbox").show();

		change_tray();

	});
	jQuery("#symposium_sent_tab").live('click', function() {

		jQuery('#symposium_compose_tab').removeClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-active-link");

		jQuery("#compose_form").hide();
		jQuery("#mail-main-div #mailbox").show();

		change_tray();

	});

	// Go straight to compose form
	if (__wps__.view == 'compose') {

		jQuery(".__wps__pleasewait").inmiddle().show();

		var mail_to = __wps__.to;
		jQuery('#mail_recipient_list').val(mail_to);

		jQuery("#compose_form").show();
		jQuery("#mail-main-div #mailbox").hide();

		jQuery('#compose_subject').focus();
		jQuery(".__wps__pleasewait").fadeOut("slow");

		__wps__.view = 'in';

		// Switch tabs
		jQuery('#symposium_compose_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

	};

	// Default load	
	if (jQuery("#compose_form").length && __wps__.view != 'compose') {

		// Load box on first page load
		jQuery('#mailbox_list').html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery('#messagebox').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				uid: __wps__.current_user_page,
				action: "getBox",
				tray: "in",
				unread: jQuery("#unread_only").is(":checked"),
				term: "",
				start: jQuery('#next_message_id').html(),
				length: 5
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				if (strpos(str, 'mail_mid')) {

					var html = "";

					var msg_count = 0;

					var template = __wps__.template_mail_tray;

					template = template.replace(/\\/g, '');
					template = template.replace(/&lt;/g, '<');
					template = template.replace(/&gt;/g, '>');
					template = template.replace(/\[\]/g, '');

					var rows = jQuery.parseJSON(str);
					jQuery.each(rows, function(i, row) {

						msg_count++;
						if (msg_count == 1) {
							jQuery('#next_message_id').html(row.next_message_id);
						} else {

							if (html == "") {

								// Check for default mail ID
								var mail_id = row.mail_mid;
								if (__wps__.mail_id != '') {
									mail_id = __wps__.mail_id;
								}

								// Show first message as default message
								jQuery.ajax({
									url: __wps__.plugin_url + "ajax/mail_functions.php",
									type: "POST",
									data: ({
										action: "getMailMessage",
										tray: "in",
										mid: mail_id
									}),
									dataType: "html",
									async: true,
									success: function(str) {
										var details = str.split("[split]");
										if (details[2] == "in") {
											jQuery("#" + details[0]).removeClass("row");
											jQuery("#" + details[0]).addClass("row_odd");
										}
										jQuery("#messagebox").html(details[3]);
										if (details[1] > 0) {
											jQuery("#in_unread").html('(' + details[1] + ')');
										} else {
											jQuery("#in_unread").html('');
										}
										jQuery(".__wps__pleasewait").fadeOut("slow");
									},
									error: function(xhr, ajaxOptions, thrownError) {
										if (show_js_errors) {
											alert(xhr.status);
											alert(xhr.statusText);
											alert(thrownError);
										}
									}
								});
							}

							var new_item = template;
							new_item = new_item.replace(/mail_mid/, row.mail_mid);
							new_item = new_item.replace(/mail_read/, row.mail_read);
							new_item = new_item.replace(/\[mail_sent\]/, row.mail_sent);
							new_item = new_item.replace(/\[mail_from\]/, row.mail_from);
							new_item = new_item.replace(/\[mail_subject\]/, row.mail_subject);
							new_item = new_item.replace(/\[bulk_action\]/, "<input type='checkbox' data-mid='"+row.mail_mid+"' class='bulk_action'>");

							if (row.message != null) {
								new_item = new_item.replace(/\[mail_message\]/, row.message);
							} else {
								new_item = new_item.replace(/\[mail_message\]/, '');
							}
							html += new_item;

						}

					});

					html += '<div id="show_more_mail" style="text-align:center; padding:6px; cursor:pointer;">' + more + '</div>';
					jQuery('#mailbox_list').html(html);

				} else {

					jQuery('#mailbox_list').html('');
					jQuery('#messagebox').html('');

				}

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	}
	
	// Mail bulk actions
	jQuery(".bulk_action").live('click', function() {
		if (jQuery('.bulk_action').is(":checked")) {
			jQuery('#__wps__mail_bulk_action').val('');
		} else {
			jQuery('#__wps__mail_bulk_action').val('');
		}
	});
	jQuery('#__wps__mail_bulk_action').change(function() {
		var action = jQuery('#__wps__mail_bulk_action').val();
		// bulk: delete all
		if (action == 'deleteall') {
			if (confirm(areyousure)) {
				// check which tray
				var tray = 'in';
				if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active"))
					var tray = 'sent';
				// do the delete
				jQuery(".__wps__pleasewait").inmiddle().show();
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/mail_functions.php",
					type: "POST",
					data: ({
						action: "bulk_delete",
						data: '',
						tray: tray,
						uid: __wps__.current_user_id,
						scope: 'all'
					}),
					dataType: "html",
					async: false,
					success: function(str) {
						if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
					}
				});
				change_tray();
				jQuery(".__wps__pleasewait").fadeOut("slow");
			}
		}

		// bulk: recover all
		if (action == 'recoverall') {
			if (confirm(areyousure)) {
				// check which tray
				var tray = 'in';
				if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active"))
					var tray = 'sent';
				// do the delete
				jQuery(".__wps__pleasewait").inmiddle().show();
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/mail_functions.php",
					type: "POST",
					data: ({
						action: "bulk_recover",
						tray: tray,
						uid: __wps__.current_user_id
					}),
					dataType: "html",
					async: false,
					success: function(str) {
						if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
					}
				});
				change_tray();
				jQuery(".__wps__pleasewait").fadeOut("slow");
			}
		}

		// bulk: mark all as read
		if (action == 'readall') {
			if (confirm(areyousure)) {
				// do the mark
				jQuery(".__wps__pleasewait").inmiddle().show();
				// check which tray
				var tray = 'in';
				if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active")) {
					alert('You cannot mark sent mail as read!');
				} else {
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/mail_functions.php",
						type: "POST",
						data: ({
							action: "bulk_readall",
							uid: __wps__.current_user_id
						}),
						dataType: "html",
						async: false,
						success: function(str) {
							if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
						}
					});
					change_tray();
				}
				jQuery(".__wps__pleasewait").fadeOut("slow");
			}
		}
						
								
		// bulk: delete checked
		if (action == 'delete') {
			if (jQuery('.bulk_action').is(":checked")) {
				if (confirm(areyousure)) {
					var bulk_list = [];
					jQuery('.bulk_action').each(function(){
						var this_mail = jQuery(this);
						if (typeof this_mail != 'undefined' && this_mail.attr("data-mid") != 'undefined' && this_mail.is(":checked")) {
							bulk_list.push(this_mail.attr("data-mid"));
							if (action == 'delete') {
								jQuery(this_mail).hide();
								jQuery('#'+this_mail.attr("data-mid")).hide();
							}
						}
					});	
					jQuery('.bulk_action').attr('checked', false);
					// check which tray
					var tray = 'in';
					if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active"))
						var tray = 'sent';
					// do the delete
					jQuery(".__wps__pleasewait").inmiddle().show();
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/mail_functions.php",
						type: "POST",
						data: ({
							action: "bulk_delete",
							data: bulk_list,
							tray: tray,
							uid: __wps__.current_user_id,
							scope: 'marked'
						}),
						dataType: "html",
						async: false,
						success: function(str) {
							if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
						}
					});
					change_tray();
					jQuery(".__wps__pleasewait").fadeOut("slow");
				}	
			} else {
				jQuery('#__wps__mail_bulk_action').val('');
			}
		}				
	});

	// Clicked on More...
	jQuery("#show_more_mail").live('click', function() {
		
		var tray = 'in';
		if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active")) {
			var tray = 'sent';
		};

		jQuery('#show_more_mail').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var template = __wps__.template_mail_tray;

		template = template.replace(/\\/g, '');
		template = template.replace(/&lt;/g, '<');
		template = template.replace(/&gt;/g, '>');
		template = template.replace(/\[\]/g, '');

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				uid: __wps__.current_user_page,
				action: "getBox",
				tray: tray,
				unread: jQuery("#unread_only").is(":checked"),
				term: "",
				start: jQuery('#next_message_id').html()
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				if (strpos(str, 'mail_mid')) {

					var msg_count = 0;
					var html = "";
					var rows = jQuery.parseJSON(str);
					jQuery.each(rows, function(i, row) {

						msg_count++;
						if (msg_count == 1) {
							jQuery('#next_message_id').html(row.next_message_id);
						} else {

							var new_item = template;
							new_item = new_item.replace(/mail_mid/, row.mail_mid);
							new_item = new_item.replace(/mail_read/, row.mail_read);
							new_item = new_item.replace(/\[mail_sent\]/, row.mail_sent);
							new_item = new_item.replace(/\[mail_from\]/, row.mail_from);
							new_item = new_item.replace(/\[mail_subject\]/, row.mail_subject);
							new_item = new_item.replace(/\[mail_message\]/, row.message);
							new_item = new_item.replace(/\[bulk_action\]/, "<input type='checkbox' data-mid='"+row.mail_mid+"' class='bulk_action'>");
							html += new_item;

						}

					});

					html += '<div id="show_more_mail" style="text-align:center; padding:6px; cursor:pointer;">' + more + '</div>';
					
					jQuery('#show_more_mail').remove();
					jQuery(html).appendTo('#mailbox_list');

				} else {
					// No more...
					jQuery('#show_more_mail').remove();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// Send
	jQuery("#mail_send_button").live('click', function() {

		var recipient_id = jQuery("#mail_recipient_list").val();

		jQuery("#compose_form").hide();
		jQuery('#mail_sent_message').show().html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery("#mail_office").show();

		// Strip out HTML tags, and then replace URL with a hyperlink
		var msg = jQuery('#compose_text').val().replace(/(<([^>]+)>)/ig, '');
		var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
		msg = msg.replace(exp, "<a href='$1'>$1</a>");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				action: "sendMail",
				compose_recipient_id: recipient_id,
				compose_subject: jQuery('#compose_subject').val().replace(/(<([^>]+)>)/ig, ''),
				compose_text: msg,
				compose_previous: jQuery('#compose_previous').val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#mail_sent_message").html(str);
				jQuery("#mail_sent_message").delay(1000).slideUp("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

		// Show InBox...
		jQuery('#symposium_compose_tab').removeClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

		jQuery("#compose_form").hide();
		jQuery("#mail-main-div #mailbox").show();

		change_tray();

	});


	// Delete message
	jQuery(".message_delete").live('click', function() {

		if (confirm(areyousure)) {

			var tray = 'in';
			if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active")) {
				var tray = 'sent';
			};

			var mail_id = jQuery(this).attr("id");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/mail_functions.php",
				type: "POST",
				data: ({
					action: "deleteMail",
					mid: mail_id,
					tray: tray
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					jQuery("#messagebox").html(str);
					jQuery("#" + mail_id).slideUp("slow");
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});

		}

	});

	// Reply
	jQuery(".message_reply").live('click', function() {

		var mail_id = jQuery(this).attr("title");
		var mail_from = jQuery(this).attr("id");
		var mail_name = jQuery(this).attr("rel");

		// Change tab to Compose
		jQuery('#symposium_compose_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

		if (jQuery('#mail_recipient_list option[value=' + mail_from + ']').length == 0) {
			jQuery('#mail_recipient_list').append('<option value=' + mail_from + '>' + mail_name + '</option>');
		}

		jQuery('#mail_recipient_list').val(mail_from);
		jQuery('#compose_recipient_name').val('');
		jQuery('#compose_text').val('');
		jQuery('#compose_previous').val('');
		jQuery("#mail_sent_message").hide();

		jQuery("#compose_form").show();
		jQuery("#mail-main-div #mailbox").hide();

		jQuery(".__wps__pleasewait").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				action: "getReply",
				mail_id: mail_id,
				recipient_id: mail_from,
				mail_name: mail_name
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				var detail = jQuery.parseJSON(str);
				jQuery("#compose_subject").val(detail[0].subject);
				jQuery("#compose_text").val(detail[0].message);

				jQuery(".__wps__pleasewait").fadeOut("slow");

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// Search
	jQuery("#search_inbox_go").live('click', function() {
		symposium_do_mail_search();
	});
	jQuery('#search_inbox').live('keypress', function(e) {
		if (e.keyCode == 13) {
			symposium_do_mail_search();
		}
	});

	function symposium_do_mail_search() {
		var term = jQuery("#search_inbox").val();

		var tray = 'in';
		if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active")) {
			var tray = 'sent';
		};

		if (term != '') {
			jQuery('#mailbox_list').html("<img src='" + __wps__.images_url + "/busy.gif' />");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/mail_functions.php",
				type: "POST",
				data: ({
					uid: __wps__.current_user_page,
					action: "getBox",
					tray: tray,
					unread: jQuery("#unread_only").is(":checked"),
					term: term
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					var html = "";
					var rows = jQuery.parseJSON(str);
					var first_mail_mid = '';
					jQuery.each(rows, function(i, row) {
						if (row.mail_mid != undefined) {
							if (first_mail_mid == '') {
								first_mail_mid = row.mail_mid;
							}
							html += "<div id='" + row.mail_mid + "' class='mail_item " + row.mail_read + "'>";
							html += "<div class='mail_item_age'>" + row.mail_sent + "</div>";
							html += "<strong>" + row.mail_from + "</strong><br />";
							html += "<span class='mailbox_message_subject'>" + row.mail_subject + "</span><br />";
							html += "<span class='mailbox_message'>" + row.message + "</span>";
							html += "</div>";
						}
					});
					jQuery('#mailbox_list').html(html);

					// Load first retrieved message
					if (first_mail_mid != '') {

						jQuery('#messagebox').html("<img src='" + __wps__.images_url + "/busy.gif' />");

						// Show first message as default message
						jQuery.ajax({
							url: __wps__.plugin_url + "ajax/mail_functions.php",
							type: "POST",
							data: ({
								action: "getMailMessage",
								tray: "in",
								mid: first_mail_mid
							}),
							dataType: "html",
							async: true,
							success: function(str) {
								var details = str.split("[split]");
								jQuery("#messagebox").html(details[3]);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								if (show_js_errors) {
									alert(xhr.status);
									alert(xhr.statusText);
									alert(thrownError);
								}
							}
						});

					} else {

						jQuery("#messagebox").html('');

					}

				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
		}
	}

	// Toggle Unread only
	jQuery("#unread_only").live('click', function() {
		change_tray();
	});
	// Change tray
	jQuery(".mail_tray").live('click', function() {
		change_tray();
	});

	function change_tray() {

		jQuery("#search_inbox").val('');
		jQuery('#__wps__mail_bulk_action').val('');

		var tray = 'in';
		if (jQuery("#symposium_sent_tab").hasClass("nav-tab-active")) {
			var tray = 'sent';
		};

		if (tray == 'in') {
			jQuery("#__wps__mark_all").attr('disabled', false);
		} else {
			jQuery("#__wps__mark_all").attr('disabled', true);
		}

		jQuery('#mailbox_list').html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery('#messagebox').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var template = __wps__.template_mail_tray;

		template = template.replace(/\\/g, '');
		template = template.replace(/&lt;/g, '<');
		template = template.replace(/&gt;/g, '>');
		template = template.replace(/\[\]/g, '');

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				uid: __wps__.current_user_page,
				action: "getBox",
				unread: jQuery("#unread_only").is(":checked"),
				tray: tray,
				term: ""
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				if (strpos(str, 'mail_mid')) {

					var msg_count = 0;
					var html = "";
					var rows = jQuery.parseJSON(str);
					jQuery.each(rows, function(i, row) {


						msg_count++;
						if (msg_count == 1) {
							jQuery('#next_message_id').html(row.next_message_id);
						} else {

							if (html == "") {
								// Show first message as default message
								jQuery.ajax({
									url: __wps__.plugin_url + "ajax/mail_functions.php",
									type: "POST",
									data: ({
										action: "getMailMessage",
										tray: tray,
										mid: row.mail_mid
									}),
									dataType: "html",
									async: true,
									success: function(str) {
										var details = str.split("[split]");
										if (details[2] == "in") {
											jQuery("#" + details[0]).removeClass("row");
											jQuery("#" + details[0]).addClass("row_odd");
											if (details[1] > 0) {
												jQuery("#in_unread").html('(' + details[1] + ')');
											} else {
												jQuery("#in_unread").html('');
											}
										}
										jQuery("#messagebox").html(details[3]);
										jQuery(".__wps__pleasewait").fadeOut("slow");
									},
									error: function(xhr, ajaxOptions, thrownError) {
										if (show_js_errors) {
											alert(xhr.status);
											alert(xhr.statusText);
											alert(thrownError);
										}
									}
								});
							}

							var new_item = template;
							new_item = new_item.replace(/mail_mid/, row.mail_mid);
							new_item = new_item.replace(/mail_read/, row.mail_read);
							new_item = new_item.replace(/\[mail_sent\]/, row.mail_sent);
							new_item = new_item.replace(/\[mail_from\]/, row.mail_from);
							new_item = new_item.replace(/\[mail_subject\]/, row.mail_subject);
							new_item = new_item.replace(/\[bulk_action\]/, "<input type='checkbox' data-mid='"+row.mail_mid+"' class='bulk_action'>");
							if (row.message != null) {
								new_item = new_item.replace(/\[mail_message\]/, row.message);
							} else {
								new_item = new_item.replace(/\[mail_message\]/, '');
							}
							html += new_item;

						}

					});
					html += '<div id="show_more_mail" style="text-align:center; padding:6px; cursor:pointer;">' + more + '</div>';
					jQuery('#mailbox_list').html(html);

				} else {

					jQuery('#mailbox_list').html('');
					jQuery('#messagebox').html('');

				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});
	}

	// React to click on message list
	jQuery(".mail_item").live('click', function() {
		
		jQuery('#messagebox').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var mail_mid = jQuery(this).attr("id");

		var tray = 'in';
		if (jQuery("#sent").is(":checked")) {
			var tray = 'sent';
		};

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/mail_functions.php",
			type: "POST",
			data: ({
				action: "getMailMessage",
				tray: tray,
				'mid': mail_mid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				var details = str.split("[split]");
				if (details[2] == "in") {
					jQuery("#" + details[0]).removeClass("row");
					jQuery("#" + details[0]).addClass("row_odd");
				}
				jQuery("#messagebox").html(details[3]);
				if (details[1] > 0 && tray == 'in') {
					jQuery("#in_unread").html('(' + details[1] + ')');
				} else {
					jQuery("#in_unread").html('');
				}
				jQuery(".__wps__pleasewait").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});
	
	});


	// Cancel on Compose
	jQuery("#mail_cancel_button").live('click', function() {
		jQuery("#compose_form").hide();
		jQuery("#mail-main-div #mailbox").show();

		// Show InBox...
		jQuery('#symposium_compose_tab').removeClass("nav-tab-active");
		jQuery('#symposium_compose_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_compose_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_compose_tab a').addClass("nav-tab-inactive-link");

		jQuery('#symposium_inbox_tab').removeClass("nav-tab-inactive");
		jQuery('#symposium_inbox_tab a').removeClass("nav-tab-inactive-link");
		jQuery('#symposium_inbox_tab').addClass("nav-tab-active");
		jQuery('#symposium_inbox_tab a').addClass("nav-tab-active-link");

		jQuery('#symposium_sent_tab').removeClass("nav-tab-active");
		jQuery('#symposium_sent_tab a').removeClass("nav-tab-active-link");
		jQuery('#symposium_sent_tab').addClass("nav-tab-inactive");
		jQuery('#symposium_sent_tab a').addClass("nav-tab-inactive-link");

		jQuery("#compose_form").hide();
		jQuery("#mail-main-div #mailbox").show();

		change_tray();

	});




/*
	   +------------------------------------------------------------------------------------------+
	   |                                         PROFILE                                          |
	   +------------------------------------------------------------------------------------------+
	*/

	// Act on "view" parameter on first page load
	if ((jQuery("#profile_body").length) && (__wps__.embed != 'on')) {

		var menu_id = 'menu_' + __wps__.view;

		if (menu_id == 'menu_in') {
			menu_id = 'menu_friends';
		}

		if (jQuery('#force_profile_page').length && __wps__.view == 'in') {
			menu_id = 'menu_' + jQuery('#force_profile_page').html();
		}
		// Override if sending to a specific post
		if (__wps__.post != '') {
			menu_id = 'menu_wall';
		}

		if ((menu_id == 'menu_extended') || (menu_id == 'menu_wall') || (menu_id == 'menu_activity') || (menu_id == 'menu_all') || (menu_id == 'menu_mentions') || (menu_id == 'menu_groups') || (menu_id == 'menu_friends') || (menu_id == 'menu_avatar') || (menu_id == 'menu_personal') || (menu_id == 'menu_settings')) {
			
			var ajax_path = __wps__.plugin_url + "ajax/profile_functions.php";			

		} else if ((menu_id == 'menu_gallery') || (menu_id == 'menu_events') || (menu_id == 'menu_plus') || (menu_id == 'menu_plus_me') || (menu_id == 'menu_lounge')) {
			
			var ajax_part = menu_id.replace(/menu_/g, "");

			if (strpos(ajax_part, '->') !== false) {
				ajax_sub = ajax_part.substring(0, strpos(ajax_part, '->'));
				var ajax_path = __wps__.plugin_url + "ajax/" + ajax_sub + "_functions.php";
				menu_id = menu_id.replace(/->/g, "_");
			} else {
				ajax_part = ajax_part.replace(/_me/g, "");
				var ajax_path = __wps__.plugin_url + "ajax/" + ajax_part + "_functions.php";
			}
			
		} else {
			// passed in URL
			var ajax_path = __wps__.plugins+'/wp-symposium-'+__wps__.view+'/ajax/wp-symposium-'+__wps__.view+'_functions.php';
			menu_id = 'wp-symposium-'+__wps__.view;
		}
		

		if (menu_id == 'menu_') {
			menu_id = 'menu_wall';
			ajax_path = __wps__.plugin_url + "ajax/profile_functions.php";
		}

		// Highlight default menu choice	      	
		jQuery('#' + menu_id).addClass('__wps__profile_current');

		// Now do AJAX stuff
		jQuery.ajax({
			url: ajax_path,
			type: "POST",
			data: ({
				action: menu_id,
				post: __wps__.post,
				limit_from: 0,
				uid1: __wps__.current_user_page
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				jQuery('#profile_body').html(str);
				
				var user_id = jQuery("#symposium_user_id").html();
				var user_login = jQuery("#symposium_user_login").html();
				var user_email = jQuery("#symposium_user_email").html();

				// Set up auto-expanding textboxes
				if (jQuery(".elastic").length) {
					jQuery('.elastic').elastic();
				}
				
				// Reduce width of status/reply fields on activity to take into account background edit icon
				if (jQuery('.__wps__reply').length) {
					var w = jQuery('.__wps__reply').css('width').replace(/px/g, "");
					jQuery('.__wps__reply').css('width', (w-30)+'px');
				}
				if (jQuery('#__wps__comment').length) {
					var w = jQuery('#__wps__comment').css('width').replace(/px/g, "");
					jQuery('#__wps__comment').css('width', (w-30)+'px');
				}

				// Init file upload
				__wps__init_file_upload();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(xhr.status);
				//alert(xhr.statusText);
				//alert(thrownError);
			}

		});


	}
	
	// For [symposium-stream]
	// 1. re-size comment box
	if (jQuery('.__wps__reply').length) {
		var w = jQuery('.__wps__reply').css('width').replace(/px/g, "");
		jQuery('.__wps__reply').css('width', (w-30)+'px');
	}
	if (jQuery('#__wps__comment').length) {
		var w = jQuery('#__wps__comment').css('width').replace(/px/g, "");
		jQuery('#__wps__comment').css('width', (w-30)+'px');
	}
	// 2. Activity file upload (loads with page shortcode)
	__wps__init_file_upload();
	
	jQuery('.symposium_wall_replies').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery("#__wps__reply_div_" + jQuery(this).attr("id")).hide().slideDown("fast");
		} else {
			jQuery("#__wps__reply_div_" + jQuery(this).attr("id")).slideUp("fast");
		}
	});

	// Show mail/delete link on friend hover
	jQuery('.friend_div').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".friend_icons").show();
		} else {
			jQuery(this).find(".friend_icons").hide();
		}
	});

	// Remove avatar
	jQuery("#symposium_remove_avatar").live('click', function() {
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: 'remove_avatar',
				uid: __wps__.current_user_page
			}),
			dataType: "html",
			async: false,
			success: function(str) {
				window.location.href = window.location.href;
			}
		});
	});

	// Poke
	jQuery(".poke-button").live('click', function() {
		jQuery("#dialog").html('Message sent!');
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 200,
			height: 175,
			modal: true,
			buttons: {
				"OK": function() {
					jQuery("#dialog").dialog('close');
				}
			}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: 'send_poke',
				recipient: __wps__.current_user_page
			}),
			dataType: "html",
			async: false,
			success: function(str) {}
		});
	});

	

	// Clicked on show more...
	jQuery(".showmore_wall").live('click', function() {

		var limit_from = jQuery(this).attr("title");
		jQuery(this).html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var menu_id = 'menu_' + jQuery(this).attr("id");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: menu_id,
				post: '',
				limit_from: limit_from,
				uid1: __wps__.current_user_page
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery('.showmore_wall').remove();
				if (jQuery("#profile_body").length) {
					jQuery(str).appendTo('#profile_body').hide().slideDown("slow");
				} else {
					jQuery(str).appendTo('.__wps__wrapper').hide().slideDown("slow");
				}
			}
		});

	});

	// Menu choices
	jQuery(".__wps__profile_menu").click(function() {

		// Check if using horizontal menu (tabs)
		if (jQuery(".__wps__top_menu").length) {
			jQuery('.__wps__top_menu').removeClass('__wps__dropdown_tab_on').addClass('__wps__dropdown_tab_off');
			jQuery(this).closest('.__wps__top_menu').removeClass('__wps__dropdown_tab_off').addClass('__wps__dropdown_tab_on');
			
			jQuery(this).parent().hide();
		}

		jQuery('.__wps__profile_menu').removeClass('__wps__profile_current');
		jQuery(this).addClass('__wps__profile_current');

		var menu_id = jQuery(this).attr("id");
		var menu_slug = jQuery(this).attr("name");		
		jQuery('#profile_body').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		if (!(jQuery("#profile_body").length)) {
			var view = menu_id.replace(/menu_/g, "");
			var q = symposium_q(__wps__.profile_url);
			window.location.href = __wps__.profile_url + q + 'view=' + view;
		}

		if ((menu_id == 'menu_extended') || (menu_id == 'menu_wall') || (menu_id == 'menu_activity') || (menu_id == 'menu_all') || (menu_id == 'menu_mentions') || (menu_id == 'menu_groups') || (menu_id == 'menu_friends') || (menu_id == 'menu_avatar') || (menu_id == 'menu_personal') || (menu_id == 'menu_settings')) {
			
			var ajax_path = __wps__.plugin_url + "ajax/profile_functions.php";			

		} else if ((menu_id == 'menu_gallery') || (menu_id == 'menu_events') || (menu_id == 'menu_plus') || (menu_id == 'menu_plus_me') || (menu_id == 'menu_lounge')) {
			
			var ajax_part = menu_id.replace(/menu_/g, "");

			if (strpos(ajax_part, '->') !== false) {
				ajax_sub = ajax_part.substring(0, strpos(ajax_part, '->'));
				var ajax_path = __wps__.plugin_url + "ajax/" + ajax_sub + "_functions.php";
				menu_id = menu_id.replace(/->/g, "_");
			} else {
				ajax_part = ajax_part.replace(/_me/g, "");
				var ajax_path = __wps__.plugin_url + "ajax/" + ajax_part + "_functions.php";
			}
			
		} else {
			
			// added on menu item (ie. ID = wp-symposium-example)
			var ajax_path = __wps__.plugins+'/'+menu_id+'/ajax/'+menu_id+'_functions.php';

		}

		jQuery.ajax({
			url: ajax_path,
			type: "POST",
			data: ({
				action: menu_id,
				post: '',
				limit_from: 0,
				menu_slug:menu_slug,
				uid1: __wps__.current_user_page,
				display_name: __wps__.current_user_display_name
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				jQuery('#profile_body').html(str);

				var user_id = jQuery("#symposium_user_id").html();
				var user_login = jQuery("#symposium_user_login").html();
				var user_email = jQuery("#symposium_user_email").html();

				// Set up auto-expanding textboxes
				if (jQuery(".elastic").length) {
					jQuery('.elastic').elastic();
				}

				// Reduce width of status/reply fields on activity to take into account background edit icon
				if (jQuery('.__wps__reply').length) {
					var w = jQuery('.__wps__reply').css('width').replace(/px/g, "");
					jQuery('.__wps__reply').css('width', (w-30)+'px');
				}
				if (jQuery('#__wps__comment').length) {
					var w = jQuery('#__wps__comment').css('width').replace(/px/g, "");
					jQuery('#__wps__comment').css('width', (w-30)+'px');
				}

				// Init file upload
				__wps__init_file_upload();

			}
		});

	});

	if (jQuery("#profile_jcrop_target").length) {
		jQuery('#profile_jcrop_target').Jcrop({
			onChange: showPreview,
			onSelect: showPreview
		});
	}

	function showProfilePreview(coords) {
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;

		jQuery('#x').val(coords.x);
		jQuery('#y').val(coords.y);
		jQuery('#x2').val(coords.x2);
		jQuery('#y2').val(coords.y2);
		jQuery('#w').val(coords.w);
		jQuery('#h').val(coords.h);

		jQuery('#profile_preview').css({
			width: Math.round(rx * jQuery('#profile_jcrop_target').width()) + 'px',
			height: Math.round(ry * jQuery('#profile_jcrop_target').height()) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	};

	// Save profile avatar
	jQuery("#saveProfileAvatar").live('click', function() {
		

		//if (jQuery("#w").val() > 0) {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "saveProfileAvatar",
				uid: __wps__.current_user_page,
				x: jQuery("#x").val(),
				y: jQuery("#y").val(),
				w: jQuery("#w").val(),
				h: jQuery("#h").val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str.length <= 1 || trim(str) == '' || trim(str) == '0') {
					location.reload();
				} else {
					jQuery(".__wps__notice").fadeOut("slow");
					alert('Oops: ' + str);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

		//} else {
		//	alert('Please select an area in your uploaded image');
		//}
	});

	// Show delete link on wall post hover
	jQuery('.wall_post_div').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".report_post_top").show();
			jQuery(this).find(".delete_post_top").show();
		} else {
			jQuery(this).find(".report_post_top").hide();
			jQuery(this).find(".delete_post_top").hide();
		}
	});

	// Show delete link on reply hover
	jQuery('.wall_reply').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".report_post").show();
			jQuery(this).find(".delete_reply").show();
		} else {
			jQuery(this).find(".report_post").hide();
			jQuery(this).find(".delete_reply").hide();
		}
	});

	// View all comments
	jQuery(".view_all_comments").live('click', function() {
		var parent_comment_id = jQuery(this).attr("title");
		jQuery('#' + parent_comment_id).find(".reply_div").slideDown("slow");
	});

	// Delete a reply
	jQuery(".delete_post").live('click', function() {

		var comment_id = jQuery(this).attr("title");
		if (jQuery(this).attr("rel") == 'post') {
			jQuery('#post_' + comment_id).slideUp("slow");
		} else {
			jQuery('#' + comment_id).slideUp("slow");
		}

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "deletePost",
				cid: comment_id
			}),
			dataType: "html",
			async: false,
			success: function(str) {
				if (str.substring(0, 4) == 'FAIL') {
					alert("delete_post:" + str);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// new post on activity stream
	jQuery("#__wps__add_comment").live('click', function() {
		symposium_add_comment_to_profile();
	});
	jQuery('#__wps__comment').live('keypress', function(e) {
		if (e.keyCode == 13 && (!jQuery("#__wps__add_comment").length)) {
			symposium_add_comment_to_profile();
		}
	});

	function symposium_add_comment_to_profile() {
		
		var comment_text = jQuery("#__wps__comment").val().replace(/(<([^>]+)>)/ig, '');
		if (comment_text == whatsup)
			comment_text = '';
		// Video
		if (typeof jQuery('#activity_youtube_embed_button_used') !== "undefined")
			var code = jQuery('#activity_youtube_embed_button_used').attr('title');
		if (typeof code === "undefined")
			code = '';
		// Image
		var img = '';
		if (jQuery("#activity_file_upload_file").length) {
			if (jQuery("#activity_file_upload_file").html() != '') {
				img = 'yes';
			}
		}
		
		
		if (comment_text != '' || code != '' || img != '') {

			if (code != '') {
	    	    var width = '100%';
		        var height = 250;
	
	        	var iFrame = '<iframe width="'+width+'" height="'+height+'" src="http://www.youtube.com/embed/'+code+'?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
	        	comment_text = iFrame + comment_text;
	        	jQuery('#activity_youtube_embed_button_used').attr('id', 'activity_youtube_embed_button');
	        	jQuery('#activity_youtube_embed_button').attr('title', '');
			}

			comment_text = comment_text.replace(/\n/g, '<br>');
			
			// temporarily remove and re-show after 3 seconds

			jQuery('#symposium_add_status').hide();
			setTimeout(function() {
				if (jQuery('#btn-span-tmp').html() != '') {
					jQuery('#btn-span').html(jQuery('#btn-span-tmp').html());
				}
				jQuery('#fileupload-info').html('');
				jQuery('#__wps__comment').css('height', '50px');
				jQuery('#symposium_add_status').slideDown('fast');
			}, 3000);

			var comment = "<div class='add_wall_post_div' style='"
			if (__wps__.row_border_size != '') {
				comment = comment + " border-top:" + __wps__.row_border_size + "px " + __wps__.row_border_style + " " + __wps__.text_color_2 + ";";
			}
			comment = comment + "'>";
			comment = comment + "<div class='add_wall_post'>";
			comment = comment + "<div class='add_wall_post_text'>";
			var q = symposium_q(__wps__.profile_url);
			comment = comment + '<a href="' + __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '">';
			comment = comment + __wps__.current_user_display_name + '</a><br />';
			comment = comment + comment_text;

			// Check for uploaded image
			if (jQuery("#activity_file_upload_file").length) {
				if (jQuery("#activity_file_upload_file").html() != '') {
					comment = comment + "<div id='myimage'><img id='symposium_tmp' src='" + __wps__.images_url + "/busy.gif' /></div>";
				}
			}
			
			comment = comment + "</div>";
			comment = comment + "</div>";
			comment = comment + "<div class='add_wall_post_avatar'>";
			comment = comment + "<img src='" + jQuery('#__wps__current_user_avatar img:first').attr('src') + "' style='width:64px; height:64px' />";
			comment = comment + "</div>";

			jQuery("#__wps__comment").val('');
			jQuery(comment).insertAfter('#symposium_add_status').hide().slideDown('fast');


			var facebook_post = 0;
			if (jQuery("#post_to_facebook").length) {
				if (jQuery("#post_to_facebook").is(":checked")) {
					if (__wps__.current_user_page == __wps__.current_user_id) {
						facebook_post = 1;
					}
				}
			}


			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/profile_functions.php",
				type: "POST",
				data: ({
					action: "addStatus",
					subject_uid: __wps__.current_user_page,
					parent: 0,
					text: comment_text,
					facebook: facebook_post
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str) {
						jQuery('#myimage:first').html('<a target="_blank" href="'+str+'" rev="1" rel="symposium_tmp_activity_image" data-owner="'+__wps__.current_user_id+'" data-name="" data-iid="1" class="wps_gallery_album"><img class="profile_activity_image" src="' + str + '" /></a>');
					}
					jQuery('#activity_youtube_embed_button_used').attr('id', 'activity_youtube_embed_button');
					jQuery('#activity_youtube_embed_button').val("YouTube");
					jQuery('#activity_file_upload_file').html('');
					jQuery(".__wps__notice").fadeOut("slow");

				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			});
			

		}
	}

	// new reply/comment
	jQuery(".symposium_add_reply").live('click', function() {
		jQuery(this).parent().hide();
		var t = this;
		setTimeout(function() {
			jQuery('.__wps__reply').css('height', '44px');
			jQuery(t).parent().slideDown('fast');
		}, 3000);
		symposium_add_comment(this);
	});
	jQuery('.__wps__reply').live('keypress', function(e) {
		if (e.keyCode == 13 && (!jQuery(".symposium_add_reply").length)) {
			jQuery(this).parent().hide();
			var t = this;
			setTimeout(function() {
				jQuery('.__wps__reply').css('height', '44px');
				jQuery(t).parent().slideDown('fast');
			}, 3000);
			symposium_add_comment(this);
		}
	});

	function symposium_add_comment(comment_trigger) {

		var comment_id = jQuery(comment_trigger).attr("title");
		var author_id = jQuery('#symposium_author_' + comment_id).val();
		var comment_text = jQuery("#__wps__reply_" + comment_id).val().replace(/(<([^>]+)>)/ig, '');

		if (comment_text != '' && comment_text != '\n' && comment_text != write_a_comment && comment_text != write_a_comment + '\n') {

			comment_text = comment_text.replace(/\n/g, '<br>');

			var comment = "<div class='reply_div'>";
			comment = comment + "<div class='__wps__wall_reply_div'";
			if (__wps__.bg_color_2 != '') {
				comment = comment + " style='background-color:" + __wps__.bg_color_2 + "'";
			}
			comment = comment + ">";
			comment = comment + "<div class='wall_reply'>";
			var q = symposium_q(__wps__.profile_url);
			comment = comment + '<a href="' + __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '">';
			comment = comment + __wps__.current_user_display_name + '</a><br />';
			comment = comment + comment_text;
			comment = comment + "</div>";
			comment = comment + "</div>";
			comment = comment + "<div class='wall_reply_avatar'>";
			comment = comment + "<img src='" + jQuery('#__wps__current_user_avatar img:first').attr('src') + "' style='width:40px; height:40px' />";
			comment = comment + "</div>";
			comment = comment + "</div>";

			jQuery(comment).appendTo('#__wps__comment_' + comment_id);
			jQuery("#__wps__reply_" + comment_id).val('');

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/profile_functions.php",
				type: "POST",
				data: ({
					action: "addComment",
					uid: author_id,
					parent: comment_id,
					text: comment_text
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (__wps__.debug) {
						jQuery("#dialog").html(str).dialog({
							title: __wps__.site_title + ' debug info',
							width: 800,
							height: 500,
							modal: true
						});
					}
				}
			});
		} else {
			jQuery("#__wps__reply_" + comment_id).val('');
		}
	}

	// update settings
	jQuery("#updateSettingsButton").live('click', function() {

		var display_name = jQuery("#display_name").val().replace(/(<([^>]+)>)/ig, '');
		var user_firstname = jQuery("#user_firstname").val().replace(/(<([^>]+)>)/ig, '');
		var user_lastname = jQuery("#user_lastname").val().replace(/(<([^>]+)>)/ig, '');
		var user_email = jQuery("#user_email").val().replace(/(<([^>]+)>)/ig, '');
		var signature = jQuery("#signature").val().replace(/(<([^>]+)>)/ig, '');
		var profile_label = jQuery("#__wps__profile_label").val().replace(/(<([^>]+)>)/ig, '');

		if (signature.length > 128) {
			jQuery("#dialog").html('Maximum length for signatures is 128 characters.');
			jQuery("#dialog").dialog({
				title: 'Signature',
				width: 600,
				height: 225,
				modal: true,
				buttons: {
					"OK": function() {
						jQuery("#dialog").dialog('close');
					}
				}
			});
		}

		if (display_name == '') {
			jQuery("#display_name").effect("highlight", {}, 4000);
		} else {

			if (user_email == '') {
				jQuery("#user_email").effect("highlight", {}, 4000);
			} else {

				jQuery(".__wps__notice").inmiddle().show();

				if (jQuery("#notify_new_messages").is(":checked")) {
					var notify_new_messages = 'on';
				} else {
					var notify_new_messages = '';
				}

				if (jQuery("#notify_new_wall").is(":checked")) {
					var notify_new_wall = 'on';
				} else {
					var notify_new_wall = '';
				}

				if (jQuery("#notify_likes").is(":checked")) {
					var notify_likes = 'on';
				} else {
					var notify_likes = '';
				}

				if (jQuery("#forum_all").is(":checked")) {
					var forum_all = 'on';
				} else {
					var forum_all = '';
				}

				if (jQuery("#trusted").length) {
					if (jQuery("#trusted").is(":checked")) {
						var trusted = 'on';
					} else {
						var trusted = '';
					}
				} else {
					var trusted = jQuery("#trusted_hidden").val();
				}

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/profile_functions.php",
					type: "POST",
					data: ({
						action: "updateSettings",
						uid: __wps__.current_user_page,
						trusted: trusted,
						bar_position: jQuery("#bar_position").val(),
						user_firstname: user_firstname,
						user_lastname: user_lastname,
						display_name: display_name,
						user_email: user_email,
						signature: signature,
						notify_new_messages: notify_new_messages,
						notify_new_wall: notify_new_wall,
						notify_likes: notify_likes,
						forum_all: forum_all,
						profile_label: profile_label,
						xyz1: jQuery("#xyz1").val(),
						xyz2: jQuery("#xyz2").val()
					}),
					dataType: "html",
					async: true,
					success: function(str) {
						if (str == 'PASSWORD CHANGED') { /* when password changes, have to log in again, can't work out why */
							window.location.href = window.location.href;
						}
						if (str != "OK") {
							jQuery("#dialog").html(str);
							jQuery("#dialog").dialog({
								title: __wps__.site_title,
								width: 300,
								height: 175,
								modal: true,
								buttons: {
									"OK": function() {
										jQuery("#dialog").dialog('close');
									}
								}
							});
						}
						jQuery(".__wps__notice").fadeOut("slow");
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});
			}
		}

	});

	// update personal
	jQuery("#updatePersonalButton").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		var extended = '';

		jQuery('.eid_value').each(function(index) {
			var title = jQuery(this).attr("title");
			var value = jQuery(this).val().replace(/(<([^>]+)>)/ig, '');

			if (value == 'on')
				value = jQuery(this).is(":checked");

			extended += title + '[]';
			extended += value + '[|]';
		});
		
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "updatePersonal",
				uid: __wps__.current_user_page,
				dob_day: jQuery("#dob_day").val(),
				dob_month: jQuery("#dob_month").val(),
				dob_year: jQuery("#dob_year").val(),
				city: jQuery("#city").val().replace(/(<([^>]+)>)/ig, ''),
				country: jQuery("#country").val().replace(/(<([^>]+)>)/ig, ''),
				share: jQuery("#share").val(),
				wall_share: jQuery("#wall_share").val(),
				rss_share: jQuery("#rss_share").val(),
				chat_sound: jQuery("#chat_sound").val(),
				extended: extended
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'OK') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// delete a friend
	jQuery(".frienddelete").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "deleteFriend",
				friend: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery("#friend_" + str).slideUp("slow");
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// send mail (via send mail button)
	jQuery("#profile_send_mail_button").live('mousedown', function() {
		var q = symposium_q(__wps__.mail_url);
		document.location = __wps__.mail_url + q + 'view=compose&to=' + __wps__.current_user_page;
	});

	// add a friend request
	jQuery("#addasfriend").live('click', function() {
		addasfriend(this, '');
	});
	jQuery(".addasfriend").live('click', function() {
		addasfriend(this, jQuery(this).prev().val());
	});
	jQuery('#addfriend').live('keypress', function(e) {
		if (e.keyCode == 13) {
			addasfriend(this, '');
		}
	});
	jQuery('.addfriend_text').live('keypress', function(e) {
		if (e.keyCode == 13) {
			addasfriend(this, jQuery(this).val());
		}
	});

	function addasfriend(id, msg) {
		var uid = jQuery(id).attr("title");
		if (msg == '') msg = jQuery('#addfriend').val();

		jQuery("#addasfriend_done1_" + uid).hide();
		jQuery("#addasfriend_done2_" + uid).show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "addFriend",
				friend_to: uid,
				friend_message: msg
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(__wps__.request_sent);
				jQuery("#dialog").dialog({
					title: __wps__.site_title,
					width: 600,
					height: 225,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery("#dialog").dialog('close');
						}
					}
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	};

	// cancel a friend request
	jQuery("#cancelfriendrequest").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "cancelFriend",
				friend_to: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery("#cancelfriendrequest").hide();
					jQuery("#cancelfriendrequest_done").show();
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// reject a friend request
	jQuery("#rejectfriendrequest").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "rejectFriend",
				friend_to: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery("#request_" + str).slideUp("slow");
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// accept a friend request
	jQuery("#acceptfriendrequest").live('click', function() {

		jQuery("#request_" + jQuery(this).attr("title")).slideUp("slow");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: "acceptFriend",
				friend_to: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}
		});

	});

	// clear all current subscriptions
	jQuery("#symposium_clear_all_subs").live('click', function() {

		if (confirm(areyousure)) {

			jQuery("#dialog").html('All subscriptions cleared.');
			jQuery("#dialog").dialog({
				title: 'Preferences',
				width: 600,
				height: 225,
				modal: true,
				buttons: {
					"OK": function() {
						jQuery("#dialog").dialog('close');
					}
				}
			});

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/profile_functions.php",
				type: "POST",
				data: ({
					action: "clearSubs"
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str != 'OK') {
						jQuery("#dialog").html(str).dialog({
							title: __wps__.site_title + ' debug info',
							width: 800,
							height: 500,
							modal: true
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
		}

	});

/*
	   +------------------------------------------------------------------------------------------+
	   |                                       PROFILE PLUS                                       |
	   +------------------------------------------------------------------------------------------+
	*/

	// Horizontal menu/tabs (also shared with Groups)
	
    jQuery("ul.__wps__dropdown li").hover(function(){
    
        jQuery(this).addClass("hover");
        jQuery('ul:first',this).css('visibility', 'visible').show();
    
    }, function(){
    
        jQuery(this).removeClass("hover");
        jQuery('ul:first',this).css('visibility', 'hidden');
    
    });
    
    
	// Scrolling Profile Menu
	if (__wps__.profile_menu_scrolls == 'on') {
		
		var el = jQuery('#profile_menu');
		if (el.length) {

			el.parent().css('position', 'relative');
			var elpos = el.offset().top;
			var t = el.position().top;
			var d = parseInt(__wps__.profile_menu_delta);
			el.css('position', 'absolute');
			el.css('top', '0px');
			jQuery('.__wps__wrapper').addClass('__wps__wrapper-min-height');
			
			jQuery(window).scroll(function() {

				el.parent().css('position', 'relative');
				var elpos = el.offset().top;
				var t = el.position().top;
				var d = parseInt(__wps__.profile_menu_delta);
				el.css('position', 'absolute');
				el.css('top', '0px');

				var wrapper_top = jQuery('#profile_wrapper').offset().top;
				var y = jQuery(this).scrollTop();
				if (y > wrapper_top - d) {
					el.css('position', 'fixed');
					el.css('top', d);
				} else {
					el.css('position', 'absolute');
					el.css('top', '0px');
				}			

			});
						
		}
		
	} else {

		var el = jQuery('#profile_menu');
		if (el.length) {

			var elpos = el.offset().top;
			var w = jQuery('#profile_wrapper');
			el.parent().css('position', 'relative');
			el.css('position', 'absolute');
			el.css('top', '0px');
				
		}

	}

	// Likes/Dislikes on activity
	jQuery(".wall_add_like").live('click', function() {
		var cid = jQuery(this).attr('rel');
		var choice = jQuery(this).attr("data-action");
		jQuery("#__wps__likes_" + cid).html('<strong>' + jQuery(this).attr('title') + '</strong>').effect("slide");
		jQuery(this).parent().hide();

		// Store choice
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/plus_functions.php",
			type: "POST",
			data: ({
				action: "likeDislike",
				choice: choice,
				cid: cid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
			}
		});
	})


	jQuery("#symposium_show_likes").live('click', function() {
		var cid = jQuery(this).attr('rel');
		var str = '';
		var busy = "<img id='symposium_tmp' src='" + __wps__.images_url + "/busy.gif' />";
		str += '<div id="__wps__who_dislikes" style="width:49%;float:right;"><strong>' + symposium_dislikes + '</strong><br /><br />' + busy + '</div>';
		str += '<div id="__wps__who_likes" style="width:49%;"><strong>' + symposium_likes + '</strong><br /><br />' + busy + '</div>';
		jQuery("#dialog").html(str).dialog({
			title: __wps__.site_title,
			width: 600,
			height: 300,
			modal: true
		});

		// Get those who like and dislike
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/plus_functions.php",
			type: "POST",
			data: ({
				action: "getLikesDislikes",
				type: "like",
				cid: cid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'None') {
					var rows = jQuery.parseJSON(str);
					var likes = '';
					jQuery.each(rows, function(i, row) {
						likes += '<div style="height:28px;">';
						likes += '<div style="padding-right:6px; float:left;">' + row.avatar + '</div>';
						likes += row.display_name;
						likes += '</div>';
					});
					likes = '<strong>' + symposium_likes + '</strong><br /><br />' + likes;
					jQuery('#__wps__who_likes').html(likes);
				} else {
					jQuery('#__wps__who_likes').html('<strong>' + symposium_likes + '</strong>');
				}
			}
		});
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/plus_functions.php",
			type: "POST",
			data: ({
				action: "getLikesDislikes",
				type: "dislike",
				cid: cid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'None') {
					var rows = jQuery.parseJSON(str);
					var dislikes = '';
					jQuery.each(rows, function(i, row) {
						dislikes += '<div style="height:28px;">';
						dislikes += '<div style="padding-right:6px; float:left;">' + row.avatar + '</div>';
						dislikes += row.display_name;
						dislikes += '</div>';
					});
					dislikes = '<strong>' + symposium_dislikes + '</strong><br /><br />' + dislikes;
					jQuery('#__wps__who_dislikes').html(dislikes);
				} else {
					jQuery('#__wps__who_dislikes').html('<strong>' + symposium_dislikes + '</strong>');
				}
			}
		});
	})

	// Info on what a usertag is
	jQuery("#symposium_tag").live('click', function() {
		jQuery("#symposium_tag_info").show("fast");
	})

	// YouTube embed
	jQuery("#activity_youtube_embed_button").live('click', function() {
		var m = '';
		m += 'Enter the YouTube video URL:<br /><br />';
		m += '<input type="text" id="activity_youtube_embed" style="width:430px" />';
		jQuery("#dialog").html(m);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 500,
			height: 190,
			modal: true,
			buttons: {
				"OK": function() {
					var src = jQuery("#activity_youtube_embed").val();
					if (src != '') {
						if(src.indexOf('/embed/') != -1) {
							video_id = src.split('/embed/')[1];
							video_id = video_id.split('"')[0];
						} else {
							if(src.indexOf('http://youtu.be/') == 0) {
								video_id = src.split('//')[1];
								video_id = video_id.split('/')[1];
							} else {							
								var video_id = src.split('v=')[1];
								if (typeof video_id !== "undefined") {
									if(video_id.indexOf('&') != -1) {
									  video_id = video_id.split('&')[0];
									}                    
								} else {
									video_id = src;
								}
							}
						}
					} else {
						jQuery("#dialog").dialog('close');
					}
					if (video_id != '') {
						jQuery('#activity_youtube_embed_button').attr('id', 'activity_youtube_embed_button_used');
						jQuery('#activity_youtube_embed_button_used').attr('title', video_id);
						jQuery('#activity_youtube_embed_button_used').css('cursor', 'default');
						jQuery('#activity_youtube_embed_button_used').val("Done!");
						jQuery("#dialog").dialog('close');
					}
				},
				"Cancel": function() {
					jQuery("#dialog").dialog('close');
				}
			}
		});
	})

	
	// Combined search
	if (jQuery("input#__wps__member_small").length) {

		jQuery("input#__wps__member_small").autocomplete({
			source: __wps__.plugin_url + "ajax/plus_functions.php",
			minLength: 1,
			focus: function(event, ui) {
				jQuery("input#__wps__member_small").val(ui.item.name);
				return false;
			},
			select: function(event, ui) {
				jQuery(".__wps__pleasewait").inmiddle().show().delay(3000).fadeOut("slow");
				switch (ui.item.type) {
				case 'topic':
					var q = symposium_q(__wps__.forum_url);
					window.location.href = __wps__.forum_url + q + 'show=' + ui.item.id;
					break;
				case 'post':
					window.location.href = ui.item.url;
					break;
				case 'page':
					window.location.href = ui.item.url;
					break;
				case 'amember':
					var q = symposium_q(__wps__.profile_url);
					window.location.href = __wps__.profile_url + q + 'uid=' + ui.item.value;
					break;
				case 'group':
					var q = symposium_q(__wps__.group_url);
					window.location.href = __wps__.group_url + q + 'gid=' + ui.item.value;
					break;
				case 'gallery':
					var q = symposium_q(__wps__.profile_url);
					window.location.href = __wps__.profile_url + q + 'uid=' + ui.item.owner + '&embed=on&album_id=' + ui.item.id;
					break;
				}
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {

			if (item.type == 'sep') {

				return jQuery('<li class="__wps__autocomplete_sep"></li>').append(item.name).appendTo(ul);

			} else {

				var group = "<a>";
				group += "<div style='height:40px; overflow: hidden; width:250px;'>";
				group += "<div style=\'float:left; background-color:#fff; margin-right: 8px; width:40px; height:40px; \'>";
				group += item.avatar;
				group += "</div>";
				group += "<div style='height:20px;overflow:hidden;'>" + item.name + "</div>";
				var sep = (item.city != '' && item.country != '') ? ', ' : '';
				group += "<div style='font-size:80%'>" + item.city + sep + item.country + "</div>";
				group += "<br style='clear:both' />";
				group += "</div>";
				group += "</a>";
				return jQuery('<li class="__wps__autocomplete_item"></li>').data("item.autocomplete", item).append(group).appendTo(ul);

			}
		};

	}

	jQuery('.__wps__follow').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter' && __wps__.current_user_id > 0) {

			var display_name = jQuery(this).attr("title");
			var id = jQuery(this).attr("id");
			var rel = jQuery(this).attr("rel");
			var rev = jQuery(this).attr("rev");
			var src = jQuery(this).attr("src");

			var xPos = event.pageX - (jQuery('#__wps__follow_box').width() / 2);
			var yPos = event.pageY - (jQuery('#__wps__follow_box').height() / 2);
			var html = '<img id="symposium_plus_box_avatar" src="' + src + '" style="width:20px;height:20px;float:right;" />';
			var q = symposium_q(__wps__.profile_url);
			
			html += '<a style="font-size:14px;" href="' + __wps__.profile_url + q + 'uid=' + id + '">' + display_name + '</a><br />';
			if (rel == 'friend' || id == __wps__.current_user_id) {
				if (strpos(__wps__.mail_url, 'INVALID PLUGIN URL REQUESTED') === false && id != __wps__.current_user_id) {
					html += '<input id="symposium_plus_sendmail" ref="' + id + '" type="text" class="__wps__hover_input" onblur = "this.value=(this.value==\'\') ? \'' + __wps__.sendmail + '\' : this.value;"  onfocus= "this.value=(this.value==\'' + __wps__.sendmail + '\') ? \'\' : this.value;"  value  = "' + __wps__.sendmail + '" />';
				} else {
					html += '<input id="symposium_plus_post" type="text" class="__wps__hover_input"  onfocus= "this.value= \'\'"  value  = "' + __wps__.whatsup + '" />';
				}
			} else {
				if (rel == 'pending') {
					var q = symposium_q(__wps__.profile_url);
					html += '<div style="margin-top:4px;margin-bottom:8px;"><a href="' + __wps__.profile_url + q + 'view=friends">' + __wps__.friendpending + '</a></div>';
				} else {
					html += '<input id="symposium_plus_addasafriend" title="' + id + '" type="text" class="__wps__hover_input"  onblur = "this.value=(this.value==\'\') ? \'' + __wps__.addasafriend + '...\' : this.value;"  onfocus= "this.value=(this.value==\'' + __wps__.addasafriend + '...\') ? \'\' : this.value;"  value  = "' + __wps__.addasafriend + '..." />';
				}
			}
			html += "<img id='symposium_plus_profile' ref='" + id + "' title='" + __wps__.profile_info + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/profile.png' />";
			html += "<img id='symposium_plus_friends' ref='" + id + "' title='" + __wps__.plus_friends + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/friends.png' />";
			if (id == __wps__.current_user_id) {
				html += "<img id='symposium_following_who' title='" + __wps__.plus_follow_who + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/fav-who.png' />";
				html += "<img id='symposium_plus_mail' title='" + __wps__.plus_mail + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/mail.png' />";
			} else {
				if (rev == 'following') {
					html += "<img id='symposium_following' title='" + __wps__.unfollow + "' ref='" + id + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/fav-on.png' />";
				} else {
					html += "<img id='symposium_following' title='" + __wps__.follow + "' ref='" + id + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/fav-off.png' />";
				}
				if (__wps__.wps_use_poke) {
					html += "<img id='__wps__attention' title='" + __wps__.attention + "' ref='" + id + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/attention.png' />";
				}
			}
			if (strpos(__wps__.forum_url, 'INVALID PLUGIN URL REQUESTED') === false) {
				html += "<img id='symposium_forum_search' title='" + __wps__.forumsearch + "' rel='" + display_name + "' ref='" + id + "' style='float:left; margin-right:5px; cursor:pointer' src='" + __wps__.images_url + "/search2.png' />";
			}
			if (__wps__.gallery_url != '') {
				html += '<img style="cursor:pointer" rel="' + display_name + '" id="symposium_gallery_search" src="' + __wps__.images_url + '/gallery.png" title="' + __wps__.gallerysearch + '" /></a>';
			}

			jQuery('#__wps__follow_box').html(html);
			jQuery('#__wps__follow_box').css({
				'z-index': 999999,
				'position': 'absolute',
				'top': yPos,
				'left': xPos
			}).show();
		}
	});
	jQuery('#symposium_plus_box_avatar').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery('#__wps__follow_box').css({
				'height': '280px'
			});
			jQuery('#symposium_plus_box_avatar').css({
				'width': '200px',
				'height': '200px'
			});
		}
	});
	jQuery('#__wps__follow_box').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseleave') {
			jQuery(this).hide();
			jQuery('#__wps__follow_box').css({
				'height': '80px'
			});
		}
	});

	// Go to friends page
	jQuery("#symposium_plus_friends").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var q = symposium_q(__wps__.profile_url);
		window.location = __wps__.profile_url + q + 'uid=' + jQuery(this).attr("ref") + '&view=friends';
	});

	// Go to profile information page
	jQuery("#symposium_plus_profile").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var q = symposium_q(__wps__.profile_url);
		window.location = __wps__.profile_url + q + 'uid=' + jQuery(this).attr("ref") + '&view=extended';
	});

	// Post a status on pressing return (via hover box, if mail plugin not activated)
	jQuery('#symposium_plus_post').live('keypress', function(e) {
		if (e.keyCode == 13) {
			jQuery(".__wps__pleasewait").inmiddle().show();
			var message = jQuery(this).val().replace(/(<([^>]+)>)/ig, '');
			jQuery(this).val('');

			if (message != '' && message != jQuery('#symposium_whatsup').html()) {

				// Add to wall if on page
				if (jQuery("#__wps__wall").css("display") != 'none' && __wps__.current_user_page == __wps__.current_user_id) {
					var comment = "<div class='add_wall_post_div'>";
					comment = comment + "<div class='add_wall_post'>";
					comment = comment + "<div class='add_wall_post_text'>";
					var q = symposium_q(__wps__.profile_url);
					comment = comment + '<a href="' + __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '">';
					comment = comment + __wps__.current_user_display_name + '</a><br />';
					comment = comment + message;
					comment = comment + "</div>";
					comment = comment + "</div>";
					comment = comment + "<div class='add_wall_post_avatar'>";
					comment = comment + "<img src='" + jQuery('#__wps__current_user_avatar img:first').attr('src') + "' style='width:64px; height:64px' />";
					comment = comment + "</div>";
					comment = comment + "</div>";
					jQuery(comment).prependTo('#__wps__wall');
				}

				// Update status
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/profile_functions.php",
					type: "POST",
					data: ({
						action: "addStatus",
						text: message,
						facebook: 0
					}),
					dataType: "html",
					async: true,
					success: function(str) {
						jQuery(".__wps__pleasewait").hide();
						jQuery('.__wps__follow_box').hide();

						if (__wps__.current_user_page != __wps__.current_user_id || jQuery("#__wps__wall").length == 0) {
							jQuery("#dialog").html(__wps__.whatsup_done + '<br /><br />' + message);
							jQuery("#dialog").dialog({
								title: __wps__.site_title,
								width: 300,
								height: 250,
								modal: true,
								buttons: {
									"OK": function() {
										jQuery("#dialog").dialog('close');
									},
									"View": function() {
										jQuery(".__wps__pleasewait").inmiddle().show();
										window.location = __wps__.profile_url;
									}
								}
							});
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});


			}

		}
	});

	// Following who?
	jQuery("#symposium_following_who").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var q = symposium_q(__wps__.profile_url);
		window.location = __wps__.profile_url + q + 'view=plus';
	});

	// Go to mail (via hover box)
	jQuery("#symposium_plus_mail").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		window.location = __wps__.mail_url;
	});

	// Gallery search (via hover box)
	jQuery("#symposium_gallery_search").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var q = symposium_q(__wps__.gallery_url);
		window.location = __wps__.gallery_url + q + 'term=' + jQuery(this).attr("rel");
	});

	// Forum search (via hover box)
	jQuery("#symposium_forum_search").live('click', function() {
		do_show_search();
		jQuery("#search-box-input").val(jQuery(this).attr("rel"));
		do_forum_search();
	});

	// Grab attention
	jQuery("#__wps__attention").live('click', function() {
		var avatar = "<img style='float:left; width:48px; height:48px;margin-right:5px;' src='" + jQuery('#symposium_plus_box_avatar').attr("src") + "' />";
		jQuery("#dialog").html(avatar + __wps__.sent);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 200,
			height: 175,
			modal: true,
			buttons: {
				"OK": function() {
					jQuery("#dialog").dialog('close');
				}
			}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/profile_functions.php",
			type: "POST",
			data: ({
				action: 'send_poke',
				recipient: jQuery(this).attr("ref")
			}),
			dataType: "html",
			async: true,
			success: function(str) {}
		});
	});

	// Toggle following (via profile header)
	jQuery(".follow-button").live('click', function() {

		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/plus_functions.php",
			type: "POST",
			data: ({
				action: 'toggle_following',
				following: __wps__.current_user_page
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery(".follow-button").val(str);
				}
			}
		});
		jQuery(".__wps__pleasewait").hide();

	});

	// Toggle following (via hover box)
	jQuery("#symposium_following").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		if (jQuery(this).attr("src") == __wps__.images_url + '/fav-on.png') {
			// Remove from following
			jQuery(this).attr("src", __wps__.images_url + '/fav-off.png');
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/plus_functions.php",
				type: "POST",
				data: ({
					action: 'toggle_following',
					following: jQuery(this).attr("ref")
				}),
				dataType: "html",
				async: false
			});
		} else {
			// Add to following
			jQuery(this).attr("src", __wps__.images_url + '/fav-on.png');
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/plus_functions.php",
				type: "POST",
				data: ({
					action: 'toggle_following',
					following: jQuery(this).attr("ref")
				}),
				dataType: "html",
				async: false
			});
		}
		location.reload();
	});

	// Send mail on pressing return
	jQuery('#symposium_plus_sendmail').live('keypress', function(e) {
		if (e.keyCode == 13) {
			jQuery(".__wps__sending").inmiddle().show();
			var recipient_id = jQuery(this).attr("ref");
			var message = jQuery(this).val().replace(/(<([^>]+)>)/ig, '');
			jQuery(this).val('');

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/mail_functions.php",
				type: "POST",
				data: ({
					action: "sendMail",
					compose_recipient_id: recipient_id,
					compose_subject: __wps__.privatemail,
					compose_text: message,
					compose_previous: ''
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					jQuery(".__wps__sending").hide();
					jQuery('.__wps__follow_box').hide();
					var avatar = "<img style='float:left; width:48px; height:48px;margin-right:10px;' src='" + jQuery('#symposium_plus_box_avatar').attr("src") + "' />";
					jQuery("#dialog").html(avatar + message);
					jQuery("#dialog").dialog({
						title: __wps__.privatemailsent,
						width: 300,
						height: 250,
						modal: true,
						buttons: {
							"OK": function() {
								jQuery("#dialog").dialog('close');
							}
						}
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});

		}
	});

	// Add as a friend on pressing return
	jQuery('#symposium_plus_addasafriend').live('keypress', function(e) {
		if (e.keyCode == 13) {
			jQuery(".__wps__pleasewait").inmiddle().show();
			addasfriend(this, jQuery('#symposium_plus_addasafriend').val());
			jQuery(".__wps__pleasewait").hide();
		}
	});


/*
	   +------------------------------------------------------------------------------------------+
	   |                                          FORUM                                           |
	   +------------------------------------------------------------------------------------------+
	*/


	// If using AJAX, set up Forum deep linking
	if (__wps__.forum_ajax == 'on') {

		jQuery(window).bind('hashchange', function(e) {

			var tmp_loc = window.location.href.replace(/\//g, '').toLowerCase();
			var tmp_url = __wps__.forum_url.replace(/\//g, '').toLowerCase();
			if (strpos(tmp_loc, tmp_url) !== false) {
				var hash = window.location.hash.replace(/#/g, '');
				if (hash == '') {
					if (tmp_loc == tmp_url) {
						getForum(0);
					} else {
						var params = tmp_loc.split('?');
						var pieces = params[1].split('&');
						var goto_cid = false;
						var cid = 0;
						var goto_tid = false;
						for (var num = 0; num < pieces.length; num++) {
							var piece = pieces[num].split('=');
							if (piece[0] == 'cid') {
								goto_cid = true;
								cid = piece[1];
							}
							if (piece[0] == 'tid' || piece[0] == 'show') {
								goto_tid = true;
								var tid = piece[1];
							}

						}
						if (goto_tid == true) {
							getTopic(tid);
						} else {
							if (jQuery("#new-topic").length == 0 && goto_cid == false && goto_tid == false) {
								getForum(__wps__.cat_id);
							}
							if (goto_cid == true && goto_tid == false) {
								getForum(cid);
							}
						}
					}
				} else {
					var pieces = hash.split(',');
					var goto_cid = false;
					var cid = 0;
					var goto_tid = false;
					for (var num = 0; num < pieces.length; num++) {
						var piece = pieces[num].split('=');
						if (piece[0] == 'cid') {
							if (__wps__.cat_id != piece[1]) {
								goto_cid = true;
								cid = piece[1];
							}
						}
						if (piece[0] == 'tid') {
							goto_tid = true;
							var tid = piece[1];
						}

					}
					if (goto_tid == true) {
						getTopic(tid);
					} else {
						if (jQuery("#new-topic").length == 0 && goto_cid == false && goto_tid == false) {
							getForum(__wps__.cat_id);
						}
						if (goto_cid == true && goto_tid == false) {
							getForum(cid);
						}
					}
				}
			}
		});
	}

	// On page load, get forum top level, but first check for deep linking	
	if (jQuery("#__wps__forum_div").length) {

		// Make sure reply field is empty
		jQuery('#__wps__reply_text').val('');
		if (jQuery("#__wps__forum_div").html() == '') {

			var sub = "getForum";
			if (__wps__.show_tid > 0) {
				var sub = "getTopic";
			}

			var hash = window.location.hash.replace(/#/g, '');
			if (hash != '') {

				var pieces = hash.split(',');
				var goto_cid = false;
				var goto_tid = false;
				for (var num = 0; num < pieces.length; num++) {
					var piece = pieces[num].split('=');
					if (piece[0] == 'cid') {
						__wps__.cat_id = piece[1];
						goto_cid = true;
					}
					if (piece[0] == 'tid') {
						goto_tid = true;
						__wps__.show_tid = piece[1];
					}

				}
				if (goto_cid == true && goto_tid == false) {
					sub = "getForum";
				}
				if (goto_tid == true) {
					sub = "getTopic";
				}
			}

			jQuery(".__wps__pleasewait").inmiddle().show();

			// Check for permalink over-rides
			if (jQuery("#symposium_perma_topic_id").length) {
				__wps__.show_tid = jQuery("#symposium_perma_topic_id").html();
				if (jQuery("#symposium_perma_cat_id").length) {
					__wps__.cat_id = jQuery("#symposium_perma_cat_id").html();
				}
				if (__wps__.show_tid != '') {
					sub = "getTopic";
				} else {
					sub = "getForum";
				}
			}
			var show_tid = __wps__.show_tid;
			if (show_tid == '') show_tid = '0';

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/forum_functions.php",
				type: "POST",
				data: ({
					action: sub,
					limit_from: 0,
					cat_id: __wps__.cat_id,
					topic_id: __wps__.show_tid,
					group_id: __wps__.current_group
				}),
				dataType: "html",
				async: true,
				success: function(str) {

					jQuery(".__wps__pleasewait").fadeOut("slow");

					if (str != 'DONTSHOW') {
						str = trim(str);

						if (strpos(str, "[|]", 0)) {
							var details = str.split("[|]");
							jQuery(document).attr('title', details[0]);
							str = details[1];

							// Strip out redundant <br /> tags that occur with lists in TinyMCE
							if (__wps__.wps_wysiwyg == 'skipping_this_as_caused_layout_problems_on') {
								str = str.replace(/(<ul.*?>)(.*)(?=<\/ul>)/gi, function(x, y, z) {
									return y + z.replace(/\<br \/\>/gi, '')
								});
								str = str.replace('<br /><ul>', '<ul>').replace('</ul><br />', '</ul>');
								str = str.replace('<br /><ol>', '<ol>').replace('</ol><br />', '</ol>');
							}

						}
						jQuery("#__wps__forum_div").html(str);

						// Enable file uploading
						__wps__init_file_upload();

						// Init TinyMCE
						if (__wps__.wps_wysiwyg == 'on') {
							if (sub == 'getForum') {
								tiny_mce_init('new_topic_text');
								if (typeof tinyMCE.get("new_topic_text") != 'undefined') tinyMCE.get('new_topic_text').setContent('');
							} else {
								tiny_mce_init('__wps__reply_text');
								if (typeof tinyMCE.get("__wps__reply_text") != 'undefined') tinyMCE.get('__wps__reply_text').setContent('');
							}
						}

					}

				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}

			});

		} else {
			
			var str = jQuery("#__wps__forum_div").html();
			// strip out and apply page title if available
			if (strpos(str, "[|]", 0)) {
				var details = str.split("[|]");
				jQuery(document).attr('title', details[0]);
				str = details[1];
				jQuery("#__wps__forum_div").html(str);
			}

			// content already there, i.e. not AJAX loaded
			tiny_mce_init('new_topic_text');
			tiny_mce_init('__wps__reply_text');

		}

		// Enable file uploading
		if (__wps__.show_tid == 0) {
			jQuery("#symposium_perma_topic_id").html() != '' ? __wps__.show_tid = jQuery("#symposium_perma_topic_id").html() : 0;
		} // If not using AJAX
		if (!__wps__.show_tid && __wps__.show_tid != 0) __wps__.show_tid = 0;

		// Enable file uploading
		__wps__init_file_upload();

	}

	// Set up auto-expanding textboxes
	if (jQuery(".elastic").length) {
		jQuery('.elastic').elastic();
	}

	// Clicked on a social network icon
	jQuery(".symposium_social_share").live('click', function() {
		var destination = jQuery(this).attr("id");
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "socialShare",
				destination: destination
			}),
			dataType: "html",
			async: true
		});
	});

	// Comment on a reply
	jQuery(".quick-comment-box-show-link").live('click', function() {
		jQuery(this).hide();
		jQuery(this).parent().parent().find(".quick-comment-box").show();
		if (jQuery(".elastic").length) {
			jQuery('.elastic').elastic();
		}
	});
	jQuery(".quick-comment-box-add").live('click', function() {
		var comment = jQuery(this).parent().find(".quick-comment-box-comment").val();
		comment = comment.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/\n/g, "<br />");

		jQuery(this).parent().find(".quick-comment-box-comment").val('');
		jQuery(this).parent().hide();

		var html = "<div class='reply-comments-reply'>";
		var a = jQuery('#__wps__current_user_avatar').html();
		if (a && a.length > 0) {
			a = a.replace(/200/g, '32');
			a = a.replace(/196/g, '32');
			html += a;
		} else {
			// Problem retrieving user avatar - is there a wp_footer action to run __wps__lastactivity() ?
		}

		html += "<div style='margin-left: 45px;'>";
		html += comment;
		html += "</div>";

		// Add to the page
		jQuery(html).appendTo(jQuery(this).parent().parent().find(".reply-comments-box"));
		var comment_link = jQuery(this).parent().parent().find('.quick-comment-box-show-link');
		// Show after a pause to avoid multiple posts due to inpatience
		setTimeout(function() {
			comment_link.show('slow');
		}, 3000);	

		// Now add to database
		// If successful, send out email notifications
		// No need to wait for emails to be sent, refresh page if not using AJAX or refreshing the page

		var rid = jQuery(this).attr("rel");
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "replycomment",
				'tid': jQuery('#__wps__reply_tid').val(),
				'cid': jQuery('#__wps__reply_cid').val(),
				'rid': rid,
				'comment_text': comment,
				'group_id': __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				// Stored in database, so tell members via email
				if (__wps__.debug == 'on') {
					alert('STR: ' + str);
				}

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "replycommentemails",
						'rid': str
					}),
					async: true,
					success: function(str) {
						if (__wps__.debug) {
							jQuery("#dialog").html(str).dialog({
								title: __wps__.site_title + ' debug info (send replies)',
								width: 800,
								height: 500,
								modal: true
							});
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (__wps__.debug) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	});
	

	// Answer accepted
	jQuery(".forum_post_answer").live('click', function() {
		var tid = jQuery(this).attr("id");

		jQuery(this).text("");
		jQuery("<img id='symposium_tmp' src='" + __wps__.images_url + "/busy.gif' />").prependTo(this);

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "acceptAnswer",
				tid: tid
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				var row = jQuery.parseJSON(str)[0];
				jQuery("#dialog").html(row.message);
				jQuery("#dialog").dialog({
					title: row.title,
					width: 600,
					height: 225,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery("#dialog").dialog('close');
						}
					}
				});

			}
		});

		jQuery('.forum_post_answer').hide();
		jQuery('#symposium_accepted_answer').hide();
		jQuery("#symposium_tmp").hide();
		jQuery(this).show();
		jQuery("<img id='symposium_tmp' src='" + __wps__.images_url + "/tick.png' />").prependTo(this);

	});

	// Remove uploaded image
	jQuery(".remove_forum_post").live('click', function() {

		var folder = jQuery(this).attr("id");
		var file = jQuery(this).attr("title");
		var me = this;
		jQuery(this).attr('src', __wps__.images_url + "/busy.gif");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "removeUploadedImage",
				folder: folder,
				file: file
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str == 'OK') {
					jQuery(me).parent().hide();
				} else {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
			}
		});
	});

	// Warning
	jQuery(".symposium_report").live('click', function() {

		var code = jQuery(this).attr('title');
		var str = '<p>Please provide as much information about your report to the site administrator as possible.</p>';
		str += '<p style="margin-top:3px"><em>Ref: ' + code + '</em></p>';
		str += '<p style="margin-top:3px"><em>URL: ' + document.location.href + '</em></p>';
		str += '<textarea id="report_text" style="margin:auto;margin-top:10px;width:98%; height:200px"></textarea>';
		jQuery("#dialog").html(str);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 600,
			height: 400,
			modal: true,
			buttons: {
				"Report": function() {

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/ajax_functions.php",
						type: "POST",
						data: ({
							action: "sendReport",
							report_text: jQuery('#report_text').val(),
							url: document.location.href,
							code: code
						}),
						dataType: "html",
						async: true,
						success: function(str) {
							jQuery("#dialog").html('Your report has been sent to the site administrator.');
							jQuery("#dialog").dialog({
								title: __wps__.site_title,
								width: 650,
								height: 150,
								modal: true,
								buttons: {}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
					jQuery(this).dialog("close");
				},
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});

	});

	// Share permalink
	jQuery("#share_permalink").live('click', function() {
		var str = 'Copy and Paste the following:';
		str += '<br /><input type="text" style="width:550px;" value="' + jQuery(this).attr("title") + '" />';
		jQuery("#dialog").html(str);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 650,
			height: 150,
			modal: true,
			buttons: {}
		});
	});

	// Clicked on show more...
	jQuery("#showmore_forum").live('click', function() {

		var details = jQuery(this).attr("title").split(",");
		limit_from = details[0];
		cat_id = details[1];

		jQuery('#showmore_forum').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getForum",
				limit_from: limit_from,
				cat_id: cat_id,
				group_id: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery('#showmore_forum').remove();
				jQuery(str).appendTo('#__wps__table').hide().slideDown("slow");
			}
		});

	});

	// Click on drop-down list to change category
	jQuery("#__wps__change_forum_category").live('change', function() {	
		var cat_id = jQuery(this).val();
		if (cat_id >= 0) {
			getForum(cat_id);
		}
	});	
	
	// Click on category title to drill down
	jQuery(".category_title").live('click', function() {
		getForum(jQuery(this).attr("title"));
	});

	function getForum(id) {

		__wps__.cat_id = id;

		jQuery(".__wps__pleasewait").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getForum",
				cat_id: id,
				group_id: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				str = trim(str);
				if (strpos(str, "[|]", 0)) {
					var details = str.split("[|]");
					jQuery(document).attr('title', details[0]);
					str = details[1];
				}

				if (jQuery("#__wps__forum_div").length) {
					jQuery("#__wps__forum_div").html(str);
				} else {
					jQuery("#group_body").html(str);
				}

				// Enable file uploading
				__wps__init_file_upload();

				//window.scrollTo(0,0);
				jQuery(".__wps__pleasewait").fadeOut("slow");

				// Set up auto-expanding textboxes
				if (jQuery(".elastic").length) {
					jQuery('.elastic').elastic();
				}

				// Init TinyMCE
				tiny_mce_init('new_topic_text');

				jQuery(".__wps__pleasewait").fadeOut("slow");

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	}

	// Click on topic subject title
	jQuery(".topic_subject").live('click', function() {
		getTopic(jQuery(this).attr("title"));
	});

	function getTopic(id) {

		jQuery(".__wps__pleasewait").inmiddle().show();

		var topic_id = id;
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getTopic",
				topic_id: topic_id,
				group_id: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				str = trim(str);
				if (strpos(str, "[|]", 0)) {
					var details = str.split("[|]");
					jQuery(document).attr('title', details[0]);
					str = details[1];
				}

				// Show the content
				if (jQuery("#__wps__forum_div").length) {
					jQuery("#__wps__forum_div").html(str);
				} else {
					jQuery("#group_body").html(str);
				}

				// Enable file uploading
				__wps__init_file_upload();

				//window.scrollTo(0,0);
				jQuery(".__wps__pleasewait").fadeOut("slow");

				// Set up auto-expanding textboxes
				if (jQuery(".elastic").length) {
					jQuery('.elastic').elastic();
				}

				// Init TinyMCE
				tiny_mce_init('__wps__reply_text');

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	}

	// Fav Icon
	jQuery("#fav_link").live('click', function() {

		if (jQuery('#fav_link').attr('src') == __wps__.images_url + '/fav-on.png') {
			jQuery('#fav_link').attr({
				src: __wps__.images_url + '/fav-off.png'
			});
		} else {
			jQuery('#fav_link').attr({
				src: __wps__.images_url + '/fav-on.png'
			});
		}
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "toggleFav",
				tid: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) { }
		});

	});

	// Show favourites list
	jQuery("#show_favs").live('click', function() {

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 500,
			modal: true,
			buttons: {}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getFavs",
				tid: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});
	// Delete a favourite
	jQuery(".__wps__delete_fav").live('click', function() {

		jQuery(".__wps__notice").inmiddle().fadeIn();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "toggleFav",
				tid: jQuery(this).attr("title")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#fav_" + str).slideUp("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

		jQuery(".__wps__notice").delay(100).fadeOut("slow");

	});
	// Delete fav link
	jQuery('.fav_row').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".__wps__delete_fav").show();
		} else {
			jQuery(this).find(".__wps__delete_fav").hide();
		}
	});

	// Show activity list
	jQuery("#show_activity").live('click', function() {

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 500,
			modal: true,
			buttons: {}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getActivity"
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});
	// Show all activity list
	jQuery("#show_all_activity").live('click', function() {

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 500,
			modal: true,
			buttons: {}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getAllActivity",
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});
	// Show all activity threads
	jQuery("#show_threads_activity").live('click', function() {
		
		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 500,
			modal: true,
			buttons: {}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getThreadsActivity",
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});

	// Show search
	jQuery("#show_search").live('click', function() {
		do_show_search();
	});

	function do_show_search() {
		var search_form = "<div id='search-box' style='clear:both;margin-top:6px;'>";
		search_form += "<input type='text' id='search-box-input' style='width:50%; float: left; ' />";
		search_form += "<input type='submit' class='__wps__button' style='margin-top:2px; margin-left:10px;' id='search-box-button' value='Go' />";
		search_form += "</div>";
		search_form += "<div id='search-internal' style='clear:both;padding-left:6px;'></div>";

		jQuery("#dialog").html(search_form);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 500,
			modal: true,
			buttons: {}
		});
	}

	// Do search on pressing return
	jQuery('#search-box-input').live('keypress', function(e) {
		if (e.keyCode == 13) {
			do_forum_search();
		}
	});
	// Do search on pressing button
	jQuery("#search-box-button").live('click', function() {
		do_forum_search();
	});

	function do_forum_search() {
		jQuery("#search-internal").html("<img src='" + __wps__.images_url + "/busy.gif' />");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getSearch",
				term: jQuery("#search-box-input").val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#search-internal").hide().html(str).fadeIn("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	}

	// Edit topic (AJAX)
	jQuery('#starting-post').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".symposium_report").show();
			jQuery(this).find(".edit").show();
		} else {
			jQuery(this).find(".symposium_report").hide();
			jQuery(this).find(".edit").hide();
		}
	});

	// Edit the topic
	jQuery("#edit-this-topic").live('click', function() {
		
		var tid = jQuery(this).attr("title");
		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		var h = 430;
		if (__wps__.wps_wysiwyg == 'on') {
			h = 580;
		}
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 600,
			height: h,
			modal: true,
			buttons: {
				"Update": function() {
					jQuery(".__wps__notice").inmiddle().show();
					var tid = jQuery(".edit-topic-tid").attr("id");
					var parent = jQuery(".edit-topic-parent").attr("id");
					var topic_subject = jQuery(".new-topic-subject-input").val();
					var topic_post = jQuery("#edit_topic_text").val();
					if (__wps__.wps_wysiwyg == 'on') {
						var topic_post = tinyMCE.get('edit_topic_text').getContent();
					}
					var topic_category = jQuery(".new-category").val();

					if (parent == 0) {
						jQuery(".topic-post-header").html(topic_subject.replace(/\</g, "&lt;").replace(/\>/g, "&gt;"));
						if (__wps__.wps_wysiwyg != 'on') {
							jQuery(".topic-post-post").html(topic_post.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/\n/g, "<br />"));
						} else {
							jQuery(".topic-post-post").html(topic_post);
						}
					} else {
						if (__wps__.wps_wysiwyg != 'on') {
							jQuery("#child_" + tid).html("<p>" + topic_post.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/\n/g, "<br />") + "</p>");
						} else {
							jQuery("#child_" + tid).html(topic_post);
						}
					}

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/forum_functions.php",
						type: "POST",
						data: ({
							action: "updateEditDetails",
							'tid': tid,
							'topic_subject': topic_subject,
							'topic_post': topic_post,
							'topic_category': topic_category
						}),
						dataType: "html",
						async: true,
						success: function(str) {
							jQuery(".__wps__notice").fadeOut("fast");
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
					jQuery("#edit-topic-div").html(window.html_tmp);
					jQuery(this).dialog("close");

				},
				"Cancel": function() {
					jQuery("#edit-topic-div").html(window.html_tmp);
					jQuery(this).dialog("close");

				}

			}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getEditDetails",
				tid: tid
			}),
			dataType: "html",
			async: true,
			success: function(str) {

				jQuery("#dialog").html(jQuery("#edit-topic-div").html());

				window.html_tmp = jQuery("#edit-topic-div").html();
				jQuery("#edit-topic-div").html('');
				jQuery(".new-category-div").show();
				var details = str.split("[split]");
				jQuery(".new-topic-subject-input").val(details[0]);
				jQuery(".new-topic-subject-input").removeAttr("disabled");
				jQuery("#edit_topic_text").html(details[1]);
				jQuery(".edit-topic-parent").attr("id", details[2]);
				jQuery(".new-category").val(details[4]);

				// Init TinyMCE
				tiny_mce_init('edit_topic_text');

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});

	// Edit a reply
	jQuery(".edit_forum_reply").live('click', function() {

		var tid = jQuery(this).attr("id");
		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		var h = 430;
		if (__wps__.wps_wysiwyg == 'on') {
			h = 580;
		}
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 600,
			height: h,
			modal: true,
			buttons: {
				"Update": function() {
					jQuery(".__wps__notice").inmiddle().show();
					var tid = jQuery(".edit-topic-tid").attr("id");
					var parent = jQuery(".edit-topic-parent").attr("id");
					var topic_subject = jQuery(".new-topic-subject-input").val();
					var topic_post = jQuery("#edit_topic_text").val();
					if (__wps__.wps_wysiwyg == 'on') {
						var topic_post = tinyMCE.get('edit_topic_text').getContent();
					}
					var topic_category = jQuery(".new-category").val();
					
					if (parent == 0) {
						jQuery(".topic-post-header").html(topic_subject);
						if (__wps__.wps_wysiwyg != 'on') {
							jQuery(".topic-post-post").html(topic_post.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/\n/g, "<br />"));
						} else {
							jQuery(".topic-post-post").html(topic_post);
						}
					} else {
						if (__wps__.wps_wysiwyg != 'on') {
							jQuery("#child_" + tid).html("<p>" + topic_post.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/\n/g, "<br />") + "</p>");
						} else {
							jQuery("#child_" + tid).html("<p>" + topic_post + "</p>");
						}
					}

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/forum_functions.php",
						type: "POST",
						data: ({
							action: "updateEditDetails",
							'tid': tid,
							'topic_subject': topic_subject,
							'topic_post': topic_post,
							'topic_category': topic_category
						}),
						dataType: "html",
						async: true,
						success: function(str) {
							jQuery(".__wps__notice").fadeOut("fast");
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
					jQuery("#edit-topic-div").html(window.html_tmp);
					jQuery(this).dialog("close");
				},
				"Cancel": function() {
					jQuery("#edit-topic-div").html(window.html_tmp);
					jQuery(this).dialog("close");
				}
			}
		});

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "getEditDetails",
				tid: tid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(jQuery("#edit-topic-div").html());
				window.html_tmp = jQuery("#edit-topic-div").html();
				jQuery("#edit-topic-div").html('');
				jQuery(".new-category-div").hide();
				var details = str.split("[split]");
				jQuery(".new-topic-subject-input").val(details[0]);
				jQuery(".new-topic-subject-input").attr("disabled", "enabled");
				jQuery("#edit_topic_text").html(details[1]);
				jQuery(".edit-topic-parent").attr("id", details[2]);
				jQuery(".edit-topic-tid").attr("id", details[3]);

				// Init TinyMCE
				tiny_mce_init('edit_topic_text');

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	});

	// Add new reply to a topic
	jQuery("#quick-reply-warning").live('click', function() {

		if (!__wps__.use_wp_editor) {

			var reply_text = jQuery('#__wps__reply_text').val().replace(/[\n\r]$/, "");

			if (__wps__.wps_wysiwyg == 'on') {
				var reply_text = tinyMCE.get('__wps__reply_text').getContent();
			}

		} else {
			var reply_text = tinyMCE.activeEditor.getContent();
		}

		if (reply_text == '') {
			if (__wps__.wps_wysiwyg != 'on') {
				jQuery("#__wps__reply_text").css('border', '1px solid red').effect("highlight", {}, 4000);
			}
		} else {

			// Temporarily hide
			jQuery('#reply-topic-bottom').hide();

			if (__wps__.wps_forum_refresh != 'on') {

				// As it's AJAX, reshow reply form after a little pause
				setTimeout(function() {
					jQuery('#reply-topic-bottom').slideDown('fast');
				}, 3000);

				var html = "<div class='child-reply' style='overflow:hidden'>";
				html += "<div class='avatar'>";
				var a = jQuery('#__wps__current_user_avatar').html();
				if (a && a.length > 0) {
					a = a.replace(/200/g, '64');
					a = a.replace(/196/g, '64');
					html += a;
				} else {
					// Problem retrieving user avatar - is there a wp_footer action to run __wps__lastactivity() ?
				}
				html += "</div>";
				html += "<div style='padding-left: 85px;'>";
				html += "<div class='child-reply-post'>";
				if (__wps__.wps_wysiwyg == 'on') {
					html += reply_text;
				} else {
					html += reply_text.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").replace(/(<([^>]+)>)/ig, '').replace(/\n/g, "<br />");
				}
				html += "</div>";
				html += "<br class='clear' />";
				html += "</div>";
				if (jQuery('#forum_file_list').length) {
					html += jQuery('#forum_file_list').html().replace(/<.*?>/g, '');
				}
				html += "</div>";
				html += "<div class='sep'></div>";
				jQuery(html).appendTo('#child-posts');
				if (__wps__.wps_wysiwyg != 'on') {
					jQuery('#__wps__reply_text').val('');
				} else {
					if (!__wps__.use_wp_editor) {
						tinyMCE.get('__wps__reply_text').setContent('');
					} else {
						tinyMCE.activeEditor.setContent('');
					}
				}
				jQuery('#forum_file_list').html('');

			}

			// Default to answered?
			var answered = '';
			if (jQuery('#quick-reply-answer').is(":checked")) {
				var answered = 'on';
			}

			if (__wps__.wps_forum_refresh == 'on') {
				jQuery(".__wps__pleasewait").inmiddle().show();
			}

			// Process the reply
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/forum_functions.php",
				type: "POST",
				data: ({
					action: "reply",
					'tid': jQuery('#__wps__reply_tid').val(),
					'cid': jQuery('#__wps__reply_cid').val(),
					'reply_text': reply_text,
					'group_id': __wps__.current_group,
					'answered': answered
				}),
				dataType: "html",
				async: true,
				success: function(str) {

					// Stored in database, so tell members via email
					if (__wps__.debug == 'on') {
						alert('STR: ' + str);
					}

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/forum_functions.php",
						type: "POST",
						data: ({
							action: "forumReplyEmails",
							'tid': str
						}),
						async: true,
						success: function(str) {
							if (__wps__.debug) {
								jQuery("#dialog").html(str).dialog({
									title: __wps__.site_title + ' debug info (send replies)',
									width: 800,
									height: 500,
									modal: true
								});
							}

							// Reload page, if applicable
							if (__wps__.wps_forum_refresh == 'on' && __wps__.debug != 'on') {
								location.reload();
							}

						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});


				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (__wps__.debug) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}

			});

		}

	});


	// Show delete links on hover
	jQuery('.row').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".delete_topic").show()
		} else {
			jQuery(this).find(".delete_topic").hide();
		}
	});
	jQuery('.row_odd').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".delete_topic").show()
		} else {
			jQuery(this).find(".delete_topic").hide();
		}
	});
	jQuery('.child-reply').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".symposium_report").show();
			jQuery(this).find(".delete_forum_reply").show();
			jQuery(this).find(".edit_forum_reply").show();
		} else {
			jQuery(this).find(".symposium_report").hide();
			jQuery(this).find(".delete_forum_reply").hide();
			jQuery(this).find(".edit_forum_reply").hide();
		}
	});

	// Delete reply
	jQuery('.delete_forum_reply').live('click', function(event) {

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/forum_functions.php",
			type: "POST",
			data: ({
				action: "deleteReply",
				topic_id: jQuery(this).attr("id")
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (jQuery('#reply' + str).length) {
					jQuery('#reply' + str).slideUp("slow");
				}
				if (jQuery('#comment' + str).length) {
					jQuery('#comment' + str).slideUp("slow");
				}
			}
		});

	});

	// Delete topic
	jQuery(".delete_topic").live('click', function() {

		if (confirm(areyousure)) {

			var topic_id = jQuery(this).attr("id");
			jQuery('#row'+topic_id).slideUp("slow");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/forum_functions.php",
				type: "POST",
				data: ({
					action: "deleteTopic",
					topic_id: topic_id
				}),
				dataType: "html",
				async: false,
				success: function(str) {
				}
			});

		}

	});

	// Show new topic and reply topic forms
	jQuery("#new-topic-button").live('click', function() {
		jQuery("#new-topic").show();
		jQuery("#forum_options").hide();
		jQuery("#share_link").hide();
		jQuery(".__wps__forum_table").hide();
		jQuery(".__wps__subscribe_option").hide();
		jQuery("#new-topic-button").hide();
		jQuery("#forum_breadcrumbs").hide();
		jQuery("#__wps__forum_dropdown").hide();
	});
	jQuery("#cancel_post").live('click', function() {
		jQuery("#new-topic").hide();
		jQuery("#forum_options").show();
		jQuery("#share_link").show();
		jQuery(".__wps__forum_table").show();
		jQuery(".__wps__subscribe_option").show();
		jQuery("#new-topic-button").show();
		jQuery("#forum_breadcrumbs").show();
		jQuery("#__wps__forum_dropdown").show();
	});

	// Post a new topic
	jQuery("#symposium_new_post").live('click', function() {

		var subject = jQuery('#new_topic_subject').val();
		var text = jQuery('#new_topic_text').val();
		if (__wps__.wps_wysiwyg == 'on') {
			var text = tinyMCE.get('new_topic_text').getContent();
		}

		var category = 0;
		if (jQuery("#new_topic_category").length) {
			category = jQuery('#new_topic_category').val();
		}

		if (subject == '') {
			jQuery("#new_topic_subject").css('border', '1px solid red').effect("highlight", {}, 4000);
		} else {

			if (text == '') {
				if (__wps__.wps_wysiwyg != 'on') {
					jQuery("#new_topic_text").css('border', '1px solid red').effect("highlight", {}, 4000);
				}
			} else {

				jQuery(".__wps__pleasewait").inmiddle().show();

				var subscribed = '';
				if (jQuery('#new_topic_subscribe').is(":checked")) {
					var subscribed = 'on'
				}
				var info_only = '';
				if (jQuery('#info_only').is(":checked")) {
					var info_only = 'on'
				}

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "forumNewPost",
						'subject': subject,
						'text': text,
						'category': category,
						'subscribed': subscribed,
						'info_only': info_only,
						'group_id': __wps__.current_group
					}),
					async: false,
					success: function(str) {

						var details = str.split("[|]");
						var new_tid = details[0];
						var url = details[1];
						
						// Stored in database, so tell members via email
						jQuery.ajax({
							url: __wps__.plugin_url + "ajax/forum_functions.php",
							type: "POST",
							data: ({
								action: "forumNewPostEmails",
								'new_tid': new_tid,
								'cat_id': category,
								'group_id': __wps__.current_group
							}),
							async: true,
							success: function(str) {
								if (__wps__.debug) {
									jQuery("#dialog").html(str).dialog({
										title: __wps__.site_title + ' debug info',
										width: 800,
										height: 500,
										modal: true
									});
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								if (show_js_errors) {
									alert(xhr.status);
									alert(xhr.statusText);
									alert(thrownError);
								}
							}
						});

						// Redirect, no need to wait for email's to be sent out, can happen in background
						// But add a little pause to make sure script above has been initiated
						if (!__wps__.debug) {
							setTimeout(function() { window.location.href = url; },3000);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});
			}
		}
	});


	// Has a checkbox been clicked? If so, check if one for symposium (AJAX)
	jQuery("input[type='checkbox']").live('click', function() {

		var checkbox = jQuery(this).attr("id");

		// Toggle for info only
		if (checkbox == "symposium_for_info") {
			var value = '';
			if (jQuery(this).is(":checked")) {
				value = 'on';
				jQuery(".forum_post_answer").hide();
			} else {
				jQuery(".forum_post_answer").show();
			}
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/forum_functions.php",
				type: "POST",
				data: ({
					action: "toggleForInfo",
					tid: jQuery(this).attr("title"),
					value: value
				}),
				dataType: "html",
				async: true
			});
		};

		// Subscribe to New Forum Topics in a category
		if (checkbox == "symposium_subscribe") {
			jQuery(".__wps__notice").inmiddle().fadeIn();
			if (jQuery(this).is(":checked")) {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForumSubscribe",
						'cid': jQuery(this).attr("title"),
						"value": 1
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForumSubscribe",
						'cid': jQuery(this).attr("title"),
						"value": 0
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
			jQuery(".__wps__notice").delay(100).fadeOut("slow");
		}

		// Subscribe to Topic Posts
		if (checkbox == "subscribe") {
			jQuery(".__wps__notice").inmiddle().fadeIn();
			if (jQuery(this).is(":checked")) {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForum",
						'tid': jQuery(this).attr("title"),
						'value': 1
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForum",
						'tid': jQuery(this).attr("title"),
						'value': 0
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
			jQuery(".__wps__notice").delay(100).fadeOut("slow");
		}

		// Sticky Topics
		if (checkbox == "sticky") {
			jQuery(".__wps__notice").inmiddle().fadeIn();
			if (jQuery(this).is(":checked")) {
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForumSticky",
						'tid': jQuery(this).attr("title"),
						'value': 1
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateForumSticky",
						'tid': jQuery(this).attr("title"),
						'value': 0
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
			jQuery(".__wps__notice").delay(100).fadeOut("slow");
		}

		// Digest
		if (checkbox == "symposium_digest") {
			jQuery(".__wps__notice").inmiddle().fadeIn();
			if (jQuery(this).is(":checked")) {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateDigest",
						'value': 'on'
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateDigest",
						'value': ''
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});
			}
			jQuery(".__wps__notice").delay(100).fadeOut("slow");
		}

		// Replies
		if (checkbox == "replies") {
			jQuery(".__wps__notice").inmiddle().fadeIn();
			if (jQuery(this).is(":checked")) {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateTopicReplies",
						'tid': jQuery(this).attr("title"),
						'value': 'on'
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateTopicReplies",
						'tid': jQuery(this).attr("title"),
						'value': ''
					}),
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
			jQuery(".__wps__notice").delay(100).fadeOut("slow");
		}

	});

	// Score
	jQuery(".forum_post_score_change").live('click', function() {

		if (__wps__.current_user_id == 0) {
			alert('Please log in to register your vote.');
		} else {

			var change = jQuery(this).attr("title");
			var tid = jQuery(this).attr("id");
			var score = parseFloat(jQuery('#forum_score_' + tid).html());

			if (change == 'novote') {

				jQuery("#dialog").html(jQuery("#symposium_novote").html());
				jQuery("#dialog").dialog({
					title: jQuery('#symposium_novote_dialog').html(),
					width: 600,
					height: 175,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery("#dialog").dialog('close');
						}
					}
				});

			} else {

				if (change == 'plus') {
					var change = 1;
				} else {
					var change = -1;
				}

				var vote_off = parseFloat(jQuery('#symposium_forum_vote_remove').html());
				var new_score = score + change;

				if (vote_off != 0 && vote_off == new_score) {
					jQuery('#reply' + tid).html(jQuery('#symposium_forum_vote_remove_msg').html())
				}

				jQuery('#forum_score_' + tid).html("<img src='" + __wps__.images_url + "/busy.gif' />");

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: "updateTopicScore",
						'tid': tid,
						'change': change
					}),
					success: function(str) {
						var rows = jQuery.parseJSON(str);
						jQuery.each(rows, function(i, row) {

							var score = row.score;
							if (score > 0) {
								score = '+' + score;
							}
							jQuery('#forum_score_' + tid).html(score);
							if (row.str != 'OK') {
								jQuery("#dialog").html(row.str);
								jQuery("#dialog").dialog({
									title: jQuery('#symposium_novote_dialog').html(),
									width: 600,
									height: 225,
									modal: true,
									buttons: {
										"OK": function() {
											jQuery("#dialog").dialog('close');
										}
									}
								});
							}
						})
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}

		}

	});



/*
	   +------------------------------------------------------------------------------------------+
	   |                                          PANEL                                           |
	   +------------------------------------------------------------------------------------------+
	*/



	if (jQuery("#__wps__notification_bar").length) {

		// Quick check on polling frequency
		if (__wps__.panel_enabled) {
			
			// Chat ========================
			
			//MINIMIZE, MAXIMIZE, CLOSE CHAT WINDOW			
				
				//minimize function
			jQuery('.minimize_chatbox').live('click',function(){
				//remove chat,message area			
				jQuery(this).closest('.chatbox').find('.chat_area,.chat_message').css('height','0px');		
				jQuery(this).closest('.chatbox').css('height','25px');
				
				//replace minimize icon
				jQuery(this).css('display','none');
				jQuery(this).closest('.chatbox').find('.maximize_chatbox').css('display','inline');
				jQuery(this).closest('.chatbox').data('chatbox_status',2);
				UpdateChatWindowStatus();
				return false;
			});
			
			
			//maximize function
			jQuery('.maximize_chatbox').live('click',function(){
				//remove chat,message area	
				jQuery(this).closest('.chatbox').find('.chat_area').css('height','200px');		
				jQuery(this).closest('.chatbox').find('.chat_message').css('height','55px');		
				jQuery(this).closest('.chatbox').css('height','300px');
				jQuery(this).closest('.chatbox').find('.header_bg_blink').removeClass("header_bg_blink").addClass("header_bg_default");
				
				//replace minimize icon
				jQuery(this).css('display','none');
				jQuery(this).closest('.chatbox').find('.minimize_chatbox').css('display','inline');
				jQuery(this).closest('.chatbox').find('.header .new_message').remove();
				jQuery(this).closest('.chatbox').find('.chat_message textarea').focus();
				jQuery(this).closest('.chatbox').data('chatbox_status',1);
				UpdateChatWindowStatus();
				return false;
			});
			
			//clear function
			jQuery('.clear_chatbox').live('click',function(){
				jQuery(this).closest('.chatbox').find('.chat_area').html('<p class="system">'+__wps__.pleasewait+'...</p>');
				var to_id = jQuery(this).closest('.chatbox').attr('title');
				var datastring = 'from_id='+__wps__.current_user_id+'&to_id='+to_id;
				jQuery.ajax({
					type: "POST",
					url: __wps__.plugin_url+'ajax/chat/clear_chat.php',	
					async: true,					
					data: datastring,
					success: function(i){			
						if (i != '1')
							alert(i);
					}
				});
			});
			
			//popup function
			jQuery('.popup_chatbox').live('click',function(){
				var url = __wps__.plugin_url+'ajax/chat/popup.html?heartBeat='+heartBeat+'&id='+__wps__.current_user_id+'&partner='+jQuery(this).attr('title')+'&partner_id='+jQuery(this).attr('rel')+'&url='+__wps__.plugin_url+'&chat_sound='+__wps__.chat_sound;
		        var windowName = "popUp"+Math.floor(Math.random()*1000);
		        var windowSizeArray = [ "width=216,height=280","width=216,height=280,scrollbars=no" ]
		        var w = window.open(url, windowName, windowSizeArray);
		        event.preventDefault();		    		
		    });

			//close function
			jQuery('.close_chatbox').live('click',function(){
				var closed_pos = parseInt(jQuery(this).closest('.chatbox').css('right'));
				chatboxcount --;
				jQuery(this).closest('.chatbox').remove();		
				//set nu position for all appearing chat window
				jQuery('.chatbox').each(function(){
					var prev_pos = parseInt(jQuery(this).css('right'));
					if(prev_pos != 10 && (prev_pos > closed_pos )){
						var nu_pos = prev_pos - 210;
						jQuery(this).css('right',nu_pos+'px');
					}
				});

				UpdateChatWindowStatus();
				return false;
			});		
			
			//ON USER CLICK POP UP CHAT
		
			jQuery('.__wps__chat_user').live('click',function(){
				var substr = jQuery(this).attr('alt').split('|');
				var user_id = substr[0];
				var user_name = substr[1];
				
				//check if a windows is already open with this user first!
				if(jQuery('div[title="'+user_id+'"]').length > 0){					
					//alert('You\'re already chatting with him/her!');
				}else{
					PopupChat(user_id,user_name,1,1);
				}
					
			});
			
			// set focus in Message area
			jQuery('.chatbox').live('click',function(){
				jQuerytextarea = jQuery('.chat_message textarea',this);		
				jQuerytextarea.focus();
			});
			
		
			
			//HIGHLIGHT Active chat window
			jQuery('.chat_message textarea').live('focus',function() {
				var chatbox = jQuery(this).closest('.chatbox');
				this_chatbox_headerbg = jQuery('.header',chatbox);
				this_chatbox_headerbg.removeClass("header_bg_blink").addClass("header_bg_default");
				chatbox.removeClass("cb_default").addClass("cb_highlight"); // add highligt to chat window
				chatbox.data('focused',1);		   // enable focus variable
				chatbox.data('havenewmessage',0); // clear new message
				chatbox.data('playedsound',1); // clear new message
			});
			jQuery('.chat_message textarea').live('blur',function() {
				var chatbox = jQuery(this).closest('.chatbox');
				chatbox.removeClass("cb_highlight").addClass("cb_default"); // remove highligt of chat window
				chatbox.data('focused',0);	// disable focus variable
			});
			
			//SEND MESSAGE ON ENTER		
			jQuery('.chat_message textarea').live('keypress', function (e) {

				if (e.keyCode == 13 && !e.shiftKey) {
					e.preventDefault();
					
					//add to MySQL DB with AJAX and PHP
					var to_id = jQuery(this).closest('.chatbox').attr('title');
					var this_chat_window_id = jQuery(this).closest('.chatbox').attr('id');
					var this_textarea = jQuery(this);
					var datastring = 'from_id='+__wps__.current_user_id+'&to_id='+to_id+'&message='+this_textarea.val();
					
					if (this_textarea.val() != __wps__.sending+'... '+__wps__.pleasewait+'  ') {

						if (this_textarea.val() != '') {
					
							// show sending message
							this_textarea.val(__wps__.sending+'... '+__wps__.pleasewait+'  ').attr('disabled', 'disabled');
		
							jQuery.ajax({
								type: "POST",
								url: __wps__.plugin_url+'ajax/chat/send_message.php',	
								async: true,					
								data: datastring,
								success: function(i){			
									if(i == 1){
										//if success, reload chat area
										this_textarea.val('').removeAttr('disabled');							
									}else{					
										//if error,  print it into chat
										print_to_chat(this_chat_window_id,'<p><span class="error">Error! Message not sent!</span></p>');
										//uncomment to print mysql error to chat
										print_to_chat(i);								
									}
								}
							});
							
						}
						
					}
					
				}else{
				}				
			});
			
			//LOOP OF LIFE - checks every ... seconds if there's a new message
			function liveChat(__wps__first_load){
				//go through all popped up window and reload messages, mark those messages as received			
				jQuery('.chatbox').each(function(){												
					var this_chatbox = jQuery(this);
					var this_chatbox_chat_area = jQuery('.chat_area',this);		
					var this_chatbox_headerbg = jQuery('.header',this);				
					var this_chatbox_header = jQuery('.header p',this);				
					var this_chatbox_max_btn  = jQuery('.header .maximize_chatbox',this);				
					var this_chatbox_id = jQuery(this).attr('title');
					var this_newmessage = jQuery('.header p .new_message',this);				
					//v1.2 -----------------------------------------------------
					
					var this_chatbox_textarea  = jQuery('.chat_message textarea',this);			

					// typing?
					if (this_chatbox_textarea.val() != '') {
						is_typing = 1;
					} else {
						is_typing = 0;
					}

					//v1.2 -----------------------------------------------------								
					jQuery.ajax({
						type: "POST",
						url: __wps__.plugin_url+'ajax/chat/load_message.php',								
						data: 'own_id='+__wps__.current_user_id+'&partner_id='+this_chatbox_id+'&typing='+is_typing,
						async: true,					
						success: function(i){	

							//reload messages in chat area
							if(i != 0){			

								// is typing?		
								if (i.indexOf('#(') >= 0) {
									var start_pos = i.indexOf('#(') + 2;
									var end_pos = i.indexOf(')#',start_pos);
									var is_typing = i.substring(start_pos,end_pos);
									if (is_typing == 0) {
										this_chatbox_textarea.parent().find(".chat_user_replying").hide();
									} else {
										this_chatbox_textarea.parent().find(".chat_user_replying").show();
									}
								}
															
								// id of last message			
								var current_chat = this_chatbox_chat_area.html();
								var old_id = false;
								if (current_chat.indexOf('#[') >= 0) {
									var start_pos = current_chat.indexOf('#[') + 2;
									var end_pos = current_chat.indexOf(']#',start_pos);
									old_id = current_chat.substring(start_pos,end_pos);
								}
															
								var new_chat = i.replace(/\\\'/g,'\'').replace(/\\\"/g,'\"');								
								start_pos = new_chat.indexOf('#[') + 2;
								end_pos = new_chat.indexOf(']#',start_pos);
								var new_id = new_chat.substring(start_pos,end_pos);
								
								// who sent last message?

								if (new_chat.indexOf('#{') >= 0) {
									start_pos = new_chat.indexOf('#{') + 2;
									end_pos = new_chat.indexOf('}#',start_pos);
									var last_id = new_chat.substring(start_pos,end_pos);
								}

								if (old_id) {
									
									if (old_id != new_id){

										// show chat
										this_chatbox_textarea.parent().find(".chat_user_replying").hide();
																												
										var chat_output = i;
										chat_output = i.replace(/\\\'/g,'\'').replace(/\\\"/g,'\"');
										chat_output = chat_output.replace(/#\(/g,'<div style="clear:both;height:2px;width:2px;display:none;">#(').replace(/\)#/g,')#</div>');
										chat_output = chat_output.replace(/#\[/g,'<div style="clear:both;height:2px;width:2px;display:none;">#[').replace(/\]#/g,']#</div>');
										chat_output = chat_output.replace(/#\{/g,'<div style="clear:both;height:2px;width:2px;display:none;">#{').replace(/\}#/g,'}#</div>');
										this_chatbox_chat_area.html(chat_output);	
										
										// scroll to bottom
										this_chatbox_chat_area.animate({scrollTop: 9999999},200);
							
										if (last_id != __wps__.current_user_id) {

											this_chatbox.data('havenewmessage',1);
											this_chatbox.data('playedsound', 0);
											
											// alert user?
											if (this_chatbox.data('focused') != 1 && this_chatbox.data('havenewmessage') == 1) { // blinking chat window if not focused and have a new message
												this_chatbox_headerbg.removeClass("header_bg_default").addClass("header_bg_blink");
											}					
											if (this_chatbox.data('havenewmessage') == 1) { // blinking chat window if not focused and have a new message
												if (__wps__.chat_sound != 'none' && this_chatbox.data('playedsound') == 0) { // sound
													this_chatbox.data('playedsound', 1);
													jQuery("#player_div").empty();
													jQuery("#player_div").prepend(__wps__insertPlayer(__wps__.plugin_url+'ajax/chat/flash/player.swf', __wps__.plugin_url+'/ajax/chat/flash/'+__wps__.chat_sound));
												}
											}	
	
										}					
									
									}
									
								} else {

										// show chat
																		
										var chat_output = i;
										chat_output = i.replace(/\\\'/g,'\'').replace(/\\\"/g,'\"');
										chat_output = chat_output.replace(/#\(/g,'<div style="clear:both;height:2px;width:2px;display:none;">#(').replace(/\)#/g,')#</div>');
										chat_output = chat_output.replace(/#\[/g,'<div style="clear:both;display:none;">#[').replace(/\]#/g,']#</div>');
										chat_output = chat_output.replace(/#\{/g,'<div style="clear:both;display:none;">#{').replace(/\}#/g,'}#</div>');
										this_chatbox_chat_area.html(chat_output);	

										// scroll to bottom
										this_chatbox_chat_area.animate({scrollTop: 9999999},200);

								}									
								
							}else{
								this_chatbox_chat_area.html('');
							}
						}
					});	
					
				});
				
				
				//check if current user has a new message (to_id = current_user_id) which haven't been received and there's no popup with that user
				jQuery.ajax({
					type: "POST",
					url: __wps__.plugin_url+'ajax/chat/load_popup_message.php',	
					async: true,					
					data: 'own_id='+__wps__.current_user_id,
					success: function(o){	
						if(o != 0){
							//there's a new message, so just open up a new chat window and message will be loaded automatically
							var substr = o.split(';;;');		
	
							//check if a windows is already open with this user first!
							if(jQuery('div[title="'+substr[0]+'"]').length == 0){					
								PopupChat(substr[0],substr[1],1,__wps__first_load);
							}												
							
						}
					}
				});
				
				__wps__first_load = 0;
				
				//and start the loop again
				
				t=setTimeout(function() {
				    liveChat(__wps__first_load);
				}, heartBeat);
			}
			
			var __wps__first_load = 1;					
			liveChat(__wps__first_load);	//start the chat
			
			// Set up icon actions ******************************************************
			
			// Hover/click on logout?
			jQuery("#__wps__logout").mouseenter(function() {
				jQuery("#__wps__logout_div").show();
			});
			jQuery("#__wps__logout_div").mouseleave(function() {
				jQuery("#__wps__logout_div").fadeOut('slow');
			});

			// Click on change online status?
			jQuery("#__wps__online_status").click(function() {
				var status = jQuery("#__wps__online_status").is(":checked");
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/bar_functions.php",
					type: "POST",
					data: ({
						action: 'symposium_status',
						status: status
					}),
					dataType: "html",
					async: false
				});
			});

			// Click on logout link
			jQuery("#__wps__logout-link").click(function() {
				if (confirm(areyousure)) {
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/ajax_functions.php",
						type: "POST",
						data: ({
							action: 'symposium_logout'
						}),
						dataType: "html",
						async: false,
						success: function(str) {
							window.location.href = __wps__.site_url;
						}
					});
				} else {
					jQuery("#__wps__logout_div").hide();
				}
			});

			// Email icon
			if (jQuery("#__wps__email_box").css("display") != "none") {
				jQuery("#__wps__email_box").click(function() {
					window.location.href = __wps__.mail_url;
				});

			}

			// Icon Actions
			if (jQuery("#__wps__friends_box").css("display") != "none") {
				jQuery("#__wps__friends_box").click(function() {
					var q = symposium_q(__wps__.profile_url);
					window.location.href = __wps__.profile_url + q + 'view=friends';
				});
				jQuery("#__wps__online_box").click(function() {
					jQuery('#__wps__who_online').show();
				});
				jQuery("#__wps__who_online_close").click(function() {
					jQuery('#__wps__who_online').hide();
				});
			}

			// Scheduled checks for unread mail ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			if (__wps__.current_user_id > 0) {

				// Clear locking cookies
				eraseCookie('wps_bar_check');

				// Check for notifications, unread mail, friend requests, etc
				bar_polling();
			}
			
		}
	}


	function do_online_friends_check() {
		

		// Friends Online ******************************************************
		if (jQuery("#__wps__friends_box").css("display") != "none") {

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/bar_functions.php",
				type: "POST",
				data: ({
					action: "symposium_friendrequests"
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str > 0) {
						jQuery("#__wps__friends_box").html(str);
						jQuery("#__wps__friends_box").removeClass("__wps__friends_box-none");
						jQuery("#__wps__friends_box").addClass("__wps__friends_box-new");
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}

			});

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/bar_functions.php",
				type: "POST",
				data: ({
					action: "symposium_getfriendsonline",
					uid: __wps__.current_user_id,
					inactive: __wps__.inactive,
					offline: __wps__.offline,
					use_chat: __wps__.use_chat
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str != '') {
						var split = str.split("[split]");
						jQuery("#__wps__online_box").html(split[0]);
						jQuery("#__wps__friends_online_list").html(split[1]);
						if (split[0] > 0) {
							jQuery("#__wps__online_box").removeClass("__wps__online_box-none");
							jQuery("#__wps__online_box").addClass("__wps__online_box");
						} else {
							jQuery("#__wps__online_box").removeClass("__wps__online_box");
							jQuery("#__wps__online_box").addClass("__wps__online_box-none");
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}

			});

		}
	}

	function do_bar_check() {

		var bar_check_cookie = readCookie('wps_bar_check');

		if (bar_check_cookie == 'lock') {
			// Still processing previous
			//alert('BAR LOCKED');
		} else {
			// Set cookie (to avoid over-lapping checks)
			createCookie('wps_bar_check', 'lock', 1);
			//alert('CREATE BAR LOCK');
			// Email ******************************************************
			if (jQuery("#__wps__email_box").css("display") != "none") {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/bar_functions.php",
					type: "POST",
					data: ({
						action: "symposium_getunreadmail"
					}),
					dataType: "html",
					async: true,
					success: function(str) {
						if (str > 0) {
							jQuery("#__wps__email_box").html(str);
							jQuery("#__wps__email_box").removeClass("__wps__email_box-read");
							jQuery("#__wps__email_box").addClass("__wps__email_box-unread");
						}
						eraseCookie('wps_bar_check');
						//alert('CLEAR BAR LOCK');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
		}
	}

	function removeHTMLTags(strInputCode) {
		strInputCode = strInputCode.replace(/&(lt|gt);/g, function(strMatch, p1) {
			return (p1 == "lt") ? "<" : ">";
		});
		var strTagStrippedText = strInputCode.replace(/<\/?[^>]+(>|$)/g, "");
		return strTagStrippedText;
	}

	// Cookies


	function createCookie(name, value, days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			var expires = "; expires=" + date.toGMTString();
		} else var expires = "";
		document.cookie = name + "=" + value + expires + "; path=/";
	}

	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}

	function eraseCookie(name) {
		createCookie(name, "", -1);
	}


	// Form validations


	function validate_form(thisform) {
		form_id = thisform.id;

		// Login
		if ((form_id) == "symposium_login") {
			var r = true;
			with(thisform) {
				if (forgotten_email.value == '') {
					if (username.value == '' || username.value == null) {
						jQuery("#username-warning").show("slow");
						username.focus();
					} else {
						jQuery("#username-warning").hide("slow");
					}
					if (pwd.value == '' || pwd.value == null) {
						jQuery("#pwd-warning").show("slow");
						username.focus();
					} else {
						jQuery("#pwd-warning").hide("slow");
					}
				}
			}
			// return false to avoid submit, redirect handled in jQuery
			return false;
		}

		// Registration
		if ((form_id) == "symposium_registration") {
			var r = true;
			with(thisform) {
				if ((pwd.value != '' || pwd2.value != null) && (pwd.value != pwd2.value)) {
					jQuery("#password2-warning").show("slow");
					pwd.focus();
					r = false;
				} else {
					jQuery("#password2-warning").hide("slow");
				}
				if (pwd.value == '' || pwd.value == null) {
					jQuery("#password-warning").show("slow");
					pwd.focus();
					r = false;
				} else {
					jQuery("#password-warning").hide("slow");
				}
				if (youremail.value == '' || youremail.value == null) {
					jQuery("#youremail-warning").show("slow");
					youremail.focus();
					r = false;
				} else {
					jQuery("#youremail-warning").hide("slow");
				}
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				if (reg.test(youremail.value) == false) {
					jQuery("#youremail-warning").show("slow");
					youremail.focus();
					r = false;
				} else {
					jQuery("#youremail-warning").hide("slow");
				}
				if (display_name.value == '' || display_name.value == null) {
					jQuery("#display_name-warning").show("slow");
					display_name.focus();
					r = false;
				} else {
					jQuery("#display_name-warning").hide("slow");
				}
				if (username.value == '' || username.value == null) {
					jQuery("#username-warning").show("slow");
					username.focus();
					r = false;
				} else {
					jQuery("#username-warning").hide("slow");
				}
			}
			return r;
		}

		// Forum	
		if ((form_id) == "start-new-topic") {
			with(thisform) {
				if (new_topic_subject.value == '' || new_topic_subject.value == null) {
					jQuery(".new-topic-subject-warning").show("slow");
					new_topic_subject.focus();
					return false;
				}
				if (new_topic_text.value == '' || new_topic_text.value == null) {
					jQuery(".new_topic_text-warning").show("slow");
					new_topic_text.focus();
					return false;
				}
			}
		}
		if ((form_id) == "start-reply-topic") {
			with(thisform) {
				if (reply_text.value == '' || reply_text.value == null) {
					jQuery(".reply_text-warning").show("slow");
					reply_text.focus();
					return false;
				}
			}
		}


	}

	function strpos(haystack, needle, offset) {
		var i = (haystack + '').indexOf(needle, (offset || 0));
		return i === -1 ? false : i;
	}

	function trim(s) {
		var l = 0;
		var r = s.length - 1;
		while (l < s.length && s[l] == ' ') {
			l++;
		}
		while (r > l && s[r] == ' ') {
			r -= 1;
		}
		return s.substring(l, r + 1);
	}

	function bar_polling() {
		do_bar_check();
		do_online_friends_check();
		if (__wps__.wps_lite != 'on') {
			setTimeout(bar_polling, __wps__.bar_polling * 1000);
		}
	}


/*
	   +------------------------------------------------------------------------------------------+
	   |                                        ALERTS                                            |
	   +------------------------------------------------------------------------------------------+
	*/

	if (jQuery("#__wps__alerts").length && __wps__.wps_alerts_activated == 1) {
		
		// Start regular checks for lounge contents
		if (__wps__.is_admin == 0) {
			news_polling();
		}

		// Show/hide news events as drop down below menu item
		jQuery('#__wps__alerts').live('mouseenter', function(event) {
			// Only show if list is present
			if (!(jQuery('#__wps__news_items').is(':visible'))) {
				if (event.type == 'mouseenter') {
					jQuery("#__wps__news_items").show();

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/news_functions.php",
						type: "POST",
						data: ({
							action: 'clear_read_news'
						}),
						dataType: "html",
						success: function(str) {},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
				}
			}
		});
		jQuery('#__wps__alerts').live('mouseleave', function(event) {
			if (event.type == 'mouseleave') {
				jQuery("#__wps__news_items").hide();
				jQuery("#__wps__news_highlight").remove();
			}
		});

		// Clear all notifications
		jQuery('#symposium_clear_news').live('click', function(event) {
			jQuery(".__wps__news_item").remove();
			jQuery("#__wps__news_highlight").remove();
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/news_functions.php",
				type: "POST",
				data: ({
					action: 'delete_all_news'
				}),
				dataType: "html",
				success: function(str) {}
			});
		});

	}

	function news_polling() {

		// Only do if not admin and Alerts is ativated
		if (__wps__.is_admin == 0) {

			// Don't poll if showing drop-down list of items
			if (!(jQuery('#__wps__news_items').is(':visible'))) {

				var news_items = '';

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/news_functions.php",
					type: "POST",
					data: ({
						action: 'get_news'
					}),
					dataType: "html",
					success: function(str) {

						jQuery("#__wps__news_items").remove();
						if (str != '[]') {

							var rows = jQuery.parseJSON(str);

							var items = "<div id='__wps__news_items' style='display:none; left:inherit; top:inherit;'>";

							var row_count = 0;
							var new_count = 0;
							var url = '';

							jQuery.each(rows, function(i, row) {
								if (row.nid > 0) {
									if (row.new_item == 'on') {
										new_count++;
									}
									if (row_count < 10) {
										row_count++;
										items += "<div class='__wps__news_item'>";
										if (row.new_item == 'on') {
											items += "<span class='__wps__news_item_newitem'>";
										}
										items += stripslashes(row.news);
										if (row.new_item == 'on') {
											items += "</span>";
										}
										items += "</div>";
									}
								} else {
									url = row.news;
								}
							});

							if (url != '') {
								items += "<div class='__wps__news_item'>";
								items += '<a id="symposium_clear_news" style="float:right" href="javascript:void(0)">' + symposium_clear + '</a>';
								items += "<a style='float:left' href='" + url + "'>" + more + "</a>";
								items += "</div>";
							}

							items += '</div>';

							if (new_count > 0) {
								if (jQuery("#__wps__news_highlight").length > 0) {
									jQuery("#__wps__news_highlight").html(new_count);
								} else {
									jQuery('<span id="__wps__news_highlight">' + new_count + '</span>').appendTo('#__wps__alerts');
								}
							} else {
								jQuery("#__wps__news_highlight").hide();
							}
							jQuery(items).appendTo('#__wps__alerts');
							var __wps__news_y_offset = jQuery("#__wps__news_y_offset").html();
							var __wps__news_y_offset = jQuery("#__wps__news_y_offset").html();
							jQuery("#__wps__news_items").css("margin-left", (__wps__news_y_offset - 17) + "px");
							jQuery("#__wps__news_items").css("margin-top", (__wps__news_y_offset - 44) + "px");
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}

		}

		// Repeat check every 5 seconds
		if (__wps__.wps_lite != 'on') {
			var polling = jQuery('#__wps__news_polling').html();
			if (polling < 1) {
				polling = 5;
			}
			setTimeout(news_polling, polling * 1000);
		}

	}

	function stripslashes(str) {
		str = str.replace(/\\'/g, '\'');
		str = str.replace(/\\"/g, '"');
		str = str.replace(/\\0/g, '\0');
		str = str.replace(/\\\\/g, '\\');
		return str;
	}

/*
	   +------------------------------------------------------------------------------------------+
	   |                                        EVENTS                                            |
	   +------------------------------------------------------------------------------------------+
	*/



	// Act on default view on profile page being for Events
	if (__wps__.view == 'wps_events' && __wps__.embed == 'on') {

		jQuery('#profile_body').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var menu_id = 'menu_events';
		var ajax_path = __wps__.plugin_url + "ajax/events_functions.php";

		jQuery.ajax({
			url: ajax_path,
			type: "POST",
			data: ({
				action: menu_id,
				post: '',
				limit_from: 0,
				uid1: __wps__.current_user_page
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery('#profile_body').hide().html(str).fadeIn("slow");
			}
		});
	}
	
	// Calendar view
	if (jQuery("#__wps__events_calendar").length) {
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "calendar_view",
				month: 3,
				year: 2013
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#__wps__events_calendar").html(str);
				jQuery(".__wps__pleasewait").hide();
			}
		});		
	}
	jQuery("#__wps__event_move").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "calendar_view",
				month: jQuery(this).data('month'),
				year: jQuery(this).data('year'),
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#__wps__events_calendar").html(str);
				jQuery(".__wps__pleasewait").hide();
			}
		});		
	});
				
	// Payment	
	jQuery("#symposium_pay_event").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "event_payment",
				'bid': jQuery(this).data('bid')
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				jQuery("#dialog").html(str).dialog({
					title: __wps__.site_title,
					width: 400,
					height: 300,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery("#dialog").dialog('close');
						}
					}
				});
			}
		});
	});

	// Cancel
	jQuery("#symposium_cancel_event").live('click', function() {
		var answer = confirm(areyousure);
		if (answer) {			
			jQuery('.symposium_cancel_event_button').hide();
			jQuery('.symposium_pay_event_button').hide();
			jQuery(".__wps__pleasewait").inmiddle().show();
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/events_functions.php",
				type: "POST",
				data: ({
					action: "cancel_event",
					'eid': jQuery(this).data('eid')
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					jQuery(".__wps__pleasewait").hide();
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title,
						width: 400,
						height: 300,
						modal: true,
						buttons: {
							"OK": function() {
								jQuery("#dialog").dialog('close');
							}
						}
					});
				}
			});
		}
	});

	// Book
	jQuery("#symposium_book_event").live('click', function() {

		jQuery('.symposium_book_event_button').hide();

		// dialog from events.php
		var events_howmany = __wps__.events_howmany;

		jQuery(this).hide();
		var str = events_howmany;
		str += ' <select id="symposium_events_how_many">';
		for (i = 1; i <= jQuery(this).data('max'); i++) {
			str += '<option value=' + i + '>' + i;
		}
		str += '</select><br /><br />';
		str += '<input id="symposium_book_event_next" data-eid="' + jQuery(this).data('eid') + '" type="submit" class="__wps__button" value="' + symposium_next + ' &gt;" />';
		jQuery("#dialog").html(str).dialog({
			title: __wps__.site_title,
			width: 400,
			height: 300,
			modal: true
		});
	});
	jQuery("#symposium_book_event_next").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "register_event",
				'eid': jQuery(this).data('eid'),
				'howmany': jQuery('#symposium_events_how_many').val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				jQuery("#dialog").html(str).dialog({
					title: __wps__.site_title,
					width: 400,
					height: 300,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery("#dialog").dialog('close');
						}
					}
				});
			}
		});
	});

	/* Show/hide edit and delete icons */
	jQuery('.__wps__event_list_item').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".link_cursor").show();
		} else {
			jQuery(this).find(".link_cursor").hide();
		}
	});

	/* Create button */
	jQuery("#__wps__create_event_button").live('click', function() {
		jQuery("#__wps__create_event_button").hide();
		jQuery("#__wps__events_list").hide();
		jQuery("#__wps__create_event_form").show();
		jQuery(".datepicker").datepicker({
			showButtonPanel: true
		});
	});

	/* Cancel button */
	jQuery("#symposium_cancel_event_button").live('click', function() {
		jQuery("#__wps__create_event_button").show();
		jQuery("#__wps__events_list").show();
		jQuery("#__wps__create_event_form").hide();
	});

	/* Create (save) button */
	jQuery("#symposium_add_event_button").live('click', function() {
		var name = jQuery("#__wps__create_event_name").val().replace(/(<([^>]+)>)/ig, '');
		if (name == '') {
			jQuery("#__wps__create_event_name").css('border', '1px solid red').effect("highlight", {}, 4000);
		} else {
			// submit to database
			var desc = jQuery("#__wps__create_event_desc").val().replace(/(<([^>]+)>)/ig, '');
			var location = jQuery("#__wps__create_event_location").val().replace(/(<([^>]+)>)/ig, '');
			var start_date = jQuery("#event_start").val();
			var end_date = jQuery("#event_end").val();
			var start_hours = jQuery("#event_start_time_hours").val();
			var start_minutes = jQuery("#event_start_time_minutes").val();
			var end_hours = jQuery("#event_end_time_hours").val();
			var end_minutes = jQuery("#event_end_time_minutes").val();

			var d1 = Date.parse(start_date);
			var d2 = Date.parse(end_date);
			if (d2 < d1) {

				jQuery("#event_start").css('border', '1px solid red').effect("highlight", {}, 4000);
				jQuery("#event_end").css('border', '1px solid red').effect("highlight", {}, 4000);

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/events_functions.php",
					type: "POST",
					data: ({
						action: "addEvent",
						'name': name,
						'desc': desc,
						'location': location,
						'start_date': start_date,
						'start_hours': start_hours,
						'start_minutes': start_minutes,
						'end_date': end_date,
						'end_hours': end_hours,
						'end_minutes': end_minutes
					}),
					dataType: "html",
					async: true,
					success: function(str) {
						if (str == 'OK') {
							jQuery(".__wps__pleasewait").inmiddle().show();
							var q = symposium_q(__wps__.profile_url);
							var reload_page = __wps__.profile_url + q + "uid=" + __wps__.current_user_page + "&embed=on&view=wps_events";
							window.location.href = reload_page;
						} else {
							jQuery("#dialog").html(str).dialog({
								title: __wps__.site_title + ' debug info',
								width: 800,
								height: 500,
								modal: true
							});
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});
			}
		}
	});

	/* Delete event */
	jQuery(".symposium_delete_event").live('click', function() {

		var answer = confirm(areyousure);
		if (answer) {
			var event_id = jQuery(this).attr("id");
			jQuery(this).parent().parent().slideUp("slow");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/events_functions.php",
				type: "POST",
				data: ({
					action: "deleteEvent",
					'eid': event_id
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					if (str != 'OK') {
						jQuery("#dialog").html(str).dialog({
							title: __wps__.site_title + ' debug info',
							width: 800,
							height: 500,
							modal: true
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
		}
	});

	// Edit event
	jQuery(".__wps__edit_event").live('click', function() {

		// Get translated strings
		var events_max_places = __wps__.events_max_places;
		var events_enable_places = __wps__.events_enable_places;
		var events_show_max = __wps__.events_show_max;
		var events_confirmation = __wps__.events_confirmation;
		var events_tickets_per_booking = __wps__.events_tickets_per_booking;
		var events_tab_1 = __wps__.events_tab_1;
		var events_tab_2 = __wps__.events_tab_2;
		var events_tab_3 = __wps__.events_tab_3;
		var events_tab_4 = __wps__.events_tab_4;
		var events_send_email = __wps__.events_send_email;
		var events_pay_link = __wps__.events_pay_link;
		var events_cost = __wps__.events_cost;
		var events_replacements = __wps__.events_replacements;
		var events_labels = __wps__.events_labels.split('|');

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");

		var event_id = jQuery(this).attr("id");
		var event_owner = 'x';

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "editEvent",
				eid: event_id
			}),
			dataType: "html",
			async: false,
			success: function(str) {

				var rows = jQuery.parseJSON(str);
				var html = '<div id="__wps__edit_event_eid" style="display:none;">' + event_id + '</div>';

				jQuery.each(rows, function(i, row) {

					html += '<div id="tabs_wrapper" style="width:100%; height:425px">';

					html += '<div id="tabs" style="border:0; overflow:auto;">';
					html += '<ul>';
					html += '<li>';
					html += '<a id="tab-1" href="#tabs-1">' + events_tab_1 + '</a>';
					html += '</li>';
					html += '<li>';
					html += '<a id="tab-2" href="#tabs-2">' + events_tab_2 + '</a>';
					html += '</li>';
					if (__wps__.events_user_places || __wps__.current_user_level == 5) {
						html += '<li>';
						html += '<a id="tab-3" href="#tabs-3">' + events_tab_3 + '</a>';
						html += '</li>';
						html += '<li>';
						html += '<a id="tab-4" href="#tabs-4">' + events_tab_4 + '</a>';
						html += '</li>';
					}
					html += '</ul>';
					html += '<div id="tabs-1">';

					html += '<div id="__wps__edit_event">';

					if (__wps__.events_user_places || __wps__.current_user_level == 5) {
						html += "<div style='width:48%; float:right; text-align:left;'>";
						html += "<div style='clear:both'>" + events_enable_places + " <input type='checkbox' style='float:right' id='__wps__edit_event_enable_places'";
						if (row.enable_places) {
							html += ' CHECKED';
						}
						html += "></div>";
						html += "<div style='clear:both;'>" + events_show_max + " <input type='checkbox' style='float:right' id='__wps__edit_event_show_max'";
						if (row.show_max) {
							html += ' CHECKED';
						}
						html += "></div>";
						html += "<div style='clear:both;'>" + events_confirmation + " <input type='checkbox' style='float:right' id='__wps__edit_event_confirmation'";
						if (row.confirmation) {
							html += ' CHECKED';
						}
						html += "></div>";
						html += "<div style='clear:both;'>" + events_send_email + " <input type='checkbox' style='float:right' id='__wps__edit_event_send_email'";
						if (row.send_email) {
							html += ' CHECKED';
						}
						html += "></div>";
						html += "<div style='clear:both;'>" + events_max_places + " <input type='text' id='__wps__edit_event_max_places' style='float:right; width:30px' value='" + row.max_places + "'></div>";
						html += "<div style='clear:both;'>" + events_tickets_per_booking + " <input type='text' id='__wps__edit_event_tickets_per_booking' style='float:right; width:30px' value='" + row.tickets_per_booking + "'></div>";
						html += "<div style='clear:both;'>" + events_cost + " <input type='text' id='__wps__edit_event_cost' style='float:right; width:30px' value='" + row.cost + "'></div>";
						html += "<div style='clear:both; '>" + events_pay_link + " <a href='javascript:void()' id='symposium_event_help'><img src='" + __wps__.images_url + "/info.png' /></a><a style='display:none' href='javascript:void()' id='symposium_event_help_close'><img src='" + __wps__.images_url + "/edit.png' /></a><br />";
						html += '<div id="event_tag_help" style="display:none; width:370px; height:102px;">';
						html += '##refnumber##<br />';
						html += '##eventname##<br />';
						html += '##userlogin##<br />';
						html += '##useremail##<br />';
						html += '##quantity##<br />';
						html += '##unitcost##';
						html += '</div>';
						html += "<textarea id='__wps__edit_event_pay_link' style='font-size:11px;font-family:courier new;letter-spacing:-1px;width:370px; height:102px;'>" + row.pay_link + "</textarea>";
						html += "</div>";
						html += '</div>';
					}

					if (__wps__.events_user_places || __wps__.current_user_level == 5) {
						html += "<div style='width:50%'>";
					} else {
						html += "<div style='width:100%'>";
					}

					html += "<input type='text' id='__wps__edit_event_name' class='__wps__edit_event_input' value='" + row.event_name.replace(/'/g, '&apos;') + "'><br />";
					html += "<input type='text' id='__wps__edit_event_location' class='__wps__edit_event_input' value='" + row.event_location.replace(/'/g, '&apos;') + "'><br />";
					html += "<textarea type='text' id='__wps__edit_event_desc' class='__wps__edit_event_textarea'>" + row.event_description.replace(/'/g, '&apos;') + "</textarea><br /><br />";

					html += '</div>';

					html += '<div style="margin-top:-13px; text-align:left;width:700px;">';
					html += '<input type="text" id="__wps__edit_event_start" style="width:100px" class="datepicker" value="';
					if (row.start_date != '01/01/1970' && row.start_date != '11/30/-0001') {
						html += row.start_date
					};
					html += '" /> ';
					html += '<select id="__wps__edit_event_start_time_hours">';
					html += '<option value=99>-</option>';
					for (i = 0; i <= 23; i++) {
						html += '<option value=' + i;
						if (i == row.start_hours) {
							html += ' SELECTED';
						}
						html += '>' + i + '</option>';
					}
					html += '</select> : ';
					html += '<select id="__wps__edit_event_start_time_minutes">';
					html += '<option value=99>-</option>';
					for (i = 0; i <= 3; i++) {
						html += '<option value=' + (i * 15);
						if (i * 15 == row.start_minutes) {
							html += ' SELECTED';
						}
						html += '>' + (i * 15) + '</option>';
					}
					html += '</select>';
					html += ' &rarr; ';
					html += '<input type="text" id="__wps__edit_event_end" style="width:100px" class="datepicker" value="';
					if (row.end_date != '01/01/1970' && row.end_date != '11/30/-0001') {
						html += row.end_date
					};
					html += '" /> ';
					html += '<select id="__wps__edit_event_end_time_hours">';
					html += '<option value=99>-</option>';
					for (i = 0; i <= 23; i++) {
						html += '<option value=' + i;
						if (i == row.end_hours) {
							html += ' SELECTED';
						}
						html += '>' + i + '</option>';
					}
					html += '</select> : ';
					html += '<select id="__wps__edit_event_end_time_minutes">';
					html += '<option value=99>-</option>';
					for (i = 0; i <= 3; i++) {
						html += '<option value=' + (i * 15);
						if (i * 15 == row.end_minutes) {
							html += ' SELECTED';
						}
						html += '>' + (i * 15) + '</option>';
					}
					html += '</select>';

					html += '<div style="clear: both; margin-top:7px;">';
					html += '<input id="symposium_event_update_status" type="checkbox" ';
					if (row.event_live == 'on') {
						html += 'CHECKED';
					}
					html += ' /> Published?';
					html += '<input style="margin-left:25px" id="event_google_map_status" type="checkbox" ';
					if (row.event_google_map == 'on') {
						html += 'CHECKED';
					}
					html += ' /> Google Map?';
					html += '</div>';

					html += '</div>';

					html += '</div>';

					html += '</div>'; // End of tab-1
					html += '<div id="tabs-2">';
					if (__wps__.events_use_wysiwyg) {
						html += '<textarea id="edit_event_more">' + row.more + '</textarea>';
					} else {
						html += '<textarea id="edit_event_more" style="width:98%;height:356px">' + row.more + '</textarea>';
					}
					html += '</div>'; // End of tab-2
					if (__wps__.events_user_places || __wps__.current_user_level == 5) {
						html += '<div id="tabs-3">';

						html += '<div style="float:right; width:200px">';
						html += events_replacements + '<br /><br />';
						html += '##displayname##<br />';
						html += '##email##<br />';
						html += '##refnumber##<br />';
						html += '</div>';
						if (__wps__.events_use_wysiwyg) {
							html += '<textarea id="edit_event_text">' + row.email + '</textarea>';
						} else {
							html += '<textarea id="edit_event_text" style="width:560px;height:356px">' + row.email + '</textarea>';
						}

						html += '</div>'; // End of tab-3
						html += '<div id="tabs-4">';
						var attendees = row.attendees;
						html += '<div style="overflow:auto; width:100%; height:368px">';
						html += '<table style="width:100%" cellspacing="1" cellpadding="2">';
						html += '<tr style="font-weight:strong; text-decoration:underline;">';
						html += '<td>' + events_labels[0] + '</td>';
						html += '<td>' + events_labels[1] + '</td>';
						html += '<td>' + events_labels[2] + '</td>';
						html += '<td>' + events_labels[3] + '</td>';
						html += '<td style="text-align:center">' + events_labels[4] + '</td>';
						html += '<td>' + events_labels[5] + '</td>';
						html += '<td>' + events_labels[6] + '</td>';
						html += '</tr>';
						var total = 0;
						jQuery.each(attendees, function(i, attendee) {
							html += '<tr id="attendee_row_' + attendee.bid + '">';
							html += '<td>' + row.id + '/' + attendee.bid + '</td>';
							var q = symposium_q(__wps__.profile_url);
							var url = __wps__.profile_url + q + "uid=" + attendee.uid;
							html += '<td><a target="_blank" href="' + url + '">' + attendee.display_name + '</a></td>';
							html += '<td>' + attendee.booked + '</td>';
							html += '<td>' + attendee.email_sent + '</td>';
							html += '<td style="text-align:center">' + attendee.tickets + '</td>';
							html += '<td>' + attendee.payment_processed + '</td>';
							html += '<td>';
							html += '<a href="mailto:' + attendee.email + '"><img src="' + __wps__.images_url + '/orange-tick.gif" /></a>&nbsp;&nbsp;';
							html += '<img id="symposium_events_resend_email" data-bid="' + attendee.bid + '" style="cursor:pointer" src="' + __wps__.images_url + '/update.png" />&nbsp;&nbsp;';
							html += '<img id="symposium_events_remove_attendee" data-bid="' + attendee.bid + '" style="cursor:pointer" src="' + __wps__.images_url + '/delete.png" />&nbsp;&nbsp;';
							if (!attendee.payment_processed) {
								html += '<img id="symposium_events_payment_processed" data-bid="' + attendee.bid + '" style="cursor:pointer" src="' + __wps__.images_url + '/cash.png" />&nbsp;&nbsp;';
							}
							if (!attendee.confirmed) {
								html += '<img id="symposium_events_confirm_attendee" data-bid="' + attendee.bid + '" style="cursor:pointer; width:15px;height:15px" src="' + __wps__.images_url + '/tick.png" />&nbsp;&nbsp;';
							}
							html += '</td>';
							html += '</tr>';
							total += parseInt(attendee.tickets);
						});
						html += '<tr><td colspan="4">&nbsp;</td><td style="text-align:center;">Total: ' + total + '</td><td>&nbsp;</td></tr>';
						html += '<tr><td colspan="7" align="right"><br /><strong>' + events_labels[6] + ':</strong><br />';
						var g = '&nbsp;&nbsp;&nbsp;';
						html += '<img src="' + __wps__.images_url + '/orange-tick.gif" /> ' + events_labels[8] + g;
						html += '<img src="' + __wps__.images_url + '/update.png" /> ' + events_labels[9] + g;
						html += '<img src="' + __wps__.images_url + '/delete.png" /> ' + events_labels[10] + g;
						html += '<img src="' + __wps__.images_url + '/cash.png" /> ' + events_labels[11] + g;
						html += '<img style="width:15px;height:15px" src="' + __wps__.images_url + '/tick.png" /> ' + events_labels[7] + g;
						html += '</td></tr>';
						html += '</table>';
						html += '</div>';
						html += '</div>'; // End of tab-4
					}
					html += '</div>';

					html += '<div style="clear: both; float: left; margin-left:5px;">';
					html += '<input type="submit" class="symposium_event_update_button __wps__button" value="' + symposium_update + '" />';
					html += '<div style="float: right;" id="symposium_edit_wait"></div>';
					html += '</div>';

					event_owner = row.event_owner;

				});

				jQuery("#dialog").dialog({
					title: __wps__.site_title + ' Event ID:' + event_id,
					width: 850,
					height: 550,
					modal: true,
					buttons: {},
					close: function(ev, ui) {
						jQuery(".__wps__pleasewait").inmiddle().show();
						var q = symposium_q(__wps__.profile_url);
						var reload = __wps__.profile_url + q + "uid=" + event_owner + "&embed=on&view=wps_events";
						window.location.href = reload;
					}
				});
				jQuery('#dialog').html(html);
				if (__wps__.events_use_wysiwyg) {
					tiny_mce_init('edit_event_text');
					tiny_mce_init('edit_event_more');
				}
				jQuery(".datepicker").datepicker({
					showButtonPanel: true
				});
				jQuery("#tabs").tabs();
				jQuery("#tabs a").click(function() {
					var tab = jQuery(this).attr('id');
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});

	// Make as paid
	jQuery("#symposium_events_payment_processed").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var bid = jQuery(this).data("bid");
		jQuery(this).hide();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "payment_recd",
				'bid': bid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				if (str != 'OK') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title,
						width: 400,
						height: 300,
						modal: true,
						buttons: {
							"OK": function() {
								jQuery("#dialog").dialog('close');
							}
						}
					});
				}
			}
		});
	});

	// Re-send confirmation email
	jQuery("#symposium_events_resend_email").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var bid = jQuery(this).data("bid");
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "resendEmail",
				'bid': bid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				if (str != 'OK') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title,
						width: 400,
						height: 300,
						modal: true,
						buttons: {
							"OK": function() {
								jQuery("#dialog").dialog('close');
							}
						}
					});
				}
			}
		});
	});

	// Remove attendee
	jQuery("#symposium_events_remove_attendee").live('click', function() {
		if (confirm(areyousure)) {
			jQuery(".__wps__pleasewait").inmiddle().show();
			var bid = jQuery(this).data("bid");
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/events_functions.php",
				type: "POST",
				data: ({
					action: "removeAttendee",
					'bid': bid
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					jQuery(".__wps__pleasewait").hide();
					if (str != 'OK') {
						jQuery("#dialog").html(str).dialog({
							title: __wps__.site_title,
							width: 400,
							height: 300,
							modal: true,
							buttons: {
								"OK": function() {
									jQuery("#dialog").dialog('close');
								}
							}
						});
					}
					jQuery("#attendee_row_" + bid).hide('slow');

				}
			});
		}
	});

	// Confirm attendee
	jQuery("#symposium_events_confirm_attendee").live('click', function() {
		jQuery(".__wps__pleasewait").inmiddle().show();
		var bid = jQuery(this).data("bid");
		jQuery(this).hide();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "confirmAttendee",
				'bid': bid
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				if (str != 'OK') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title,
						width: 400,
						height: 300,
						modal: true,
						buttons: {
							"OK": function() {
								jQuery("#dialog").dialog('close');
							}
						}
					});
				}

			}
		});
	});

	// Help for Payment HTML
	jQuery("#symposium_event_help").live('click', function() {
		jQuery('#__wps__edit_event_pay_link').hide();
		jQuery('#symposium_event_help').hide();
		jQuery('#symposium_event_help_close').show();
		jQuery('#event_tag_help').show();
	});
	jQuery("#symposium_event_help_close").live('click', function() {
		jQuery('#__wps__edit_event_pay_link').show();
		jQuery('#symposium_event_help').show();
		jQuery('#symposium_event_help_close').hide();
		jQuery('#event_tag_help').hide();
	});


	// Update event
	jQuery(".symposium_event_update_button").live('click', function() {

		jQuery(".__wps__pleasewait").inmiddle().show();

		if (__wps__.events_user_places || __wps__.current_user_level == 5) {
			if (__wps__.events_use_wysiwyg) {
				var more = tinyMCE.get('edit_event_more').getContent();
				var email = tinyMCE.get('edit_event_text').getContent();
			} else {
				var more = jQuery('#edit_event_more').val();
				var email = jQuery('#edit_event_text').val();
			}
			var max_places = jQuery('#__wps__edit_event_max_places').val();
			var cost = jQuery('#__wps__edit_event_cost').val();
			var pay_link = jQuery('#__wps__edit_event_pay_link').val();
			var tickets_per_booking = jQuery('#__wps__edit_event_tickets_per_booking').val();
			var enable_places = '';
			if (jQuery('#__wps__edit_event_enable_places:checked').val() == 'on') {
				enable_places = 'on';
			}
			var show_max = '';
			if (jQuery('#__wps__edit_event_show_max:checked').val() == 'on') {
				show_max = 'on';
			}
			var confirmation = '';
			if (jQuery('#__wps__edit_event_confirmation:checked').val() == 'on') {
				confirmation = 'on';
			}
			var send_email = '';
			if (jQuery('#__wps__edit_event_send_email:checked').val() == 'on') {
				send_email = 'on';
			}
		} else {
			var max_places = 0;
			var cost = 0;
			var tickets_per_booking = 0;
			var more = '';
			var pay_link = '';
			var enable_places = '';
			var show_max = '';
			var confirmation = '';
			var send_email = '';
			var email = '';
		}


		var eid = jQuery('#__wps__edit_event_eid').html();
		var name = jQuery('#__wps__edit_event_name').val();
		var location = jQuery('#__wps__edit_event_location').val();
		var google_map = jQuery('#__wps__edit_event_location').val();
		var desc = jQuery('#__wps__edit_event_desc').val();
		var start = jQuery('#__wps__edit_event_start').val();
		var start_hours = jQuery('#__wps__edit_event_start_time_hours').find(':selected').val();
		var start_minutes = jQuery('#__wps__edit_event_start_time_minutes').find(':selected').val();
		var end = jQuery('#__wps__edit_event_end').val();
		var end_hours = jQuery('#__wps__edit_event_end_time_hours').find(':selected').val();
		var end_minutes = jQuery('#__wps__edit_event_end_time_minutes').find(':selected').val();
		var event_live = '';
		if (jQuery('#symposium_event_update_status:checked').val() == 'on') {
			event_live = 'on';
		}

		var google_map = '';
		if (jQuery('#event_google_map_status:checked').val() == 'on') {
			google_map = 'on';
		}


		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/events_functions.php",
			type: "POST",
			data: ({
				action: "updateEvent",
				'eid': eid,
				'name': name,
				'location': location,
				'google_map': google_map,
				'desc': desc,
				'start_date': start,
				'start_hours': start_hours,
				'start_minutes': start_minutes,
				'end_date': end,
				'end_hours': end_hours,
				'end_minutes': end_minutes,
				'event_live': event_live,
				'enable_places': enable_places,
				'max_places': max_places,
				'show_max': show_max,
				'confirmation': confirmation,
				'tickets_per_booking': tickets_per_booking,
				'send_email': send_email,
				'email': email,
				'pay_link': pay_link,
				'cost': cost,
				'more': more
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (__wps__.debug) {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
				jQuery(".__wps__pleasewait").hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	});

/*
	   +------------------------------------------------------------------------------------------+
	   |                                        GALLERY                                           |
	   +------------------------------------------------------------------------------------------+
	*/

	// autocomplete
	if (jQuery("input#gallery_member").length) {
		jQuery("input#gallery_member").autocomplete({
			source: __wps__.plugin_url + "ajax/gallery_functions.php",
			minLength: 3,
			focus: function(event, ui) {
				jQuery("input#gallery_member").val(ui.item.name);
				return false;
			},
			select: function(event, ui) {
				jQuery(".__wps__pleasewait").inmiddle().show().delay(3000).fadeOut("slow");
				var q = symposium_q(__wps__.profile_url);
				window.location.href = __wps__.profile_url + q + 'uid=' + ui.item.owner + '&embed=on&album_id=' + ui.item.id;
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			var group = "<a>";
			group += "<div style='height:40px; overflow:hidden'>";
			group += "<div style=\'float:left; background-color:#fff; margin-right: 8px; width:40px; height:40px; \'>";
			group += item.avatar;
			group += "</div>";
			group += "<div>" + item.display_name + "</div>";
			group += "<div style='font-size:80%'>" + item.name + "</div>";
			group += "<br style='clear:both' />";
			group += "</div>";
			group += "</a>";
			return jQuery("<li></li>").data("item.autocomplete", item).append(group).appendTo(ul);
		};
	}


	// Act on "album_id" parameter, load album straight away (remember to set embed=on on hyperlink)
	if (__wps__.album_id > 0 && __wps__.embed == 'on') {

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: 'menu_gallery',
				album_id: __wps__.album_id,
				uid1: __wps__.current_user_page
			}),
			dataType: "html",
			success: function(str) {
				jQuery('#profile_body').html(str);
				
				var user_login = jQuery("#symposium_user_login").html();
				var user_email = jQuery("#symposium_user_email").html();
						
				// File upload
				__wps__init_file_upload();

			}
		});


	}

	jQuery('#gallery_go_button').live('click', function() {
		jQuery("#symposium_gallery_start").html('0');
		symposium_do_gallery_search();
	});
	jQuery('#gallery_member').live('keypress', function(e) {
		if (e.keyCode == 13) {
			jQuery("#symposium_gallery_start").html('0');
			symposium_do_gallery_search();
		}
	});

	// Search
	jQuery('#showmore_gallery').live('click', function() {
		jQuery(this).html("<br /><img src='" + __wps__.images_url + "/busy.gif' />");
		symposium_do_gallery_search();
	});

	function symposium_do_gallery_search() {

		var page_length = jQuery('#symposium_gallery_page_length').html();
		var start = jQuery("#symposium_gallery_start").html();

		if (start == 0) {
			jQuery('#symposium_gallery_albums').html("<br /><img src='" + __wps__.images_url + "/busy.gif' />");
		}

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: "getGallery",
				start: start,
				term: jQuery('#gallery_member').val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				var details = str.split("[split]");
				str = details[1];
				var new_start = parseFloat(start) + parseFloat(details[0]);
				jQuery("#symposium_gallery_start").html(new_start);
				if (start == 0) {
					jQuery('#symposium_gallery_albums').html(str);
				} else {
					jQuery('#symposium_gallery_albums').html(jQuery('#symposium_gallery_albums').html() + str);
					jQuery('#showmore_gallery').remove();
				}

			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	}

	// Function to display gallery dialog box, following click on an image hyperlink

	function prepare_colorbox(t) {

		var rel = jQuery(t).attr("rel");
		var div_title = "#wps_gallery_album_name_" + rel.replace('symposium_gallery_photos_', '');
		var album_title = '';
		if (typeof jQuery(t).attr("title") != 'undefined') {
			album_title = jQuery(t).attr("title");
			if (album_title == '' && jQuery(div_title).html() != '') {
				album_title = jQuery(div_title).html();
			}
		}

		// Get list and number of images in this album
		var max_images = -1;
		var list = new Array();
		var ids = new Array();
		var names = new Array();
		var owner = 0;
		jQuery("a[rel='" + rel + "']").each(function(index) {
			if (jQuery.inArray(jQuery(this).attr("data-iid"), ids) == -1) {
				max_images++;
				if (typeof jQuery(this).attr("data-name") != 'undefined') {
					list.push(jQuery(this).attr("href"));
					ids.push(jQuery(this).attr("data-iid"));
					names.push(jQuery(this).attr("data-name"));
				}

				// Record owner
				if (typeof jQuery(this).attr("data-owner") != 'undefined') owner = jQuery(this).attr("data-owner");
			}
		})

		var current_image = jQuery(t).attr("rev");

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />");
		if (ids[0] == 0 || album_title == '') {
			jQuery("#dialog").dialog({
				zIndex: 10000,
				title: '',
				width: 650,
				height: 600,
				modal: true,
				buttons: {},
				open: function(event, ui) {
					jQuery('body').css('overflow-x', 'hidden');
					jQuery('.ui-widget-overlay').css('width', '100%');
				},
				close: function(event, ui) {
					jQuery('body').css('overflow', 'auto');
				}
			});
		} else {
			jQuery("#dialog").dialog({
				zIndex: 10000,
				title: album_title,
				width: 1000,
				height: 600,
				modal: true,
				buttons: {},
				open: function(event, ui) {
					jQuery('body').css('overflow-x', 'hidden');
					jQuery('.ui-widget-overlay').css('width', '100%');
				},
				close: function(event, ui) {
					jQuery('body').css('overflow', 'auto');
				}
			});
		}

		// Build HTML			
		var html = '';
		html += '<div id="__wps__photo_gallery"';
		if (max_images == 0) html += ' style="height:440px"';		
		html += '>';

		// Info
		html += '<input id="symposium_current_image" style="display:none" value="' + current_image + '" />';
		html += '<input id="symposium_max_images" style="display:none" value="' + max_images + '" />';
		html += '<input id="__wps__album_title" style="display:none" value="' + album_title + '" />';

		if (ids[0] != 0) {
			html += '<div id="__wps__photo_comments">';

			// Management
			if ((owner == __wps__.current_user_id || __wps__.current_user_level == 5) && album_title != '') {
				html += '<div id="__wps__photo_management" style="margin-bottom:10px">';
				var gallery_labels = __wps__.gallery_labels.split('|');

				html += '<div id="__wps__rename_photo" style="margin-bottom:5px"><input type="text" id="__wps__rename" value="" /><input type="submit" id="__wps__rename_button" class="__wps__button" value="' + gallery_labels[0] + '" /></div>';
				html += '<div id="__wps__rename_photo_confirm" style="margin-bottom:11px;display:none;">' + gallery_labels[1] + '</div>';
				html += '<div id="__wps__photo_order" style="clear:both">' + gallery_labels[2] + ' <a id="__wps__photo_order_save" style="text-decoration:underline" href="javascript:void(0);">' + gallery_labels[3] + '</a>.' + '</div>';
				html += '<div class="__wps__photo_delete" style="cursor:pointer; text-decoration:underline;">' + gallery_labels[4] + '</div>';
				html += '<div class="__wps__photo_select_cover_button" style="cursor:pointer; text-decoration:underline;">' + gallery_labels[5] + '</div>';
				html += '</div>';
			}

			// Actions
			if (__wps__.current_user_id > 0 && album_title != '') {
				html += '<div id="__wps__photo_actions">';
				html += '<a href="" target="_blank" id="show_original">' + show_original + '</a>';
				html += '</div>';
			}

			// Preload images
			for (var i = 0; i < list.length; i++) {
				var url = list[i];
				html += '<img id="cache_' + i + '" style="display:none;width:20px;height:20px" src="' + url + '" />';
			}

			// Comments
			if (__wps__.current_user_id > 0 && album_title != '') {
				html += '<div id="__wps__add_a_comment_label">'+add_a_comment+'</div>';
				html += '<textarea id="__wps__photo_add_comment" class="elastic"></textarea>';
				html += '<textarea id="__wps__photo_add_comment_old" style="display:none"></textarea>';
					
				html += '<input id="__wps__photo_submit_comment" type="button" class="__wps__button" value="' + btn_add + '" />';
				html += '<input id="__wps__photo_submit_comment_cancel" type="button" class="__wps__button" style="display:none" value="' + jQuery('#__wps__cancel').html() + '" />';
			}
			if (album_title != '') {
				html += '<div id="__wps__photo_comments_container"></div>';
			}

			html += '</div>';
		}

		html += '<div id="__wps__photo_container">';

		// Photo frame
		html += '<div id="__wps__photo_frame">';
		html += '</div>';

		// Thumbnails
		html += '<div id="__wps__photo_thumbnails"';
		if (max_images == 0) {
			html += ' style="display:none;"';
			jQuery("#dialog").height('450px');
		}
		html += '>';
		for (var i = 0; i < list.length; i++) {
			var url = list[i].replace('show_', 'thumb_');
			var name = names[i];
			html += '<img id="thumb_' + i + '" title="' + name + '" rel="' + ids[i] + '" class="__wps__photo_thumbnail __wps__photo_thumbnail_border_off " src="' + url + '" />';
		}
		html += '</div>';
		
		html += '</div>';

		html += '</div>';

		jQuery("#dialog").html(html);

		// Sortable thumbnails
		if (owner == __wps__.current_user_id && album_title != '') {
			jQuery('#__wps__photo_thumbnails').sortable();
			jQuery('#__wps__photo_order_save').live('click', function() {
				var order = '';
				jQuery('#__wps__photo_thumbnails').children(jQuery('.__wps__photo_thumbnails')).each(function(i) {
					order += jQuery(this).attr('rel') + ',';
				});

				jQuery("#__wps__photo_order").html("<img src='" + __wps__.images_url + "/busy.gif' />");
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/gallery_functions.php",
					type: "POST",
					data: ({
						action: 'symposium_reorder_photos',
						album_id: rel,
						order: order
					}),
					async: false,
					dataType: "html",
					success: function(str) {
						if (str != 'OK') {
							jQuery("#__wps__photo_order").html(str).effect("highlight", {}, 3000);
						}
					}
				});
			});
		}

		if (__wps__.use_elastic == 'on' && jQuery(".elastic").length) {
			jQuery('.elastic').elastic();
		}

		return false;

	}

	jQuery("#__wps__photo_submit_comment").live('click', function() {
		var comment = jQuery('#__wps__photo_add_comment').val();
		jQuery('#__wps__photo_add_comment').val('');
		jQuery('#__wps__photo_add_comment').css('height', '40px');

		if (comment != '') {

		  var current = parseInt(jQuery("#symposium_current_image").val());
			var id = jQuery("#thumb_" + current).attr("rel");
			jQuery('#__wps__photo_comments_container').html("<img src='" + __wps__.images_url + "/busy.gif' />");

			if (this.value != jQuery('#__wps__update').html()) {
				// Add
			  jQuery.ajax({
				  url: __wps__.plugin_url + "ajax/gallery_functions.php",
				  type: "POST",
				  data: ({
			  		action: 'symposium_add_photo_comment',
			  		photo_id: id,
				  	comment: comment
				  }),
				  async: false,
				  dataType: "html",
				  success: function(str) {
					  symposium_show_photo_comments(jQuery("#thumb_" + current));
				  }
			  });

		  } else {
			// Update
		    jQuery('#__wps__photo_submit_comment_cancel').hide();
        	jQuery("#__wps__photo_submit_comment").val(btn_add);
		    jQuery('#__wps__photo_add_comment').val('');
				
				jQuery.ajax({
				  url: __wps__.plugin_url + "ajax/gallery_functions.php",
				  type: "POST",
				  data: ({
			  		action: 'symposium_update_photo_comment',
			  		photo_id: id,
				  	comment: comment,
					  old_comment: jQuery('#__wps__photo_add_comment_old').val()
				  }),
				  async: false,
				  dataType: "html",
				  success: function(str) {
						jQuery("#__wps__add_a_comment_label").show();
					  symposium_show_photo_comments(jQuery("#thumb_" + current));
				  }
			  });
			}
		}

	})

	jQuery(".wps_gallery_album").live('click', function() {
		prepare_colorbox(this);
		var current = parseInt(jQuery(this).attr("rev")) - 1;
		var t = jQuery("#thumb_" + current);
		display_gallery_item(t);
		return false;
	})

	jQuery(".__wps__photo_thumbnail").live('click', function() {
		var current = jQuery(this).attr("id").replace('thumb_', '');
		jQuery("#symposium_current_image").val(current);
		display_gallery_item(jQuery("#thumb_" + current));
	})

	jQuery(".__wps__photo").live('click', function() {
		var current = parseInt(jQuery("#symposium_current_image").val());
		var max = parseInt(jQuery("#symposium_max_images").val());
		if (current < max) {
			current++;
		} else {
			current = 0;
		}
		jQuery("#symposium_current_image").val(current);
		if (jQuery("#thumb_" + current).length) {
			display_gallery_item(jQuery("#thumb_" + current));
		}
	})

	// Set up left/right arrows
	jQuery("body").keydown(function(e) {

		if (!jQuery('#__wps__photo_add_comment').is(':focus')) {

			if ((e.keyCode == 39 || e.keyCode == 13) && jQuery("#__wps__photo_gallery").length) {
				var current = parseInt(jQuery("#symposium_current_image").val());
				var max = parseInt(jQuery("#symposium_max_images").val());
				if (current < max) {
					current++;
				} else {
					current = 0;
				}
				jQuery("#symposium_current_image").val(current);
				if (jQuery("#thumb_" + current).length) {
					display_gallery_item(jQuery("#thumb_" + current));
				}
			}
			if (e.keyCode == 37 && jQuery("#__wps__photo_gallery").length) {
				var current = parseInt(jQuery("#symposium_current_image").val());
				var max = parseInt(jQuery("#symposium_max_images").val());
				if (current > 0) {
					current--;
				} else {
					current = max;
				}
				jQuery("#symposium_current_image").val(current);
				if (jQuery("#thumb_" + current).length) {
					display_gallery_item(jQuery("#thumb_" + current));
				}
			}

		}
	});

	function display_gallery_item(t) {

		// Show waiting...
		var middle_of_photo_frame_height = (jQuery("#__wps__photo_frame").height() / 2);
		jQuery("#__wps__photo_frame").html("<img style='margin-top:" + middle_of_photo_frame_height + "px' src='" + __wps__.images_url + "/busy.gif' />");

		// Load the image
		var url = jQuery(t).attr("src").replace('thumb_', 'show_');
		var img = new Image();
		img.src = url;
		var w = img.width;
		var h = img.height;

		// Increase wait in steps until image is loaded
		if (w > 0) {
			symposium_show_image(t, url, w, h);
		} else {
			setTimeout(function() {
				var w = img.width;
				var h = img.height;
				if (w > 0) {
					symposium_show_image(t, url, w, h);
				} else {
					setTimeout(function() {
						var w = img.width;
						var h = img.height;
						if (w > 0) {
							symposium_show_image(t, url, w, h);
						} else {
							setTimeout(function() {
								var w = img.width;
								var h = img.height;
								symposium_show_image(t, url, w, h);
							}, 3000);
						}
					}, 1500);
				}
			}, 1000);
		}

	}

	function symposium_show_image(t, url, w, h) {

		var photo_frame_width = jQuery("#__wps__photo_frame").width();
		var photo_frame_height = jQuery("#__wps__photo_frame").height();

		var photo_width = photo_frame_width;
		var max_photo_height = photo_frame_height;
		var width_factor = w / photo_frame_width;

		if (w > photo_frame_width || h > photo_frame_height) {
			w = w / width_factor;
			h = h / width_factor;
			if (h > max_photo_height) {
				height_factor = h / max_photo_height;
				w = w / height_factor;
				h = h / height_factor;
			}
			var photo_margin_top = 0;
		}

		if (h < photo_frame_height) {
			photo_margin_top = (photo_frame_height - h) / 2;
		}
		var show_image = '<img class="__wps__photo" width=0 height=0 style="margin-top:' + photo_margin_top + 'px; width:' + w + 'px; height:' + h + 'px;" src="' + url + '" />';

		jQuery("#symposium_current_image").val(jQuery(t).attr("id").replace('thumb_', ''));
		jQuery("#__wps__photo_frame").html(show_image);

		jQuery(".__wps__photo_thumbnail").addClass("__wps__photo_thumbnail_border_off").removeClass("__wps__photo_thumbnail_border_on");
		jQuery(t).toggleClass("__wps__photo_thumbnail_border_off").toggleClass("__wps__photo_thumbnail_border_on");

		// Photo title
		var album_title = jQuery('#__wps__album_title').val();
		if (jQuery(t).attr('title')) {
			var photo_title = jQuery(t).attr('title').replace(/(<([^>]+)>)/ig, '');
			if (album_title != '' && photo_title != '') album_title += ' - ';
			jQuery("#dialog").dialog('option', 'title', album_title + photo_title);
		} else {
			jQuery("#dialog").dialog('option', 'title', album_title);
		}
		// Set current name (for editing)
		var current_image = jQuery("#symposium_current_image").val();
		var current_name = jQuery('#thumb_' + current_image).attr('title');
		current_name = current_name.replace(/(<([^>]+)>)/ig, '');
		jQuery('#__wps__rename').val(current_name);

		// Actions
		jQuery("#show_original").attr("href", url.replace('show_', ''));

		symposium_show_photo_comments(t);
		jQuery('.__wps__photo_delete').show();
		jQuery('.__wps__photo_select_cover_button').show();
		jQuery('#__wps__rename_photo').show();
		jQuery('#__wps__rename_photo_confirm').hide();


	}

	function symposium_show_photo_comments(t) {
		// Comments
		jQuery('#__wps__photo_comments_container').html("<img src='" + __wps__.images_url + "/busy.gif' />");
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: 'symposium_get_photo_comments',
				photo_id: t.attr("rel")
			}),
			dataType: "html",
			success: function(str) {
				if (str != '[]') {
					var html = '';
					var rows = jQuery.parseJSON(str);
					jQuery.each(rows, function(i, row) {
						html += '<div class="__wps__photo_comment hover_row">';
						html += '<div class="__wps__photo_comment_avatar">';
						html += row.avatar;
						html += '</div>';
						html += '<div>';
						if (row.author_id == __wps__.current_user_id || __wps__.current_user_level == 5) {
							html += '<div class="hover_child" style="width:50px;float:right;display:none;">';
							var edit_icon = "<a title='Edit' id='" + row.ID + "' href='javascript:void(0);' class='__wps__photo_comment_edit_icon'><img src='" + __wps__.images_url + "/edit.png' style='width:16px;height:16px;' /></a>";
							var del_icon = "<a title='Delete' id='" + row.ID + "' href='javascript:void(0);' class='__wps__photo_comment_del_icon'><img src='" + __wps__.images_url + "/delete.png' style='width:16px;height:16px;margin-left:6px;' /></a>";
							html += edit_icon;
							html += del_icon;
							html += '</div>';
						}
						html += row.display_name_link + ', ' + row.timestamp + '<br />';
						html += row.comment.replace(/\n/g, "<br />");
						html += '</div>';
						html += '</div>';

					})

				} else {
					html = '';
				}
				jQuery('#__wps__photo_comments_container').html(html);

			}
		});
	}

	// Show icons on hover
	jQuery('.hover_row').live('mouseenter mouseleave', function(event) {
		if (event.type == 'mouseenter') {
			jQuery(this).find(".hover_child").show()
		} else {
			jQuery(this).find(".hover_child").hide();
		}
	});

	// Edit photo comment
  jQuery(".__wps__photo_comment_edit_icon").live('click', function() {
		jQuery("#__wps__add_a_comment_label").hide();
		jQuery("#__wps__photo_submit_comment").val(jQuery('#__wps__update').html());
		jQuery('#__wps__photo_submit_comment_cancel').show();
		var id = this.id;
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: '__wps__get_gallery_comment',
				cid: id
			}),
			dataType: "html",
			async: false,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				str = trim(str);
				jQuery('#__wps__photo_add_comment').val(str);
				jQuery('#__wps__photo_add_comment_old').val(str);
				jQuery('#__wps__photo_add_comment').effect("highlight", {}, 3000);;
			}
		});
	});
	
	// Cancel edit photo comment
  jQuery("#__wps__photo_submit_comment_cancel").live('click', function() {
		jQuery(this).hide();
    jQuery("#__wps__photo_submit_comment").val(btn_add);
		jQuery('#__wps__photo_add_comment').val('');
		jQuery("#__wps__add_a_comment_label").show();
	});
	
	// Delete photo comment
  jQuery(".__wps__photo_comment_del_icon").live('click', function() {
		var id = this.id;
		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: '__wps__delete_gallery_comment',
				cid: id
			}),
			dataType: "html",
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				var current = parseInt(jQuery("#symposium_current_image").val());
				symposium_show_photo_comments(jQuery("#thumb_" + current));
			}
		});

	});

	// Stretch div on activity stream
	jQuery("#wps_gallery_comment_more").live('click', function() {
		jQuery(this).hide();
		jQuery(this).parent().parent().find("#wps_comment_plus").css("overflow", "visible");
	});

	// Manage album (select cover)
	jQuery(".__wps__photo_select_cover_button").live('click', function() {

		var current = jQuery("#symposium_current_image").val();
		var t = this;
		// loop through to current image
		var cnt = 0;
		jQuery('#__wps__photo_thumbnails').children(jQuery('.__wps__photo_thumbnails')).each(function(i) {
			if (cnt == current) {

				var tmp = jQuery(t).html();
				jQuery(t).html("<img src='" + __wps__.images_url + "/busy.gif' />");
				var item_id = jQuery(this).attr("rel");
				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/gallery_functions.php",
					type: "POST",
					data: ({
						action: 'menu_gallery_select_cover',
						item_id: item_id
					}),
					dataType: "html",
					async: false,
					success: function(str) {
						jQuery(t).slideUp('fast').html(tmp);
					}
				});

			}
			cnt++;
		});

	});

	// Change sharing status
	jQuery("#gallery_share").live('change', function() {

		jQuery('#__wps__album_sharing_save').show();

		__wps__.album_id = jQuery(this).attr("title");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: 'menu_gallery_change_share',
				album_id: __wps__.album_id,
				new_share: jQuery("#gallery_share").val()
			}),
			dataType: "html",
			success: function(str) {
				jQuery('#__wps__album_sharing_save').hide();
				if (str != 'OK') {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
			}
		});

	});

	// Delete all
	jQuery(".__wps__photo_delete_all").live('click', function() {

		if (confirm("Are you sure?")) {

			__wps__.album_id = jQuery(this).attr("rel");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/gallery_functions.php",
				type: "POST",
				data: ({
					action: 'menu_gallery_manage_delete_all',
					album_id: __wps__.album_id
				}),
				dataType: "html",
				success: function(str) {
					jQuery('.__wps__photo_row').slideUp("slow");
					var q = symposium_q(__wps__.profile_url);
					var reload_page = __wps__.profile_url + q + "uid=" + __wps__.current_user_page + "&view=gallery";
					window.location.href = reload_page;
				}
			});

		}

	});

	// Delete
	jQuery(".__wps__photo_delete").live('click', function() {

		var current = jQuery("#symposium_current_image").val();
		var t = this;

		var tmp = jQuery(t).html();
		jQuery(t).html("<img src='" + __wps__.images_url + "/busy.gif' />");

		// loop through to current image
		var cnt = 0;
		jQuery('#__wps__photo_thumbnails').children(jQuery('.__wps__photo_thumbnails')).each(function(i) {
			if (cnt == current) {
				if (confirm("Are you sure?")) {
					// Delete this thumbnail and image
					var item_id = jQuery(this).attr("rel");
					jQuery(this).remove();
					jQuery("#__wps__photo_frame").children().fadeOut('slow');

					// Delete image on the page (and surrounding DIVs)
					jQuery('a[data-iid="' + item_id + '"]').parent().parent().parent().remove();
					jQuery('a[data-iid="' + item_id + '"]').parent().parent().remove();
					jQuery('a[data-iid="' + item_id + '"]').parent().remove();
					jQuery('a[data-iid="' + item_id + '"]').remove();

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/gallery_functions.php",
						type: "POST",
						data: ({
							action: 'menu_gallery_manage_delete',
							item_id: item_id
						}),
						dataType: "html",
						async: false,
						success: function(str) {
							jQuery(t).slideUp('fast').html(tmp);
						}
					});
				}
			}
			cnt++;
		});

	});

	// Rename photo
	jQuery("#__wps__rename_button").live('click', function() {

		var current = jQuery("#symposium_current_image").val();
		var t = this;

		jQuery("#__wps__rename_photo").hide();
		jQuery("#__wps__rename_photo_confirm").show().effect("highlight", {}, 3000);

		// loop through to current image
		var cnt = 0;
		jQuery('#__wps__photo_thumbnails').children(jQuery('.__wps__photo_thumbnails')).each(function(i) {
			if (cnt == current) {

				// Delete this thumbnail and image
				var item_id = jQuery(this).attr("rel");
				var new_name = jQuery("#__wps__rename").val();
				jQuery('#thumb_' + current).attr('title', new_name);
				// Rename image on the page
				jQuery('a[data-iid="' + item_id + '"]').attr('data-name', new_name);
				// Update dialog title
				jQuery("#dialog").dialog('option', 'title', jQuery('#__wps__album_title').val() + ' - ' + new_name);

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/gallery_functions.php",
					type: "POST",
					data: ({
						action: 'menu_gallery_manage_rename',
						item_id: item_id,
						new_name: new_name
					}),
					dataType: "html",
					async: false,
					success: function(str) {}
				});

			}
			cnt++;
		});

	});

	// Click on album cover
	jQuery(".__wps__album_cover_action").live('click', function() {

		__wps__.album_id = jQuery(this).attr("title");
		symposium_show_album();

	});

	// Back to top
	jQuery("#__wps__gallery_top").live('click', function() {
		__wps__.album_id = 0;
		symposium_show_album();
	});

	// Up a level
	jQuery("#symposium_gallery_up").live('click', function() {
		__wps__.album_id = jQuery(this).attr("title");
		symposium_show_album();
	});

	// Function to show album (for above)	


	function symposium_show_album() {

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: 'menu_gallery',
				album_id: __wps__.album_id,
				uid1: __wps__.current_user_page
			}),
			dataType: "html",
			success: function(str) {
				jQuery('#profile_body').html(str);
				
				var user_login = jQuery("#symposium_user_login").html();
				var user_email = jQuery("#symposium_user_email").html();
				
				// File upload
				__wps__init_file_upload();

			}
		});
	}

	// Toggle new album form
	jQuery(".symposium_new_album_button").live('click', function() {
		jQuery('.symposium_new_album_button').hide();
		jQuery('.__wps__photo_delete_all').hide();
		jQuery("#__wps__create_gallery").show();
		jQuery("#__wps__album_covers").hide();
		jQuery("#__wps__album_content").hide();

		if (__wps__.album_id > 0) {
			jQuery(".__wps__create_sub_gallery").show();
		} else {
			jQuery(".__wps__create_sub_gallery").hide();
		}
	});
	jQuery("#symposium_cancel_album").live('click', function() {
		jQuery('.symposium_new_album_button').show();
		jQuery('.__wps__photo_delete_all').show();
		jQuery('.album_name').show();
		jQuery("#__wps__album_covers").show();
		jQuery("#__wps__album_content").show();
		jQuery("#__wps__create_gallery").hide();
	});

	// Create new album
	jQuery("#symposium_new_album").live('click', function() {

		jQuery(".__wps__pleasewait").inmiddle().show();
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/gallery_functions.php",
			type: "POST",
			data: ({
				action: 'create_album',
				name: jQuery("#symposium_new_album_title").val(),
				sub_album: jQuery("#__wps__create_sub_gallery_select").is(":checked"),
				parent: jQuery("#__wps__create_sub_gallery_select").attr("title")
			}),
			dataType: "html",
			success: function(str) {
				var q = symposium_q(__wps__.profile_url);
				var reload_page = __wps__.profile_url + q + "uid=" + __wps__.current_user_page + "&embed=on&album_id=" + str;
				window.location.href = reload_page;
			}
		});
	});



/*
	   +------------------------------------------------------------------------------------------+
	   |                                        LOUNGE                                            |
	   +------------------------------------------------------------------------------------------+
	*/

	// Start regular checks for lounge contents
	lounge_polling();

	// Add comment via button
	jQuery("#__wps__lounge_add_comment_button").live('click', function() {
		add_comment_to_lounge();
	});

	// Add comment via Return on keyboard
	jQuery('#__wps__lounge_add_comment').live('keypress', function(e) {
		if (e.keyCode == 13) {
			add_comment_to_lounge();
		}
	});

	// Delete comment via trash icon
	jQuery(".__wps__lounge_del_icon").live('click', function() {
		var comment_id = jQuery(this).attr("id");
		jQuery("#comment_" + comment_id).slideUp("slow");
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/lounge_functions.php",
			type: "POST",
			data: ({
				action: 'delete_comment',
				comment_id: comment_id
			}),
			dataType: "html",
			success: function(str) {
				if (str != "OK") {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});




	function add_comment_to_lounge() {

		var new_comment = jQuery('#__wps__lounge_add_comment').val();

		if (jQuery('#__wps__lounge_add_comment').val() != 'Add a comment..') {
			var items = '';
			items += '<div id="__wps__lounge_comment">';
			items += '<div class="__wps__lounge_new_comment __wps__lounge_new_comment_you">' + new_comment + '</div>';
			items += '<div class="__wps__lounge_new_status">';
			items += '<img style="float: left" src="' + __wps__.images_url + '/online.gif">';
			items += '</div>';
			items += '<div class="__wps__lounge_new_author">';
			items += 'You';
			items += '</div>';
			jQuery(items).prependTo('#__wps__lounge_div');
			jQuery('#__wps__lounge_add_comment').val('');
		}

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/lounge_functions.php",
			type: "POST",
			data: ({
				action: 'add_comment',
				comment: new_comment
			}),
			dataType: "html",
			success: function(str) {},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	}

	function lounge_polling() {

		if (jQuery("#__wps__lounge_div").length) {

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/lounge_functions.php",
				type: "POST",
				data: ({
					action: 'get_comments',
					inactive: __wps__.inactive,
					offline: __wps__.offline
				}),
				dataType: "html",
				success: function(str) {

					// AJAX function return JSON array of comments to create HTML
					var items = "";
					var rows = jQuery.parseJSON(str);

					jQuery.each(rows, function(i, row) {
						items += '<div id="comment_' + row.lid + '" class="__wps__lounge_comment">';
						if (__wps__.current_user_level == 5) {
							var del_icon = "<a title='Delete' id='" + row.lid + "' href='javascript:void(0);' class='__wps__lounge_del_icon'><img src='" + __wps__.images_url + "/delete.png' style='width:16px;height:16px' /></a>";
							items += del_icon;
						}

						items += '<div class="__wps__lounge_new_comment';
						if (row.author_id == __wps__.current_user_id) {
							items += ' __wps__lounge_new_comment_you';
						}
						items += '">' + row.comment + '</div>';
						items += '<div class="__wps__lounge_new_status">';
						items += '<img style="float: left" src="' + __wps__.images_url + '/' + row.status + '.gif">';
						items += '</div>';
						items += '<div class="__wps__lounge_new_author">';
						items += row.author + ' ' + row.added;
						items += '</div>';
						items += '</div>';
					});
					jQuery('#__wps__lounge_div').html(items);

				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}

			});
		}

		// Repeat check every 5 seconds
		setTimeout(lounge_polling, 5000);
	}


/*
	   +------------------------------------------------------------------------------------------+
	   |                                       RSS FEED                                           |
	   +------------------------------------------------------------------------------------------+
	*/


	jQuery("#__wps__rss_icon").live('click', function() {
		var str = "Use the following to receive an RSS feed of this member's activity:";
		str += '<br /><input type="text" style="width:650px;" value="' + __wps__.plugin_url + 'activity.php?uid=' + __wps__.current_user_page + '" />';
		str += '<br /><a href=' + __wps__.plugin_url + 'activity.php?uid=' + __wps__.current_user_page + ' target="_blank">View</a>';
		jQuery("#dialog").html(str);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 700,
			height: 175,
			modal: true,
			buttons: {}
		});

	});


/*
	   +------------------------------------------------------------------------------------------+
	   |                                        WIDGET: VOTE                                      |
	   +------------------------------------------------------------------------------------------+
*/

	if (jQuery(".symposium_answer").length) {
		jQuery(".symposium_answer").click(function() {

			var vote_answer = jQuery(this).attr("title");
			jQuery("#__wps__vote_thankyou").slideDown("fast").effect("highlight", {}, 3000);

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/widget_functions.php",
				type: "POST",
				data: ({
					action: "doVote",
					vote: vote_answer
				}),
				dataType: "html",
				async: true,
				success: function(str) {},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});
			jQuery(".__wps__pleasewait").fadeOut("slow");
		});
	}

	if (jQuery("#__wps__chartcontainer").length) {

		var yes = parseFloat(jQuery('#__wps__chart_yes').html());
		var no = parseFloat(jQuery('#symposium_chart_no').html());

		if (jQuery("#symposium_chart_counts").html() != 'on') {
			if (yes > 0) {
				if (no > 0) {
					yes = Math.floor(yes / (yes + no) * 100);
					no = 100 - yes;
				} else {
					yes = 100;
					no = 0;
				}
			} else {
				yes = 0;
				if (no > 0) {
					no = 100;
				} else {
					no = 0;
				}
			}
		}

		var myData = new Array(['Yes', yes], ['No', no]);
		var bar_type = 'bar';
		if (jQuery("#symposium_chart_type").html() != '') {
			bar_type = jQuery("#symposium_chart_type").html();
		}
		var myChart = new JSChart('__wps__chartcontainer', bar_type, jQuery('#symposium_chart_key').html());
		myChart.setDataArray(myData);
		var myColors = new Array('#09f', '#06a')
		if (bar_type == 'bar') {
			myChart.colorizeBars(myColors);
		} else {
			myChart.colorizePie(myColors);
		}
		myChart.setSize(200, 200);
		myChart.setTitleFontSize(14);
		myChart.setTitle("");
		myChart.setAxisNameX("");
		myChart.setAxisNameY("");
		myChart.setAxisPaddingTop(15);
		myChart.setAxisPaddingBottom(15);
		myChart.setAxisPaddingLeft(0);
		if (jQuery("#symposium_chart_counts").html() != 'on') {
			if (bar_type == 'bar') {
				myChart.setBarValuesSuffix('%');
			} else {
				myChart.setPieValuesSuffix('%');
			}
		}
		myChart.draw();

	}


/*
	   +------------------------------------------------------------------------------------------+
	   |                                          GROUP                                           |
	   +------------------------------------------------------------------------------------------+
	*/

	// Menu choices
	jQuery(".__wps__group_menu").live('click', function() {
		
		// Check if using horizontal menu (tabs)
		if (jQuery(".__wps__top_menu").length) {
			jQuery('.__wps__top_menu').removeClass('__wps__dropdown_tab_on').addClass('__wps__dropdown_tab_off');
			jQuery(this).closest('.__wps__top_menu').removeClass('__wps__dropdown_tab_off').addClass('__wps__dropdown_tab_on');
			
			jQuery(this).parent().hide();
		}

		jQuery('.__wps__group_menu').removeClass('__wps__profile_current');
		jQuery(this).addClass('__wps__profile_current');

		
		var menu_id = jQuery(this).attr("id");

		if (menu_id == 'group_menu_all') {
			window.location.href = __wps__.groups_url;
		} else {

			jQuery('#group_body').html("<img src='" + __wps__.images_url + "/busy.gif' />");

			if (menu_id != 'group_menu_forum') {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/group_functions.php",
					type: "POST",
					data: ({
						action: menu_id,
						post: '',
						limit_from: 0,
						uid1: __wps__.current_group
					}),
					dataType: "html",
					success: function(str) {
						jQuery('#group_body').html(str);

						var user_login = jQuery("#symposium_user_login").html();
						var user_email = jQuery("#symposium_user_email").html();

						// Set up auto-expanding textboxes
						if (jQuery(".elastic").length) {
							jQuery('.elastic').elastic();
						}

						// Adjust width of textboxes (for background image)
						if (jQuery('#__wps__group_comment').length) {
							var w = jQuery('#__wps__group_comment').css('width').replace(/px/g, "");
							jQuery('#__wps__group_comment').css('width', (w-30)+'px');
						}
						if (jQuery('.__wps__group_reply').length) {
							var w = jQuery('.__wps__group_reply').css('width').replace(/px/g, "");
							jQuery('.__wps__group_reply').css('width', (w-30)+'px');
						}
						
						// File upload (for group avatar)
						__wps__init_file_upload();

					}
				});

			} else {

				jQuery.ajax({
					url: __wps__.plugin_url + "ajax/forum_functions.php",
					type: "POST",
					data: ({
						action: 'getForum',
						limit_from: 0,
						cat_id: __wps__.cat_id,
						topic_id: __wps__.show_tid,
						group_id: __wps__.current_group
					}),
					dataType: "html",
					async: true,
					success: function(str) {

						str = trim(str);

						if (strpos(str, "[|]", 0)) {
							var details = str.split("[|]");
							jQuery(document).attr('title', details[0]);
							str = details[1];
						}

						jQuery("#group_body").html(str);

						var user_login = jQuery("#symposium_user_login").html();
						var user_email = jQuery("#symposium_user_email").html();

						// File upload
						__wps__init_file_upload();

						// Set up auto-expanding textboxes
						if (jQuery(".elastic").length) {
							jQuery('.elastic').elastic();
						}

						// Init TinyMCE
						tiny_mce_init('new_topic_text');

					},
					error: function(xhr, ajaxOptions, thrownError) {
						if (show_js_errors) {
							alert(xhr.status);
							alert(xhr.statusText);
							alert(thrownError);
						}
					}
				});

			}
		}

	});
	
	// Edit group about page
	jQuery("#__wps__about_group_edit").live('click', function() {

		jQuery("#dialog").html("<img src='" + __wps__.images_url + "/busy.gif' />").dialog({
			title: __wps__.site_title,
			width: 850,
			height: 550,
			modal: true,
			buttons: {
				"Update": function() {
					jQuery(".__wps__notice").inmiddle().show();
					var message = tinyMCE.get('__wps__about_group_edit_textarea').getContent();
					jQuery("#__wps__group_about_page").html(message);
					jQuery(this).dialog("close");

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/group_functions.php",
						type: "POST",
						data: ({
							action: "update_welcome_message",
							'message': message,
							'gid': __wps__.current_group
						}),
						dataType: "html",
						async: true,
						success: function(str) {
							if (str != 'OK') jQuery("#dialog").html(str).dialog({ title: __wps__.site_title+' debug info', width: 800, height: 500, modal: true });
							jQuery(".__wps__notice").fadeOut("fast");
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
				},
				"Cancel": function() {
					jQuery("#dialog").dialog('close');
				}
			}
		});	
		
		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: 'group_menu_about_edit',
				group_id: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#dialog").html(str);
				tiny_mce_init('__wps__about_group_edit_textarea');
			}
		});
		
	});

	function showPreview(coords) {
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;

		jQuery('#x').val(coords.x);
		jQuery('#y').val(coords.y);
		jQuery('#x2').val(coords.x2);
		jQuery('#y2').val(coords.y2);
		jQuery('#w').val(coords.w);
		jQuery('#h').val(coords.h);

		jQuery('#profile_preview').css({
			width: Math.round(rx * jQuery('#profile_jcrop_target').width()) + 'px',
			height: Math.round(ry * jQuery('#profile_jcrop_target').height()) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	};
	
	// Act on "view" parameter on first page load (forum or activity post)
	if ( (__wps__.current_group > 0) && ((__wps__.cat_id != '') && (__wps__.cat_id)) || ((__wps__.post != '') && (__wps__.current_group > 0)) ) {

		if (__wps__.post != '') {

			// Activity parameter passed, show activity
			
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/group_functions.php",
				type: "POST",
				data: ({
					action: 'group_menu_wall',
					post: __wps__.post,
					limit_from: 0,
					uid1: __wps__.current_group
				}),
				dataType: "html",
				success: function(str) {
					jQuery('#group_body').html(str);
	
					// Set up auto-expanding textboxes
					if (jQuery(".elastic").length) {
						jQuery('.elastic').elastic();
					}

					// Adjust width of textboxes (for background image)
					if (jQuery('#__wps__group_comment').length) {
						var w = jQuery('#__wps__group_comment').css('width').replace(/px/g, "");
						jQuery('#__wps__group_comment').css('width', (w-30)+'px');
					}
					if (jQuery('.__wps__group_reply').length) {
						var w = jQuery('.__wps__group_reply').css('width').replace(/px/g, "");
						jQuery('.__wps__group_reply').css('width', (w-30)+'px');
					}	
	
				}
			});
					
		} else {
			
		  // Forum parameter passed, show forum
		  
		  var sub = "getForum";
		  if (__wps__.show_tid > 0) {
			  var sub = "getTopic";
		  }

		  jQuery.ajax({
			  url: __wps__.plugin_url + "ajax/forum_functions.php",
			  type: "POST",
			  data: ({
				  action: sub,
				  limit_from: 0,
				  cat_id: __wps__.cat_id,
				  topic_id: __wps__.show_tid,
				  group_id: __wps__.current_group
			  }),
			  dataType: "html",
			  async: true,
			  success: function(str) {
				  if (str != 'DONTSHOW') {
					  str = trim(str);
  
					  if (strpos(str, "[|]", 0)) {
						  var details = str.split("[|]");
						  jQuery(document).attr('title', details[0]);
						  str = details[1];
					  }
  
					  jQuery("#group_body").html(str);
  
					  var user_login = jQuery("#symposium_user_login").html();
					  var user_email = jQuery("#symposium_user_email").html();

					  // File upload
					  __wps__init_file_upload();
  
					  // Set up auto-expanding textboxes
					  if (jQuery(".elastic").length) {
						  jQuery('.elastic').elastic();
					  }
  
					  // Init TinyMCE
					  if (sub == 'getForum') {
						  tiny_mce_init('new_topic_text');
					  } else {
						  tiny_mce_init('__wps__reply_text');
					  }
 
				  }
  
			  },
			  error: function(xhr, ajaxOptions, thrownError) {
				  if (show_js_errors) {
					  alert(xhr.status);
					  alert(xhr.statusText);
					  alert(thrownError);
				  }
			  }
  
		  });
		
		}
		
	} else {
		
		// Load defaut page
		if (jQuery("#force_group_page").length) {

			if (jQuery("#force_group_page").html() != '') {

				var menu_id = 'group_menu_' + jQuery('#force_group_page').html();

				if (menu_id == 'group_menu_about') {
					
					// Default to about page
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/group_functions.php",
						type: "POST",
						data: ({
							action: 'group_menu_about',
							uid1: __wps__.current_group
						}),
						dataType: "html",
						success: function(str) {
							jQuery('#group_body').html(str);	
				
							// Init TinyMCE
							tiny_mce_init('__wps__about_group_edit_div');

						}
					});

										
				}
				
				if (menu_id == 'group_menu_activity') {
					
					// Default to activity

					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/group_functions.php",
						type: "POST",
						data: ({
							action: 'group_menu_wall',
							post: '',
							limit_from: 0,
							uid1: __wps__.current_group
						}),
						dataType: "html",
						success: function(str) {
							jQuery('#group_body').html(str);
	
							// Set up auto-expanding textboxes
							if (jQuery(".elastic").length) {
								jQuery('.elastic').elastic();
							}

							// Adjust width of textboxes (for background image)
							if (jQuery('#__wps__group_comment').length) {
								var w = jQuery('#__wps__group_comment').css('width').replace(/px/g, "");
								jQuery('#__wps__group_comment').css('width', (w-30)+'px');
							}
							if (jQuery('.__wps__group_reply').length) {
								var w = jQuery('.__wps__group_reply').css('width').replace(/px/g, "");
								jQuery('.__wps__group_reply').css('width', (w-30)+'px');
							}	
						}
					});
				
				}
				
				if (menu_id == 'group_menu_forum') {

					// Default to forum
					
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/forum_functions.php",
						type: "POST",
						data: ({
							action: 'getForum',
							limit_from: 0,
							cat_id: __wps__.cat_id,
							topic_id: __wps__.show_tid,
							group_id: __wps__.current_group
						}),
						dataType: "html",
						async: true,
						success: function(str) {
	
							str = trim(str);
	
							if (strpos(str, "[|]", 0)) {
								var details = str.split("[|]");
								jQuery(document).attr('title', details[0]);
								str = details[1];
							}
	
							jQuery("#group_body").html(str);
	
							var user_login = jQuery("#symposium_user_login").html();
							var user_email = jQuery("#symposium_user_email").html();
	
						    // File upload
						    __wps__init_file_upload();
	
							// Set up auto-expanding textboxes
							if (jQuery(".elastic").length) {
								jQuery('.elastic').elastic();
							}
	
							// Init TinyMCE
							tiny_mce_init('new_topic_text');
	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
							
					
				}

			}
		}
	}

	// Delete group member
	jQuery(".delete_group_member").live('click', function() {

		if (confirm("Are you sure?")) {

			var id = jQuery(this).attr("title");
			jQuery(this).parent().parent().slideUp("slow");

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/group_functions.php",
				type: "POST",
				data: ({
					action: "member_delete",
					group_id: __wps__.current_group,
					id: id
				}),
				dataType: "html",
				success: function(str) {}
			});

		}
	});


	// Clicked on show more...
	jQuery("#showmore_group_wall").live('click', function() {

		var limit_from = jQuery(this).attr("title");
		jQuery('#showmore_group_wall').html("<img src='" + __wps__.images_url + "/busy.gif' />");

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: menu_id,
				post: '',
				limit_from: limit_from,
				uid1: __wps__.current_group,
				uid2: __wps__.current_user_id
			}),
			dataType: "html",
			success: function(str) {
				jQuery('#showmore_group_wall').remove();
				jQuery(str).appendTo('#group_body').hide().slideDown("slow");

			}
		});

	});

	// new post to group wall
	jQuery("#symposium_group_add_comment").live('click', function() {
		jQuery(this).parent().hide();
		var t = this;
		setTimeout(function() {
			jQuery(t).parent().slideDown('fast');
		}, 3000);		
		symposium_add_group_comment();
	});
	jQuery('#__wps__group_comment').live('keypress', function(e) {
		if (e.keyCode == 13 && (!jQuery("#symposium_group_add_comment").length)) {
			jQuery(this).parent().hide();
			var t = this;
			setTimeout(function() {
				jQuery(t).parent().slideDown('fast');
			}, 3000);
			symposium_add_group_comment();
		}
	});

	function symposium_add_group_comment() {

		var comment_text = jQuery("#__wps__group_comment").val();
		jQuery("#__wps__group_comment").val('');

		var comment = "<div class='add_wall_post_div' style='"
		if (__wps__.row_border_size != '') {
			comment = comment + " border-top:" + __wps__.row_border_size + "px " + __wps__.row_border_style + " " + __wps__.text_color_2 + ";";
		}
		comment = comment + "'>";
		comment = comment + "<div class='add_wall_post'>";
		comment = comment + "<div class='add_wall_post_text'>";
		var q = symposium_q(__wps__.profile_url);
		comment = comment + '<a href="' + __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '">';
		comment = comment + __wps__.current_user_display_name + '</a><br />';

		comment_text = comment_text.replace(/\n/g, '<br>');

		comment = comment + comment_text;
		comment = comment + "</div>";
		comment = comment + "</div>";
		comment = comment + "<div class='add_wall_post_avatar'>";
		comment = comment + "<img src='" + jQuery('#__wps__current_user_avatar img:first').attr('src') + "' style='width:64px; height:64px' />";
		comment = comment + "</div>";

		jQuery("#__wps__comment").val('');
		//jQuery(comment).prependTo('#__wps__wall');
		jQuery(comment).insertAfter('#symposium_add_status').hide().slideDown('fast');
		

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "group_addStatus",
				subject_uid: __wps__.current_group,
				author_uid: __wps__.current_user_id,
				parent: 0,
				text: comment_text
			}),
			dataType: "html",
			async: true
		});
	}

	// new reply
	jQuery(".reply_field-button").live('click', function() {
		jQuery(this).parent().hide();
		var t = this;
		setTimeout(function() {
			jQuery(t).parent().slideDown('fast');
		}, 3000);
		symposium_add_group_reply(this);
	});
	jQuery('.__wps__group_reply').live('keypress', function(e) {
		if (e.keyCode == 13 && (!jQuery(".reply_field-button").length)) {
			jQuery(this).parent().hide();
			var t = this;
			setTimeout(function() {
				jQuery(t).parent().slideDown('fast');
			}, 3000);
			symposium_add_group_reply(this);
		}
	});

	function symposium_add_group_reply(t) {

		var comment_id = jQuery(t).attr("title");
		var author_id = jQuery('#symposium_author_' + comment_id).val();
		var comment_text = jQuery("#__wps__reply_" + comment_id).val();

		jQuery("#__wps__reply_" + comment_id).val('');

		var comment = "<div class='reply_div'>";
		comment = comment + "<div class='__wps__wall_reply_div'";
		if (__wps__.bg_color_2 != '') {
			comment = comment + " style='background-color:" + __wps__.bg_color_2 + "'";
		}
		comment = comment + ">";
		comment = comment + "<div class='wall_reply'>";
		var q = symposium_q(__wps__.profile_url);
		comment = comment + '<a href="' + __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '">';
		comment = comment + __wps__.current_user_display_name + '</a><br />';
		comment_text = comment_text.replace(/\n/g, '<br>');
		comment = comment + comment_text;
		comment = comment + "</div>";
		comment = comment + "</div>";
		comment = comment + "<div class='wall_reply_avatar'>";
		comment = comment + "<img src='" + jQuery('#__wps__current_user_avatar img:first').attr('src') + "' style='width:40px; height:40px' />";
		comment = comment + "</div>";
		comment = comment + "</div>";

		jQuery(comment).appendTo('#__wps__comment_' + comment_id);
		jQuery("#__wps__reply_" + comment_id).val('');

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "group_addComment",
				uid: __wps__.current_group,
				parent: comment_id,
				text: comment_text
			}),
			dataType: "html",
			async: true
		});
	}


	// update group settings
	jQuery("#updateGroupSettingsButton").live('click', function() {

		var complete = true;
		var groupname = jQuery("#groupname").val();
		var groupdesc = jQuery("#groupdescription").val();
		if (groupname == '') {
			jQuery("#groupname").effect("highlight", {}, 4000);
			complete = false;
		}
		if (groupdesc == '') {
			jQuery("#groupdescription").effect("highlight", {}, 4000);
			complete = false;
		}
		if (!complete) {
			jQuery("#dialog").html('Please complete group name and description').dialog({ title: __wps__.site_title, width: 300, height: 200, modal: true, buttons: { "OK": function() { jQuery(this).dialog("close"); } } });
		} else {
			
			jQuery(".__wps__notice").inmiddle().show();
	
			var is_private = '';
			if (jQuery("#private").is(":checked")) {
				is_private = 'on';
			}
	
			var content_private = '';
			if (jQuery("#content_private").is(":checked")) {
				var content_private = 'on';
			}
	
			var group_forum = '';
			if (jQuery("#group_forum").is(":checked")) {
				group_forum = 'on';
			}
	
			var default_page = jQuery("#default_page").val();
	
			var allow_new_topics = '';
			if (jQuery("#allow_new_topics").is(":checked")) {
				allow_new_topics = 'on';
			}
	
			var new_member_emails = '';
			if (jQuery("#new_member_emails").is(":checked")) {
				new_member_emails = 'on';
			}
	
			var add_alerts = '';
			if (jQuery("#add_alerts").is(":checked")) {
				add_alerts = 'on';
			}
	
			var group_admin = jQuery("#transfer_admin").val();
	
			jQuery("#group_name").html(jQuery("#groupname").val());
			jQuery("#group_description").html(jQuery("#groupdescription").val());
	
			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/group_functions.php",
				type: "POST",
				data: ({
					action: "updateGroupSettings",
					gid: __wps__.current_group,
					groupname: jQuery("#groupname").val(),
					groupdescription: jQuery("#groupdescription").val(),
					is_private: is_private,
					content_private: content_private,
					group_forum: group_forum,
					default_page: default_page,
					allow_new_topics: allow_new_topics,
					group_admin: group_admin,
					new_member_emails: new_member_emails,
					add_alerts: add_alerts,
					x: jQuery("#x").val(),
					y: jQuery("#y").val(),
					w: jQuery("#w").val(),
					h: jQuery("#h").val()
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					location.reload();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
	
			});
			
		}

	});

	// Join group
	jQuery("#groups_join_button").live('click', function() {

		jQuery(".__wps__pleasewait").inmiddle().show();

		jQuery("#groups_join_button").hide();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "joinGroup",
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: false,
			success: function(str) {
				if (__wps__.debug) {
					jQuery("#dialog").html(str).dialog({
						title: __wps__.site_title + ' debug info',
						width: 800,
						height: 500,
						modal: true
					});
				}
				jQuery("#groups_join_button_done").effect("highlight", {}, 3000);
				location.reload();
			}
		});

	});

	// Delete group
	jQuery("#groups_delete_button").live('click', function() {

		var answer = confirm("This cannot be un-done - are you really sure?");

		if (answer) {

			jQuery("#groups_delete_button").hide();
			jQuery("#groups_delete_button_done").effect("highlight", {}, 3000);

			jQuery(".__wps__pleasewait").inmiddle().show();

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/group_functions.php",
				type: "POST",
				data: ({
					action: "deleteGroup",
					gid: __wps__.current_group
				}),
				dataType: "html",
				async: false,
				success: function(str) {
					window.location.href = __wps__.groups_url;
				}
			});

		}

	});

	// Delete group request
	jQuery("#groups_delete_button_request").live('click', function() {

		var group_id = jQuery(this).attr('title');
		var str = '<p>Why do you want to delete this group?<br />Note: this cannot be reversed!';
		str += '<br /><em>Ref: ' + group_id + '</em></p>';
		str += '<textarea id="request_text" style="width:100%; height:180px"></textarea>';
		jQuery("#dialog").html(str);
		jQuery("#dialog").dialog({
			title: __wps__.site_title,
			width: 600,
			height: 400,
			modal: true,
			buttons: {
				"Delete Group": function() {
					jQuery.ajax({
						url: __wps__.plugin_url + "ajax/group_functions.php",
						type: "POST",
						data: ({
							action: "requestDelete",
							request_text: jQuery('#request_text').val(),
							group_id: group_id
						}),
						dataType: "html",
						async: true,
						success: function(str) {
							jQuery("#dialog").html('Your request for this group to be deleted has been sent to the site administrator.');
							jQuery("#dialog").dialog({
								title: __wps__.site_title,
								width: 650,
								height: 150,
								modal: true,
								buttons: {}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							if (show_js_errors) {
								alert(xhr.status);
								alert(xhr.statusText);
								alert(thrownError);
							}
						}
					});
					jQuery(this).dialog("close");
				},
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});

	});

	// Leave group
	jQuery("#groups_leave_button").live('click', function() {

		if (confirm(areyousure)) {

			jQuery(".__wps__pleasewait").inmiddle().show();
			jQuery("#groups_leave_button").hide();

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/group_functions.php",
				type: "POST",
				data: ({
					action: "leaveGroup",
					gid: __wps__.current_group
				}),
				dataType: "html",
				async: false,
				success: function(str) {
					jQuery("#groups_leave_button_done").effect("highlight", {}, 3000);
					location.reload();
				}

			});
		}

	});

	// Subscribe/unsubscribe
	jQuery("#group_notify").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		if (jQuery("#group_notify").is(":checked")) {
			var group_notify = 'on';
		} else {
			var group_notify = '';
		}

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "group_subscribe",
				notify: group_notify,
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__notice").fadeOut("slow");
			}
		});
	});

	// reject a group request
	jQuery("#rejectgrouprequest").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "rejectGroup",
				uid: jQuery(this).attr("title"),
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery("#request_" + str).slideUp("slow");
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	});

	// accept a group request
	jQuery("#acceptgrouprequest").live('click', function() {
		jQuery(".__wps__notice").inmiddle().show();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "acceptGroup",
				uid: jQuery(this).attr("title"),
				gid: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				if (str != 'NOT LOGGED IN') {
					jQuery("#request_" + str).slideUp("slow");
				}
				jQuery(".__wps__notice").fadeOut("slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});

	});

/*
	   +------------------------------------------------------------------------------------------+
	   |                                          GROUPS                                          |
	   +------------------------------------------------------------------------------------------+
	*/

	if (jQuery("input#__wps__group").length) {

		jQuery("input#__wps__group").autocomplete({
			source: __wps__.plugin_url + "ajax/groups_functions.php",
			minLength: 3,
			focus: function(event, ui) {
				jQuery("input#group").val(ui.item.name);
				jQuery("input#group_id").val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				jQuery("input#group").val(ui.item.name);
				jQuery("input#group_id").val(ui.item.value);
				var q = symposium_q(__wps__.group_url);
				window.location.href = __wps__.group_url + q + 'gid=' + ui.item.value;
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			var group = "<a>";
			group += "<div style='height:40px; overflow:hidden'>";
			group += "<div style=\'float:left; background-color:#fff; margin-right: 8px; width:40px; height:40px; \'>";
			group += item.avatar;
			group += "</div>";
			group += "<div>" + item.name + "</div>";
			group += "<br style='clear:both' />";
			group += "</div>";
			group += "</a>";
			return jQuery("<li></li>").data("item.autocomplete", item).append(group).appendTo(ul);
		};
		

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/groups_functions.php",
			type: "POST",
			data: ({
				action: "getGroups",
				page: 1,
				me: __wps__.current_user_id
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#__wps__groups").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	};

	jQuery("#groups_go_button").click(function() {

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/groups_functions.php",
			type: "POST",
			data: ({
				action: "getGroups",
				page: 1,
				me: __wps__.current_user_id,
				term: jQuery("#__wps__group").val()
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery("#__wps__groups").html(str);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
		
	});


	jQuery("#show_create_group_button").click(function() {
		jQuery("#show_create_group_button").hide();
		jQuery("#groups_results").hide();
		jQuery("#create_group_form").fadeIn("slow");
	});

	jQuery("#cancel_create_group_button").click(function() {
		jQuery("#show_create_group_button").show();
		jQuery("#create_group_form").hide();
		jQuery("#groups_results").fadeIn("slow");
	});

	jQuery("#create_group_button").click(function() {

		var name_of_group = jQuery('#name_of_group').val();
		var description_of_group = jQuery('#description_of_group').val();

		if (name_of_group != '') {

			jQuery.ajax({
				url: __wps__.plugin_url + "ajax/groups_functions.php",
				type: "POST",
				data: ({
					action: "createGroup",
					me: __wps__.current_user_id,
					name_of_group: name_of_group,
					description_of_group: description_of_group
				}),
				dataType: "html",
				async: true,
				success: function(str) {
					var q = symposium_q(__wps__.group_url);
					window.location.href = __wps__.group_url + q + 'gid=' + trim(str);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					if (show_js_errors) {
						alert(xhr.status);
						alert(xhr.statusText);
						alert(thrownError);
					}
				}
			});

		}

	});


	jQuery("#symposium_group_invites_button").live('click', function() {

		jQuery(".__wps__pleasewait").inmiddle().show();

		var emails = jQuery('#symposium_group_invites').val();

		jQuery.ajax({
			url: __wps__.plugin_url + "ajax/group_functions.php",
			type: "POST",
			data: ({
				action: "sendInvites",
				emails: emails,
				group_id: __wps__.current_group
			}),
			dataType: "html",
			async: true,
			success: function(str) {
				jQuery(".__wps__pleasewait").hide();
				jQuery('#symposium_group_invites_button').hide();
				jQuery('#symposium_group_invites').hide();
				jQuery('#symposium_group_invites_sent').html(str).show();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if (show_js_errors) {
					alert(xhr.status);
					alert(xhr.statusText);
					alert(thrownError);
				}
			}

		});
	});


});

/*
	   +------------------------------------------------------------------------------------------+
	   |                                     SHARED FUNCTIONS                                     |
	   +------------------------------------------------------------------------------------------+
	*/

function trim(s) {
	var l = 0;
	var r = s.length - 1;
	while (l < s.length && s[l] == ' ') {
		l++;
	}
	while (r > l && s[r] == ' ') {
		r -= 1;
	}
	return s.substring(l, r + 1);
}

// sort out ? or & in WPS link


function symposium_q(u) {

	var q = '?';
	if (u.indexOf('?') > 0) {
		q = '&';
	}
	return q;
}


/*
	   +------------------------------------------------------------------------------------------+
	   |                                     EXTERNAL SCRIPTS                                     |
	   +------------------------------------------------------------------------------------------+
	*/

/**
 *	@name							Elastic
 *	@descripton						Elastic is Jquery plugin that grow and shrink your textareas automaticliy
 *	@version						1.6.4
 *	@requires						Jquery 1.2.6+
 *
 *	@author							Jan Jarfalk
 *	@author-email					jan.jarfalk@unwrongest.com
 *	@author-website					http://www.unwrongest.com
 *
 *	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
 */

(function(jQuery) {

	jQuery.fn.extend({
		elastic: function() {

			//	We will create a div clone of the textarea
			//	by copying these attributes from the textarea to the div.
			var mimics = ['paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft', 'fontSize', 'lineHeight', 'fontFamily', 'width', 'fontWeight'];

			return this.each(function() {

				// Elastic only works on textareas
				if (this.type != 'textarea') {
					return false;
				}

				var $textarea = jQuery(this),
					$twin = jQuery('<div />').css({
						'position': 'absolute',
						'display': 'none',
						'word-wrap': 'break-word'
					}),
					lineHeight = parseInt($textarea.css('line-height'), 10) || parseInt($textarea.css('font-size'), '10'),
					minheight = parseInt($textarea.css('height'), 10) || lineHeight * 3,
					maxheight = parseInt($textarea.css('max-height'), 10) || Number.MAX_VALUE,
					goalheight = 0,
					i = 0;

				// Opera returns max-height of -1 if not set
				if (maxheight < 0) {
					maxheight = Number.MAX_VALUE;
				}

				// Append the twin to the DOM
				// We are going to meassure the height of this, not the textarea.
				$twin.appendTo($textarea.parent());

				// Copy the essential styles (mimics) from the textarea to the twin
				var i = mimics.length;
				while (i--) {
					$twin.css(mimics[i].toString(), $textarea.css(mimics[i].toString()));
				}


				// Sets a given height and overflow state on the textarea


				function setHeightAndOverflow(height, overflow) {
					curratedHeight = Math.floor(parseInt(height, 10));
					if ($textarea.height() != curratedHeight) {
						$textarea.css({
							'height': curratedHeight + 'px',
							'overflow': overflow
						});

					}
				}


				// This function will update the height of the textarea if necessary 


				function update() {

					// Get curated content from the textarea.
					var textareaContent = $textarea.val().replace(/&/g, '&amp;').replace(/  /g, '&nbsp;').replace(/<|>/g, '&gt;').replace(/\n/g, '<br />');

					var twinContent = $twin.html();

					if (textareaContent + '&nbsp;' != twinContent) {

						// Add an extra white space so new rows are added when you are at the end of a row.
						$twin.html(textareaContent + '&nbsp;');

						// Change textarea height if twin plus the height of one line differs more than 3 pixel from textarea height
						if (Math.abs($twin.height() + lineHeight - $textarea.height()) > 3) {

							var goalheight = $twin.height() + lineHeight;
							if (goalheight >= maxheight) {
								setHeightAndOverflow(maxheight, 'auto');
							} else if (goalheight <= minheight) {
								setHeightAndOverflow(minheight, 'hidden');
							} else {
								setHeightAndOverflow(goalheight, 'hidden');
							}

						}

					}

				}

				// Hide scrollbars
				$textarea.css({
					'overflow': 'hidden'
				});

				// Update textarea size on keyup
				$textarea.keyup(function() {
					update();
				});

				// And this line is to catch the browser paste event
				$textarea.live('input paste', function(e) {
					setTimeout(update, 250);
				});

				// Run update once when elastic is initialized
				update();

			});

		}
	});
})(jQuery);

/* FILE UPLOAD */
function __wps__init_file_upload() {

//	if (jQuery('#__wps__fileupload').length) {
		
	    // Initialize the jQuery File Upload widget:
	    jQuery('#__wps__fileupload').fileupload({
	        // Uncomment the following to send cross-domain cookies:
	        //xhrFields: {withCredentials: true},
	        url: __wps__.plugin_url+'/server/php/',
	        autoUpload: true,
	        maxNumberOfFiles: 100,
	        sequentialUploads: true
	    });
	
	
	    // Enable iframe cross-domain access via redirect option:
	    jQuery('#__wps__fileupload').fileupload(
	        'option',
	        'redirect',
	        window.location.href.replace(
	            /\/[^\/]*$/,
	            '/cors/result.html?%s'
	        )
	    );
	
	
	    // Load existing files:
	    jQuery('#__wps__fileupload').fileupload('option', {
	        maxFileSize: 5000000,
	        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
	        process: [
	            {
	                action: 'load',
	                fileTypes: /^image\/(gif|jpeg|png)$/,
	                maxFileSize: 20000000 // 20MB
	            },
	            {
	                action: 'resize',
	                maxWidth: 640,
	                maxHeight: 800
	            },
	            {
	                action: 'save'
	            }
	        ]
	    });

		jQuery('#__wps__fileupload').bind('fileuploadstop', function (e) {
			if (jQuery('#uploader_ver').val() == 'gallery') {
				jQuery(".__wps__pleasewait").inmiddle().show();
				var q = symposium_q(__wps__.profile_url);
				var href = __wps__.profile_url + q + 'uid=' + __wps__.current_user_id + '&view=gallery';
				window.location.href = href;
			}
		});
	
		jQuery('#__wps__fileupload').bind('fileuploaddone', function (e, data) { 
			
			ver = jQuery('#uploader_ver').val();
			
			jQuery('#btn-span-tmp').html(jQuery('#btn-span').html());
			jQuery('#activity_file_upload_file').html(data.files[0].name);
			if (jQuery('#fileupload-info-label').html() != '')
				jQuery('#fileupload-info').html(data.files[0].name+' '+jQuery('#fileupload-info-label').html());
	
			jQuery.ajax({
				url: __wps__.plugin_url + 'server/upload_'+ver+'.php',
				type: "POST",
				data: ({
					action: "after_upload_complete",
					uid: __wps__.current_user_page,
					user_id: __wps__.current_user_id,
					user_login: jQuery("#symposium_user_login").html(),
					user_email: jQuery("#symposium_user_email").html(),
					uploaded_file: __wps__.wps_content_dir+'/members/'+__wps__.current_user_page+'/'+ver+'_upload/'+data.files[0].name,
					uploaded_filename: data.files[0].name,
					uploader_tid: jQuery("#uploader_tid").val(),
					uploader_gid: jQuery("#uploader_gid").val(),
					uploader_aid: jQuery("#uploader_aid").val()
				}),
				dataType: "html",
				async: false,
				success: function(str) {
					if (str.substring(0, 5) == 'Error') {
						alert(str);
					} else {
						// avatar crop
						if (jQuery('#profile_image_to_crop').length) {
							jQuery('#fileupload-info').hide();
							jQuery('#btn-span').parent().hide();
							jQuery('#profile_image_to_crop').html(str);
							jQuery('#profile_jcrop_target').Jcrop({
								onChange: showProfilePreview,
								onSelect: showProfilePreview
							});
						}
						// group avatar crop
						if (jQuery('#group_image_to_crop').length) {
							jQuery('#fileupload-info').hide();
							jQuery('#btn-span').parent().hide();
							jQuery('#group_image_to_crop').html(str);
							jQuery('#profile_jcrop_target').Jcrop({
								onChange: showProfilePreview,
								onSelect: showProfilePreview
							});
						}					
						// forum list
						if (jQuery('#forum_file_list').length) {
							if (str != '') {
								jQuery('#forum_file_list').html(str);
							}
						}					
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status+', '+xhr.statusText);
				}
	
			});
			
		});
		
//	}
	

	function showProfilePreview(coords) {
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;
	
		jQuery('#x').val(coords.x);
		jQuery('#y').val(coords.y);
		jQuery('#x2').val(coords.x2);
		jQuery('#y2').val(coords.y2);
		jQuery('#w').val(coords.w);
		jQuery('#h').val(coords.h);
	
		jQuery('#profile_preview').css({
			width: Math.round(rx * jQuery('#profile_jcrop_target').width()) + 'px',
			height: Math.round(ry * jQuery('#profile_jcrop_target').height()) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	};
	
}



	// CHAT FUNCTIONS

	history.navigationMode = 'compatible';	//for Opera to support unload function
	var heartBeat = __wps__.chat_polling * 1000;	//chat auto refresh time in miliseconds	
	if (heartBeat < 1000) heartBeat = 1000;
	var windowscount = 0;	
	var chatboxcount = 0;
	
	function print_to_chat(window_id,text){
		jQuery('#'+window_id+' .chat_area').append(text);
	}

	function __wps__insertPlayer(playerpath, filename){
		
			var mp3html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
			mp3html += 'width="1" height="1" ';
			mp3html += 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">';
			mp3html += '<param name="movie" value="'+playerpath+'?';
			mp3html += 'showDownload=false&file=' + filename + '&autoStart=true';
			mp3html += '&backColor=ffffff&frontColor=ffffff';
			mp3html += '&repeatPlay=false&songVolume=50" />';
			mp3html += '<param name="wmode" value="transparent" />';
			mp3html += '<embed wmode="transparent" width="1" height="1" ';
			mp3html += 'src="' + playerpath + '?'
			mp3html += 'showDownload=false&file=' + filename + '&autoStart=true';
			mp3html += '&backColor=ffffff&frontColor=ffffff';
			mp3html += '&repeatPlay=false&songVolume=50" ';
			mp3html += 'type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
			mp3html += '</object>';
			return mp3html;
			
	}
			
	function PopupChat(partner_id,partner_username,chatbox_status,__wps__first_load){
		windowscount ++;
		chatboxcount ++;
		var wctr = windowscount;
		var q = symposium_q(__wps__.profile_url);
		var href = __wps__.profile_url + q + 'uid=' + partner_id;
		
		jQuery('body').append('<div class="chatbox cb_default" id="chat_window_'+wctr+'" title="'+partner_id+'">'+
			'<div class="header header_bg_default" title="'+partner_username+'">'+
				'<p style="width:100px;overflow:hidden;white-space: nowrap;height:20px;"><a style="float:left;" href="'+href+'">'+partner_username+'</a></p>'+
				'<a href="#" class="close_chatbox" title="close chat window"><img src="'+__wps__.images_url+'/close.png" alt="Close" /></a>'+
				'<a href="#" class="popup_chatbox" rel="'+partner_id+'" title="'+partner_username+'"><img src="'+__wps__.images_url+'/popup.gif" alt="Popup" /></a>'+
				'<a href="#" class="clear_chatbox" title="clear chat window"><img src="'+__wps__.images_url+'/delete.png" alt="Clear" /></a>'+
				'<a href="#" class="minimize_chatbox" title="minimize chat window">_</a>'+
				'<a href="#" class="maximize_chatbox" title="maximize chat window">&#8254;</a>'+
			'</div>'+
			'<div class="chat_area" title="'+partner_username+'">'+
			'</div>'+
			'<div class="chat_message" title="Type your message here">'+
				'<div class="chat_user_replying"><img src="'+__wps__.images_url+'/chat_replying.png" alt="Typing..." /></div>' +
				'<textarea></textarea>'+
			'</div>'+
		'</div>');
		if (chatbox_status == 2) {
			jQuery('#chat_window_'+wctr).css('height','0px');		
			jQuery('#chat_window_'+wctr).css('height','25px');
			jQuery('#chat_window_'+wctr+',.minimize_chatbox').css('display','none');
			jQuery('#chat_window_'+wctr+',.maximize_chatbox').css('display','inline');
		}
		var nu_w_pos = 0;
		if (chatboxcount == 1) {
			nu_w_pos = 10;
		} else {
			nu_w_pos = ((chatboxcount-1) * 213)+10;
		}	
		nu_w_pos += 202;
		jQuery('#chat_window_'+wctr).css('right',nu_w_pos+'px');
		jQuery('#chat_window_'+wctr).data('chatbox_status',chatbox_status);
		jQuery('#chat_window_'+wctr).data('partner_id',partner_id);
		jQuery('#chat_window_'+wctr).data('partner_username',partner_username);
		print_to_chat('chat_window_'+wctr,'<p class="system">'+__wps__.pleasewait+'...</p>');

		if (!__wps__first_load && __wps__.chat_sound != 'none') { // sound
			jQuery("#player_div").empty();
			jQuery("#player_div").prepend(__wps__insertPlayer(__wps__.plugin_url+'/ajax/chat/flash/player.swf', __wps__.plugin_url+'/ajax/chat/flash/'+__wps__.chat_sound));
		} 
		       
		UpdateChatWindowStatus();
		return false;
	}
	
	function UpdateChatWindowStatus(){
		var chatboxdata = [];
		jQuery('.chatbox').each(function(){
			var this_chatbox = jQuery(this);
			chatboxdata.push({ partner_id:this_chatbox.data('partner_id'),partner_username : this_chatbox.data('partner_username'),chatbox_status:this_chatbox.data('chatbox_status')});
		});	
		jQuery.ajax({
			type: "POST",
			url: __wps__.plugin_url+'ajax/chat/set_status.php',
			async: false,					
			data: ({ chatbox_status: chatboxdata }),
			//data: 'chatbox_status=1',
			success: function(i){	
			}
		});
		return false;
	}	

// TinyMCE ***************************************************************************


function tiny_mce_init(editor_id) {

	if (!__wps__.use_wp_editor) {

		if (__wps__.wps_wysiwyg == 'on' || editor_id == '__wps__about_group_edit_textarea' || editor_id == 'edit_event_text' || editor_id == 'edit_event_more') {

			var css = __wps__.wps_wysiwyg_css;
			if (editor_id == 'edit_topic_text' || editor_id == '__wps__about_group_edit_textarea') {
				// revert to default to tie in with dialog white background
				css = __wps__.plugins + "/wp-symposium/tiny_mce/themes/advanced/skins/wps.css";
			}

			var my_width = __wps__.wps_wysiwyg_width;
			var my_height = __wps__.wps_wysiwyg_height;
			var toolbar1 = __wps__.wps_wysiwyg_1;
			var toolbar2 = __wps__.wps_wysiwyg_2;
			var toolbar3 = __wps__.wps_wysiwyg_3;
			var toolbar4 = __wps__.wps_wysiwyg_4;
			if (editor_id == 'edit_event_text' || editor_id == 'edit_event_more' || editor_id == '__wps__about_group_edit_textarea') {
				my_width = '565px';
				my_height = '355px';
				toolbar1 = 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,emotions|,fontselect,fontsizeselect,|,removeformat,fullscreen';
				if (__wps__.debug) toolbar1 += ',code';
				toolbar2 = 'cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,youtubeIframe,|,forecolor,backcolor';
				toolbar3 = 'tablecontrols';
				toolbar4 = '';
			}
			if (editor_id == 'edit_event_more') {
				my_width = '790px';
			}
			if (editor_id == '__wps__about_group_edit_textarea') {
				my_width = '820px';
				my_height  = '410px';
			}

			if (__wps__.include_context == 'on') {
				include_context = 'contextmenu,';
			} else {
				include_context = '';
			}

			tinyMCE.init({
				// General options
				theme: "advanced",
				plugins: "fullscreen,youtubeIframe,safari,pagebreak,table,emotions,inlinepopups,insertdatetime,preview,media,searchreplace," + include_context + "paste,directionality,visualchars,nonbreaking",
				mode: "exact",
				elements: editor_id,
				relative_urls: false,
				remove_script_host: false,
				width: my_width,
				height: my_height,
				force_br_newlines: true,
				force_p_newlines: false,
				content_css: css,
				skin: __wps__.wps_wysiwyg_skin,
				theme_advanced_buttons1: toolbar1,
				theme_advanced_buttons2: toolbar2,
				theme_advanced_buttons3: toolbar3,
				theme_advanced_buttons4: toolbar4,
				theme_advanced_toolbar_location: "top",
				theme_advanced_toolbar_align: "left",
				theme_advanced_statusbar_location: "none",
				theme_advanced_resizing: true
			});

		}
	}
}

// JW Player ***************************************************************************
if (typeof jwplayer == "undefined") {
	var jwplayer = function(a) {
			if (jwplayer.api) {
				return jwplayer.api.selectPlayer(a)
			}
		};
	var $jw = jwplayer;
	jwplayer.version = "5.7.1896";
	jwplayer.vid = document.createElement("video");
	jwplayer.audio = document.createElement("audio");
	jwplayer.source = document.createElement("source");
	(function(b) {
		b.utils = function() {};
		b.utils.typeOf = function(d) {
			var c = typeof d;
			if (c === "object") {
				if (d) {
					if (d instanceof Array) {
						c = "array"
					}
				} else {
					c = "null"
				}
			}
			return c
		};
		b.utils.extend = function() {
			var c = b.utils.extend["arguments"];
			if (c.length > 1) {
				for (var e = 1; e < c.length; e++) {
					for (var d in c[e]) {
						c[0][d] = c[e][d]
					}
				}
				return c[0]
			}
			return null
		};
		b.utils.clone = function(f) {
			var c;
			var d = b.utils.clone["arguments"];
			if (d.length == 1) {
				switch (b.utils.typeOf(d[0])) {
				case "object":
					c = {};
					for (var e in d[0]) {
						c[e] = b.utils.clone(d[0][e])
					}
					break;
				case "array":
					c = [];
					for (var e in d[0]) {
						c[e] = b.utils.clone(d[0][e])
					}
					break;
				default:
					return d[0];
					break
				}
			}
			return c
		};
		b.utils.extension = function(c) {
			if (!c) {
				return ""
			}
			c = c.substring(c.lastIndexOf("/") + 1, c.length);
			c = c.split("?")[0];
			if (c.lastIndexOf(".") > -1) {
				return c.substr(c.lastIndexOf(".") + 1, c.length).toLowerCase()
			}
			return
		};
		b.utils.html = function(c, d) {
			c.innerHTML = d
		};
		b.utils.wrap = function(c, d) {
			if (c.parentNode) {
				c.parentNode.replaceChild(d, c)
			}
			d.appendChild(c)
		};
		b.utils.ajax = function(g, f, c) {
			var e;
			if (window.XMLHttpRequest) {
				e = new XMLHttpRequest()
			} else {
				e = new ActiveXObject("Microsoft.XMLHTTP")
			}
			e.onreadystatechange = function() {
				if (e.readyState === 4) {
					if (e.status === 200) {
						if (f) {
							f(e)
						}
					} else {
						if (c) {
							c(g)
						}
					}
				}
			};
			try {
				e.open("GET", g, true);
				e.send(null)
			} catch (d) {
				if (c) {
					c(g)
				}
			}
			return e
		};
		b.utils.load = function(d, e, c) {
			d.onreadystatechange = function() {
				if (d.readyState === 4) {
					if (d.status === 200) {
						if (e) {
							e()
						}
					} else {
						if (c) {
							c()
						}
					}
				}
			}
		};
		b.utils.find = function(d, c) {
			return d.getElementsByTagName(c)
		};
		b.utils.append = function(c, d) {
			c.appendChild(d)
		};
		b.utils.isIE = function() {
			return ((!+"\v1") || (typeof window.ActiveXObject != "undefined"))
		};
		b.utils.isLegacyAndroid = function() {
			var c = navigator.userAgent.toLowerCase();
			return (c.match(/android 2.[012]/i) !== null)
		};
		b.utils.isIOS = function(d) {
			if (typeof d == "undefined") {
				d = /iP(hone|ad|od)/i
			}
			var c = navigator.userAgent.toLowerCase();
			return (c.match(d) !== null)
		};
		b.utils.isIPad = function() {
			return b.utils.isIOS(/iPad/i)
		};
		b.utils.isIPod = function() {
			return b.utils.isIOS(/iP(hone|od)/i)
		};
		b.utils.getFirstPlaylistItemFromConfig = function(c) {
			var d = {};
			var e;
			if (c.playlist && c.playlist.length) {
				e = c.playlist[0]
			} else {
				e = c
			}
			d.file = e.file;
			d.levels = e.levels;
			d.streamer = e.streamer;
			d.playlistfile = e.playlistfile;
			d.provider = e.provider;
			if (!d.provider) {
				if (d.file && (d.file.toLowerCase().indexOf("youtube.com") > -1 || d.file.toLowerCase().indexOf("youtu.be") > -1)) {
					d.provider = "youtube"
				}
				if (d.streamer && d.streamer.toLowerCase().indexOf("rtmp://") == 0) {
					d.provider = "rtmp"
				}
				if (e.type) {
					d.provider = e.type.toLowerCase()
				}
			}
			if (d.provider == "audio") {
				d.provider = "sound"
			}
			return d
		};
		b.utils.getOuterHTML = function(c) {
			if (c.outerHTML) {
				return c.outerHTML
			} else {
				try {
					return new XMLSerializer().serializeToString(c)
				} catch (d) {
					return ""
				}
			}
		};
		b.utils.setOuterHTML = function(f, e) {
			if (f.outerHTML) {
				f.outerHTML = e
			} else {
				var g = document.createElement("div");
				g.innerHTML = e;
				var c = document.createRange();
				c.selectNodeContents(g);
				var d = c.extractContents();
				f.parentNode.insertBefore(d, f);
				f.parentNode.removeChild(f)
			}
		};
		b.utils.hasFlash = function() {
			if (typeof navigator.plugins != "undefined" && typeof navigator.plugins["Shockwave Flash"] != "undefined") {
				return true
			}
			if (typeof window.ActiveXObject != "undefined") {
				try {
					new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
					return true
				} catch (c) {}
			}
			return false
		};
		b.utils.getPluginName = function(c) {
			if (c.lastIndexOf("/") >= 0) {
				c = c.substring(c.lastIndexOf("/") + 1, c.length)
			}
			if (c.lastIndexOf("-") >= 0) {
				c = c.substring(0, c.lastIndexOf("-"))
			}
			if (c.lastIndexOf(".swf") >= 0) {
				c = c.substring(0, c.lastIndexOf(".swf"))
			}
			if (c.lastIndexOf(".js") >= 0) {
				c = c.substring(0, c.lastIndexOf(".js"))
			}
			return c
		};
		b.utils.getPluginVersion = function(c) {
			if (c.lastIndexOf("-") >= 0) {
				if (c.lastIndexOf(".js") >= 0) {
					return c.substring(c.lastIndexOf("-") + 1, c.lastIndexOf(".js"))
				} else {
					if (c.lastIndexOf(".swf") >= 0) {
						return c.substring(c.lastIndexOf("-") + 1, c.lastIndexOf(".swf"))
					} else {
						return c.substring(c.lastIndexOf("-") + 1)
					}
				}
			}
			return ""
		};
		b.utils.getAbsolutePath = function(j, h) {
			if (!b.utils.exists(h)) {
				h = document.location.href
			}
			if (!b.utils.exists(j)) {
				return undefined
			}
			if (a(j)) {
				return j
			}
			var k = h.substring(0, h.indexOf("://") + 3);
			var g = h.substring(k.length, h.indexOf("/", k.length + 1));
			var d;
			if (j.indexOf("/") === 0) {
				d = j.split("/")
			} else {
				var e = h.split("?")[0];
				e = e.substring(k.length + g.length + 1, e.lastIndexOf("/"));
				d = e.split("/").concat(j.split("/"))
			}
			var c = [];
			for (var f = 0; f < d.length; f++) {
				if (!d[f] || !b.utils.exists(d[f]) || d[f] == ".") {
					continue
				} else {
					if (d[f] == "..") {
						c.pop()
					} else {
						c.push(d[f])
					}
				}
			}
			return k + g + "/" + c.join("/")
		};

		function a(d) {
			if (!b.utils.exists(d)) {
				return
			}
			var e = d.indexOf("://");
			var c = d.indexOf("?");
			return (e > 0 && (c < 0 || (c > e)))
		}
		b.utils.pluginPathType = {
			ABSOLUTE: "ABSOLUTE",
			RELATIVE: "RELATIVE",
			CDN: "CDN"
		};
		b.utils.getPluginPathType = function(d) {
			if (typeof d != "string") {
				return
			}
			d = d.split("?")[0];
			var e = d.indexOf("://");
			if (e > 0) {
				return b.utils.pluginPathType.ABSOLUTE
			}
			var c = d.indexOf("/");
			var f = b.utils.extension(d);
			if (e < 0 && c < 0 && (!f || !isNaN(f))) {
				return b.utils.pluginPathType.CDN
			}
			return b.utils.pluginPathType.RELATIVE
		};
		b.utils.mapEmpty = function(c) {
			for (var d in c) {
				return false
			}
			return true
		};
		b.utils.mapLength = function(d) {
			var c = 0;
			for (var e in d) {
				c++
			}
			return c
		};
		b.utils.log = function(d, c) {
			if (typeof console != "undefined" && typeof console.log != "undefined") {
				if (c) {
					console.log(d, c)
				} else {
					console.log(d)
				}
			}
		};
		b.utils.css = function(d, g, c) {
			if (b.utils.exists(d)) {
				for (var e in g) {
					try {
						if (typeof g[e] === "undefined") {
							continue
						} else {
							if (typeof g[e] == "number" && !(e == "zIndex" || e == "opacity")) {
								if (isNaN(g[e])) {
									continue
								}
								if (e.match(/color/i)) {
									g[e] = "#" + b.utils.strings.pad(g[e].toString(16), 6)
								} else {
									g[e] = Math.ceil(g[e]) + "px"
								}
							}
						}
						d.style[e] = g[e]
					} catch (f) {}
				}
			}
		};
		b.utils.isYouTube = function(c) {
			return (c.indexOf("youtube.com") > -1 || c.indexOf("youtu.be") > -1)
		};
		b.utils.transform = function(c, d) {
			c.style.webkitTransform = d;
			c.style.MozTransform = d;
			c.style.OTransform = d
		};
		b.utils.stretch = function(h, n, m, f, l, g) {
			if (typeof m == "undefined" || typeof f == "undefined" || typeof l == "undefined" || typeof g == "undefined") {
				return
			}
			var d = m / l;
			var e = f / g;
			var k = 0;
			var j = 0;
			n.style.overflow = "hidden";
			b.utils.transform(n, "");
			var c = {};
			switch (h.toUpperCase()) {
			case b.utils.stretching.NONE:
				c.width = l;
				c.height = g;
				break;
			case b.utils.stretching.UNIFORM:
				if (d > e) {
					c.width = l * e;
					c.height = g * e
				} else {
					c.width = l * d;
					c.height = g * d
				}
				break;
			case b.utils.stretching.FILL:
				if (d > e) {
					c.width = l * d;
					c.height = g * d
				} else {
					c.width = l * e;
					c.height = g * e
				}
				break;
			case b.utils.stretching.EXACTFIT:
				b.utils.transform(n, ["scale(", d, ",", e, ")", " translate(0px,0px)"].join(""));
				c.width = l;
				c.height = g;
				break;
			default:
				break
			}
			c.top = (f - c.height) / 2;
			c.left = (m - c.width) / 2;
			b.utils.css(n, c)
		};
		b.utils.stretching = {
			NONE: "NONE",
			FILL: "FILL",
			UNIFORM: "UNIFORM",
			EXACTFIT: "EXACTFIT"
		};
		b.utils.deepReplaceKeyName = function(h, e, c) {
			switch (b.utils.typeOf(h)) {
			case "array":
				for (var g = 0; g < h.length; g++) {
					h[g] = b.utils.deepReplaceKeyName(h[g], e, c)
				}
				break;
			case "object":
				for (var f in h) {
					var d = f.replace(new RegExp(e, "g"), c);
					h[d] = b.utils.deepReplaceKeyName(h[f], e, c);
					if (f != d) {
						delete h[f]
					}
				}
				break
			}
			return h
		};
		b.utils.isInArray = function(e, d) {
			if (!(e) || !(e instanceof Array)) {
				return false
			}
			for (var c = 0; c < e.length; c++) {
				if (d === e[c]) {
					return true
				}
			}
			return false
		};
		b.utils.exists = function(c) {
			switch (typeof(c)) {
			case "string":
				return (c.length > 0);
				break;
			case "object":
				return (c !== null);
			case "undefined":
				return false
			}
			return true
		};
		b.utils.empty = function(c) {
			if (typeof c.hasChildNodes == "function") {
				while (c.hasChildNodes()) {
					c.removeChild(c.firstChild)
				}
			}
		};
		b.utils.parseDimension = function(c) {
			if (typeof c == "string") {
				if (c === "") {
					return 0
				} else {
					if (c.lastIndexOf("%") > -1) {
						return c
					} else {
						return parseInt(c.replace("px", ""), 10)
					}
				}
			}
			return c
		};
		b.utils.getDimensions = function(c) {
			if (c && c.style) {
				return {
					x: b.utils.parseDimension(c.style.left),
					y: b.utils.parseDimension(c.style.top),
					width: b.utils.parseDimension(c.style.width),
					height: b.utils.parseDimension(c.style.height)
				}
			} else {
				return {}
			}
		};
		b.utils.timeFormat = function(c) {
			str = "00:00";
			if (c > 0) {
				str = Math.floor(c / 60) < 10 ? "0" + Math.floor(c / 60) + ":" : Math.floor(c / 60) + ":";
				str += Math.floor(c % 60) < 10 ? "0" + Math.floor(c % 60) : Math.floor(c % 60)
			}
			return str
		}
	})(jwplayer);
	(function(a) {
		a.events = function() {};
		a.events.COMPLETE = "COMPLETE";
		a.events.ERROR = "ERROR"
	})(jwplayer);
	(function(jwplayer) {
		jwplayer.events.eventdispatcher = function(debug) {
			var _debug = debug;
			var _listeners;
			var _globallisteners;
			this.resetEventListeners = function() {
				_listeners = {};
				_globallisteners = []
			};
			this.resetEventListeners();
			this.addEventListener = function(type, listener, count) {
				try {
					if (!jwplayer.utils.exists(_listeners[type])) {
						_listeners[type] = []
					}
					if (typeof(listener) == "string") {
						eval("listener = " + listener)
					}
					_listeners[type].push({
						listener: listener,
						count: count
					})
				} catch (err) {
					jwplayer.utils.log("error", err)
				}
				return false
			};
			this.removeEventListener = function(type, listener) {
				if (!_listeners[type]) {
					return
				}
				try {
					for (var listenerIndex = 0; listenerIndex < _listeners[type].length; listenerIndex++) {
						if (_listeners[type][listenerIndex].listener.toString() == listener.toString()) {
							_listeners[type].splice(listenerIndex, 1);
							break
						}
					}
				} catch (err) {
					jwplayer.utils.log("error", err)
				}
				return false
			};
			this.addGlobalListener = function(listener, count) {
				try {
					if (typeof(listener) == "string") {
						eval("listener = " + listener)
					}
					_globallisteners.push({
						listener: listener,
						count: count
					})
				} catch (err) {
					jwplayer.utils.log("error", err)
				}
				return false
			};
			this.removeGlobalListener = function(listener) {
				if (!_globallisteners[type]) {
					return
				}
				try {
					for (var globalListenerIndex = 0; globalListenerIndex < _globallisteners.length; globalListenerIndex++) {
						if (_globallisteners[globalListenerIndex].listener.toString() == listener.toString()) {
							_globallisteners.splice(globalListenerIndex, 1);
							break
						}
					}
				} catch (err) {
					jwplayer.utils.log("error", err)
				}
				return false
			};
			this.sendEvent = function(type, data) {
				if (!jwplayer.utils.exists(data)) {
					data = {}
				}
				if (_debug) {
					jwplayer.utils.log(type, data)
				}
				if (typeof _listeners[type] != "undefined") {
					for (var listenerIndex = 0; listenerIndex < _listeners[type].length; listenerIndex++) {
						try {
							_listeners[type][listenerIndex].listener(data)
						} catch (err) {
							jwplayer.utils.log("There was an error while handling a listener: " + err.toString(), _listeners[type][listenerIndex].listener)
						}
						if (_listeners[type][listenerIndex]) {
							if (_listeners[type][listenerIndex].count === 1) {
								delete _listeners[type][listenerIndex]
							} else {
								if (_listeners[type][listenerIndex].count > 0) {
									_listeners[type][listenerIndex].count = _listeners[type][listenerIndex].count - 1
								}
							}
						}
					}
				}
				for (var globalListenerIndex = 0; globalListenerIndex < _globallisteners.length; globalListenerIndex++) {
					try {
						_globallisteners[globalListenerIndex].listener(data)
					} catch (err) {
						jwplayer.utils.log("There was an error while handling a listener: " + err.toString(), _globallisteners[globalListenerIndex].listener)
					}
					if (_globallisteners[globalListenerIndex]) {
						if (_globallisteners[globalListenerIndex].count === 1) {
							delete _globallisteners[globalListenerIndex]
						} else {
							if (_globallisteners[globalListenerIndex].count > 0) {
								_globallisteners[globalListenerIndex].count = _globallisteners[globalListenerIndex].count - 1
							}
						}
					}
				}
			}
		}
	})(jwplayer);
	(function(a) {
		var b = {};
		a.utils.animations = function() {};
		a.utils.animations.transform = function(c, d) {
			c.style.webkitTransform = d;
			c.style.MozTransform = d;
			c.style.OTransform = d;
			c.style.msTransform = d
		};
		a.utils.animations.transformOrigin = function(c, d) {
			c.style.webkitTransformOrigin = d;
			c.style.MozTransformOrigin = d;
			c.style.OTransformOrigin = d;
			c.style.msTransformOrigin = d
		};
		a.utils.animations.rotate = function(c, d) {
			a.utils.animations.transform(c, ["rotate(", d, "deg)"].join(""))
		};
		a.utils.cancelAnimation = function(c) {
			delete b[c.id]
		};
		a.utils.fadeTo = function(m, f, e, j, h, d) {
			if (b[m.id] != d && a.utils.exists(d)) {
				return
			}
			if (m.style.opacity == f) {
				return
			}
			var c = new Date().getTime();
			if (d > c) {
				setTimeout(function() {
					a.utils.fadeTo(m, f, e, j, 0, d)
				}, d - c)
			}
			if (m.style.display == "none") {
				m.style.display = "block"
			}
			if (!a.utils.exists(j)) {
				j = m.style.opacity === "" ? 1 : m.style.opacity
			}
			if (m.style.opacity == f && m.style.opacity !== "" && a.utils.exists(d)) {
				if (f === 0) {
					m.style.display = "none"
				}
				return
			}
			if (!a.utils.exists(d)) {
				d = c;
				b[m.id] = d
			}
			if (!a.utils.exists(h)) {
				h = 0
			}
			var k = (e > 0) ? ((c - d) / (e * 1000)) : 0;
			k = k > 1 ? 1 : k;
			var l = f - j;
			var g = j + (k * l);
			if (g > 1) {
				g = 1
			} else {
				if (g < 0) {
					g = 0
				}
			}
			m.style.opacity = g;
			if (h > 0) {
				b[m.id] = d + h * 1000;
				a.utils.fadeTo(m, f, e, j, 0, b[m.id]);
				return
			}
			setTimeout(function() {
				a.utils.fadeTo(m, f, e, j, 0, d)
			}, 10)
		}
	})(jwplayer);
	(function(a) {
		a.utils.arrays = function() {};
		a.utils.arrays.indexOf = function(c, d) {
			for (var b = 0; b < c.length; b++) {
				if (c[b] == d) {
					return b
				}
			}
			return -1
		};
		a.utils.arrays.remove = function(c, d) {
			var b = a.utils.arrays.indexOf(c, d);
			if (b > -1) {
				c.splice(b, 1)
			}
		}
	})(jwplayer);
	(function(a) {
		a.utils.extensionmap = {
			"3gp": {
				html5: "video/3gpp",
				flash: "video"
			},
			"3gpp": {
				html5: "video/3gpp"
			},
			"3g2": {
				html5: "video/3gpp2",
				flash: "video"
			},
			"3gpp2": {
				html5: "video/3gpp2"
			},
			flv: {
				flash: "video"
			},
			f4a: {
				html5: "audio/mp4"
			},
			f4b: {
				html5: "audio/mp4",
				flash: "video"
			},
			f4v: {
				html5: "video/mp4",
				flash: "video"
			},
			mov: {
				html5: "video/quicktime",
				flash: "video"
			},
			m4a: {
				html5: "audio/mp4",
				flash: "video"
			},
			m4b: {
				html5: "audio/mp4"
			},
			m4p: {
				html5: "audio/mp4"
			},
			m4v: {
				html5: "video/mp4",
				flash: "video"
			},
			mp4: {
				html5: "video/mp4",
				flash: "video"
			},
			rbs: {
				flash: "sound"
			},
			aac: {
				html5: "audio/aac",
				flash: "video"
			},
			mp3: {
				html5: "audio/mp3",
				flash: "sound"
			},
			ogg: {
				html5: "audio/ogg"
			},
			oga: {
				html5: "audio/ogg"
			},
			ogv: {
				html5: "video/ogg"
			},
			webm: {
				html5: "video/webm"
			},
			m3u8: {
				html5: "audio/x-mpegurl"
			},
			gif: {
				flash: "image"
			},
			jpeg: {
				flash: "image"
			},
			jpg: {
				flash: "image"
			},
			swf: {
				flash: "image"
			},
			png: {
				flash: "image"
			},
			wav: {
				html5: "audio/x-wav"
			}
		}
	})(jwplayer);
	(function(e) {
		e.utils.mediaparser = function() {};
		var g = {
			element: {
				width: "width",
				height: "height",
				id: "id",
				"class": "className",
				name: "name"
			},
			media: {
				src: "file",
				preload: "preload",
				autoplay: "autostart",
				loop: "repeat",
				controls: "controls"
			},
			source: {
				src: "file",
				type: "type",
				media: "media",
				"data-jw-width": "width",
				"data-jw-bitrate": "bitrate"
			},
			video: {
				poster: "image"
			}
		};
		var f = {};
		e.utils.mediaparser.parseMedia = function(j) {
			return d(j)
		};

		function c(k, j) {
			if (!e.utils.exists(j)) {
				j = g[k]
			} else {
				e.utils.extend(j, g[k])
			}
			return j
		}
		function d(n, j) {
			if (f[n.tagName.toLowerCase()] && !e.utils.exists(j)) {
				return f[n.tagName.toLowerCase()](n)
			} else {
				j = c("element", j);
				var o = {};
				for (var k in j) {
					if (k != "length") {
						var m = n.getAttribute(k);
						if (e.utils.exists(m)) {
							o[j[k]] = m
						}
					}
				}
				var l = n.style["#background-color"];
				if (l && !(l == "transparent" || l == "rgba(0, 0, 0, 0)")) {
					o.screencolor = l
				}
				return o
			}
		}
		function h(n, k) {
			k = c("media", k);
			var l = [];
			var j = e.utils.selectors("source", n);
			for (var m in j) {
				if (!isNaN(m)) {
					l.push(a(j[m]))
				}
			}
			var o = d(n, k);
			if (e.utils.exists(o.file)) {
				l[0] = {
					file: o.file
				}
			}
			o.levels = l;
			return o
		}
		function a(l, k) {
			k = c("source", k);
			var j = d(l, k);
			j.width = j.width ? j.width : 0;
			j.bitrate = j.bitrate ? j.bitrate : 0;
			return j
		}
		function b(l, k) {
			k = c("video", k);
			var j = h(l, k);
			return j
		}
		f.media = h;
		f.audio = h;
		f.source = a;
		f.video = b
	})(jwplayer);
	(function(a) {
		a.utils.loaderstatus = {
			NEW: "NEW",
			LOADING: "LOADING",
			ERROR: "ERROR",
			COMPLETE: "COMPLETE"
		};
		a.utils.scriptloader = function(c) {
			var d = a.utils.loaderstatus.NEW;
			var b = new a.events.eventdispatcher();
			a.utils.extend(this, b);
			this.load = function() {
				if (d == a.utils.loaderstatus.NEW) {
					d = a.utils.loaderstatus.LOADING;
					var e = document.createElement("script");
					e.onload = function(f) {
						d = a.utils.loaderstatus.COMPLETE;
						b.sendEvent(a.events.COMPLETE)
					};
					e.onerror = function(f) {
						d = a.utils.loaderstatus.ERROR;
						b.sendEvent(a.events.ERROR)
					};
					e.onreadystatechange = function() {
						if (e.readyState == "loaded" || e.readyState == "complete") {
							d = a.utils.loaderstatus.COMPLETE;
							b.sendEvent(a.events.COMPLETE)
						}
					};
					document.getElementsByTagName("head")[0].appendChild(e);
					e.src = c
				}
			};
			this.getStatus = function() {
				return d
			}
		}
	})(jwplayer);
	(function(a) {
		a.utils.selectors = function(b, e) {
			if (!a.utils.exists(e)) {
				e = document
			}
			b = a.utils.strings.trim(b);
			var c = b.charAt(0);
			if (c == "#") {
				return e.getElementById(b.substr(1))
			} else {
				if (c == ".") {
					if (e.getElementsByClassName) {
						return e.getElementsByClassName(b.substr(1))
					} else {
						return a.utils.selectors.getElementsByTagAndClass("*", b.substr(1))
					}
				} else {
					if (b.indexOf(".") > 0) {
						var d = b.split(".");
						return a.utils.selectors.getElementsByTagAndClass(d[0], d[1])
					} else {
						return e.getElementsByTagName(b)
					}
				}
			}
			return null
		};
		a.utils.selectors.getElementsByTagAndClass = function(e, h, g) {
			var j = [];
			if (!a.utils.exists(g)) {
				g = document
			}
			var f = g.getElementsByTagName(e);
			for (var d = 0; d < f.length; d++) {
				if (a.utils.exists(f[d].className)) {
					var c = f[d].className.split(" ");
					for (var b = 0; b < c.length; b++) {
						if (c[b] == h) {
							j.push(f[d])
						}
					}
				}
			}
			return j
		}
	})(jwplayer);
	(function(a) {
		a.utils.strings = function() {};
		a.utils.strings.trim = function(b) {
			return b.replace(/^\s*/, "").replace(/\s*$/, "")
		};
		a.utils.strings.pad = function(c, d, b) {
			if (!b) {
				b = "0"
			}
			while (c.length < d) {
				c = b + c
			}
			return c
		};
		a.utils.strings.serialize = function(b) {
			if (b == null) {
				return null
			} else {
				if (b == "true") {
					return true
				} else {
					if (b == "false") {
						return false
					} else {
						if (isNaN(Number(b)) || b.length > 5 || b.length == 0) {
							return b
						} else {
							return Number(b)
						}
					}
				}
			}
		};
		a.utils.strings.seconds = function(d) {
			d = d.replace(",", ".");
			var b = d.split(":");
			var c = 0;
			if (d.substr(-1) == "s") {
				c = Number(d.substr(0, d.length - 1))
			} else {
				if (d.substr(-1) == "m") {
					c = Number(d.substr(0, d.length - 1)) * 60
				} else {
					if (d.substr(-1) == "h") {
						c = Number(d.substr(0, d.length - 1)) * 3600
					} else {
						if (b.length > 1) {
							c = Number(b[b.length - 1]);
							c += Number(b[b.length - 2]) * 60;
							if (b.length == 3) {
								c += Number(b[b.length - 3]) * 3600
							}
						} else {
							c = Number(d)
						}
					}
				}
			}
			return c
		};
		a.utils.strings.xmlAttribute = function(b, c) {
			for (var d = 0; d < b.attributes.length; d++) {
				if (b.attributes[d].name && b.attributes[d].name.toLowerCase() == c.toLowerCase()) {
					return b.attributes[d].value.toString()
				}
			}
			return ""
		};
		a.utils.strings.jsonToString = function(f) {
			var h = h || {};
			if (h && h.stringify) {
				return h.stringify(f)
			}
			var c = typeof(f);
			if (c != "object" || f === null) {
				if (c == "string") {
					f = '"' + f + '"'
				} else {
					return String(f)
				}
			} else {
				var g = [],
					b = (f && f.constructor == Array);
				for (var d in f) {
					var e = f[d];
					switch (typeof(e)) {
					case "string":
						e = '"' + e + '"';
						break;
					case "object":
						if (a.utils.exists(e)) {
							e = a.utils.strings.jsonToString(e)
						}
						break
					}
					if (b) {
						if (typeof(e) != "function") {
							g.push(String(e))
						}
					} else {
						if (typeof(e) != "function") {
							g.push('"' + d + '":' + String(e))
						}
					}
				}
				if (b) {
					return "[" + String(g) + "]"
				} else {
					return "{" + String(g) + "}"
				}
			}
		}
	})(jwplayer);
	(function(c) {
		var d = new RegExp(/^(#|0x)[0-9a-fA-F]{3,6}/);
		c.utils.typechecker = function(g, f) {
			f = !c.utils.exists(f) ? b(g) : f;
			return e(g, f)
		};

		function b(f) {
			var g = ["true", "false", "t", "f"];
			if (g.toString().indexOf(f.toLowerCase().replace(" ", "")) >= 0) {
				return "boolean"
			} else {
				if (d.test(f)) {
					return "color"
				} else {
					if (!isNaN(parseInt(f, 10)) && parseInt(f, 10).toString().length == f.length) {
						return "integer"
					} else {
						if (!isNaN(parseFloat(f)) && parseFloat(f).toString().length == f.length) {
							return "float"
						}
					}
				}
			}
			return "string"
		}
		function e(g, f) {
			if (!c.utils.exists(f)) {
				return g
			}
			switch (f) {
			case "color":
				if (g.length > 0) {
					return a(g)
				}
				return null;
			case "integer":
				return parseInt(g, 10);
			case "float":
				return parseFloat(g);
			case "boolean":
				if (g.toLowerCase() == "true") {
					return true
				} else {
					if (g == "1") {
						return true
					}
				}
				return false
			}
			return g
		}
		function a(f) {
			switch (f.toLowerCase()) {
			case "blue":
				return parseInt("0000FF", 16);
			case "green":
				return parseInt("00FF00", 16);
			case "red":
				return parseInt("FF0000", 16);
			case "cyan":
				return parseInt("00FFFF", 16);
			case "magenta":
				return parseInt("FF00FF", 16);
			case "yellow":
				return parseInt("FFFF00", 16);
			case "black":
				return parseInt("000000", 16);
			case "white":
				return parseInt("FFFFFF", 16);
			default:
				f = f.replace(/(#|0x)?([0-9A-F]{3,6})$/gi, "$2");
				if (f.length == 3) {
					f = f.charAt(0) + f.charAt(0) + f.charAt(1) + f.charAt(1) + f.charAt(2) + f.charAt(2)
				}
				return parseInt(f, 16)
			}
			return parseInt("000000", 16)
		}
	})(jwplayer);
	(function(a) {
		a.utils.parsers = function() {};
		a.utils.parsers.localName = function(b) {
			if (!b) {
				return ""
			} else {
				if (b.localName) {
					return b.localName
				} else {
					if (b.baseName) {
						return b.baseName
					} else {
						return ""
					}
				}
			}
		};
		a.utils.parsers.textContent = function(b) {
			if (!b) {
				return ""
			} else {
				if (b.textContent) {
					return b.textContent
				} else {
					if (b.text) {
						return b.text
					} else {
						return ""
					}
				}
			}
		}
	})(jwplayer);
	(function(a) {
		a.utils.parsers.jwparser = function() {};
		a.utils.parsers.jwparser.PREFIX = "jwplayer";
		a.utils.parsers.jwparser.parseEntry = function(c, d) {
			for (var b = 0; b < c.childNodes.length; b++) {
				if (c.childNodes[b].prefix == a.utils.parsers.jwparser.PREFIX) {
					d[a.utils.parsers.localName(c.childNodes[b])] = a.utils.strings.serialize(a.utils.parsers.textContent(c.childNodes[b]))
				}
				if (!d.file && String(d.link).toLowerCase().indexOf("youtube") > -1) {
					d.file = d.link
				}
			}
			return d
		};
		a.utils.parsers.jwparser.getProvider = function(c) {
			if (c.type) {
				return c.type
			} else {
				if (c.file.indexOf("youtube.com/w") > -1 || c.file.indexOf("youtube.com/v") > -1 || c.file.indexOf("youtu.be/") > -1) {
					return "youtube"
				} else {
					if (c.streamer && c.streamer.indexOf("rtmp") == 0) {
						return "rtmp"
					} else {
						if (c.streamer && c.streamer.indexOf("http") == 0) {
							return "http"
						} else {
							var b = a.utils.strings.extension(c.file);
							if (extensions.hasOwnProperty(b)) {
								return extensions[b]
							}
						}
					}
				}
			}
			return ""
		}
	})(jwplayer);
	(function(a) {
		a.utils.parsers.mediaparser = function() {};
		a.utils.parsers.mediaparser.PREFIX = "media";
		a.utils.parsers.mediaparser.parseGroup = function(d, f) {
			var e = false;
			for (var c = 0; c < d.childNodes.length; c++) {
				if (d.childNodes[c].prefix == a.utils.parsers.mediaparser.PREFIX) {
					if (!a.utils.parsers.localName(d.childNodes[c])) {
						continue
					}
					switch (a.utils.parsers.localName(d.childNodes[c]).toLowerCase()) {
					case "content":
						if (!e) {
							f.file = a.utils.strings.xmlAttribute(d.childNodes[c], "url")
						}
						if (a.utils.strings.xmlAttribute(d.childNodes[c], "duration")) {
							f.duration = a.utils.strings.seconds(a.utils.strings.xmlAttribute(d.childNodes[c], "duration"))
						}
						if (a.utils.strings.xmlAttribute(d.childNodes[c], "start")) {
							f.start = a.utils.strings.seconds(a.utils.strings.xmlAttribute(d.childNodes[c], "start"))
						}
						if (d.childNodes[c].childNodes && d.childNodes[c].childNodes.length > 0) {
							f = a.utils.parsers.mediaparser.parseGroup(d.childNodes[c], f)
						}
						if (a.utils.strings.xmlAttribute(d.childNodes[c], "width") || a.utils.strings.xmlAttribute(d.childNodes[c], "bitrate") || a.utils.strings.xmlAttribute(d.childNodes[c], "url")) {
							if (!f.levels) {
								f.levels = []
							}
							f.levels.push({
								width: a.utils.strings.xmlAttribute(d.childNodes[c], "width"),
								bitrate: a.utils.strings.xmlAttribute(d.childNodes[c], "bitrate"),
								file: a.utils.strings.xmlAttribute(d.childNodes[c], "url")
							})
						}
						break;
					case "title":
						f.title = a.utils.parsers.textContent(d.childNodes[c]);
						break;
					case "description":
						f.description = a.utils.parsers.textContent(d.childNodes[c]);
						break;
					case "keywords":
						f.tags = a.utils.parsers.textContent(d.childNodes[c]);
						break;
					case "thumbnail":
						f.image = a.utils.strings.xmlAttribute(d.childNodes[c], "url");
						break;
					case "credit":
						f.author = a.utils.parsers.textContent(d.childNodes[c]);
						break;
					case "player":
						var b = d.childNodes[c].url;
						if (b.indexOf("youtube.com") >= 0 || b.indexOf("youtu.be") >= 0) {
							e = true;
							f.file = a.utils.strings.xmlAttribute(d.childNodes[c], "url")
						}
						break;
					case "group":
						a.utils.parsers.mediaparser.parseGroup(d.childNodes[c], f);
						break
					}
				}
			}
			return f
		}
	})(jwplayer);
	(function(b) {
		b.utils.parsers.rssparser = function() {};
		b.utils.parsers.rssparser.parse = function(f) {
			var c = [];
			for (var e = 0; e < f.childNodes.length; e++) {
				if (b.utils.parsers.localName(f.childNodes[e]).toLowerCase() == "channel") {
					for (var d = 0; d < f.childNodes[e].childNodes.length; d++) {
						if (b.utils.parsers.localName(f.childNodes[e].childNodes[d]).toLowerCase() == "item") {
							c.push(a(f.childNodes[e].childNodes[d]))
						}
					}
				}
			}
			return c
		};

		function a(d) {
			var e = {};
			for (var c = 0; c < d.childNodes.length; c++) {
				if (!b.utils.parsers.localName(d.childNodes[c])) {
					continue
				}
				switch (b.utils.parsers.localName(d.childNodes[c]).toLowerCase()) {
				case "enclosure":
					e.file = b.utils.strings.xmlAttribute(d.childNodes[c], "url");
					break;
				case "title":
					e.title = b.utils.parsers.textContent(d.childNodes[c]);
					break;
				case "pubdate":
					e.date = b.utils.parsers.textContent(d.childNodes[c]);
					break;
				case "description":
					e.description = b.utils.parsers.textContent(d.childNodes[c]);
					break;
				case "link":
					e.link = b.utils.parsers.textContent(d.childNodes[c]);
					break;
				case "category":
					if (e.tags) {
						e.tags += b.utils.parsers.textContent(d.childNodes[c])
					} else {
						e.tags = b.utils.parsers.textContent(d.childNodes[c])
					}
					break
				}
			}
			e = b.utils.parsers.mediaparser.parseGroup(d, e);
			e = b.utils.parsers.jwparser.parseEntry(d, e);
			return new b.html5.playlistitem(e)
		}
	})(jwplayer);
	(function(a) {
		var c = {};
		var b = {};
		a.plugins = function() {};
		a.plugins.loadPlugins = function(e, d) {
			b[e] = new a.plugins.pluginloader(new a.plugins.model(c), d);
			return b[e]
		};
		a.plugins.registerPlugin = function(h, f, e) {
			var d = a.utils.getPluginName(h);
			if (c[d]) {
				c[d].registerPlugin(h, f, e)
			} else {
				a.utils.log("A plugin (" + h + ") was registered with the player that was not loaded. Please check your configuration.");
				for (var g in b) {
					b[g].pluginFailed()
				}
			}
		}
	})(jwplayer);
	(function(a) {
		a.plugins.model = function(b) {
			this.addPlugin = function(c) {
				var d = a.utils.getPluginName(c);
				if (!b[d]) {
					b[d] = new a.plugins.plugin(c)
				}
				return b[d]
			}
		}
	})(jwplayer);
	(function(a) {
		a.plugins.pluginmodes = {
			FLASH: "FLASH",
			JAVASCRIPT: "JAVASCRIPT",
			HYBRID: "HYBRID"
		};
		a.plugins.plugin = function(b) {
			var d = "http://plugins.longtailvideo.com";
			var j = a.utils.loaderstatus.NEW;
			var k;
			var h;
			var l;
			var c = new a.events.eventdispatcher();
			a.utils.extend(this, c);

			function e() {
				switch (a.utils.getPluginPathType(b)) {
				case a.utils.pluginPathType.ABSOLUTE:
					return b;
				case a.utils.pluginPathType.RELATIVE:
					return a.utils.getAbsolutePath(b, window.location.href);
				case a.utils.pluginPathType.CDN:
					var n = a.utils.getPluginName(b);
					var m = a.utils.getPluginVersion(b);
					return d + "/" + a.version.split(".")[0] + "/" + n + "/" + n + (m !== "" ? ("-" + m) : "") + ".js"
				}
			}
			function g(m) {
				l = setTimeout(function() {
					j = a.utils.loaderstatus.COMPLETE;
					c.sendEvent(a.events.COMPLETE)
				}, 1000)
			}
			function f(m) {
				j = a.utils.loaderstatus.ERROR;
				c.sendEvent(a.events.ERROR)
			}
			this.load = function() {
				if (j == a.utils.loaderstatus.NEW) {
					if (b.lastIndexOf(".swf") > 0) {
						k = b;
						j = a.utils.loaderstatus.COMPLETE;
						c.sendEvent(a.events.COMPLETE);
						return
					}
					j = a.utils.loaderstatus.LOADING;
					var m = new a.utils.scriptloader(e());
					m.addEventListener(a.events.COMPLETE, g);
					m.addEventListener(a.events.ERROR, f);
					m.load()
				}
			};
			this.registerPlugin = function(o, n, m) {
				if (l) {
					clearTimeout(l);
					l = undefined
				}
				if (n && m) {
					k = m;
					h = n
				} else {
					if (typeof n == "string") {
						k = n
					} else {
						if (typeof n == "function") {
							h = n
						} else {
							if (!n && !m) {
								k = o
							}
						}
					}
				}
				j = a.utils.loaderstatus.COMPLETE;
				c.sendEvent(a.events.COMPLETE)
			};
			this.getStatus = function() {
				return j
			};
			this.getPluginName = function() {
				return a.utils.getPluginName(b)
			};
			this.getFlashPath = function() {
				if (k) {
					switch (a.utils.getPluginPathType(k)) {
					case a.utils.pluginPathType.ABSOLUTE:
						return k;
					case a.utils.pluginPathType.RELATIVE:
						if (b.lastIndexOf(".swf") > 0) {
							return a.utils.getAbsolutePath(k, window.location.href)
						}
						return a.utils.getAbsolutePath(k, e());
					case a.utils.pluginPathType.CDN:
						if (k.indexOf("-") > -1) {
							return k + "h"
						}
						return k + "-h"
					}
				}
				return null
			};
			this.getJS = function() {
				return h
			};
			this.getPluginmode = function() {
				if (typeof k != "undefined" && typeof h != "undefined") {
					return a.plugins.pluginmodes.HYBRID
				} else {
					if (typeof k != "undefined") {
						return a.plugins.pluginmodes.FLASH
					} else {
						if (typeof h != "undefined") {
							return a.plugins.pluginmodes.JAVASCRIPT
						}
					}
				}
			};
			this.getNewInstance = function(n, m, o) {
				return new h(n, m, o)
			};
			this.getURL = function() {
				return b
			}
		}
	})(jwplayer);
	(function(a) {
		a.plugins.pluginloader = function(h, e) {
			var g = {};
			var k = a.utils.loaderstatus.NEW;
			var d = false;
			var b = false;
			var c = new a.events.eventdispatcher();
			a.utils.extend(this, c);

			function f() {
				if (!b) {
					b = true;
					k = a.utils.loaderstatus.COMPLETE;
					c.sendEvent(a.events.COMPLETE)
				}
			}
			function j() {
				if (!b) {
					var m = 0;
					for (plugin in g) {
						var l = g[plugin].getStatus();
						if (l == a.utils.loaderstatus.LOADING || l == a.utils.loaderstatus.NEW) {
							m++
						}
					}
					if (m == 0) {
						f()
					}
				}
			}
			this.setupPlugins = function(n, l, s) {
				var m = {
					length: 0,
					plugins: {}
				};
				var p = {
					length: 0,
					plugins: {}
				};
				for (var o in g) {
					var q = g[o].getPluginName();
					if (g[o].getFlashPath()) {
						m.plugins[g[o].getFlashPath()] = l.plugins[o];
						m.plugins[g[o].getFlashPath()].pluginmode = g[o].getPluginmode();
						m.length++
					}
					if (g[o].getJS()) {
						var r = document.createElement("div");
						r.id = n.id + "_" + q;
						r.style.position = "absolute";
						r.style.zIndex = p.length + 10;
						p.plugins[q] = g[o].getNewInstance(n, l.plugins[o], r);
						p.length++;
						if (typeof p.plugins[q].resize != "undefined") {
							n.onReady(s(p.plugins[q], r, true));
							n.onResize(s(p.plugins[q], r))
						}
					}
				}
				n.plugins = p.plugins;
				return m
			};
			this.load = function() {
				k = a.utils.loaderstatus.LOADING;
				d = true;
				for (var l in e) {
					if (a.utils.exists(l)) {
						g[l] = h.addPlugin(l);
						g[l].addEventListener(a.events.COMPLETE, j);
						g[l].addEventListener(a.events.ERROR, j)
					}
				}
				for (l in g) {
					g[l].load()
				}
				d = false;
				j()
			};
			this.pluginFailed = function() {
				f()
			};
			this.getStatus = function() {
				return k
			}
		}
	})(jwplayer);
	(function(b) {
		var a = [];
		b.api = function(d) {
			this.container = d;
			this.id = d.id;
			var n = {};
			var s = {};
			var q = {};
			var c = [];
			var h = undefined;
			var l = false;
			var j = [];
			var p = b.utils.getOuterHTML(d);
			var r = {};
			var k = {};
			this.getBuffer = function() {
				return this.callInternal("jwGetBuffer")
			};
			this.getContainer = function() {
				return this.container
			};

			function e(u, t) {
				return function(z, v, w, x) {
					if (u.renderingMode == "flash" || u.renderingMode == "html5") {
						var y;
						if (v) {
							k[z] = v;
							y = "jwplayer('" + u.id + "').callback('" + z + "')"
						} else {
							if (!v && k[z]) {
								delete k[z]
							}
						}
						h.jwDockSetButton(z, y, w, x)
					}
					return t
				}
			}
			this.getPlugin = function(t) {
				var v = this;
				var u = {};
				if (t == "dock") {
					return b.utils.extend(u, {
						setButton: e(v, u),
						show: function() {
							v.callInternal("jwDockShow");
							return u
						},
						hide: function() {
							v.callInternal("jwDockHide");
							return u
						},
						onShow: function(w) {
							v.componentListener("dock", b.api.events.JWPLAYER_COMPONENT_SHOW, w);
							return u
						},
						onHide: function(w) {
							v.componentListener("dock", b.api.events.JWPLAYER_COMPONENT_HIDE, w);
							return u
						}
					})
				} else {
					if (t == "controlbar") {
						return b.utils.extend(u, {
							show: function() {
								v.callInternal("jwControlbarShow");
								return u
							},
							hide: function() {
								v.callInternal("jwControlbarHide");
								return u
							},
							onShow: function(w) {
								v.componentListener("controlbar", b.api.events.JWPLAYER_COMPONENT_SHOW, w);
								return u
							},
							onHide: function(w) {
								v.componentListener("controlbar", b.api.events.JWPLAYER_COMPONENT_HIDE, w);
								return u
							}
						})
					} else {
						if (t == "display") {
							return b.utils.extend(u, {
								show: function() {
									v.callInternal("jwDisplayShow");
									return u
								},
								hide: function() {
									v.callInternal("jwDisplayHide");
									return u
								},
								onShow: function(w) {
									v.componentListener("display", b.api.events.JWPLAYER_COMPONENT_SHOW, w);
									return u
								},
								onHide: function(w) {
									v.componentListener("display", b.api.events.JWPLAYER_COMPONENT_HIDE, w);
									return u
								}
							})
						} else {
							return this.plugins[t]
						}
					}
				}
			};
			this.callback = function(t) {
				if (k[t]) {
					return k[t]()
				}
			};
			this.getDuration = function() {
				return this.callInternal("jwGetDuration")
			};
			this.getFullscreen = function() {
				return this.callInternal("jwGetFullscreen")
			};
			this.getHeight = function() {
				return this.callInternal("jwGetHeight")
			};
			this.getLockState = function() {
				return this.callInternal("jwGetLockState")
			};
			this.getMeta = function() {
				return this.getItemMeta()
			};
			this.getMute = function() {
				return this.callInternal("jwGetMute")
			};
			this.getPlaylist = function() {
				var u = this.callInternal("jwGetPlaylist");
				if (this.renderingMode == "flash") {
					b.utils.deepReplaceKeyName(u, "__dot__", ".")
				}
				for (var t = 0; t < u.length; t++) {
					if (!b.utils.exists(u[t].index)) {
						u[t].index = t
					}
				}
				return u
			};
			this.getPlaylistItem = function(t) {
				if (!b.utils.exists(t)) {
					t = this.getCurrentItem()
				}
				return this.getPlaylist()[t]
			};
			this.getPosition = function() {
				return this.callInternal("jwGetPosition")
			};
			this.getRenderingMode = function() {
				return this.renderingMode
			};
			this.getState = function() {
				return this.callInternal("jwGetState")
			};
			this.getVolume = function() {
				return this.callInternal("jwGetVolume")
			};
			this.getWidth = function() {
				return this.callInternal("jwGetWidth")
			};
			this.setFullscreen = function(t) {
				if (!b.utils.exists(t)) {
					this.callInternal("jwSetFullscreen", !this.callInternal("jwGetFullscreen"))
				} else {
					this.callInternal("jwSetFullscreen", t)
				}
				return this
			};
			this.setMute = function(t) {
				if (!b.utils.exists(t)) {
					this.callInternal("jwSetMute", !this.callInternal("jwGetMute"))
				} else {
					this.callInternal("jwSetMute", t)
				}
				return this
			};
			this.lock = function() {
				return this
			};
			this.unlock = function() {
				return this
			};
			this.load = function(t) {
				this.callInternal("jwLoad", t);
				return this
			};
			this.playlistItem = function(t) {
				this.callInternal("jwPlaylistItem", t);
				return this
			};
			this.playlistPrev = function() {
				this.callInternal("jwPlaylistPrev");
				return this
			};
			this.playlistNext = function() {
				this.callInternal("jwPlaylistNext");
				return this
			};
			this.resize = function(u, t) {
				if (this.renderingMode == "html5") {
					h.jwResize(u, t)
				} else {
					this.container.width = u;
					this.container.height = t
				}
				return this
			};
			this.play = function(t) {
				if (typeof t == "undefined") {
					t = this.getState();
					if (t == b.api.events.state.PLAYING || t == b.api.events.state.BUFFERING) {
						this.callInternal("jwPause")
					} else {
						this.callInternal("jwPlay")
					}
				} else {
					this.callInternal("jwPlay", t)
				}
				return this
			};
			this.pause = function(t) {
				if (typeof t == "undefined") {
					t = this.getState();
					if (t == b.api.events.state.PLAYING || t == b.api.events.state.BUFFERING) {
						this.callInternal("jwPause")
					} else {
						this.callInternal("jwPlay")
					}
				} else {
					this.callInternal("jwPause", t)
				}
				return this
			};
			this.stop = function() {
				this.callInternal("jwStop");
				return this
			};
			this.seek = function(t) {
				this.callInternal("jwSeek", t);
				return this
			};
			this.setVolume = function(t) {
				this.callInternal("jwSetVolume", t);
				return this
			};
			this.onBufferChange = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_BUFFER, t)
			};
			this.onBufferFull = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_BUFFER_FULL, t)
			};
			this.onError = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_ERROR, t)
			};
			this.onFullscreen = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_FULLSCREEN, t)
			};
			this.onMeta = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_META, t)
			};
			this.onMute = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_MUTE, t)
			};
			this.onPlaylist = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_PLAYLIST_LOADED, t)
			};
			this.onPlaylistItem = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_PLAYLIST_ITEM, t)
			};
			this.onReady = function(t) {
				return this.eventListener(b.api.events.API_READY, t)
			};
			this.onResize = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_RESIZE, t)
			};
			this.onComplete = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_COMPLETE, t)
			};
			this.onSeek = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_SEEK, t)
			};
			this.onTime = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_TIME, t)
			};
			this.onVolume = function(t) {
				return this.eventListener(b.api.events.JWPLAYER_MEDIA_VOLUME, t)
			};
			this.onBuffer = function(t) {
				return this.stateListener(b.api.events.state.BUFFERING, t)
			};
			this.onPause = function(t) {
				return this.stateListener(b.api.events.state.PAUSED, t)
			};
			this.onPlay = function(t) {
				return this.stateListener(b.api.events.state.PLAYING, t)
			};
			this.onIdle = function(t) {
				return this.stateListener(b.api.events.state.IDLE, t)
			};
			this.remove = function() {
				n = {};
				j = [];
				if (b.utils.getOuterHTML(this.container) != p) {
					b.api.destroyPlayer(this.id, p)
				}
			};
			this.setup = function(u) {
				if (b.embed) {
					var t = this.id;
					this.remove();
					var v = b(t);
					v.config = u;
					return new b.embed(v)
				}
				return this
			};
			this.registerPlugin = function(v, u, t) {
				b.plugins.registerPlugin(v, u, t)
			};
			this.setPlayer = function(t, u) {
				h = t;
				this.renderingMode = u
			};
			this.stateListener = function(t, u) {
				if (!s[t]) {
					s[t] = [];
					this.eventListener(b.api.events.JWPLAYER_PLAYER_STATE, g(t))
				}
				s[t].push(u);
				return this
			};

			function g(t) {
				return function(v) {
					var u = v.newstate,
						x = v.oldstate;
					if (u == t) {
						var w = s[u];
						if (w) {
							for (var y = 0; y < w.length; y++) {
								if (typeof w[y] == "function") {
									w[y].call(this, {
										oldstate: x,
										newstate: u
									})
								}
							}
						}
					}
				}
			}
			this.componentListener = function(t, u, v) {
				if (!q[t]) {
					q[t] = {}
				}
				if (!q[t][u]) {
					q[t][u] = [];
					this.eventListener(u, m(t, u))
				}
				q[t][u].push(v);
				return this
			};

			function m(t, u) {
				return function(w) {
					if (t == w.component) {
						var v = q[t][u];
						if (v) {
							for (var x = 0; x < v.length; x++) {
								if (typeof v[x] == "function") {
									v[x].call(this, w)
								}
							}
						}
					}
				}
			}
			this.addInternalListener = function(t, u) {
				t.jwAddEventListener(u, 'function(dat) { jwplayer("' + this.id + '").dispatchEvent("' + u + '", dat); }')
			};
			this.eventListener = function(t, u) {
				if (!n[t]) {
					n[t] = [];
					if (h && l) {
						this.addInternalListener(h, t)
					}
				}
				n[t].push(u);
				return this
			};
			this.dispatchEvent = function(v) {
				if (n[v]) {
					var u = f(v, arguments[1]);
					for (var t = 0; t < n[v].length; t++) {
						if (typeof n[v][t] == "function") {
							n[v][t].call(this, u)
						}
					}
				}
			};

			function f(v, t) {
				var x = b.utils.extend({}, t);
				if (v == b.api.events.JWPLAYER_FULLSCREEN && !x.fullscreen) {
					x.fullscreen = x.message == "true" ? true : false;
					delete x.message
				} else {
					if (typeof x.data == "object") {
						x = b.utils.extend(x, x.data);
						delete x.data
					}
				}
				var u = ["position", "duration", "offset"];
				for (var w in u) {
					if (x[u[w]]) {
						x[u[w]] = Math.round(x[u[w]] * 1000) / 1000
					}
				}
				return x
			}
			this.callInternal = function(u, t) {
				if (l) {
					if (typeof h != "undefined" && typeof h[u] == "function") {
						if (b.utils.exists(t)) {
							return (h[u])(t)
						} else {
							return (h[u])()
						}
					}
					return null
				} else {
					j.push({
						method: u,
						parameters: t
					})
				}
			};
			this.playerReady = function(v) {
				l = true;
				if (!h) {
					this.setPlayer(document.getElementById(v.id))
				}
				this.container = document.getElementById(this.id);
				for (var t in n) {
					this.addInternalListener(h, t)
				}
				this.eventListener(b.api.events.JWPLAYER_PLAYLIST_ITEM, function(w) {
					r = {}
				});
				this.eventListener(b.api.events.JWPLAYER_MEDIA_META, function(w) {
					b.utils.extend(r, w.metadata)
				});
				this.dispatchEvent(b.api.events.API_READY);
				while (j.length > 0) {
					var u = j.shift();
					this.callInternal(u.method, u.parameters)
				}
			};
			this.getItemMeta = function() {
				return r
			};
			this.getCurrentItem = function() {
				return this.callInternal("jwGetPlaylistIndex")
			};

			function o(v, x, w) {
				var t = [];
				if (!x) {
					x = 0
				}
				if (!w) {
					w = v.length - 1
				}
				for (var u = x; u <= w; u++) {
					t.push(v[u])
				}
				return t
			}
			return this
		};
		b.api.selectPlayer = function(d) {
			var c;
			if (!b.utils.exists(d)) {
				d = 0
			}
			if (d.nodeType) {
				c = d
			} else {
				if (typeof d == "string") {
					c = document.getElementById(d)
				}
			}
			if (c) {
				var e = b.api.playerById(c.id);
				if (e) {
					return e
				} else {
					return b.api.addPlayer(new b.api(c))
				}
			} else {
				if (typeof d == "number") {
					return b.getPlayers()[d]
				}
			}
			return null
		};
		b.api.events = {
			API_READY: "jwplayerAPIReady",
			JWPLAYER_READY: "jwplayerReady",
			JWPLAYER_FULLSCREEN: "jwplayerFullscreen",
			JWPLAYER_RESIZE: "jwplayerResize",
			JWPLAYER_ERROR: "jwplayerError",
			JWPLAYER_COMPONENT_SHOW: "jwplayerComponentShow",
			JWPLAYER_COMPONENT_HIDE: "jwplayerComponentHide",
			JWPLAYER_MEDIA_BUFFER: "jwplayerMediaBuffer",
			JWPLAYER_MEDIA_BUFFER_FULL: "jwplayerMediaBufferFull",
			JWPLAYER_MEDIA_ERROR: "jwplayerMediaError",
			JWPLAYER_MEDIA_LOADED: "jwplayerMediaLoaded",
			JWPLAYER_MEDIA_COMPLETE: "jwplayerMediaComplete",
			JWPLAYER_MEDIA_SEEK: "jwplayerMediaSeek",
			JWPLAYER_MEDIA_TIME: "jwplayerMediaTime",
			JWPLAYER_MEDIA_VOLUME: "jwplayerMediaVolume",
			JWPLAYER_MEDIA_META: "jwplayerMediaMeta",
			JWPLAYER_MEDIA_MUTE: "jwplayerMediaMute",
			JWPLAYER_PLAYER_STATE: "jwplayerPlayerState",
			JWPLAYER_PLAYLIST_LOADED: "jwplayerPlaylistLoaded",
			JWPLAYER_PLAYLIST_ITEM: "jwplayerPlaylistItem"
		};
		b.api.events.state = {
			BUFFERING: "BUFFERING",
			IDLE: "IDLE",
			PAUSED: "PAUSED",
			PLAYING: "PLAYING"
		};
		b.api.playerById = function(d) {
			for (var c = 0; c < a.length; c++) {
				if (a[c].id == d) {
					return a[c]
				}
			}
			return null
		};
		b.api.addPlayer = function(c) {
			for (var d = 0; d < a.length; d++) {
				if (a[d] == c) {
					return c
				}
			}
			a.push(c);
			return c
		};
		b.api.destroyPlayer = function(g, d) {
			var f = -1;
			for (var j = 0; j < a.length; j++) {
				if (a[j].id == g) {
					f = j;
					continue
				}
			}
			if (f >= 0) {
				var c = document.getElementById(a[f].id);
				if (document.getElementById(a[f].id + "_wrapper")) {
					c = document.getElementById(a[f].id + "_wrapper")
				}
				if (c) {
					if (d) {
						b.utils.setOuterHTML(c, d)
					} else {
						var h = document.createElement("div");
						var e = c.id;
						if (c.id.indexOf("_wrapper") == c.id.length - 8) {
							newID = c.id.substring(0, c.id.length - 8)
						}
						h.setAttribute("id", e);
						c.parentNode.replaceChild(h, c)
					}
				}
				a.splice(f, 1)
			}
			return null
		};
		b.getPlayers = function() {
			return a.slice(0)
		}
	})(jwplayer);
	var _userPlayerReady = (typeof playerReady == "function") ? playerReady : undefined;
	playerReady = function(b) {
		var a = jwplayer.api.playerById(b.id);
		if (a) {
			a.playerReady(b)
		} else {
			jwplayer.api.selectPlayer(b.id).playerReady(b)
		}
		if (_userPlayerReady) {
			_userPlayerReady.call(this, b)
		}
	};
	(function(a) {
		a.embed = function(g) {
			var j = {
				width: 400,
				height: 300,
				components: {
					controlbar: {
						position: "over"
					}
				}
			};
			var f = a.utils.mediaparser.parseMedia(g.container);
			var e = new a.embed.config(a.utils.extend(j, f, g.config), this);
			var h = a.plugins.loadPlugins(g.id, e.plugins);

			function c(m, l) {
				for (var k in l) {
					if (typeof m[k] == "function") {
						(m[k]).call(m, l[k])
					}
				}
			}
			function d() {
				if (h.getStatus() == a.utils.loaderstatus.COMPLETE) {
					for (var m = 0; m < e.modes.length; m++) {
						if (e.modes[m].type && a.embed[e.modes[m].type]) {
							var k = e;
							if (e.modes[m].config) {
								k = a.utils.extend(a.utils.clone(e), e.modes[m].config)
							}
							var l = new a.embed[e.modes[m].type](document.getElementById(g.id), e.modes[m], k, h, g);
							if (l.supportsConfig()) {
								l.embed();
								c(g, e.events);
								return g
							}
						}
					}
					a.utils.log("No suitable players found");
					new a.embed.logo(a.utils.extend({
						hide: true
					}, e.components.logo), "none", g.id)
				}
			}
			h.addEventListener(a.events.COMPLETE, d);
			h.addEventListener(a.events.ERROR, d);
			h.load();
			return g
		};

		function b() {
			if (!document.body) {
				return setTimeout(b, 15)
			}
			var c = a.utils.selectors.getElementsByTagAndClass("video", "jwplayer");
			for (var d = 0; d < c.length; d++) {
				var e = c[d];
				a(e.id).setup({})
			}
		}
		b()
	})(jwplayer);
	(function(e) {
		function h() {
			return [{
				type: "flash",
				src: "/jwplayer/player.swf"
			}, {
				type: "html5"
			}, {
				type: "download"
			}]
		}
		var a = {
			players: "modes",
			autoplay: "autostart"
		};

		function b(n) {
			var m = n.toLowerCase();
			var l = ["left", "right", "top", "bottom"];
			for (var k = 0; k < l.length; k++) {
				if (m == l[k]) {
					return true
				}
			}
			return false
		}
		function c(l) {
			var k = false;
			k = (l instanceof Array) || (typeof l == "object" && !l.position && !l.size);
			return k
		}
		function j(k) {
			if (typeof k == "string") {
				if (parseInt(k).toString() == k || k.toLowerCase().indexOf("px") > -1) {
					return parseInt(k)
				}
			}
			return k
		}
		var g = ["playlist", "dock", "controlbar", "logo", "display"];

		function f(k) {
			var n = {};
			switch (e.utils.typeOf(k.plugins)) {
			case "object":
				for (var m in k.plugins) {
					n[e.utils.getPluginName(m)] = m
				}
				break;
			case "string":
				var o = k.plugins.split(",");
				for (var l = 0; l < o.length; l++) {
					n[e.utils.getPluginName(o[l])] = o[l]
				}
				break
			}
			return n
		}
		function d(o, n, m, k) {
			if (e.utils.typeOf(o[n]) != "object") {
				o[n] = {}
			}
			var l = o[n][m];
			if (e.utils.typeOf(l) != "object") {
				o[n][m] = l = {}
			}
			if (k) {
				if (n == "plugins") {
					var p = e.utils.getPluginName(m);
					l[k] = o[p + "." + k];
					delete o[p + "." + k]
				} else {
					l[k] = o[m + "." + k];
					delete o[m + "." + k]
				}
			}
		}
		e.embed.deserialize = function(l) {
			var m = f(l);
			for (var k in m) {
				d(l, "plugins", m[k])
			}
			for (var p in l) {
				if (p.indexOf(".") > -1) {
					var o = p.split(".");
					var n = o[0];
					var p = o[1];
					if (e.utils.isInArray(g, n)) {
						d(l, "components", n, p)
					} else {
						if (m[n]) {
							d(l, "plugins", m[n], p)
						}
					}
				}
			}
			return l
		};
		e.embed.config = function(k, u) {
			var t = e.utils.extend({}, k);
			var r;
			if (c(t.playlist)) {
				r = t.playlist;
				delete t.playlist
			}
			t = e.embed.deserialize(t);
			t.height = j(t.height);
			t.width = j(t.width);
			if (typeof t.plugins == "string") {
				var l = t.plugins.split(",");
				if (typeof t.plugins != "object") {
					t.plugins = {}
				}
				for (var p = 0; p < l.length; p++) {
					var q = e.utils.getPluginName(l[p]);
					if (typeof t[q] == "object") {
						t.plugins[l[p]] = t[q];
						delete t[q]
					} else {
						t.plugins[l[p]] = {}
					}
				}
			}
			for (var s = 0; s < g.length; s++) {
				var o = g[s];
				if (e.utils.exists(t[o])) {
					if (typeof t[o] != "object") {
						if (!t.components[o]) {
							t.components[o] = {}
						}
						if (o == "logo") {
							t.components[o].file = t[o]
						} else {
							t.components[o].position = t[o]
						}
						delete t[o]
					} else {
						if (!t.components[o]) {
							t.components[o] = {}
						}
						e.utils.extend(t.components[o], t[o]);
						delete t[o]
					}
				}
				if (typeof t[o + "size"] != "undefined") {
					if (!t.components[o]) {
						t.components[o] = {}
					}
					t.components[o].size = t[o + "size"];
					delete t[o + "size"]
				}
			}
			if (typeof t.icons != "undefined") {
				if (!t.components.display) {
					t.components.display = {}
				}
				t.components.display.icons = t.icons;
				delete t.icons
			}
			for (var n in a) {
				if (t[n]) {
					if (!t[a[n]]) {
						t[a[n]] = t[n]
					}
					delete t[n]
				}
			}
			var m;
			if (t.flashplayer && !t.modes) {
				m = h();
				m[0].src = t.flashplayer;
				delete t.flashplayer
			} else {
				if (t.modes) {
					if (typeof t.modes == "string") {
						m = h();
						m[0].src = t.modes
					} else {
						if (t.modes instanceof Array) {
							m = t.modes
						} else {
							if (typeof t.modes == "object" && t.modes.type) {
								m = [t.modes]
							}
						}
					}
					delete t.modes
				} else {
					m = h()
				}
			}
			t.modes = m;
			if (r) {
				t.playlist = r
			}
			return t
		}
	})(jwplayer);
	(function(a) {
		a.embed.download = function(c, g, b, d, f) {
			this.embed = function() {
				var k = a.utils.extend({}, b);
				var q = {};
				var j = b.width ? b.width : 480;
				if (typeof j != "number") {
					j = parseInt(j, 10)
				}
				var m = b.height ? b.height : 320;
				if (typeof m != "number") {
					m = parseInt(m, 10)
				}
				var u, o, n;
				var s = {};
				if (b.playlist && b.playlist.length) {
					s.file = b.playlist[0].file;
					o = b.playlist[0].image;
					s.levels = b.playlist[0].levels
				} else {
					s.file = b.file;
					o = b.image;
					s.levels = b.levels
				}
				if (s.file) {
					u = s.file
				} else {
					if (s.levels && s.levels.length) {
						u = s.levels[0].file
					}
				}
				n = u ? "pointer" : "auto";
				var l = {
					display: {
						style: {
							cursor: n,
							width: j,
							height: m,
							backgroundColor: "#000",
							position: "relative",
							textDecoration: "none",
							border: "none",
							display: "block"
						}
					},
					display_icon: {
						style: {
							cursor: n,
							position: "absolute",
							display: u ? "block" : "none",
							top: 0,
							left: 0,
							border: 0,
							margin: 0,
							padding: 0,
							zIndex: 3,
							width: 50,
							height: 50,
							backgroundImage: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdJREFUeNrs18ENgjAYhmFouDOCcQJGcARHgE10BDcgTOIosAGwQOuPwaQeuFRi2p/3Sb6EC5L3QCxZBgAAAOCorLW1zMn65TrlkH4NcV7QNcUQt7Gn7KIhxA+qNIR81spOGkL8oFJDyLJRdosqKDDkK+iX5+d7huzwM40xptMQMkjIOeRGo+VkEVvIPfTGIpKASfYIfT9iCHkHrBEzf4gcUQ56aEzuGK/mw0rHpy4AAACAf3kJMACBxjAQNRckhwAAAABJRU5ErkJggg==)"
						}
					},
					display_iconBackground: {
						style: {
							cursor: n,
							position: "absolute",
							display: u ? "block" : "none",
							top: ((m - 50) / 2),
							left: ((j - 50) / 2),
							border: 0,
							width: 50,
							height: 50,
							margin: 0,
							padding: 0,
							zIndex: 2,
							backgroundImage: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEpJREFUeNrszwENADAIA7DhX8ENoBMZ5KR10EryckCJiIiIiIiIiIiIiIiIiIiIiIh8GmkRERERERERERERERERERERERGRHSPAAPlXH1phYpYaAAAAAElFTkSuQmCC)"
						}
					},
					display_image: {
						style: {
							width: j,
							height: m,
							display: o ? "block" : "none",
							position: "absolute",
							cursor: n,
							left: 0,
							top: 0,
							margin: 0,
							padding: 0,
							textDecoration: "none",
							zIndex: 1,
							border: "none"
						}
					}
				};
				var h = function(v, x, y) {
						var w = document.createElement(v);
						if (y) {
							w.id = y
						} else {
							w.id = c.id + "_jwplayer_" + x
						}
						a.utils.css(w, l[x].style);
						return w
					};
				q.display = h("a", "display", c.id);
				if (u) {
					q.display.setAttribute("href", a.utils.getAbsolutePath(u))
				}
				q.display_image = h("img", "display_image");
				q.display_image.setAttribute("alt", "Click to download...");
				if (o) {
					q.display_image.setAttribute("src", a.utils.getAbsolutePath(o))
				}
				if (true) {
					q.display_icon = h("div", "display_icon");
					q.display_iconBackground = h("div", "display_iconBackground");
					q.display.appendChild(q.display_image);
					q.display_iconBackground.appendChild(q.display_icon);
					q.display.appendChild(q.display_iconBackground)
				}
				_css = a.utils.css;
				_hide = function(v) {
					_css(v, {
						display: "none"
					})
				};

				function r(v) {
					_imageWidth = q.display_image.naturalWidth;
					_imageHeight = q.display_image.naturalHeight;
					t()
				}
				function t() {
					a.utils.stretch(a.utils.stretching.UNIFORM, q.display_image, j, m, _imageWidth, _imageHeight)
				}
				q.display_image.onerror = function(v) {
					_hide(q.display_image)
				};
				q.display_image.onload = r;
				c.parentNode.replaceChild(q.display, c);
				var p = (b.plugins && b.plugins.logo) ? b.plugins.logo : {};
				q.display.appendChild(new a.embed.logo(b.components.logo, "download", c.id));
				f.container = document.getElementById(f.id);
				f.setPlayer(q.display, "download")
			};
			this.supportsConfig = function() {
				if (b) {
					var j = a.utils.getFirstPlaylistItemFromConfig(b);
					if (typeof j.file == "undefined" && typeof j.levels == "undefined") {
						return true
					} else {
						if (j.file) {
							return e(j.file, j.provider, j.playlistfile)
						} else {
							if (j.levels && j.levels.length) {
								for (var h = 0; h < j.levels.length; h++) {
									if (j.levels[h].file && e(j.levels[h].file, j.provider, j.playlistfile)) {
										return true
									}
								}
							}
						}
					}
				} else {
					return true
				}
			};

			function e(j, l, h) {
				if (h) {
					return false
				}
				var k = ["image", "sound", "youtube", "http"];
				if (l && (k.toString().indexOf(l) > -1)) {
					return true
				}
				if (!l || (l && l == "video")) {
					var m = a.utils.extension(j);
					if (m && a.utils.extensionmap[m]) {
						return true
					}
				}
				return false
			}
		}
	})(jwplayer);
	(function(a) {
		a.embed.flash = function(f, g, l, e, j) {
			function m(o, n, p) {
				var q = document.createElement("param");
				q.setAttribute("name", n);
				q.setAttribute("value", p);
				o.appendChild(q)
			}
			function k(o, p, n) {
				return function(q) {
					if (n) {
						document.getElementById(j.id + "_wrapper").appendChild(p)
					}
					var s = document.getElementById(j.id).getPluginConfig("display");
					o.resize(s.width, s.height);
					var r = {
						left: s.x,
						top: s.y
					};
					a.utils.css(p, r)
				}
			}
			function d(p) {
				if (!p) {
					return {}
				}
				var r = {};
				for (var o in p) {
					var n = p[o];
					for (var q in n) {
						r[o + "." + q] = n[q]
					}
				}
				return r
			}
			function h(q, p) {
				if (q[p]) {
					var s = q[p];
					for (var o in s) {
						var n = s[o];
						if (typeof n == "string") {
							if (!q[o]) {
								q[o] = n
							}
						} else {
							for (var r in n) {
								if (!q[o + "." + r]) {
									q[o + "." + r] = n[r]
								}
							}
						}
					}
					delete q[p]
				}
			}
			function b(q) {
				if (!q) {
					return {}
				}
				var t = {},
					s = [];
				for (var n in q) {
					var p = a.utils.getPluginName(n);
					var o = q[n];
					s.push(n);
					for (var r in o) {
						t[p + "." + r] = o[r]
					}
				}
				t.plugins = s.join(",");
				return t
			}
			function c(p) {
				var n = p.netstreambasepath ? "" : "netstreambasepath=" + encodeURIComponent(window.location.href.split("#")[0]) + "&";
				for (var o in p) {
					if (typeof(p[o]) == "object") {
						n += o + "=" + encodeURIComponent("[[JSON]]" + a.utils.strings.jsonToString(p[o])) + "&"
					} else {
						n += o + "=" + encodeURIComponent(p[o]) + "&"
					}
				}
				return n.substring(0, n.length - 1)
			}
			this.embed = function() {
				l.id = j.id;
				var y;
				var q = a.utils.extend({}, l);
				var n = q.width;
				var w = q.height;
				if (f.id + "_wrapper" == f.parentNode.id) {
					y = document.getElementById(f.id + "_wrapper")
				} else {
					y = document.createElement("div");
					y.id = f.id + "_wrapper";
					a.utils.wrap(f, y);
					a.utils.css(y, {
						position: "relative",
						width: n,
						height: w
					})
				}
				var o = e.setupPlugins(j, q, k);
				if (o.length > 0) {
					a.utils.extend(q, b(o.plugins))
				} else {
					delete q.plugins
				}
				var r = ["height", "width", "modes", "events"];
				for (var u = 0; u < r.length; u++) {
					delete q[r[u]]
				}
				var p = "opaque";
				if (q.wmode) {
					p = q.wmode
				}
				h(q, "components");
				h(q, "providers");
				if (typeof q["dock.position"] != "undefined") {
					if (q["dock.position"].toString().toLowerCase() == "false") {
						q.dock = q["dock.position"];
						delete q["dock.position"]
					}
				}
				var x = "#000000";
				var t;
				if (a.utils.isIE()) {
					var v = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" bgcolor="' + x + '" width="100%" height="100%" id="' + f.id + '" name="' + f.id + '" tabindex=0"">';
					v += '<param name="movie" value="' + g.src + '">';
					v += '<param name="allowfullscreen" value="true">';
					v += '<param name="allowscriptaccess" value="always">';
					v += '<param name="seamlesstabbing" value="true">';
					v += '<param name="wmode" value="' + p + '">';
					v += '<param name="flashvars" value="' + c(q) + '">';
					v += "</object>";
					a.utils.setOuterHTML(f, v);
					t = document.getElementById(f.id)
				} else {
					var s = document.createElement("object");
					s.setAttribute("type", "application/x-shockwave-flash");
					s.setAttribute("data", g.src);
					s.setAttribute("width", "100%");
					s.setAttribute("height", "100%");
					s.setAttribute("bgcolor", "#000000");
					s.setAttribute("id", f.id);
					s.setAttribute("name", f.id);
					s.setAttribute("tabindex", 0);
					m(s, "allowfullscreen", "true");
					m(s, "allowscriptaccess", "always");
					m(s, "seamlesstabbing", "true");
					m(s, "wmode", p);
					m(s, "flashvars", c(q));
					f.parentNode.replaceChild(s, f);
					t = s
				}
				j.container = t;
				j.setPlayer(t, "flash")
			};
			this.supportsConfig = function() {
				if (a.utils.hasFlash()) {
					if (l) {
						var o = a.utils.getFirstPlaylistItemFromConfig(l);
						if (typeof o.file == "undefined" && typeof o.levels == "undefined") {
							return true
						} else {
							if (o.file) {
								return flashCanPlay(o.file, o.provider)
							} else {
								if (o.levels && o.levels.length) {
									for (var n = 0; n < o.levels.length; n++) {
										if (o.levels[n].file && flashCanPlay(o.levels[n].file, o.provider)) {
											return true
										}
									}
								}
							}
						}
					} else {
						return true
					}
				}
				return false
			};
			flashCanPlay = function(n, p) {
				var o = ["video", "http", "sound", "image"];
				if (p && (o.toString().indexOf(p < 0))) {
					return true
				}
				var q = a.utils.extension(n);
				if (!q) {
					return true
				}
				if (a.utils.exists(a.utils.extensionmap[q]) && !a.utils.exists(a.utils.extensionmap[q].flash)) {
					return false
				}
				return true
			}
		}
	})(jwplayer);
	(function(a) {
		a.embed.html5 = function(c, g, b, d, f) {
			function e(j, k, h) {
				return function(l) {
					var m = document.getElementById(c.id + "_displayarea");
					if (h) {
						m.appendChild(k)
					}
					var n = m.style;
					j.resize(parseInt(n.width.replace("px", "")), parseInt(n.height.replace("px", "")));
					k.left = n.left;
					k.top = n.top
				}
			}
			this.embed = function() {
				if (a.html5) {
					d.setupPlugins(f, b, e);
					c.innerHTML = "";
					var j = a.utils.extend({
						screencolor: "0x000000"
					}, b);
					var h = ["plugins", "modes", "events"];
					for (var k = 0; k < h.length; k++) {
						delete j[h[k]]
					}
					if (j.levels && !j.sources) {
						j.sources = b.levels
					}
					if (j.skin && j.skin.toLowerCase().indexOf(".zip") > 0) {
						j.skin = j.skin.replace(/\.zip/i, ".xml")
					}
					var l = new(a.html5(c)).setup(j);
					f.container = document.getElementById(f.id);
					f.setPlayer(l, "html5")
				} else {
					return null
				}
			};
			this.supportsConfig = function() {
				if ( !! a.vid.canPlayType) {
					if (b) {
						var j = a.utils.getFirstPlaylistItemFromConfig(b);
						if (typeof j.file == "undefined" && typeof j.levels == "undefined") {
							return true
						} else {
							if (j.file) {
								return html5CanPlay(a.vid, j.file, j.provider, j.playlistfile)
							} else {
								if (j.levels && j.levels.length) {
									for (var h = 0; h < j.levels.length; h++) {
										if (j.levels[h].file && html5CanPlay(a.vid, j.levels[h].file, j.provider, j.playlistfile)) {
											return true
										}
									}
								}
							}
						}
					} else {
						return true
					}
				}
				return false
			};
			html5CanPlay = function(k, j, l, h) {
				if (h) {
					return false
				}
				if (l && l == "youtube") {
					return true
				}
				if (l && l != "video" && l != "http" && l != "sound") {
					return false
				}
				var m = a.utils.extension(j);
				if (!a.utils.exists(m) || !a.utils.exists(a.utils.extensionmap[m])) {
					return true
				}
				if (!a.utils.exists(a.utils.extensionmap[m].html5)) {
					return false
				}
				if (a.utils.isLegacyAndroid() && m.match(/m4v|mp4/)) {
					return true
				}
				return browserCanPlay(k, a.utils.extensionmap[m].html5)
			};
			browserCanPlay = function(j, h) {
				if (!h) {
					return true
				}
				if (j.canPlayType(h)) {
					return true
				} else {
					if (h == "audio/mp3" && navigator.userAgent.match(/safari/i)) {
						return j.canPlayType("audio/mpeg")
					} else {
						return false
					}
				}
			}
		}
	})(jwplayer);
	(function(a) {
		a.embed.logo = function(m, l, d) {
			var j = {
				prefix: "http://l.longtailvideo.com/" + l + "/",
				file: "logo.png",
				link: "http://www.longtailvideo.com/players/jw-flv-player/",
				margin: 8,
				out: 0.5,
				over: 1,
				timeout: 5,
				hide: false,
				position: "bottom-left"
			};
			_css = a.utils.css;
			var b;
			var h;
			k();

			function k() {
				o();
				c();
				f()
			}
			function o() {
				if (j.prefix) {
					var q = a.version.split(/\W/).splice(0, 2).join("/");
					if (j.prefix.indexOf(q) < 0) {
						j.prefix += q + "/"
					}
				}
				h = a.utils.extend({}, j)
			}
			function p() {
				var s = {
					border: "none",
					textDecoration: "none",
					position: "absolute",
					cursor: "pointer",
					zIndex: 10
				};
				s.display = h.hide ? "none" : "block";
				var r = h.position.toLowerCase().split("-");
				for (var q in r) {
					s[r[q]] = h.margin
				}
				return s
			}
			function c() {
				b = document.createElement("img");
				b.id = d + "_jwplayer_logo";
				b.style.display = "none";
				b.onload = function(q) {
					_css(b, p());
					e()
				};
				if (!h.file) {
					return
				}
				if (h.file.indexOf("http://") === 0) {
					b.src = h.file
				} else {
					b.src = h.prefix + h.file
				}
			}
			if (!h.file) {
				return
			}
			function f() {
				if (h.link) {
					b.onmouseover = g;
					b.onmouseout = e;
					b.onclick = n
				} else {
					this.mouseEnabled = false
				}
			}
			function n(q) {
				if (typeof q != "undefined") {
					q.preventDefault();
					q.stopPropagation()
				}
				if (h.link) {
					window.open(h.link, "_blank")
				}
				return
			}
			function e(q) {
				if (h.link) {
					b.style.opacity = h.out
				}
				return
			}
			function g(q) {
				if (h.hide) {
					b.style.opacity = h.over
				}
				return
			}
			return b
		}
	})(jwplayer);
	(function(a) {
		a.html5 = function(b) {
			var c = b;
			this.setup = function(d) {
				a.utils.extend(this, new a.html5.api(c, d));
				return this
			};
			return this
		}
	})(jwplayer);
	(function(b) {
		var d = b.utils;
		var c = d.css;
		b.html5.view = function(r, q, f) {
			var u = r;
			var n = q;
			var x = f;
			var w;
			var g;
			var C;
			var s;
			var D;
			var p;
			var A;

			function z() {
				w = document.createElement("div");
				w.id = n.id;
				w.className = n.className;
				_videowrapper = document.createElement("div");
				_videowrapper.id = w.id + "_video_wrapper";
				n.id = w.id + "_video";
				c(w, {
					position: "relative",
					height: x.height,
					width: x.width,
					padding: 0,
					backgroundColor: E(),
					zIndex: 0
				});

				function E() {
					if (u.skin.getComponentSettings("display") && u.skin.getComponentSettings("display").backgroundcolor) {
						return u.skin.getComponentSettings("display").backgroundcolor
					}
					return parseInt("000000", 16)
				}
				c(n, {
					width: x.width,
					height: x.height,
					top: 0,
					left: 0,
					zIndex: 1,
					margin: "auto",
					display: "block"
				});
				c(_videowrapper, {
					overflow: "hidden",
					position: "absolute",
					top: 0,
					left: 0,
					bottom: 0,
					right: 0
				});
				d.wrap(n, w);
				d.wrap(n, _videowrapper);
				s = document.createElement("div");
				s.id = w.id + "_displayarea";
				w.appendChild(s)
			}
			function k() {
				for (var E = 0; E < x.plugins.order.length; E++) {
					var F = x.plugins.order[E];
					if (d.exists(x.plugins.object[F].getDisplayElement)) {
						x.plugins.object[F].height = d.parseDimension(x.plugins.object[F].getDisplayElement().style.height);
						x.plugins.object[F].width = d.parseDimension(x.plugins.object[F].getDisplayElement().style.width);
						x.plugins.config[F].currentPosition = x.plugins.config[F].position
					}
				}
				v()
			}
			function m(E) {
				c(s, {
					display: x.getMedia().hasChrome() ? "none" : "block"
				})
			}
			function v(F) {
				var H = x.getMedia() ? x.getMedia().getDisplayElement() : null;
				if (d.exists(H)) {
					if (A != H) {
						if (A && A.parentNode) {
							A.parentNode.replaceChild(H, A)
						}
						A = H
					}
					for (var E = 0; E < x.plugins.order.length; E++) {
						var G = x.plugins.order[E];
						if (d.exists(x.plugins.object[G].getDisplayElement)) {
							x.plugins.config[G].currentPosition = x.plugins.config[G].position
						}
					}
				}
				j(x.width, x.height)
			}
			this.setup = function() {
				if (x && x.getMedia()) {
					n = x.getMedia().getDisplayElement()
				}
				z();
				k();
				u.jwAddEventListener(b.api.events.JWPLAYER_PLAYER_STATE, m);
				u.jwAddEventListener(b.api.events.JWPLAYER_MEDIA_LOADED, v);
				u.jwAddEventListener(b.api.events.JWPLAYER_MEDIA_META, function() {
					y()
				});
				var E;
				if (d.exists(window.onresize)) {
					E = window.onresize
				}
				window.onresize = function(F) {
					if (d.exists(E)) {
						try {
							E(F)
						} catch (H) {}
					}
					if (u.jwGetFullscreen()) {
						var G = document.body.getBoundingClientRect();
						x.width = Math.abs(G.left) + Math.abs(G.right);
						x.height = window.innerHeight
					}
					j(x.width, x.height)
				}
			};

			function h(E) {
				switch (E.keyCode) {
				case 27:
					if (u.jwGetFullscreen()) {
						u.jwSetFullscreen(false)
					}
					break;
				case 32:
					if (u.jwGetState() != b.api.events.state.IDLE && u.jwGetState() != b.api.events.state.PAUSED) {
						u.jwPause()
					} else {
						u.jwPlay()
					}
					break
				}
			}
			function j(H, E) {
				if (w.style.display == "none") {
					return
				}
				var G = [].concat(x.plugins.order);
				G.reverse();
				D = G.length + 2;
				if (!x.fullscreen) {
					x.width = H;
					x.height = E;
					g = H;
					C = E;
					c(s, {
						top: 0,
						bottom: 0,
						left: 0,
						right: 0,
						width: H,
						height: E,
						position: "relative"
					});
					c(w, {
						height: C,
						width: g
					});
					var F = o(t, G);
					if (F.length > 0) {
						D += F.length;
						var J = F.indexOf("playlist"),
							I = F.indexOf("controlbar");
						if (J >= 0 && I >= 0) {
							F[J] = F.splice(I, 1, F[J])[0]
						}
						o(l, F, true)
					}
				} else {
					if (!(navigator && navigator.vendor && navigator.vendor.indexOf("Apple") == 0)) {
						o(B, G, true)
					}
				}
				y()
			}
			function o(J, G, H) {
				var F = [];
				for (var E = 0; E < G.length; E++) {
					var K = G[E];
					if (d.exists(x.plugins.object[K].getDisplayElement)) {
						if (x.plugins.config[K].currentPosition != b.html5.view.positions.NONE) {
							var I = J(K, D--);
							if (!I) {
								F.push(K)
							} else {
								x.plugins.object[K].resize(I.width, I.height);
								if (H) {
									delete I.width;
									delete I.height
								}
								c(x.plugins.object[K].getDisplayElement(), I)
							}
						} else {
							c(x.plugins.object[K].getDisplayElement(), {
								display: "none"
							})
						}
					}
				}
				return F
			}
			function t(F, G) {
				if (d.exists(x.plugins.object[F].getDisplayElement)) {
					if (x.plugins.config[F].position && a(x.plugins.config[F].position)) {
						if (!d.exists(x.plugins.object[F].getDisplayElement().parentNode)) {
							w.appendChild(x.plugins.object[F].getDisplayElement())
						}
						var E = e(F);
						E.zIndex = G;
						return E
					}
				}
				return false
			}
			function l(G, H) {
				if (!d.exists(x.plugins.object[G].getDisplayElement().parentNode)) {
					s.appendChild(x.plugins.object[G].getDisplayElement())
				}
				var E = x.width,
					F = x.height;
				if (typeof x.width == "string" && x.width.lastIndexOf("%") > -1) {
					percentage = parseFloat(x.width.substring(0, x.width.lastIndexOf("%"))) / 100;
					E = Math.round(window.innerWidth * percentage)
				}
				if (typeof x.height == "string" && x.height.lastIndexOf("%") > -1) {
					percentage = parseFloat(x.height.substring(0, x.height.lastIndexOf("%"))) / 100;
					F = Math.round(window.innerHeight * percentage)
				}
				return {
					position: "absolute",
					width: (E - d.parseDimension(s.style.left) - d.parseDimension(s.style.right)),
					height: (F - d.parseDimension(s.style.top) - d.parseDimension(s.style.bottom)),
					zIndex: H
				}
			}
			function B(E, F) {
				return {
					position: "fixed",
					width: x.width,
					height: x.height,
					zIndex: F
				}
			}
			function y() {
				if (!d.exists(x.getMedia())) {
					return
				}
				s.style.position = "absolute";
				var H = x.getMedia().getDisplayElement();
				if (H && H.tagName.toLowerCase() == "video") {
					H.style.position = "absolute";
					var E, I;
					if (s.style.width.toString().lastIndexOf("%") > -1 || s.style.width.toString().lastIndexOf("%") > -1) {
						var F = s.getBoundingClientRect();
						E = Math.abs(F.left) + Math.abs(F.right);
						I = Math.abs(F.top) + Math.abs(F.bottom)
					} else {
						E = d.parseDimension(s.style.width);
						I = d.parseDimension(s.style.height)
					}
					if (H.parentNode) {
						H.parentNode.style.left = s.style.left;
						H.parentNode.style.top = s.style.top
					}
					d.stretch(u.jwGetStretching(), H, E, I, H.videoWidth ? H.videoWidth : 400, H.videoHeight ? H.videoHeight : 300)
				} else {
					var G = x.plugins.object.display.getDisplayElement();
					if (G) {
						x.getMedia().resize(d.parseDimension(G.style.width), d.parseDimension(G.style.height))
					} else {
						x.getMedia().resize(d.parseDimension(s.style.width), d.parseDimension(s.style.height))
					}
				}
			}
			function e(F) {
				var G = {
					position: "absolute",
					margin: 0,
					padding: 0,
					top: null
				};
				var E = x.plugins.config[F].currentPosition.toLowerCase();
				switch (E.toUpperCase()) {
				case b.html5.view.positions.TOP:
					G.top = d.parseDimension(s.style.top);
					G.left = d.parseDimension(s.style.left);
					G.width = g - d.parseDimension(s.style.left) - d.parseDimension(s.style.right);
					G.height = x.plugins.object[F].height;
					s.style[E] = d.parseDimension(s.style[E]) + x.plugins.object[F].height + "px";
					s.style.height = d.parseDimension(s.style.height) - G.height + "px";
					break;
				case b.html5.view.positions.RIGHT:
					G.top = d.parseDimension(s.style.top);
					G.right = d.parseDimension(s.style.right);
					G.width = x.plugins.object[F].width;
					G.height = C - d.parseDimension(s.style.top) - d.parseDimension(s.style.bottom);
					s.style[E] = d.parseDimension(s.style[E]) + x.plugins.object[F].width + "px";
					s.style.width = d.parseDimension(s.style.width) - G.width + "px";
					break;
				case b.html5.view.positions.BOTTOM:
					G.bottom = d.parseDimension(s.style.bottom);
					G.left = d.parseDimension(s.style.left);
					G.width = g - d.parseDimension(s.style.left) - d.parseDimension(s.style.right);
					G.height = x.plugins.object[F].height;
					s.style[E] = d.parseDimension(s.style[E]) + x.plugins.object[F].height + "px";
					s.style.height = d.parseDimension(s.style.height) - G.height + "px";
					break;
				case b.html5.view.positions.LEFT:
					G.top = d.parseDimension(s.style.top);
					G.left = d.parseDimension(s.style.left);
					G.width = x.plugins.object[F].width;
					G.height = C - d.parseDimension(s.style.top) - d.parseDimension(s.style.bottom);
					s.style[E] = d.parseDimension(s.style[E]) + x.plugins.object[F].width + "px";
					s.style.width = d.parseDimension(s.style.width) - G.width + "px";
					break;
				default:
					break
				}
				return G
			}
			this.resize = j;
			this.fullscreen = function(H) {
				if (navigator && navigator.vendor && navigator.vendor.indexOf("Apple") === 0) {
					if (x.getMedia().getDisplayElement().webkitSupportsFullscreen) {
						if (H) {
							try {
								x.getMedia().getDisplayElement().webkitEnterFullscreen()
							} catch (G) {}
						} else {
							try {
								x.getMedia().getDisplayElement().webkitExitFullscreen()
							} catch (G) {}
						}
					}
				} else {
					if (H) {
						document.onkeydown = h;
						clearInterval(p);
						var F = document.body.getBoundingClientRect();
						x.width = Math.abs(F.left) + Math.abs(F.right);
						x.height = window.innerHeight;
						var E = {
							position: "fixed",
							width: "100%",
							height: "100%",
							top: 0,
							left: 0,
							zIndex: 2147483000
						};
						c(w, E);
						E.zIndex = 1;
						if (x.getMedia() && x.getMedia().getDisplayElement()) {
							c(x.getMedia().getDisplayElement(), E)
						}
						E.zIndex = 2;
						c(s, E)
					} else {
						document.onkeydown = "";
						x.width = g;
						x.height = C;
						c(w, {
							position: "relative",
							height: x.height,
							width: x.width,
							zIndex: 0
						})
					}
					j(x.width, x.height)
				}
			}
		};

		function a(e) {
			return ([b.html5.view.positions.TOP, b.html5.view.positions.RIGHT, b.html5.view.positions.BOTTOM, b.html5.view.positions.LEFT].toString().indexOf(e.toUpperCase()) > -1)
		}
		b.html5.view.positions = {
			TOP: "TOP",
			RIGHT: "RIGHT",
			BOTTOM: "BOTTOM",
			LEFT: "LEFT",
			OVER: "OVER",
			NONE: "NONE"
		}
	})(jwplayer);
	(function(a) {
		var b = {
			backgroundcolor: "",
			margin: 10,
			font: "Arial,sans-serif",
			fontsize: 10,
			fontcolor: parseInt("000000", 16),
			fontstyle: "normal",
			fontweight: "bold",
			buttoncolor: parseInt("ffffff", 16),
			position: a.html5.view.positions.BOTTOM,
			idlehide: false,
			layout: {
				left: {
					position: "left",
					elements: [{
						name: "play",
						type: "button"
					}, {
						name: "divider",
						type: "divider"
					}, {
						name: "prev",
						type: "button"
					}, {
						name: "divider",
						type: "divider"
					}, {
						name: "next",
						type: "button"
					}, {
						name: "divider",
						type: "divider"
					}, {
						name: "elapsed",
						type: "text"
					}]
				},
				center: {
					position: "center",
					elements: [{
						name: "time",
						type: "slider"
					}]
				},
				right: {
					position: "right",
					elements: [{
						name: "duration",
						type: "text"
					}, {
						name: "blank",
						type: "button"
					}, {
						name: "divider",
						type: "divider"
					}, {
						name: "mute",
						type: "button"
					}, {
						name: "volume",
						type: "slider"
					}, {
						name: "divider",
						type: "divider"
					}, {
						name: "fullscreen",
						type: "button"
					}]
				}
			}
		};
		_utils = a.utils;
		_css = _utils.css;
		_hide = function(c) {
			_css(c, {
				display: "none"
			})
		};
		_show = function(c) {
			_css(c, {
				display: "block"
			})
		};
		a.html5.controlbar = function(l, V) {
			var k = l;
			var D = _utils.extend({}, b, k.skin.getComponentSettings("controlbar"), V);
			if (D.position == a.html5.view.positions.NONE || typeof a.html5.view.positions[D.position] == "undefined") {
				return
			}
			if (_utils.mapLength(k.skin.getComponentLayout("controlbar")) > 0) {
				D.layout = k.skin.getComponentLayout("controlbar")
			}
			var ac;
			var P;
			var ab;
			var E;
			var v = "none";
			var g;
			var j;
			var ad;
			var f;
			var e;
			var y;
			var Q = {};
			var p = false;
			var c = {};
			var Y;
			var h = false;
			var o;
			var d;
			var S = false;
			var G = false;
			var W = new a.html5.eventdispatcher();
			_utils.extend(this, W);

			function J() {
				if (!Y) {
					Y = k.skin.getSkinElement("controlbar", "background");
					if (!Y) {
						Y = {
							width: 0,
							height: 0,
							src: null
						}
					}
				}
				return Y
			}
			function N() {
				ab = 0;
				E = 0;
				P = 0;
				if (!p) {
					var ak = {
						height: J().height,
						backgroundColor: D.backgroundcolor
					};
					ac = document.createElement("div");
					ac.id = k.id + "_jwplayer_controlbar";
					_css(ac, ak)
				}
				var aj = (k.skin.getSkinElement("controlbar", "capLeft"));
				var ai = (k.skin.getSkinElement("controlbar", "capRight"));
				if (aj) {
					x("capLeft", "left", false, ac)
				}
				var al = {
					position: "absolute",
					height: J().height,
					left: (aj ? aj.width : 0),
					zIndex: 0
				};
				Z("background", ac, al, "img");
				if (J().src) {
					Q.background.src = J().src
				}
				al.zIndex = 1;
				Z("elements", ac, al);
				if (ai) {
					x("capRight", "right", false, ac)
				}
			}
			this.getDisplayElement = function() {
				return ac
			};
			this.resize = function(ak, ai) {
				_utils.cancelAnimation(ac);
				document.getElementById(k.id).onmousemove = A;
				e = ak;
				y = ai;
				if (G != k.jwGetFullscreen()) {
					G = k.jwGetFullscreen();
					d = undefined
				}
				var aj = w();
				A();
				I({
					id: k.id,
					duration: ad,
					position: j
				});
				u({
					id: k.id,
					bufferPercent: f
				});
				return aj
			};
			this.show = function() {
				if (h) {
					h = false;
					_show(ac);
					T()
				}
			};
			this.hide = function() {
				if (!h) {
					h = true;
					_hide(ac);
					aa()
				}
			};

			function q() {
				var aj = ["timeSlider", "volumeSlider", "timeSliderRail", "volumeSliderRail"];
				for (var ak in aj) {
					var ai = aj[ak];
					if (typeof Q[ai] != "undefined") {
						c[ai] = Q[ai].getBoundingClientRect()
					}
				}
			}
			function A(ai) {
				if (h) {
					return
				}
				if (D.position == a.html5.view.positions.OVER || k.jwGetFullscreen()) {
					clearTimeout(o);
					switch (k.jwGetState()) {
					case a.api.events.state.PAUSED:
					case a.api.events.state.IDLE:
						if (!D.idlehide || _utils.exists(ai)) {
							U()
						}
						if (D.idlehide) {
							o = setTimeout(function() {
								z()
							}, 2000)
						}
						break;
					default:
						if (ai) {
							U()
						}
						o = setTimeout(function() {
							z()
						}, 2000);
						break
					}
				}
			}
			function z(ai) {
				aa();
				_utils.cancelAnimation(ac);
				_utils.fadeTo(ac, 0, 0.1, 1, 0)
			}
			function U() {
				T();
				_utils.cancelAnimation(ac);
				_utils.fadeTo(ac, 1, 0, 1, 0)
			}
			function H(ai) {
				return function() {
					if (S && d != ai) {
						d = ai;
						W.sendEvent(ai, {
							component: "controlbar",
							boundingRect: O()
						})
					}
				}
			}
			var T = H(a.api.events.JWPLAYER_COMPONENT_SHOW);
			var aa = H(a.api.events.JWPLAYER_COMPONENT_HIDE);

			function O() {
				if (D.position == a.html5.view.positions.OVER || k.jwGetFullscreen()) {
					return _utils.getDimensions(ac)
				} else {
					return {
						x: 0,
						y: 0,
						width: 0,
						height: 0
					}
				}
			}
			function Z(am, al, ak, ai) {
				var aj;
				if (!p) {
					if (!ai) {
						ai = "div"
					}
					aj = document.createElement(ai);
					Q[am] = aj;
					aj.id = ac.id + "_" + am;
					al.appendChild(aj)
				} else {
					aj = document.getElementById(ac.id + "_" + am)
				}
				if (_utils.exists(ak)) {
					_css(aj, ak)
				}
				return aj
			}
			function M() {
				ah(D.layout.left);
				ah(D.layout.right, -1);
				ah(D.layout.center)
			}
			function ah(al, ai) {
				var am = al.position == "right" ? "right" : "left";
				var ak = _utils.extend([], al.elements);
				if (_utils.exists(ai)) {
					ak.reverse()
				}
				for (var aj = 0; aj < ak.length; aj++) {
					C(ak[aj], am)
				}
			}
			function K() {
				return P++
			}
			function C(am, ao) {
				var al, aj, ak, ai, aq;
				if (am.type == "divider") {
					x("divider" + K(), ao, true, undefined, undefined, am.width, am.element);
					return
				}
				switch (am.name) {
				case "play":
					x("playButton", ao, false);
					x("pauseButton", ao, true);
					R("playButton", "jwPlay");
					R("pauseButton", "jwPause");
					break;
				case "prev":
					x("prevButton", ao, true);
					R("prevButton", "jwPlaylistPrev");
					break;
				case "stop":
					x("stopButton", ao, true);
					R("stopButton", "jwStop");
					break;
				case "next":
					x("nextButton", ao, true);
					R("nextButton", "jwPlaylistNext");
					break;
				case "elapsed":
					x("elapsedText", ao, true);
					break;
				case "time":
					aj = !_utils.exists(k.skin.getSkinElement("controlbar", "timeSliderCapLeft")) ? 0 : k.skin.getSkinElement("controlbar", "timeSliderCapLeft").width;
					ak = !_utils.exists(k.skin.getSkinElement("controlbar", "timeSliderCapRight")) ? 0 : k.skin.getSkinElement("controlbar", "timeSliderCapRight").width;
					al = ao == "left" ? aj : ak;
					ai = k.skin.getSkinElement("controlbar", "timeSliderRail").width + aj + ak;
					aq = {
						height: J().height,
						position: "absolute",
						top: 0,
						width: ai
					};
					aq[ao] = ao == "left" ? ab : E;
					var an = Z("timeSlider", Q.elements, aq);
					x("timeSliderCapLeft", ao, true, an, ao == "left" ? 0 : al);
					x("timeSliderRail", ao, false, an, al);
					x("timeSliderBuffer", ao, false, an, al);
					x("timeSliderProgress", ao, false, an, al);
					x("timeSliderThumb", ao, false, an, al);
					x("timeSliderCapRight", ao, true, an, ao == "right" ? 0 : al);
					X("time");
					break;
				case "fullscreen":
					x("fullscreenButton", ao, false);
					x("normalscreenButton", ao, true);
					R("fullscreenButton", "jwSetFullscreen", true);
					R("normalscreenButton", "jwSetFullscreen", false);
					break;
				case "volume":
					aj = !_utils.exists(k.skin.getSkinElement("controlbar", "volumeSliderCapLeft")) ? 0 : k.skin.getSkinElement("controlbar", "volumeSliderCapLeft").width;
					ak = !_utils.exists(k.skin.getSkinElement("controlbar", "volumeSliderCapRight")) ? 0 : k.skin.getSkinElement("controlbar", "volumeSliderCapRight").width;
					al = ao == "left" ? aj : ak;
					ai = k.skin.getSkinElement("controlbar", "volumeSliderRail").width + aj + ak;
					aq = {
						height: J().height,
						position: "absolute",
						top: 0,
						width: ai
					};
					aq[ao] = ao == "left" ? ab : E;
					var ap = Z("volumeSlider", Q.elements, aq);
					x("volumeSliderCapLeft", ao, true, ap, ao == "left" ? 0 : al);
					x("volumeSliderRail", ao, true, ap, al);
					x("volumeSliderProgress", ao, false, ap, al);
					x("volumeSliderCapRight", ao, true, ap, ao == "right" ? 0 : al);
					X("volume");
					break;
				case "mute":
					x("muteButton", ao, false);
					x("unmuteButton", ao, true);
					R("muteButton", "jwSetMute", true);
					R("unmuteButton", "jwSetMute", false);
					break;
				case "duration":
					x("durationText", ao, true);
					break
				}
			}
			function x(al, ao, aj, ar, am, ai, ak) {
				if (_utils.exists(k.skin.getSkinElement("controlbar", al)) || al.indexOf("Text") > 0 || al.indexOf("divider") === 0) {
					var an = {
						height: J().height,
						position: "absolute",
						display: "block",
						top: 0
					};
					if ((al.indexOf("next") === 0 || al.indexOf("prev") === 0) && k.jwGetPlaylist().length < 2) {
						aj = false;
						an.display = "none"
					}
					var at;
					if (al.indexOf("Text") > 0) {
						al.innerhtml = "00:00";
						an.font = D.fontsize + "px/" + (J().height + 1) + "px " + D.font;
						an.color = D.fontcolor;
						an.textAlign = "center";
						an.fontWeight = D.fontweight;
						an.fontStyle = D.fontstyle;
						an.cursor = "default";
						at = 14 + 3 * D.fontsize
					} else {
						if (al.indexOf("divider") === 0) {
							if (ai) {
								if (!isNaN(parseInt(ai))) {
									at = parseInt(ai)
								}
							} else {
								if (ak) {
									var ap = k.skin.getSkinElement("controlbar", ak);
									if (ap) {
										an.background = "url(" + ap.src + ") repeat-x center left";
										at = ap.width
									}
								} else {
									an.background = "url(" + k.skin.getSkinElement("controlbar", "divider").src + ") repeat-x center left";
									at = k.skin.getSkinElement("controlbar", "divider").width
								}
							}
						} else {
							an.background = "url(" + k.skin.getSkinElement("controlbar", al).src + ") repeat-x center left";
							at = k.skin.getSkinElement("controlbar", al).width
						}
					}
					if (ao == "left") {
						an.left = isNaN(am) ? ab : am;
						if (aj) {
							ab += at
						}
					} else {
						if (ao == "right") {
							an.right = isNaN(am) ? E : am;
							if (aj) {
								E += at
							}
						}
					}
					if (_utils.typeOf(ar) == "undefined") {
						ar = Q.elements
					}
					an.width = at;
					if (p) {
						_css(Q[al], an)
					} else {
						var aq = Z(al, ar, an);
						if (_utils.exists(k.skin.getSkinElement("controlbar", al + "Over"))) {
							aq.onmouseover = function(au) {
								aq.style.backgroundImage = ["url(", k.skin.getSkinElement("controlbar", al + "Over").src, ")"].join("")
							};
							aq.onmouseout = function(au) {
								aq.style.backgroundImage = ["url(", k.skin.getSkinElement("controlbar", al).src, ")"].join("")
							}
						}
					}
				}
			}
			function F() {
				k.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED, B);
				k.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM, s);
				k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_BUFFER, u);
				k.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE, r);
				k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_TIME, I);
				k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_MUTE, ag);
				k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_VOLUME, m);
				k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_COMPLETE, L)
			}
			function B() {
				N();
				M();
				w();
				ae()
			}
			function s(ai) {
				ad = k.jwGetPlaylist()[ai.index].duration;
				I({
					id: k.id,
					duration: ad,
					position: 0
				});
				u({
					id: k.id,
					bufferProgress: 0
				})
			}
			function ae() {
				I({
					id: k.id,
					duration: k.jwGetDuration(),
					position: 0
				});
				u({
					id: k.id,
					bufferProgress: 0
				});
				ag({
					id: k.id,
					mute: k.jwGetMute()
				});
				r({
					id: k.id,
					newstate: a.api.events.state.IDLE
				});
				m({
					id: k.id,
					volume: k.jwGetVolume()
				})
			}
			function R(ak, al, aj) {
				if (p) {
					return
				}
				if (_utils.exists(k.skin.getSkinElement("controlbar", ak))) {
					var ai = Q[ak];
					if (_utils.exists(ai)) {
						_css(ai, {
							cursor: "pointer"
						});
						if (al == "fullscreen") {
							ai.onmouseup = function(am) {
								am.stopPropagation();
								k.jwSetFullscreen(!k.jwGetFullscreen())
							}
						} else {
							ai.onmouseup = function(am) {
								am.stopPropagation();
								if (_utils.exists(aj)) {
									k[al](aj)
								} else {
									k[al]()
								}
							}
						}
					}
				}
			}
			function X(ai) {
				if (p) {
					return
				}
				var aj = Q[ai + "Slider"];
				_css(Q.elements, {
					cursor: "pointer"
				});
				_css(aj, {
					cursor: "pointer"
				});
				aj.onmousedown = function(ak) {
					v = ai
				};
				aj.onmouseup = function(ak) {
					ak.stopPropagation();
					af(ak.pageX)
				};
				aj.onmousemove = function(ak) {
					if (v == "time") {
						g = true;
						var al = ak.pageX - c[ai + "Slider"].left - window.pageXOffset;
						_css(Q.timeSliderThumb, {
							left: al
						})
					}
				}
			}
			function af(aj) {
				g = false;
				var ai;
				if (v == "time") {
					ai = aj - c.timeSliderRail.left + window.pageXOffset;
					var al = ai / c.timeSliderRail.width * ad;
					if (al < 0) {
						al = 0
					} else {
						if (al > ad) {
							al = ad - 3
						}
					}
					if (k.jwGetState() == a.api.events.state.PAUSED || k.jwGetState() == a.api.events.state.IDLE) {
						k.jwPlay()
					}
					k.jwSeek(al)
				} else {
					if (v == "volume") {
						ai = aj - c.volumeSliderRail.left - window.pageXOffset;
						var ak = Math.round(ai / c.volumeSliderRail.width * 100);
						if (ak < 0) {
							ak = 0
						} else {
							if (ak > 100) {
								ak = 100
							}
						}
						if (k.jwGetMute()) {
							k.jwSetMute(false)
						}
						k.jwSetVolume(ak)
					}
				}
				v = "none"
			}
			function u(aj) {
				if (_utils.exists(aj.bufferPercent)) {
					f = aj.bufferPercent
				}
				if (c.timeSliderRail) {
					var ak = c.timeSliderRail.width;
					var ai = isNaN(Math.round(ak * f / 100)) ? 0 : Math.round(ak * f / 100);
					_css(Q.timeSliderBuffer, {
						width: ai
					})
				}
			}
			function ag(ai) {
				if (ai.mute) {
					_hide(Q.muteButton);
					_show(Q.unmuteButton);
					_hide(Q.volumeSliderProgress)
				} else {
					_show(Q.muteButton);
					_hide(Q.unmuteButton);
					_show(Q.volumeSliderProgress)
				}
			}
			function r(ai) {
				if (ai.newstate == a.api.events.state.BUFFERING || ai.newstate == a.api.events.state.PLAYING) {
					_show(Q.pauseButton);
					_hide(Q.playButton)
				} else {
					_hide(Q.pauseButton);
					_show(Q.playButton)
				}
				A();
				if (ai.newstate == a.api.events.state.IDLE) {
					_hide(Q.timeSliderBuffer);
					_hide(Q.timeSliderProgress);
					_hide(Q.timeSliderThumb);
					I({
						id: k.id,
						duration: k.jwGetDuration(),
						position: 0
					})
				} else {
					_show(Q.timeSliderBuffer);
					if (ai.newstate != a.api.events.state.BUFFERING) {
						_show(Q.timeSliderProgress);
						_show(Q.timeSliderThumb)
					}
				}
			}
			function L(ai) {
				u({
					bufferPercent: 0
				});
				I(_utils.extend(ai, {
					position: 0,
					duration: ad
				}))
			}
			function I(al) {
				if (_utils.exists(al.position)) {
					j = al.position
				}
				if (_utils.exists(al.duration)) {
					ad = al.duration
				}
				var aj = (j === ad === 0) ? 0 : j / ad;
				var am = c.timeSliderRail;
				if (am) {
					var ai = isNaN(Math.round(am.width * aj)) ? 0 : Math.round(am.width * aj);
					var ak = ai;
					if (Q.timeSliderProgress) {
						Q.timeSliderProgress.style.width = ai + "px";
						if (!g) {
							if (Q.timeSliderThumb) {
								Q.timeSliderThumb.style.left = ak + "px"
							}
						}
					}
				}
				if (Q.durationText) {
					Q.durationText.innerHTML = _utils.timeFormat(ad)
				}
				if (Q.elapsedText) {
					Q.elapsedText.innerHTML = _utils.timeFormat(j)
				}
			}
			function n() {
				var am, aj;
				var ak = document.getElementById(ac.id + "_elements");
				if (!ak) {
					return
				}
				var al = ak.childNodes;
				for (var ai in ak.childNodes) {
					if (isNaN(parseInt(ai, 10))) {
						continue
					}
					if (al[ai].id.indexOf(ac.id + "_divider") === 0 && aj && aj.id.indexOf(ac.id + "_divider") === 0 && al[ai].style.backgroundImage == aj.style.backgroundImage) {
						al[ai].style.display = "none"
					} else {
						if (al[ai].id.indexOf(ac.id + "_divider") === 0 && am && am.style.display != "none") {
							al[ai].style.display = "block"
						}
					}
					if (al[ai].style.display != "none") {
						aj = al[ai]
					}
					am = al[ai]
				}
			}
			function w() {
				n();
				if (k.jwGetFullscreen()) {
					_show(Q.normalscreenButton);
					_hide(Q.fullscreenButton)
				} else {
					_hide(Q.normalscreenButton);
					_show(Q.fullscreenButton)
				}
				var aj = {
					width: e
				};
				var ai = {};
				if (D.position == a.html5.view.positions.OVER || k.jwGetFullscreen()) {
					aj.left = D.margin;
					aj.width -= 2 * D.margin;
					aj.top = y - J().height - D.margin;
					aj.height = J().height
				}
				var al = k.skin.getSkinElement("controlbar", "capLeft");
				var ak = k.skin.getSkinElement("controlbar", "capRight");
				ai.left = al ? al.width : 0;
				ai.width = aj.width - ai.left - (ak ? ak.width : 0);
				var am = !_utils.exists(k.skin.getSkinElement("controlbar", "timeSliderCapLeft")) ? 0 : k.skin.getSkinElement("controlbar", "timeSliderCapLeft").width;
				_css(Q.timeSliderRail, {
					width: (ai.width - ab - E),
					left: am
				});
				if (_utils.exists(Q.timeSliderCapRight)) {
					_css(Q.timeSliderCapRight, {
						left: am + (ai.width - ab - E)
					})
				}
				_css(ac, aj);
				_css(Q.elements, ai);
				_css(Q.background, ai);
				q();
				return aj
			}
			function m(am) {
				if (_utils.exists(Q.volumeSliderRail)) {
					var ak = isNaN(am.volume / 100) ? 1 : am.volume / 100;
					var al = _utils.parseDimension(Q.volumeSliderRail.style.width);
					var ai = isNaN(Math.round(al * ak)) ? 0 : Math.round(al * ak);
					var an = _utils.parseDimension(Q.volumeSliderRail.style.right);
					var aj = (!_utils.exists(k.skin.getSkinElement("controlbar", "volumeSliderCapLeft"))) ? 0 : k.skin.getSkinElement("controlbar", "volumeSliderCapLeft").width;
					_css(Q.volumeSliderProgress, {
						width: ai,
						left: aj
					});
					if (_utils.exists(Q.volumeSliderCapLeft)) {
						_css(Q.volumeSliderCapLeft, {
							left: 0
						})
					}
				}
			}
			function t() {
				N();
				M();
				q();
				p = true;
				F();
				D.idlehide = (D.idlehide.toString().toLowerCase() == "true");
				if (D.position == a.html5.view.positions.OVER && D.idlehide) {
					ac.style.opacity = 0;
					S = true
				} else {
					setTimeout((function() {
						S = true;
						T()
					}), 1)
				}
				ae()
			}
			t();
			return this
		}
	})(jwplayer);
	(function(b) {
		var a = ["width", "height", "state", "playlist", "item", "position", "buffer", "duration", "volume", "mute", "fullscreen"];
		var c = b.utils;
		b.html5.controller = function(z, w, h, v) {
			var C = z;
			var G = h;
			var g = v;
			var o = w;
			var J = true;
			var e = -1;
			var A = c.exists(G.config.debug) && (G.config.debug.toString().toLowerCase() == "console");
			var m = new b.html5.eventdispatcher(o.id, A);
			c.extend(this, m);
			var E = [];
			var d = false;

			function r(M) {
				if (d) {
					m.sendEvent(M.type, M)
				} else {
					E.push(M)
				}
			}
			function K(M) {
				if (!d) {
					m.sendEvent(b.api.events.JWPLAYER_READY, M);
					if (b.utils.exists(window.playerReady)) {
						playerReady(M)
					}
					if (b.utils.exists(window[h.config.playerReady])) {
						window[h.config.playerReady](M)
					}
					while (E.length > 0) {
						var O = E.shift();
						m.sendEvent(O.type, O)
					}
					if (h.config.autostart && !b.utils.isIOS()) {
						t(G.item)
					}
					while (p.length > 0) {
						var N = p.shift();
						x(N.method, N.arguments)
					}
					d = true
				}
			}
			G.addGlobalListener(r);
			G.addEventListener(b.api.events.JWPLAYER_MEDIA_BUFFER_FULL, function() {
				G.getMedia().play()
			});
			G.addEventListener(b.api.events.JWPLAYER_MEDIA_TIME, function(M) {
				if (M.position >= G.playlist[G.item].start && e >= 0) {
					G.playlist[G.item].start = e;
					e = -1
				}
			});
			G.addEventListener(b.api.events.JWPLAYER_MEDIA_COMPLETE, function(M) {
				setTimeout(s, 25)
			});

			function u() {
				try {
					f(G.item);
					if (G.playlist[G.item].levels[0].file.length > 0) {
						if (J || G.state == b.api.events.state.IDLE) {
							G.getMedia().load(G.playlist[G.item]);
							J = false
						} else {
							if (G.state == b.api.events.state.PAUSED) {
								G.getMedia().play()
							}
						}
					}
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function I() {
				try {
					if (G.playlist[G.item].levels[0].file.length > 0) {
						switch (G.state) {
						case b.api.events.state.PLAYING:
						case b.api.events.state.BUFFERING:
							G.getMedia().pause();
							break
						}
					}
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function D(M) {
				try {
					if (G.playlist[G.item].levels[0].file.length > 0) {
						if (typeof M != "number") {
							M = parseFloat(M)
						}
						switch (G.state) {
						case b.api.events.state.IDLE:
							if (e < 0) {
								e = G.playlist[G.item].start;
								G.playlist[G.item].start = M
							}
							u();
							break;
						case b.api.events.state.PLAYING:
						case b.api.events.state.PAUSED:
						case b.api.events.state.BUFFERING:
							G.seek(M);
							break
						}
					}
					return true
				} catch (N) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, N)
				}
				return false
			}
			function n(M) {
				if (!c.exists(M)) {
					M = true
				}
				try {
					G.getMedia().stop(M);
					return true
				} catch (N) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, N)
				}
				return false
			}
			function k() {
				try {
					if (G.playlist[G.item].levels[0].file.length > 0) {
						if (G.config.shuffle) {
							f(y())
						} else {
							if (G.item + 1 == G.playlist.length) {
								f(0)
							} else {
								f(G.item + 1)
							}
						}
					}
					if (G.state != b.api.events.state.IDLE) {
						var N = G.state;
						G.state = b.api.events.state.IDLE;
						m.sendEvent(b.api.events.JWPLAYER_PLAYER_STATE, {
							oldstate: N,
							newstate: b.api.events.state.IDLE
						})
					}
					u();
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function j() {
				try {
					if (G.playlist[G.item].levels[0].file.length > 0) {
						if (G.config.shuffle) {
							f(y())
						} else {
							if (G.item === 0) {
								f(G.playlist.length - 1)
							} else {
								f(G.item - 1)
							}
						}
					}
					if (G.state != b.api.events.state.IDLE) {
						var N = G.state;
						G.state = b.api.events.state.IDLE;
						m.sendEvent(b.api.events.JWPLAYER_PLAYER_STATE, {
							oldstate: N,
							newstate: b.api.events.state.IDLE
						})
					}
					u();
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function y() {
				var M = null;
				if (G.playlist.length > 1) {
					while (!c.exists(M)) {
						M = Math.floor(Math.random() * G.playlist.length);
						if (M == G.item) {
							M = null
						}
					}
				} else {
					M = 0
				}
				return M
			}
			function t(N) {
				if (!G.playlist || !G.playlist[N]) {
					return false
				}
				try {
					if (G.playlist[N].levels[0].file.length > 0) {
						var O = G.state;
						if (O !== b.api.events.state.IDLE) {
							if (G.playlist[G.item].provider == G.playlist[N].provider) {
								n(false)
							} else {
								n()
							}
						}
						f(N);
						u()
					}
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function f(M) {
				if (!G.playlist[M]) {
					return
				}
				G.setActiveMediaProvider(G.playlist[M]);
				if (G.item != M) {
					G.item = M;
					J = true;
					m.sendEvent(b.api.events.JWPLAYER_PLAYLIST_ITEM, {
						index: M
					})
				}
			}
			function H(N) {
				try {
					f(G.item);
					var O = G.getMedia();
					switch (typeof(N)) {
					case "number":
						O.volume(N);
						break;
					case "string":
						O.volume(parseInt(N, 10));
						break
					}
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function q(N) {
				try {
					f(G.item);
					var O = G.getMedia();
					if (typeof N == "undefined") {
						O.mute(!G.mute)
					} else {
						if (N.toString().toLowerCase() == "true") {
							O.mute(true)
						} else {
							O.mute(false)
						}
					}
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function l(N, M) {
				try {
					G.width = N;
					G.height = M;
					g.resize(N, M);
					m.sendEvent(b.api.events.JWPLAYER_RESIZE, {
						width: G.width,
						height: G.height
					});
					return true
				} catch (O) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, O)
				}
				return false
			}
			function B(N) {
				try {
					if (typeof N == "undefined") {
						G.fullscreen = !G.fullscreen;
						g.fullscreen(!G.fullscreen)
					} else {
						if (N.toString().toLowerCase() == "true") {
							G.fullscreen = true;
							g.fullscreen(true)
						} else {
							G.fullscreen = false;
							g.fullscreen(false)
						}
					}
					m.sendEvent(b.api.events.JWPLAYER_RESIZE, {
						width: G.width,
						height: G.height
					});
					m.sendEvent(b.api.events.JWPLAYER_FULLSCREEN, {
						fullscreen: N
					});
					return true
				} catch (M) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, M)
				}
				return false
			}
			function L(M) {
				try {
					n();
					G.loadPlaylist(M);
					f(G.item);
					return true
				} catch (N) {
					m.sendEvent(b.api.events.JWPLAYER_ERROR, N)
				}
				return false
			}
			b.html5.controller.repeatoptions = {
				LIST: "LIST",
				ALWAYS: "ALWAYS",
				SINGLE: "SINGLE",
				NONE: "NONE"
			};

			function s() {
				switch (G.config.repeat.toUpperCase()) {
				case b.html5.controller.repeatoptions.SINGLE:
					u();
					break;
				case b.html5.controller.repeatoptions.ALWAYS:
					if (G.item == G.playlist.length - 1 && !G.config.shuffle) {
						t(0)
					} else {
						k()
					}
					break;
				case b.html5.controller.repeatoptions.LIST:
					if (G.item == G.playlist.length - 1 && !G.config.shuffle) {
						n();
						f(0)
					} else {
						k()
					}
					break;
				default:
					n();
					break
				}
			}
			var p = [];

			function F(M) {
				return function() {
					if (d) {
						x(M, arguments)
					} else {
						p.push({
							method: M,
							arguments: arguments
						})
					}
				}
			}
			function x(O, N) {
				var M = [];
				for (i = 0; i < N.length; i++) {
					M.push(N[i])
				}
				O.apply(this, M)
			}
			this.play = F(u);
			this.pause = F(I);
			this.seek = F(D);
			this.stop = F(n);
			this.next = F(k);
			this.prev = F(j);
			this.item = F(t);
			this.setVolume = F(H);
			this.setMute = F(q);
			this.resize = F(l);
			this.setFullscreen = F(B);
			this.load = F(L);
			this.playerReady = K
		}
	})(jwplayer);
	(function(a) {
		a.html5.defaultSkin = function() {
			this.text = '<?xml version="1.0" ?><skin author="LongTail Video" name="Five" version="1.0"><settings><setting name="backcolor" value="0xFFFFFF"/><setting name="frontcolor" value="0x000000"/><setting name="lightcolor" value="0x000000"/><setting name="screencolor" value="0x000000"/></settings><components><component name="controlbar"><settings><setting name="margin" value="20"/><setting name="fontsize" value="11"/></settings><elements><element name="background" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAIAAABvFaqvAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFJJREFUeNrslLENwAAIwxLU/09j5AiOgD5hVQzNAVY8JK4qEfHMIKBnd2+BQlBINaiRtL/aV2rdzYBsM6CIONbI1NZENTr3RwdB2PlnJgJ6BRgA4hwu5Qg5iswAAAAASUVORK5CYII="/><element name="capLeft" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="capRight" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="divider" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="playButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEhJREFUeNpiYqABYBo1dNRQ+hr6H4jvA3E8NS39j4SpZvh/LJig4YxEGEqy3kET+w+AOGFQRhTJhrEQkGcczfujhg4CQwECDADpTRWU/B3wHQAAAABJRU5ErkJggg=="/><element name="pauseButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAChJREFUeNpiYBgFo2DwA0YC8v/R1P4nRu+ooaOGUtnQUTAKhgIACDAAFCwQCfAJ4gwAAAAASUVORK5CYII="/><element name="prevButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEtJREFUeNpiYBgFo2Dog/9QDAPyQHweTYwiQ/2B+D0Wi8g2tB+JTdBQRiIMJVkvEy0iglhDF9Aq9uOpHVEwoE+NJDUKRsFgAAABBgDe2hqZcNNL0AAAAABJRU5ErkJggg=="/><element name="nextButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAElJREFUeNpiYBgFo2Dog/9AfB6I5dHE/lNqKAi/B2J/ahsKw/3EGMpIhKEk66WJoaR6fz61IyqemhEFSlL61ExSo2AUDAYAEGAAiG4hj+5t7M8AAAAASUVORK5CYII="/><element name="timeSliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADxJREFUeNpiYBgFo2AU0Bwwzluw+D8tLWARFhKiqQ9YuLg4aWsBGxs7bS1gZ6e5BWyjSX0UjIKhDgACDABlYQOGh5pYywAAAABJRU5ErkJggg=="/><element name="timeSliderBuffer" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD1JREFUeNpiYBgFo2AU0Bww1jc0/aelBSz8/Pw09QELOzs7bS1gY2OjrQWsrKy09gHraFIfBaNgqAOAAAMAvy0DChXHsZMAAAAASUVORK5CYII="/><element name="timeSliderProgress" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAClJREFUeNpiYBgFo2AU0BwwAvF/WlrARGsfjFow8BaMglEwCugAAAIMAOHfAQunR+XzAAAAAElFTkSuQmCC"/><element name="timeSliderThumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAICAYAAAA870V8AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABZJREFUeNpiZICA/yCCiQEJUJcDEGAAY0gBD1/m7Q0AAAAASUVORK5CYII="/><element name="muteButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADFJREFUeNpiYBgFIw3MB+L/5Gj8j6yRiRTFyICJXHfTXyMLAXlGati4YDRFDj8AEGAABk8GSqqS4CoAAAAASUVORK5CYII="/><element name="unmuteButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD1JREFUeNpiYBgFgxz8p7bm+cQa+h8LHy7GhEcjIz4bmAjYykiun/8j0fakGPIfTfPgiSr6aB4FVAcAAQYAWdwR1G1Wd2gAAAAASUVORK5CYII="/><element name="volumeSliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAYCAYAAADkgu3FAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAGpJREFUeNpi/P//PwM9ABMDncCoRYPfIqqDZcuW1UPp/6AUDcNM1DQYKtRAlaAj1mCSLSLXYIIWUctgDItoZfDA5aOoqKhGEANIM9LVR7SymGDQUctikuOIXkFNdhHEOFrDjlpEd4sAAgwAriRMub95fu8AAAAASUVORK5CYII="/><element name="volumeSliderProgress" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAYCAYAAADkgu3FAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFtJREFUeNpi/P//PwM9ABMDncCoRYPfIlqAeij9H5SiYZiqBqPTlFqE02BKLSLaYFItIttgQhZRzWB8FjENiuRJ7aAbsMQwYMl7wDIsWUUQ42gNO2oR3S0CCDAAKhKq6MLLn8oAAAAASUVORK5CYII="/><element name="fullscreenButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAE5JREFUeNpiYBgFo2DQA0YC8v/xqP1PjDlMRDrEgUgxkgHIlfZoriVGjmzLsLFHAW2D6D8eA/9Tw7L/BAwgJE90PvhPpNgoGAVDEQAEGAAMdhTyXcPKcAAAAABJRU5ErkJggg=="/><element name="normalscreenButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEZJREFUeNpiYBgFo2DIg/9UUkOUAf8JiFFsyX88fJyAkcQgYMQjNkzBoAgiezyRbE+tFGSPxQJ7auYBmma0UTAKBhgABBgAJAEY6zON61sAAAAASUVORK5CYII="/></elements></component><component name="display"><elements><element name="background" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEpJREFUeNrszwENADAIA7DhX8ENoBMZ5KR10EryckCJiIiIiIiIiIiIiIiIiIiIiIh8GmkRERERERERERERERERERERERGRHSPAAPlXH1phYpYaAAAAAElFTkSuQmCC"/><element name="playIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdJREFUeNrs18ENgjAYhmFouDOCcQJGcARHgE10BDcgTOIosAGwQOuPwaQeuFRi2p/3Sb6EC5L3QCxZBgAAAOCorLW1zMn65TrlkH4NcV7QNcUQt7Gn7KIhxA+qNIR81spOGkL8oFJDyLJRdosqKDDkK+iX5+d7huzwM40xptMQMkjIOeRGo+VkEVvIPfTGIpKASfYIfT9iCHkHrBEzf4gcUQ56aEzuGK/mw0rHpy4AAACAf3kJMACBxjAQNRckhwAAAABJRU5ErkJggg=="/><element name="muteIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNrs1jEOgCAMBVAg7t5/8qaoIy4uoobyXsLCxA+0NCUAAADGUWvdQoQ41x4ixNBB2hBvBskdD3w5ZCkl3+33VqI0kjBBlh9rp+uTcyOP33TnolfsU85XX3yIRpQph8ZQY3wTZtU5AACASA4BBgDHoVuY1/fvOQAAAABJRU5ErkJggg=="/><element name="errorIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAWlJREFUeNrsl+1twjAQhsHq/7BBYQLYIBmBDcoGMAIjtBPQTcII2SDtBDBBwrU6pGsUO7YbO470PtKJkz9iH++d4ywWAAAAAABgljRNsyWr2bZzDuJG1rLdZhcMbTjrBCGDyUKsqQLFciJb9bSvuG/WagRVRUVUI6gqy5HVeKWfSgRyJruKIU//TrZTSn2nmlaXThrloi/v9F2STC1W4+Aw5cBzkquRc09bofFNc6YLxEON0VUZS5FPTftO49vMjRsIF3RhOGr7/D/pJw+FKU+q0vDyq8W42jCunDqI3LC5XxNj2wHLU1XjaRnb0Lhykhqhhd8MtSF5J9tbjCv4mXGvKJz/65FF/qJryyaaIvzP2QRxZTX2nTuXjvV/VPFSwyLnW7mpH99yTh1FEVro6JBSd40/pMrRdV8vPtcKl28T2pT8TnFZ4yNosct3Q0io6JfBiz1FlGdqVQH3VHnepAEAAAAAADDzEGAAcTwB10jWgxcAAAAASUVORK5CYII="/><element name="bufferIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAuhJREFUeNrsWr9rU1EUznuNGqvFQh1ULOhiBx0KDtIuioO4pJuik3FxFfUPaAV1FTdx0Q5d2g4FFxehTnEpZHFoBy20tCIWtGq0TZP4HfkeHB5N8m6Sl/sa74XDybvv3vvOd8/Pe4lXrVZT3dD8VJc0B8QBcUAcEAfESktHGeR5XtMfqFQq/f92zPe/NbtGlKTdCY30kuxrpMGO94BlQCXs+rbh3ONgA6BlzP1p20d80gEI5hmA2A92Qua1Q2PtAFISM+bvjMG8U+Q7oA3rQGASwrYCU6WpNdLGYbA+Pq5jjXIiwi8EEa2UDbQSaKOIuV+SlkcCrfjY8XTI9EpKGwP0C2kru2hLtHqa4zoXtZRWyvi4CLwv9Opr6Hkn6A9HKgEANsQ1iqC3Ub/vRUk2JgmRkatK36kVrnt0qObunwUdUUMXMWYpakJsO5Am8tAw2GBIgwWA+G2S2dMpiw0gDioQRQJoKhRb1QiDwlHZUABYbaXWsm5ae6loTE4ZDxN4CZar8foVzOJ2iyZ2kWF3t7YIevffaMT5yJ70kQb2fQ1sE5SHr2wazs2wgMxgbsEKEAgxAvZUJbQLBGTSBMgNrncJbA6AljtS/eKDJ0Ez+DmrQEzXS2h1Ck25kAg0IZcUOaydCy4sYnN2fOA+2AP16gNoHALlQ+fwH7XO4CxLenUpgj4xr6ugY2roPMbMx+Xs18m/E8CVEIhxsNeg83XWOAN6grG3lGbk8uE5fr4B/WH3cJw+co/l9nTYsSGYCJ/lY5/qv0thn6nrIWmjeJcPSnWOeY++AkF8tpJHIMAUs/MaBBpj3znZfQo5psY+ZrG4gv5HickjEOymKjEeRpgyST6IuZcTcWbnjcgdPi5ghxciRKsl1lDSsgwA1i8fssonJgzmTSqfGUkCENndNdAL7PS6QQ7ZYISTo+1qq0LEWjTWcvY4isa4z+yfQB+7ooyHVg5RI7/i1Ijn/vnggDggDogD4oC00P4KMACd/juEHOrS4AAAAABJRU5ErkJggg=="/></elements></component><component name="dock"><elements><element name="button" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFBJREFUeNrs0cEJACAQA8Eofu0fu/W6EM5ZSAFDRpKTBs00CQQEBAQEBAQEBAQEBAQEBATkK8iqbY+AgICAgICAgICAgICAgICAgIC86QowAG5PAQzEJ0lKAAAAAElFTkSuQmCC"/></elements></component><component name="playlist"><elements><element name="item" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAIAAAC1nk4lAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNrs2NEJwCAMBcBYuv/CFuIE9VN47WWCR7iocXR3pdWdGPqqwIoMjYfQeAiNh9B4JHc6MHQVHnjggQceeOCBBx77TifyeOY0iHi8DqIdEY8dD5cL094eePzINB5CO/LwcOTptNB4CP25L4TIbZzpU7UEGAA5wz1uF5rF9AAAAABJRU5ErkJggg=="/><element name="sliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAA8CAIAAADpFA0BAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADhJREFUeNrsy6ENACAMAMHClp2wYxZLAg5Fcu9e3OjuOKqqfTMzbs14CIZhGIZhGIZhGP4VLwEGAK/BBnVFpB0oAAAAAElFTkSuQmCC"/><element name="sliderThumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAA8CAIAAADpFA0BAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADRJREFUeNrsy7ENACAMBLE8++8caFFKKiRffU53112SGs3ttOohGIZhGIZhGIZh+Fe8BRgAiaUGde6NOSEAAAAASUVORK5CYII="/></elements></component></components></skin>';
			this.xml = null;
			if (window.DOMParser) {
				parser = new DOMParser();
				this.xml = parser.parseFromString(this.text, "text/xml")
			} else {
				this.xml = new ActiveXObject("Microsoft.XMLDOM");
				this.xml.async = "false";
				this.xml.loadXML(this.text)
			}
			return this
		}
	})(jwplayer);
	(function(a) {
		_utils = a.utils;
		_css = _utils.css;
		_hide = function(b) {
			_css(b, {
				display: "none"
			})
		};
		_show = function(b) {
			_css(b, {
				display: "block"
			})
		};
		a.html5.display = function(k, G) {
			var j = {
				icons: true,
				showmute: false
			};
			var Q = _utils.extend({}, j, G);
			var h = k;
			var P = {};
			var e;
			var u;
			var w;
			var N;
			var s;
			var I;
			var A;
			var J = !_utils.exists(h.skin.getComponentSettings("display").bufferrotation) ? 15 : parseInt(h.skin.getComponentSettings("display").bufferrotation, 10);
			var q = !_utils.exists(h.skin.getComponentSettings("display").bufferinterval) ? 100 : parseInt(h.skin.getComponentSettings("display").bufferinterval, 10);
			var z = -1;
			var t = "";
			var K = true;
			var d;
			var g = false;
			var n = false;
			var H = new a.html5.eventdispatcher();
			_utils.extend(this, H);
			var D = {
				display: {
					style: {
						cursor: "pointer",
						top: 0,
						left: 0,
						overflow: "hidden"
					},
					click: m
				},
				display_icon: {
					style: {
						cursor: "pointer",
						position: "absolute",
						top: ((h.skin.getSkinElement("display", "background").height - h.skin.getSkinElement("display", "playIcon").height) / 2),
						left: ((h.skin.getSkinElement("display", "background").width - h.skin.getSkinElement("display", "playIcon").width) / 2),
						border: 0,
						margin: 0,
						padding: 0,
						zIndex: 3,
						display: "none"
					}
				},
				display_iconBackground: {
					style: {
						cursor: "pointer",
						position: "absolute",
						top: ((u - h.skin.getSkinElement("display", "background").height) / 2),
						left: ((e - h.skin.getSkinElement("display", "background").width) / 2),
						border: 0,
						backgroundImage: (["url(", h.skin.getSkinElement("display", "background").src, ")"]).join(""),
						width: h.skin.getSkinElement("display", "background").width,
						height: h.skin.getSkinElement("display", "background").height,
						margin: 0,
						padding: 0,
						zIndex: 2,
						display: "none"
					}
				},
				display_image: {
					style: {
						display: "none",
						width: e,
						height: u,
						position: "absolute",
						cursor: "pointer",
						left: 0,
						top: 0,
						margin: 0,
						padding: 0,
						textDecoration: "none",
						zIndex: 1
					}
				},
				display_text: {
					style: {
						zIndex: 4,
						position: "relative",
						opacity: 0.8,
						backgroundColor: parseInt("000000", 16),
						color: parseInt("ffffff", 16),
						textAlign: "center",
						fontFamily: "Arial,sans-serif",
						padding: "0 5px",
						fontSize: 14
					}
				}
			};
			h.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE, p);
			h.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_MUTE, p);
			h.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM, p);
			h.jwAddEventListener(a.api.events.JWPLAYER_ERROR, o);
			L();

			function L() {
				P.display = C("div", "display");
				P.display_text = C("div", "display_text");
				P.display.appendChild(P.display_text);
				P.display_image = C("img", "display_image");
				P.display_image.onerror = function(R) {
					_hide(P.display_image)
				};
				P.display_image.onload = y;
				P.display_icon = C("div", "display_icon");
				P.display_iconBackground = C("div", "display_iconBackground");
				P.display.appendChild(P.display_image);
				P.display_iconBackground.appendChild(P.display_icon);
				P.display.appendChild(P.display_iconBackground);
				f();
				setTimeout((function() {
					n = true;
					if (Q.icons.toString() == "true") {
						F()
					}
				}), 1)
			}
			this.getDisplayElement = function() {
				return P.display
			};
			this.resize = function(S, R) {
				_css(P.display, {
					width: S,
					height: R
				});
				_css(P.display_text, {
					width: (S - 10),
					top: ((R - P.display_text.getBoundingClientRect().height) / 2)
				});
				_css(P.display_iconBackground, {
					top: ((R - h.skin.getSkinElement("display", "background").height) / 2),
					left: ((S - h.skin.getSkinElement("display", "background").width) / 2)
				});
				if (e != S || u != R) {
					e = S;
					u = R;
					d = undefined;
					F()
				}
				c();
				p({})
			};
			this.show = function() {
				if (g) {
					g = false;
					r(h.jwGetState())
				}
			};
			this.hide = function() {
				if (!g) {
					B();
					g = true
				}
			};

			function y(R) {
				w = P.display_image.naturalWidth;
				N = P.display_image.naturalHeight;
				c()
			}
			function c() {
				_utils.stretch(h.jwGetStretching(), P.display_image, e, u, w, N)
			}
			function C(R, T) {
				var S = document.createElement(R);
				S.id = h.id + "_jwplayer_" + T;
				_css(S, D[T].style);
				return S
			}
			function f() {
				for (var R in P) {
					if (_utils.exists(D[R].click)) {
						P[R].onclick = D[R].click
					}
				}
			}
			function m(R) {
				if (typeof R.preventDefault != "undefined") {
					R.preventDefault()
				} else {
					R.returnValue = false
				}
				if (h.jwGetState() != a.api.events.state.PLAYING) {
					h.jwPlay()
				} else {
					h.jwPause()
				}
			}
			function O(R) {
				if (A) {
					B();
					return
				}
				P.display_icon.style.backgroundImage = (["url(", h.skin.getSkinElement("display", R).src, ")"]).join("");
				_css(P.display_icon, {
					width: h.skin.getSkinElement("display", R).width,
					height: h.skin.getSkinElement("display", R).height,
					top: (h.skin.getSkinElement("display", "background").height - h.skin.getSkinElement("display", R).height) / 2,
					left: (h.skin.getSkinElement("display", "background").width - h.skin.getSkinElement("display", R).width) / 2
				});
				b();
				if (_utils.exists(h.skin.getSkinElement("display", R + "Over"))) {
					P.display_icon.onmouseover = function(S) {
						P.display_icon.style.backgroundImage = ["url(", h.skin.getSkinElement("display", R + "Over").src, ")"].join("")
					};
					P.display_icon.onmouseout = function(S) {
						P.display_icon.style.backgroundImage = ["url(", h.skin.getSkinElement("display", R).src, ")"].join("")
					}
				} else {
					P.display_icon.onmouseover = null;
					P.display_icon.onmouseout = null
				}
			}
			function B() {
				if (Q.icons.toString() == "true") {
					_hide(P.display_icon);
					_hide(P.display_iconBackground);
					M()
				}
			}
			function b() {
				if (!g && Q.icons.toString() == "true") {
					_show(P.display_icon);
					_show(P.display_iconBackground);
					F()
				}
			}
			function o(R) {
				A = true;
				B();
				P.display_text.innerHTML = R.error;
				_show(P.display_text);
				P.display_text.style.top = ((u - P.display_text.getBoundingClientRect().height) / 2) + "px"
			}
			function E() {
				P.display_image.style.display = "none"
			}
			function p(R) {
				if ((R.type == a.api.events.JWPLAYER_PLAYER_STATE || R.type == a.api.events.JWPLAYER_PLAYLIST_ITEM) && A) {
					A = false;
					_hide(P.display_text)
				}
				var S = h.jwGetState();
				if (S == t) {
					return
				}
				t = S;
				if (z >= 0) {
					clearTimeout(z)
				}
				if (K || h.jwGetState() == a.api.events.state.PLAYING || h.jwGetState() == a.api.events.state.PAUSED) {
					r(h.jwGetState())
				} else {
					z = setTimeout(l(h.jwGetState()), 500)
				}
			}
			function l(R) {
				return (function() {
					r(R)
				})
			}
			function r(R) {
				if (_utils.exists(I)) {
					clearInterval(I);
					I = null;
					_utils.animations.rotate(P.display_icon, 0)
				}
				switch (R) {
				case a.api.events.state.BUFFERING:
					if (_utils.isIOS()) {
						E();
						B()
					} else {
						if (h.jwGetPlaylist()[h.jwGetItem()].provider == "sound") {
							v()
						}
						s = 0;
						I = setInterval(function() {
							s += J;
							_utils.animations.rotate(P.display_icon, s % 360)
						}, q);
						O("bufferIcon");
						K = true
					}
					break;
				case a.api.events.state.PAUSED:
					if (!_utils.isIOS()) {
						if (h.jwGetPlaylist()[h.jwGetItem()].provider != "sound") {
							_css(P.display_image, {
								background: "transparent no-repeat center center"
							})
						}
						O("playIcon");
						K = true
					}
					break;
				case a.api.events.state.IDLE:
					if (h.jwGetPlaylist()[h.jwGetItem()] && h.jwGetPlaylist()[h.jwGetItem()].image) {
						v()
					} else {
						E()
					}
					O("playIcon");
					K = true;
					break;
				default:
					if (h.jwGetPlaylist()[h.jwGetItem()] && h.jwGetPlaylist()[h.jwGetItem()].provider == "sound") {
						if (_utils.isIOS()) {
							E();
							K = false
						} else {
							v()
						}
					} else {
						E();
						K = false
					}
					if (h.jwGetMute() && Q.showmute) {
						O("muteIcon")
					} else {
						B()
					}
					break
				}
				z = -1
			}
			function v() {
				if (h.jwGetPlaylist()[h.jwGetItem()] && h.jwGetPlaylist()[h.jwGetItem()].image) {
					_css(P.display_image, {
						display: "block"
					});
					P.display_image.src = _utils.getAbsolutePath(h.jwGetPlaylist()[h.jwGetItem()].image)
				}
			}
			function x(R) {
				return function() {
					if (!n) {
						return
					}
					if (!g && d != R) {
						d = R;
						H.sendEvent(R, {
							component: "display",
							boundingRect: _utils.getDimensions(P.display_iconBackground)
						})
					}
				}
			}
			var F = x(a.api.events.JWPLAYER_COMPONENT_SHOW);
			var M = x(a.api.events.JWPLAYER_COMPONENT_HIDE);
			return this
		}
	})(jwplayer);
	(function(a) {
		_css = a.utils.css;
		a.html5.dock = function(p, u) {
			function q() {
				return {
					align: a.html5.view.positions.RIGHT
				}
			}
			var k = a.utils.extend({}, q(), u);
			if (k.align == "FALSE") {
				return
			}
			var f = {};
			var s = [];
			var g;
			var v;
			var d = false;
			var t = false;
			var e = {
				x: 0,
				y: 0,
				width: 0,
				height: 0
			};
			var r;
			var j = new a.html5.eventdispatcher();
			_utils.extend(this, j);
			var m = document.createElement("div");
			m.id = p.id + "_jwplayer_dock";
			p.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE, l);
			this.getDisplayElement = function() {
				return m
			};
			this.setButton = function(A, x, y, z) {
				if (!x && f[A]) {
					a.utils.arrays.remove(s, A);
					m.removeChild(f[A].div);
					delete f[A]
				} else {
					if (x) {
						if (!f[A]) {
							f[A] = {}
						}
						f[A].handler = x;
						f[A].outGraphic = y;
						f[A].overGraphic = z;
						if (!f[A].div) {
							s.push(A);
							f[A].div = document.createElement("div");
							f[A].div.style.position = "relative";
							m.appendChild(f[A].div);
							f[A].div.appendChild(document.createElement("img"));
							f[A].div.childNodes[0].style.position = "absolute";
							f[A].div.childNodes[0].style.left = 0;
							f[A].div.childNodes[0].style.top = 0;
							f[A].div.childNodes[0].style.zIndex = 10;
							f[A].div.childNodes[0].style.cursor = "pointer";
							f[A].div.appendChild(document.createElement("img"));
							f[A].div.childNodes[1].style.position = "absolute";
							f[A].div.childNodes[1].style.left = 0;
							f[A].div.childNodes[1].style.top = 0;
							if (p.skin.getSkinElement("dock", "button")) {
								f[A].div.childNodes[1].src = p.skin.getSkinElement("dock", "button").src
							}
							f[A].div.childNodes[1].style.zIndex = 9;
							f[A].div.childNodes[1].style.cursor = "pointer";
							f[A].div.onmouseover = function() {
								if (f[A].overGraphic) {
									f[A].div.childNodes[0].src = f[A].overGraphic
								}
								if (p.skin.getSkinElement("dock", "buttonOver")) {
									f[A].div.childNodes[1].src = p.skin.getSkinElement("dock", "buttonOver").src
								}
							};
							f[A].div.onmouseout = function() {
								if (f[A].outGraphic) {
									f[A].div.childNodes[0].src = f[A].outGraphic
								}
								if (p.skin.getSkinElement("dock", "button")) {
									f[A].div.childNodes[1].src = p.skin.getSkinElement("dock", "button").src
								}
							};
							if (f[A].overGraphic) {
								f[A].div.childNodes[0].src = f[A].overGraphic
							}
							if (f[A].outGraphic) {
								f[A].div.childNodes[0].src = f[A].outGraphic
							}
							if (p.skin.getSkinElement("dock", "button")) {
								f[A].div.childNodes[1].src = p.skin.getSkinElement("dock", "button").src
							}
						}
						if (x) {
							f[A].div.onclick = function(B) {
								B.preventDefault();
								a(p.id).callback(A);
								if (f[A].overGraphic) {
									f[A].div.childNodes[0].src = f[A].overGraphic
								}
								if (p.skin.getSkinElement("dock", "button")) {
									f[A].div.childNodes[1].src = p.skin.getSkinElement("dock", "button").src
								}
							}
						}
					}
				}
				h(g, v)
			};

			function h(x, J) {
				if (s.length > 0) {
					var y = 10;
					var I = y;
					var F = -1;
					var G = p.skin.getSkinElement("dock", "button").height;
					var E = p.skin.getSkinElement("dock", "button").width;
					var C = x - E - y;
					var H, B;
					if (k.align == a.html5.view.positions.LEFT) {
						F = 1;
						C = y
					}
					for (var z = 0; z < s.length; z++) {
						var K = Math.floor(I / J);
						if ((I + G + y) > ((K + 1) * J)) {
							I = ((K + 1) * J) + y;
							K = Math.floor(I / J)
						}
						var A = f[s[z]].div;
						A.style.top = (I % J) + "px";
						A.style.left = (C + (p.skin.getSkinElement("dock", "button").width + y) * K * F) + "px";
						var D = {
							x: a.utils.parseDimension(A.style.left),
							y: a.utils.parseDimension(A.style.top),
							width: E,
							height: G
						};
						if (!H || (D.x <= H.x && D.y <= H.y)) {
							H = D
						}
						if (!B || (D.x >= B.x && D.y >= B.y)) {
							B = D
						}
						I += p.skin.getSkinElement("dock", "button").height + y
					}
					e = {
						x: H.x,
						y: H.y,
						width: B.x - H.x + B.width,
						height: H.y - B.y + B.height
					}
				}
				if (t != p.jwGetFullscreen() || g != x || v != J) {
					g = x;
					v = J;
					t = p.jwGetFullscreen();
					r = undefined;
					setTimeout(n, 1)
				}
			}
			function b(x) {
				return function() {
					if (!d && r != x && s.length > 0) {
						r = x;
						j.sendEvent(x, {
							component: "dock",
							boundingRect: e
						})
					}
				}
			}
			function l(x) {
				if (a.utils.isIOS()) {
					switch (x.newstate) {
					case a.api.events.state.IDLE:
						o();
						break;
					default:
						c();
						break
					}
				}
			}
			var n = b(a.api.events.JWPLAYER_COMPONENT_SHOW);
			var w = b(a.api.events.JWPLAYER_COMPONENT_HIDE);
			this.resize = h;
			var o = function() {
					_css(m, {
						display: "block"
					});
					if (d) {
						d = false;
						n()
					}
				};
			var c = function() {
					_css(m, {
						display: "none"
					});
					if (!d) {
						w();
						d = true
					}
				};
			this.hide = c;
			this.show = o;
			return this
		}
	})(jwplayer);
	(function(a) {
		a.html5.eventdispatcher = function(d, b) {
			var c = new a.events.eventdispatcher(b);
			a.utils.extend(this, c);
			this.sendEvent = function(e, f) {
				if (!a.utils.exists(f)) {
					f = {}
				}
				a.utils.extend(f, {
					id: d,
					version: a.version,
					type: e
				});
				c.sendEvent(e, f)
			}
		}
	})(jwplayer);
	(function(a) {
		var b = {
			prefix: "http://l.longtailvideo.com/html5/",
			file: "logo.png",
			link: "http://www.longtailvideo.com/players/jw-flv-player/",
			margin: 8,
			out: 0.5,
			over: 1,
			timeout: 5,
			hide: true,
			position: "bottom-left"
		};
		_css = a.utils.css;
		a.html5.logo = function(n, r) {
			var q = n;
			var u;
			var d;
			var t;
			var h = false;
			g();

			function g() {
				o();
				c();
				l()
			}
			function o() {
				if (b.prefix) {
					var v = n.version.split(/\W/).splice(0, 2).join("/");
					if (b.prefix.indexOf(v) < 0) {
						b.prefix += v + "/"
					}
				}
				if (r.position == a.html5.view.positions.OVER) {
					r.position = b.position
				}
				d = a.utils.extend({}, b)
			}
			function c() {
				t = document.createElement("img");
				t.id = q.id + "_jwplayer_logo";
				t.style.display = "none";
				t.onload = function(v) {
					_css(t, k());
					q.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE, j);
					p()
				};
				if (!d.file) {
					return
				}
				if (d.file.indexOf("http://") === 0) {
					t.src = d.file
				} else {
					t.src = d.prefix + d.file
				}
			}
			if (!d.file) {
				return
			}
			this.resize = function(w, v) {};
			this.getDisplayElement = function() {
				return t
			};

			function l() {
				if (d.link) {
					t.onmouseover = f;
					t.onmouseout = p;
					t.onclick = s
				} else {
					this.mouseEnabled = false
				}
			}
			function s(v) {
				if (typeof v != "undefined") {
					v.stopPropagation()
				}
				if (!h) {
					return
				}
				q.jwPause();
				q.jwSetFullscreen(false);
				if (d.link) {
					window.open(d.link, "_top")
				}
				return
			}
			function p(v) {
				if (d.link && h) {
					t.style.opacity = d.out
				}
				return
			}
			function f(v) {
				if (d.hide.toString() == "true" && h) {
					t.style.opacity = d.over
				}
				return
			}
			function k() {
				var x = {
					textDecoration: "none",
					position: "absolute",
					cursor: "pointer"
				};
				x.display = (d.hide.toString() == "true") ? "none" : "block";
				var w = d.position.toLowerCase().split("-");
				for (var v in w) {
					x[w[v]] = d.margin
				}
				return x
			}
			function m() {
				if (d.hide.toString() == "true") {
					t.style.display = "block";
					t.style.opacity = 0;
					a.utils.fadeTo(t, d.out, 0.1, parseFloat(t.style.opacity));
					u = setTimeout(function() {
						e()
					}, d.timeout * 1000)
				}
				h = true
			}
			function e() {
				h = false;
				if (d.hide.toString() == "true") {
					a.utils.fadeTo(t, 0, 0.1, parseFloat(t.style.opacity))
				}
			}
			function j(v) {
				if (v.newstate == a.api.events.state.BUFFERING) {
					clearTimeout(u);
					m()
				}
			}
			return this
		}
	})(jwplayer);
	(function(a) {
		var c = {
			ended: a.api.events.state.IDLE,
			playing: a.api.events.state.PLAYING,
			pause: a.api.events.state.PAUSED,
			buffering: a.api.events.state.BUFFERING
		};
		var e = a.utils;
		var b = e.css;
		var d = e.isIOS();
		a.html5.mediavideo = function(h, s) {
			var r = {
				abort: n,
				canplay: k,
				canplaythrough: k,
				durationchange: G,
				emptied: n,
				ended: k,
				error: u,
				loadeddata: G,
				loadedmetadata: G,
				loadstart: k,
				pause: k,
				play: n,
				playing: k,
				progress: v,
				ratechange: n,
				seeked: k,
				seeking: k,
				stalled: k,
				suspend: k,
				timeupdate: D,
				volumechange: n,
				waiting: k,
				canshowcurrentframe: n,
				dataunavailable: n,
				empty: n,
				load: z,
				loadedfirstframe: n
			};
			var j = new a.html5.eventdispatcher();
			e.extend(this, j);
			var y = h,
				l = s,
				m, B, A, x, f, H = false,
				C, p, q;
			o();
			this.load = function(J, K) {
				if (typeof K == "undefined") {
					K = true
				}
				x = J;
				e.empty(m);
				q = 0;
				if (J.levels && J.levels.length > 0) {
					if (J.levels.length == 1) {
						m.src = J.levels[0].file
					} else {
						if (m.src) {
							m.removeAttribute("src")
						}
						for (var I = 0; I < J.levels.length; I++) {
							var L = m.ownerDocument.createElement("source");
							L.src = J.levels[I].file;
							m.appendChild(L);
							q++
						}
					}
				} else {
					m.src = J.file
				}
				if (d) {
					if (J.image) {
						m.poster = J.image
					}
					m.controls = "controls";
					m.style.display = "block"
				}
				C = p = A = false;
				y.buffer = 0;
				if (!e.exists(J.start)) {
					J.start = 0
				}
				y.duration = J.duration;
				j.sendEvent(a.api.events.JWPLAYER_MEDIA_LOADED);
				if ((!d && J.levels.length == 1) || !H) {
					m.load()
				}
				H = false;
				if (K) {
					E(a.api.events.state.BUFFERING);
					j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER, {
						bufferPercent: 0
					});
					this.play()
				}
			};
			this.play = function() {
				if (B != a.api.events.state.PLAYING) {
					t();
					if (p) {
						E(a.api.events.state.PLAYING)
					} else {
						E(a.api.events.state.BUFFERING)
					}
					m.play()
				}
			};
			this.pause = function() {
				m.pause();
				E(a.api.events.state.PAUSED)
			};
			this.seek = function(I) {
				if (!(y.duration <= 0 || isNaN(y.duration)) && !(y.position <= 0 || isNaN(y.position))) {
					m.currentTime = I;
					m.play()
				}
			};
			_stop = this.stop = function(I) {
				if (!e.exists(I)) {
					I = true
				}
				g();
				if (I) {
					m.style.display = "none";
					p = false;
					var J = navigator.userAgent;
					if (J.match(/chrome/i)) {
						m.src = undefined
					} else {
						if (J.match(/safari/i)) {
							m.removeAttribute("src")
						} else {
							m.src = ""
						}
					}
					m.removeAttribute("controls");
					m.removeAttribute("poster");
					e.empty(m);
					m.load();
					H = true;
					if (m.webkitSupportsFullscreen) {
						try {
							m.webkitExitFullscreen()
						} catch (K) {}
					}
				}
				E(a.api.events.state.IDLE)
			};
			this.fullscreen = function(I) {
				if (I === true) {
					this.resize("100%", "100%")
				} else {
					this.resize(y.config.width, y.config.height)
				}
			};
			this.resize = function(J, I) {
				if (false) {
					b(l, {
						width: J,
						height: I
					})
				}
				j.sendEvent(a.api.events.JWPLAYER_MEDIA_RESIZE, {
					fullscreen: y.fullscreen,
					width: J,
					hieght: I
				})
			};
			this.volume = function(I) {
				if (!d) {
					m.volume = I / 100;
					y.volume = I;
					j.sendEvent(a.api.events.JWPLAYER_MEDIA_VOLUME, {
						volume: Math.round(I)
					})
				}
			};
			this.mute = function(I) {
				if (!d) {
					m.muted = I;
					y.mute = I;
					j.sendEvent(a.api.events.JWPLAYER_MEDIA_MUTE, {
						mute: I
					})
				}
			};
			this.getDisplayElement = function() {
				return m
			};
			this.hasChrome = function() {
				return false
			};

			function o() {
				m = document.createElement("video");
				B = a.api.events.state.IDLE;
				for (var I in r) {
					m.addEventListener(I, function(J) {
						if (e.exists(J.target.parentNode)) {
							r[J.type](J)
						}
					}, true)
				}
				m.setAttribute("x-webkit-airplay", "allow");
				if (l.parentNode) {
					l.parentNode.replaceChild(m, l)
				}
				if (!m.id) {
					m.id = l.id
				}
			}
			function E(I) {
				if (I == a.api.events.state.PAUSED && B == a.api.events.state.IDLE) {
					return
				}
				if (B != I) {
					var J = B;
					y.state = B = I;
					j.sendEvent(a.api.events.JWPLAYER_PLAYER_STATE, {
						oldstate: J,
						newstate: I
					})
				}
			}
			function n(I) {}
			function v(K) {
				var J;
				if (e.exists(K) && K.lengthComputable && K.total) {
					J = K.loaded / K.total * 100
				} else {
					if (e.exists(m.buffered) && (m.buffered.length > 0)) {
						var I = m.buffered.length - 1;
						if (I >= 0) {
							J = m.buffered.end(I) / m.duration * 100
						}
					}
				}
				if (p === false && B == a.api.events.state.BUFFERING) {
					j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER_FULL);
					p = true
				}
				if (!C) {
					if (J == 100) {
						C = true
					}
					if (e.exists(J) && (J > y.buffer)) {
						y.buffer = Math.round(J);
						j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER, {
							bufferPercent: Math.round(J)
						})
					}
				}
			}
			function D(J) {
				if (e.exists(J) && e.exists(J.target)) {
					if (!isNaN(J.target.duration) && (isNaN(y.duration) || y.duration < 1)) {
						if (J.target.duration == Infinity) {
							y.duration = 0
						} else {
							y.duration = Math.round(J.target.duration * 10) / 10
						}
					}
					if (!A && m.readyState > 0) {
						m.style.display = "block";
						E(a.api.events.state.PLAYING)
					}
					if (B == a.api.events.state.PLAYING) {
						if (!A && m.readyState > 0) {
							A = true;
							try {
								if (m.currentTime < x.start) {
									m.currentTime = x.start
								}
							} catch (I) {}
							m.volume = y.volume / 100;
							m.muted = y.mute
						}
						y.position = y.duration > 0 ? (Math.round(J.target.currentTime * 10) / 10) : 0;
						j.sendEvent(a.api.events.JWPLAYER_MEDIA_TIME, {
							position: y.position,
							duration: y.duration
						});
						if (y.position >= y.duration && (y.position > 0 || y.duration > 0)) {
							w()
						}
					}
				}
				v(J)
			}
			function z(I) {}
			function k(I) {
				if (c[I.type]) {
					if (I.type == "ended") {
						w()
					} else {
						E(c[I.type])
					}
				}
			}
			function G(I) {
				var J = {
					height: I.target.videoHeight,
					width: I.target.videoWidth,
					duration: Math.round(I.target.duration * 10) / 10
				};
				if ((y.duration === 0 || isNaN(y.duration)) && I.target.duration != Infinity) {
					y.duration = Math.round(I.target.duration * 10) / 10
				}
				j.sendEvent(a.api.events.JWPLAYER_MEDIA_META, {
					metadata: J
				})
			}
			function u(K) {
				if (B == a.api.events.state.IDLE) {
					return
				}
				var J = "There was an error: ";
				if ((K.target.error && K.target.tagName.toLowerCase() == "video") || K.target.parentNode.error && K.target.parentNode.tagName.toLowerCase() == "video") {
					var I = !e.exists(K.target.error) ? K.target.parentNode.error : K.target.error;
					switch (I.code) {
					case I.MEDIA_ERR_ABORTED:
						J = "You aborted the video playback: ";
						break;
					case I.MEDIA_ERR_NETWORK:
						J = "A network error caused the video download to fail part-way: ";
						break;
					case I.MEDIA_ERR_DECODE:
						J = "The video playback was aborted due to a corruption problem or because the video used features your browser did not support: ";
						break;
					case I.MEDIA_ERR_SRC_NOT_SUPPORTED:
						J = "The video could not be loaded, either because the server or network failed or because the format is not supported: ";
						break;
					default:
						J = "An unknown error occurred: ";
						break
					}
				} else {
					if (K.target.tagName.toLowerCase() == "source") {
						q--;
						if (q > 0) {
							return
						}
						J = "The video could not be loaded, either because the server or network failed or because the format is not supported: "
					} else {
						e.log("An unknown error occurred.  Continuing...");
						return
					}
				}
				_stop(false);
				J += F();
				_error = true;
				j.sendEvent(a.api.events.JWPLAYER_ERROR, {
					error: J
				});
				return
			}
			function F() {
				var K = "";
				for (var J in x.levels) {
					var I = x.levels[J];
					var L = l.ownerDocument.createElement("source");
					K += a.utils.getAbsolutePath(I.file);
					if (J < (x.levels.length - 1)) {
						K += ", "
					}
				}
				return K
			}
			function t() {
				if (!e.exists(f)) {
					f = setInterval(function() {
						v()
					}, 100)
				}
			}
			function g() {
				clearInterval(f);
				f = null
			}
			function w() {
				if (B != a.api.events.state.IDLE) {
					_stop(false);
					j.sendEvent(a.api.events.JWPLAYER_MEDIA_COMPLETE)
				}
			}
		}
	})(jwplayer);
	(function(a) {
		var c = {
			ended: a.api.events.state.IDLE,
			playing: a.api.events.state.PLAYING,
			pause: a.api.events.state.PAUSED,
			buffering: a.api.events.state.BUFFERING
		};
		var b = a.utils.css;
		a.html5.mediayoutube = function(j, e) {
			var f = new a.html5.eventdispatcher();
			a.utils.extend(this, f);
			var l = j;
			var h = document.getElementById(e.id);
			var g = a.api.events.state.IDLE;
			var n, m;

			function k(p) {
				if (g != p) {
					var q = g;
					l.state = p;
					g = p;
					f.sendEvent(a.api.events.JWPLAYER_PLAYER_STATE, {
						oldstate: q,
						newstate: p
					})
				}
			}
			this.getDisplayElement = function() {
				return h
			};
			this.play = function() {
				if (g == a.api.events.state.IDLE) {
					f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER, {
						bufferPercent: 100
					});
					f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER_FULL);
					k(a.api.events.state.PLAYING)
				} else {
					if (g == a.api.events.state.PAUSED) {
						k(a.api.events.state.PLAYING)
					}
				}
			};
			this.pause = function() {
				k(a.api.events.state.PAUSED)
			};
			this.seek = function(p) {};
			this.stop = function(p) {
				if (!_utils.exists(p)) {
					p = true
				}
				l.position = 0;
				k(a.api.events.state.IDLE);
				if (p) {
					b(h, {
						display: "none"
					})
				}
			};
			this.volume = function(p) {
				l.volume = p;
				f.sendEvent(a.api.events.JWPLAYER_MEDIA_VOLUME, {
					volume: Math.round(p)
				})
			};
			this.mute = function(p) {
				h.muted = p;
				l.mute = p;
				f.sendEvent(a.api.events.JWPLAYER_MEDIA_MUTE, {
					mute: p
				})
			};
			this.resize = function(q, p) {
				if (q * p > 0 && n) {
					n.width = m.width = q;
					n.height = m.height = p
				}
				f.sendEvent(a.api.events.JWPLAYER_MEDIA_RESIZE, {
					fullscreen: l.fullscreen,
					width: q,
					height: p
				})
			};
			this.fullscreen = function(p) {
				if (p === true) {
					this.resize("100%", "100%")
				} else {
					this.resize(l.config.width, l.config.height)
				}
			};
			this.load = function(p) {
				o(p);
				b(n, {
					display: "block"
				});
				k(a.api.events.state.BUFFERING);
				f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER, {
					bufferPercent: 0
				});
				f.sendEvent(a.api.events.JWPLAYER_MEDIA_LOADED);
				this.play()
			};
			this.hasChrome = function() {
				return (g != a.api.events.state.IDLE)
			};

			function o(v) {
				var s = v.levels[0].file;
				s = ["http://www.youtube.com/v/", d(s), "&amp;hl=en_US&amp;fs=1&autoplay=1"].join("");
				n = document.createElement("object");
				n.id = h.id;
				n.style.position = "absolute";
				var u = {
					movie: s,
					allowfullscreen: "true",
					allowscriptaccess: "always"
				};
				for (var p in u) {
					var t = document.createElement("param");
					t.name = p;
					t.value = u[p];
					n.appendChild(t)
				}
				m = document.createElement("embed");
				n.appendChild(m);
				var q = {
					src: s,
					type: "application/x-shockwave-flash",
					allowfullscreen: "true",
					allowscriptaccess: "always",
					width: n.width,
					height: n.height
				};
				for (var r in q) {
					m.setAttribute(r, q[r])
				}
				n.appendChild(m);
				n.style.zIndex = 2147483000;
				if (h != n && h.parentNode) {
					h.parentNode.replaceChild(n, h)
				}
				h = n
			}
			function d(q) {
				var p = q.split(/\?|\#\!/);
				var s = "";
				for (var r = 0; r < p.length; r++) {
					if (p[r].substr(0, 2) == "v=") {
						s = p[r].substr(2)
					}
				}
				if (s == "") {
					if (q.indexOf("/v/") >= 0) {
						s = q.substr(q.indexOf("/v/") + 3)
					} else {
						if (q.indexOf("youtu.be") >= 0) {
							s = q.substr(q.indexOf("youtu.be/") + 9)
						} else {
							s = q
						}
					}
				}
				if (s.indexOf("?") > -1) {
					s = s.substr(0, s.indexOf("?"))
				}
				if (s.indexOf("&") > -1) {
					s = s.substr(0, s.indexOf("&"))
				}
				return s
			}
			this.embed = m;
			return this
		}
	})(jwplayer);
	(function(jwplayer) {
		var _configurableStateVariables = ["width", "height", "start", "duration", "volume", "mute", "fullscreen", "item", "plugins", "stretching"];
		jwplayer.html5.model = function(api, container, options) {
			var _api = api;
			var _container = container;
			var _model = {
				id: _container.id,
				playlist: [],
				state: jwplayer.api.events.state.IDLE,
				position: 0,
				buffer: 0,
				config: {
					width: 480,
					height: 320,
					item: -1,
					skin: undefined,
					file: undefined,
					image: undefined,
					start: 0,
					duration: 0,
					bufferlength: 5,
					volume: 90,
					mute: false,
					fullscreen: false,
					repeat: "",
					stretching: jwplayer.utils.stretching.UNIFORM,
					autostart: false,
					debug: undefined,
					screencolor: undefined
				}
			};
			var _media;
			var _eventDispatcher = new jwplayer.html5.eventdispatcher();
			var _components = ["display", "logo", "controlbar", "playlist", "dock"];
			jwplayer.utils.extend(_model, _eventDispatcher);
			for (var option in options) {
				if (typeof options[option] == "string") {
					var type = /color$/.test(option) ? "color" : null;
					options[option] = jwplayer.utils.typechecker(options[option], type)
				}
				var config = _model.config;
				var path = option.split(".");
				for (var edge in path) {
					if (edge == path.length - 1) {
						config[path[edge]] = options[option]
					} else {
						if (!jwplayer.utils.exists(config[path[edge]])) {
							config[path[edge]] = {}
						}
						config = config[path[edge]]
					}
				}
			}
			for (var index in _configurableStateVariables) {
				var configurableStateVariable = _configurableStateVariables[index];
				_model[configurableStateVariable] = _model.config[configurableStateVariable]
			}
			var pluginorder = _components.concat([]);
			if (jwplayer.utils.exists(_model.plugins)) {
				if (typeof _model.plugins == "string") {
					var userplugins = _model.plugins.split(",");
					for (var userplugin in userplugins) {
						if (typeof userplugins[userplugin] == "string") {
							pluginorder.push(userplugins[userplugin].replace(/^\s+|\s+$/g, ""))
						}
					}
				}
			}
			if (jwplayer.utils.isIOS()) {
				pluginorder = ["display", "logo", "dock", "playlist"];
				if (!jwplayer.utils.exists(_model.config.repeat)) {
					_model.config.repeat = "list"
				}
			} else {
				if (_model.config.chromeless) {
					pluginorder = ["logo", "dock", "playlist"];
					if (!jwplayer.utils.exists(_model.config.repeat)) {
						_model.config.repeat = "list"
					}
				}
			}
			_model.plugins = {
				order: pluginorder,
				config: {},
				object: {}
			};
			if (typeof _model.config.components != "undefined") {
				for (var component in _model.config.components) {
					_model.plugins.config[component] = _model.config.components[component]
				}
			}
			for (var pluginIndex in _model.plugins.order) {
				var pluginName = _model.plugins.order[pluginIndex];
				var pluginConfig = !jwplayer.utils.exists(_model.plugins.config[pluginName]) ? {} : _model.plugins.config[pluginName];
				_model.plugins.config[pluginName] = !jwplayer.utils.exists(_model.plugins.config[pluginName]) ? pluginConfig : jwplayer.utils.extend(_model.plugins.config[pluginName], pluginConfig);
				if (!jwplayer.utils.exists(_model.plugins.config[pluginName].position)) {
					if (pluginName == "playlist") {
						_model.plugins.config[pluginName].position = jwplayer.html5.view.positions.NONE
					} else {
						_model.plugins.config[pluginName].position = jwplayer.html5.view.positions.OVER
					}
				} else {
					_model.plugins.config[pluginName].position = _model.plugins.config[pluginName].position.toString().toUpperCase()
				}
			}
			if (typeof _model.plugins.config.dock != "undefined") {
				if (typeof _model.plugins.config.dock != "object") {
					var position = _model.plugins.config.dock.toString().toUpperCase();
					_model.plugins.config.dock = {
						position: position
					}
				}
				if (typeof _model.plugins.config.dock.position != "undefined") {
					_model.plugins.config.dock.align = _model.plugins.config.dock.position;
					_model.plugins.config.dock.position = jwplayer.html5.view.positions.OVER
				}
			}
			function _loadExternal(playlistfile) {
				var loader = new jwplayer.html5.playlistloader();
				loader.addEventListener(jwplayer.api.events.JWPLAYER_PLAYLIST_LOADED, function(evt) {
					_model.playlist = new jwplayer.html5.playlist(evt);
					_loadComplete(true)
				});
				loader.addEventListener(jwplayer.api.events.JWPLAYER_ERROR, function(evt) {
					_model.playlist = new jwplayer.html5.playlist({
						playlist: []
					});
					_loadComplete(false)
				});
				loader.load(playlistfile)
			}
			function _loadComplete() {
				if (_model.config.shuffle) {
					_model.item = _getShuffleItem()
				} else {
					if (_model.config.item >= _model.playlist.length) {
						_model.config.item = _model.playlist.length - 1
					} else {
						if (_model.config.item < 0) {
							_model.config.item = 0
						}
					}
					_model.item = _model.config.item
				}
				_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_PLAYLIST_LOADED, {
					playlist: _model.playlist
				});
				_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_PLAYLIST_ITEM, {
					index: _model.item
				})
			}
			_model.loadPlaylist = function(arg) {
				var input;
				if (typeof arg == "string") {
					if (arg.indexOf("[") == 0 || arg.indexOf("{") == "0") {
						try {
							input = eval(arg)
						} catch (err) {
							input = arg
						}
					} else {
						input = arg
					}
				} else {
					input = arg
				}
				var config;
				switch (jwplayer.utils.typeOf(input)) {
				case "object":
					config = input;
					break;
				case "array":
					config = {
						playlist: input
					};
					break;
				default:
					_loadExternal(input);
					return;
					break
				}
				_model.playlist = new jwplayer.html5.playlist(config);
				if (jwplayer.utils.extension(_model.playlist[0].file) == "xml") {
					_loadExternal(_model.playlist[0].file)
				} else {
					_loadComplete()
				}
			};

			function _getShuffleItem() {
				var result = null;
				if (_model.playlist.length > 1) {
					while (!jwplayer.utils.exists(result)) {
						result = Math.floor(Math.random() * _model.playlist.length);
						if (result == _model.item) {
							result = null
						}
					}
				} else {
					result = 0
				}
				return result
			}
			function forward(evt) {
				if (evt.type == jwplayer.api.events.JWPLAYER_MEDIA_LOADED) {
					_container = _media.getDisplayElement()
				}
				_eventDispatcher.sendEvent(evt.type, evt)
			}
			var _mediaProviders = {};
			_model.setActiveMediaProvider = function(playlistItem) {
				if (playlistItem.provider == "audio") {
					playlistItem.provider = "sound"
				}
				var provider = playlistItem.provider;
				var current = _media ? _media.getDisplayElement() : null;
				if (provider == "sound" || provider == "http" || provider == "") {
					provider = "video"
				}
				if (!jwplayer.utils.exists(_mediaProviders[provider])) {
					switch (provider) {
					case "video":
						_media = new jwplayer.html5.mediavideo(_model, current ? current : _container);
						break;
					case "youtube":
						_media = new jwplayer.html5.mediayoutube(_model, current ? current : _container);
						break
					}
					if (!jwplayer.utils.exists(_media)) {
						return false
					}
					_media.addGlobalListener(forward);
					_mediaProviders[provider] = _media
				} else {
					if (_media != _mediaProviders[provider]) {
						if (_media) {
							_media.stop()
						}
						_media = _mediaProviders[provider]
					}
				}
				return true
			};
			_model.getMedia = function() {
				return _media
			};
			_model.seek = function(pos) {
				_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_MEDIA_SEEK, {
					position: _model.position,
					offset: pos
				});
				return _media.seek(pos)
			};
			_model.setupPlugins = function() {
				if (!jwplayer.utils.exists(_model.plugins) || !jwplayer.utils.exists(_model.plugins.order) || _model.plugins.order.length == 0) {
					jwplayer.utils.log("No plugins to set up");
					return _model
				}
				for (var i = 0; i < _model.plugins.order.length; i++) {
					try {
						var pluginName = _model.plugins.order[i];
						if (jwplayer.utils.exists(jwplayer.html5[pluginName])) {
							if (pluginName == "playlist") {
								_model.plugins.object[pluginName] = new jwplayer.html5.playlistcomponent(_api, _model.plugins.config[pluginName])
							} else {
								_model.plugins.object[pluginName] = new jwplayer.html5[pluginName](_api, _model.plugins.config[pluginName])
							}
						} else {
							_model.plugins.order.splice(plugin, plugin + 1)
						}
						if (typeof _model.plugins.object[pluginName].addGlobalListener == "function") {
							_model.plugins.object[pluginName].addGlobalListener(forward)
						}
					} catch (err) {
						jwplayer.utils.log("Could not setup " + pluginName)
					}
				}
			};
			return _model
		}
	})(jwplayer);
	(function(a) {
		a.html5.playlist = function(b) {
			var d = [];
			if (b.playlist && b.playlist instanceof Array && b.playlist.length > 0) {
				for (var c in b.playlist) {
					if (!isNaN(parseInt(c))) {
						d.push(new a.html5.playlistitem(b.playlist[c]))
					}
				}
			} else {
				d.push(new a.html5.playlistitem(b))
			}
			return d
		}
	})(jwplayer);
	(function(a) {
		var c = {
			size: 180,
			position: a.html5.view.positions.NONE,
			itemheight: 60,
			thumbs: true,
			fontcolor: "#000000",
			overcolor: "",
			activecolor: "",
			backgroundcolor: "#f8f8f8",
			font: "_sans",
			fontsize: "",
			fontstyle: "",
			fontweight: ""
		};
		var b = {
			_sans: "Arial, Helvetica, sans-serif",
			_serif: "Times, Times New Roman, serif",
			_typewriter: "Courier New, Courier, monospace"
		};
		_utils = a.utils;
		_css = _utils.css;
		_hide = function(d) {
			_css(d, {
				display: "none"
			})
		};
		_show = function(d) {
			_css(d, {
				display: "block"
			})
		};
		a.html5.playlistcomponent = function(r, B) {
			var w = r;
			var e = a.utils.extend({}, c, w.skin.getComponentSettings("playlist"), B);
			if (e.position == a.html5.view.positions.NONE || typeof a.html5.view.positions[e.position] == "undefined") {
				return
			}
			var x;
			var l;
			var C;
			var d;
			var g;
			var f;
			var k = -1;
			var h = {
				background: undefined,
				item: undefined,
				itemOver: undefined,
				itemImage: undefined,
				itemActive: undefined
			};
			this.getDisplayElement = function() {
				return x
			};
			this.resize = function(F, D) {
				l = F;
				C = D;
				if (w.jwGetFullscreen()) {
					_hide(x)
				} else {
					var E = {
						display: "block",
						width: l,
						height: C
					};
					_css(x, E)
				}
			};
			this.show = function() {
				_show(x)
			};
			this.hide = function() {
				_hide(x)
			};

			function j() {
				x = document.createElement("div");
				x.id = w.id + "_jwplayer_playlistcomponent";
				switch (e.position) {
				case a.html5.view.positions.RIGHT:
				case a.html5.view.positions.LEFT:
					x.style.width = e.size + "px";
					break;
				case a.html5.view.positions.TOP:
				case a.html5.view.positions.BOTTOM:
					x.style.height = e.size + "px";
					break
				}
				A();
				if (h.item) {
					e.itemheight = h.item.height
				}
				x.style.backgroundColor = "#C6C6C6";
				w.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED, s);
				w.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM, u);
				w.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE, m)
			}
			function p() {
				var D = document.createElement("ul");
				_css(D, {
					width: x.style.width,
					minWidth: x.style.width,
					height: x.style.height,
					backgroundColor: e.backgroundcolor,
					backgroundImage: h.background ? "url(" + h.background.src + ")" : "",
					color: e.fontcolor,
					listStyle: "none",
					margin: 0,
					padding: 0,
					fontFamily: b[e.font] ? b[e.font] : b._sans,
					fontSize: (e.fontsize ? e.fontsize : 11) + "px",
					fontStyle: e.fontstyle,
					fontWeight: e.fontweight,
					overflowY: "auto"
				});
				return D
			}
			function y(D) {
				return function() {
					var E = f.getElementsByClassName("item")[D];
					var F = e.fontcolor;
					var G = h.item ? "url(" + h.item.src + ")" : "";
					if (D == w.jwGetPlaylistIndex()) {
						if (e.activecolor !== "") {
							F = e.activecolor
						}
						if (h.itemActive) {
							G = "url(" + h.itemActive.src + ")"
						}
					}
					_css(E, {
						color: e.overcolor !== "" ? e.overcolor : F,
						backgroundImage: h.itemOver ? "url(" + h.itemOver.src + ")" : G
					})
				}
			}
			function o(D) {
				return function() {
					var E = f.getElementsByClassName("item")[D];
					var F = e.fontcolor;
					var G = h.item ? "url(" + h.item.src + ")" : "";
					if (D == w.jwGetPlaylistIndex()) {
						if (e.activecolor !== "") {
							F = e.activecolor
						}
						if (h.itemActive) {
							G = "url(" + h.itemActive.src + ")"
						}
					}
					_css(E, {
						color: F,
						backgroundImage: G
					})
				}
			}
			function q(I) {
				var P = d[I];
				var O = document.createElement("li");
				O.className = "item";
				_css(O, {
					height: e.itemheight,
					display: "block",
					cursor: "pointer",
					backgroundImage: h.item ? "url(" + h.item.src + ")" : "",
					backgroundSize: "100% " + e.itemheight + "px"
				});
				O.onmouseover = y(I);
				O.onmouseout = o(I);
				var J = document.createElement("div");
				var F = new Image();
				var K = 0;
				var L = 0;
				var M = 0;
				if (v() && (P.image || P["playlist.image"] || h.itemImage)) {
					F.className = "image";
					if (h.itemImage) {
						K = (e.itemheight - h.itemImage.height) / 2;
						L = h.itemImage.width;
						M = h.itemImage.height
					} else {
						L = e.itemheight * 4 / 3;
						M = e.itemheight
					}
					_css(J, {
						height: M,
						width: L,
						"float": "left",
						styleFloat: "left",
						cssFloat: "left",
						margin: "0 5px 0 0",
						background: "black",
						overflow: "hidden",
						margin: K + "px",
						position: "relative"
					});
					_css(F, {
						position: "relative"
					});
					J.appendChild(F);
					F.onload = function() {
						a.utils.stretch(a.utils.stretching.FILL, F, L, M, this.naturalWidth, this.naturalHeight)
					};
					if (P["playlist.image"]) {
						F.src = P["playlist.image"]
					} else {
						if (P.image) {
							F.src = P.image
						} else {
							if (h.itemImage) {
								F.src = h.itemImage.src
							}
						}
					}
					O.appendChild(J)
				}
				var E = l - L - K * 2;
				if (C < e.itemheight * d.length) {
					E -= 15
				}
				var D = document.createElement("div");
				_css(D, {
					position: "relative",
					height: "100%",
					overflow: "hidden"
				});
				var G = document.createElement("span");
				if (P.duration > 0) {
					G.className = "duration";
					_css(G, {
						fontSize: (e.fontsize ? e.fontsize : 11) + "px",
						fontWeight: (e.fontweight ? e.fontweight : "bold"),
						width: "40px",
						height: e.fontsize ? e.fontsize + 10 : 20,
						lineHeight: 24,
						"float": "right",
						styleFloat: "right",
						cssFloat: "right"
					});
					G.innerHTML = _utils.timeFormat(P.duration);
					D.appendChild(G)
				}
				var N = document.createElement("span");
				N.className = "title";
				_css(N, {
					padding: "5px 5px 0 " + (K ? 0 : "5px"),
					height: e.fontsize ? e.fontsize + 10 : 20,
					lineHeight: e.fontsize ? e.fontsize + 10 : 20,
					overflow: "hidden",
					"float": "left",
					styleFloat: "left",
					cssFloat: "left",
					width: ((P.duration > 0) ? E - 50 : E) - 10 + "px",
					fontSize: (e.fontsize ? e.fontsize : 13) + "px",
					fontWeight: (e.fontweight ? e.fontweight : "bold")
				});
				N.innerHTML = P ? P.title : "";
				D.appendChild(N);
				if (P.description) {
					var H = document.createElement("span");
					H.className = "description";
					_css(H, {
						display: "block",
						"float": "left",
						styleFloat: "left",
						cssFloat: "left",
						margin: 0,
						paddingLeft: N.style.paddingLeft,
						paddingRight: N.style.paddingRight,
						lineHeight: (e.fontsize ? e.fontsize + 4 : 16) + "px",
						overflow: "hidden",
						position: "relative"
					});
					H.innerHTML = P.description;
					D.appendChild(H)
				}
				O.appendChild(D);
				return O
			}
			function s(E) {
				x.innerHTML = "";
				d = w.jwGetPlaylist();
				if (!d) {
					return
				}
				items = [];
				f = p();
				for (var F = 0; F < d.length; F++) {
					var D = q(F);
					D.onclick = z(F);
					f.appendChild(D);
					items.push(D)
				}
				k = w.jwGetPlaylistIndex();
				o(k)();
				x.appendChild(f);
				if (_utils.isIOS() && window.iScroll) {
					f.style.height = e.itemheight * d.length + "px";
					var G = new iScroll(x.id)
				}
			}
			function z(D) {
				return function() {
					w.jwPlaylistItem(D);
					w.jwPlay(true)
				}
			}
			function n() {
				f.scrollTop = w.jwGetPlaylistIndex() * e.itemheight
			}
			function v() {
				return e.thumbs.toString().toLowerCase() == "true"
			}
			function u(D) {
				if (k >= 0) {
					o(k)();
					k = D.index
				}
				o(D.index)();
				n()
			}
			function m() {
				if (e.position == a.html5.view.positions.OVER) {
					switch (w.jwGetState()) {
					case a.api.events.state.IDLE:
						_show(x);
						break;
					default:
						_hide(x);
						break
					}
				}
			}
			function A() {
				for (var D in h) {
					h[D] = t(D)
				}
			}
			function t(D) {
				return w.skin.getSkinElement("playlist", D)
			}
			j();
			return this
		}
	})(jwplayer);
	(function(b) {
		b.html5.playlistitem = function(d) {
			var e = {
				author: "",
				date: "",
				description: "",
				image: "",
				link: "",
				mediaid: "",
				tags: "",
				title: "",
				provider: "",
				file: "",
				streamer: "",
				duration: -1,
				start: 0,
				currentLevel: -1,
				levels: []
			};
			var c = b.utils.extend({}, e, d);
			if (c.type) {
				c.provider = c.type;
				delete c.type
			}
			if (c.levels.length === 0) {
				c.levels[0] = new b.html5.playlistitemlevel(c)
			}
			if (!c.provider) {
				c.provider = a(c.levels[0])
			} else {
				c.provider = c.provider.toLowerCase()
			}
			return c
		};

		function a(e) {
			if (b.utils.isYouTube(e.file)) {
				return "youtube"
			} else {
				var f = b.utils.extension(e.file);
				var c;
				if (f && b.utils.extensionmap[f]) {
					if (f == "m3u8") {
						return "video"
					}
					c = b.utils.extensionmap[f].html5
				} else {
					if (e.type) {
						c = e.type
					}
				}
				if (c) {
					var d = c.split("/")[0];
					if (d == "audio") {
						return "sound"
					} else {
						if (d == "video") {
							return d
						}
					}
				}
			}
			return ""
		}
	})(jwplayer);
	(function(a) {
		a.html5.playlistitemlevel = function(b) {
			var d = {
				file: "",
				streamer: "",
				bitrate: 0,
				width: 0
			};
			for (var c in d) {
				if (a.utils.exists(b[c])) {
					d[c] = b[c]
				}
			}
			return d
		}
	})(jwplayer);
	(function(a) {
		a.html5.playlistloader = function() {
			var c = new a.html5.eventdispatcher();
			a.utils.extend(this, c);
			this.load = function(e) {
				a.utils.ajax(e, d, b)
			};

			function d(g) {
				var f = [];
				try {
					var f = a.utils.parsers.rssparser.parse(g.responseXML.firstChild);
					c.sendEvent(a.api.events.JWPLAYER_PLAYLIST_LOADED, {
						playlist: new a.html5.playlist({
							playlist: f
						})
					})
				} catch (h) {
					b("Could not parse the playlist")
				}
			}
			function b(e) {
				c.sendEvent(a.api.events.JWPLAYER_ERROR, {
					error: e ? e : "could not load playlist for whatever reason.  too bad"
				})
			}
		}
	})(jwplayer);
	(function(a) {
		a.html5.skin = function() {
			var b = {};
			var c = false;
			this.load = function(d, e) {
				new a.html5.skinloader(d, function(f) {
					c = true;
					b = f;
					e()
				}, function() {
					new a.html5.skinloader("", function(f) {
						c = true;
						b = f;
						e()
					})
				})
			};
			this.getSkinElement = function(d, e) {
				if (c) {
					try {
						return b[d].elements[e]
					} catch (f) {
						a.utils.log("No such skin component / element: ", [d, e])
					}
				}
				return null
			};
			this.getComponentSettings = function(d) {
				if (c) {
					return b[d].settings
				}
				return null
			};
			this.getComponentLayout = function(d) {
				if (c) {
					return b[d].layout
				}
				return null
			}
		}
	})(jwplayer);
	(function(a) {
		a.html5.skinloader = function(f, p, k) {
			var o = {};
			var c = p;
			var l = k;
			var e = true;
			var j;
			var n = f;
			var s = false;

			function m() {
				if (typeof n != "string" || n === "") {
					d(a.html5.defaultSkin().xml)
				} else {
					a.utils.ajax(a.utils.getAbsolutePath(n), function(t) {
						try {
							if (a.utils.exists(t.responseXML)) {
								d(t.responseXML);
								return
							}
						} catch (u) {
							h()
						}
						d(a.html5.defaultSkin().xml)
					}, function(t) {
						d(a.html5.defaultSkin().xml)
					})
				}
			}
			function d(y) {
				var E = y.getElementsByTagName("component");
				if (E.length === 0) {
					return
				}
				for (var H = 0; H < E.length; H++) {
					var C = E[H].getAttribute("name");
					var B = {
						settings: {},
						elements: {},
						layout: {}
					};
					o[C] = B;
					var G = E[H].getElementsByTagName("elements")[0].getElementsByTagName("element");
					for (var F = 0; F < G.length; F++) {
						b(G[F], C)
					}
					var z = E[H].getElementsByTagName("settings")[0];
					if (z && z.childNodes.length > 0) {
						var K = z.getElementsByTagName("setting");
						for (var P = 0; P < K.length; P++) {
							var Q = K[P].getAttribute("name");
							var I = K[P].getAttribute("value");
							var x = /color$/.test(Q) ? "color" : null;
							o[C].settings[Q] = a.utils.typechecker(I, x)
						}
					}
					var L = E[H].getElementsByTagName("layout")[0];
					if (L && L.childNodes.length > 0) {
						var M = L.getElementsByTagName("group");
						for (var w = 0; w < M.length; w++) {
							var A = M[w];
							o[C].layout[A.getAttribute("position")] = {
								elements: []
							};
							for (var O = 0; O < A.attributes.length; O++) {
								var D = A.attributes[O];
								o[C].layout[A.getAttribute("position")][D.name] = D.value
							}
							var N = A.getElementsByTagName("*");
							for (var v = 0; v < N.length; v++) {
								var t = N[v];
								o[C].layout[A.getAttribute("position")].elements.push({
									type: t.tagName
								});
								for (var u = 0; u < t.attributes.length; u++) {
									var J = t.attributes[u];
									o[C].layout[A.getAttribute("position")].elements[v][J.name] = J.value
								}
								if (!a.utils.exists(o[C].layout[A.getAttribute("position")].elements[v].name)) {
									o[C].layout[A.getAttribute("position")].elements[v].name = t.tagName
								}
							}
						}
					}
					e = false;
					r()
				}
			}
			function r() {
				clearInterval(j);
				if (!s) {
					j = setInterval(function() {
						q()
					}, 100)
				}
			}
			function b(y, x) {
				var w = new Image();
				var t = y.getAttribute("name");
				var v = y.getAttribute("src");
				var A;
				if (v.indexOf("data:image/png;base64,") === 0) {
					A = v
				} else {
					var u = a.utils.getAbsolutePath(n);
					var z = u.substr(0, u.lastIndexOf("/"));
					A = [z, x, v].join("/")
				}
				o[x].elements[t] = {
					height: 0,
					width: 0,
					src: "",
					ready: false,
					image: w
				};
				w.onload = function(B) {
					g(w, t, x)
				};
				w.onerror = function(B) {
					s = true;
					r();
					l()
				};
				w.src = A
			}
			function h() {
				for (var u in o) {
					var w = o[u];
					for (var t in w.elements) {
						var x = w.elements[t];
						var v = x.image;
						v.onload = null;
						v.onerror = null;
						delete x.image;
						delete w.elements[t]
					}
					delete o[u]
				}
			}
			function q() {
				for (var t in o) {
					if (t != "properties") {
						for (var u in o[t].elements) {
							if (!o[t].elements[u].ready) {
								return
							}
						}
					}
				}
				if (e === false) {
					clearInterval(j);
					c(o)
				}
			}
			function g(t, v, u) {
				if (o[u] && o[u].elements[v]) {
					o[u].elements[v].height = t.height;
					o[u].elements[v].width = t.width;
					o[u].elements[v].src = t.src;
					o[u].elements[v].ready = true;
					r()
				} else {
					a.utils.log("Loaded an image for a missing element: " + u + "." + v)
				}
			}
			m()
		}
	})(jwplayer);
	(function(a) {
		a.html5.api = function(c, n) {
			var m = {};
			var f = document.createElement("div");
			c.parentNode.replaceChild(f, c);
			f.id = c.id;
			m.version = a.version;
			m.id = f.id;
			var l = new a.html5.model(m, f, n);
			var j = new a.html5.view(m, f, l);
			var k = new a.html5.controller(m, f, l, j);
			m.skin = new a.html5.skin();
			m.jwPlay = function(o) {
				if (typeof o == "undefined") {
					e()
				} else {
					if (o.toString().toLowerCase() == "true") {
						k.play()
					} else {
						k.pause()
					}
				}
			};
			m.jwPause = function(o) {
				if (typeof o == "undefined") {
					e()
				} else {
					if (o.toString().toLowerCase() == "true") {
						k.pause()
					} else {
						k.play()
					}
				}
			};

			function e() {
				if (l.state == a.api.events.state.PLAYING || l.state == a.api.events.state.BUFFERING) {
					k.pause()
				} else {
					k.play()
				}
			}
			m.jwStop = k.stop;
			m.jwSeek = k.seek;
			m.jwPlaylistItem = k.item;
			m.jwPlaylistNext = k.next;
			m.jwPlaylistPrev = k.prev;
			m.jwResize = k.resize;
			m.jwLoad = k.load;

			function h(o) {
				return function() {
					return l[o]
				}
			}
			function d(o, q, p) {
				return function() {
					var r = l.plugins.object[o];
					if (r && r[q] && typeof r[q] == "function") {
						r[q].apply(r, p)
					}
				}
			}
			m.jwGetItem = h("item");
			m.jwGetPosition = h("position");
			m.jwGetDuration = h("duration");
			m.jwGetBuffer = h("buffer");
			m.jwGetWidth = h("width");
			m.jwGetHeight = h("height");
			m.jwGetFullscreen = h("fullscreen");
			m.jwSetFullscreen = k.setFullscreen;
			m.jwGetVolume = h("volume");
			m.jwSetVolume = k.setVolume;
			m.jwGetMute = h("mute");
			m.jwSetMute = k.setMute;
			m.jwGetStretching = h("stretching");
			m.jwGetState = h("state");
			m.jwGetVersion = function() {
				return m.version
			};
			m.jwGetPlaylist = function() {
				return l.playlist
			};
			m.jwGetPlaylistIndex = m.jwGetItem;
			m.jwAddEventListener = k.addEventListener;
			m.jwRemoveEventListener = k.removeEventListener;
			m.jwSendEvent = k.sendEvent;
			m.jwDockSetButton = function(r, o, p, q) {
				if (l.plugins.object.dock && l.plugins.object.dock.setButton) {
					l.plugins.object.dock.setButton(r, o, p, q)
				}
			};
			m.jwControlbarShow = d("controlbar", "show");
			m.jwControlbarHide = d("controlbar", "hide");
			m.jwDockShow = d("dock", "show");
			m.jwDockHide = d("dock", "hide");
			m.jwDisplayShow = d("display", "show");
			m.jwDisplayHide = d("display", "hide");
			m.jwGetLevel = function() {};
			m.jwGetBandwidth = function() {};
			m.jwGetLockState = function() {};
			m.jwLock = function() {};
			m.jwUnlock = function() {};

			function b() {
				if (l.config.playlistfile) {
					l.addEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED, g);
					l.loadPlaylist(l.config.playlistfile)
				} else {
					if (typeof l.config.playlist == "string") {
						l.addEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED, g);
						l.loadPlaylist(l.config.playlist)
					} else {
						l.loadPlaylist(l.config);
						setTimeout(g, 25)
					}
				}
			}
			function g(o) {
				l.removeEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED, g);
				l.setupPlugins();
				j.setup();
				var o = {
					id: m.id,
					version: m.version
				};
				k.playerReady(o)
			}
			if (l.config.chromeless && !a.utils.isIOS()) {
				b()
			} else {
				m.skin.load(l.config.skin, b)
			}
			return m
		}
	})(jwplayer);

	
};
