(function ($) {
$(function () {

function fitProviderWidths () {
	var width = $("#all-comment-providers").parent().parent().width();
	var count = $("#all-comment-providers li").length;

	var inner = $("#all-comment-providers li a").innerWidth();
	var outer = $("#all-comment-providers li a").outerWidth();
	var new_width = (width/count) - (outer-inner);
	$("#all-comment-providers li a").width(new_width);
	/*
	$("#all-comment-providers li a").each(function () {
		$(this).width(new_width);
	});
	*/
}

function selectProviderFromListItem ($li_a) {
	var $li = $li_a.parents('li');
	if (!$li.length) return false;

	var $provider = $($li_a.attr('href'));
	if (!$provider.length) return false;

	var $oldText = $(".selected-comment-provider textarea:visible");

	// Reset all selected providers
	$('.comment-provider, ul#all-comment-providers li').each(function (){
		$(this).removeClass('selected-comment-provider');
	});

	// Select providers
	$provider.addClass('selected-comment-provider');
	$li.addClass('selected-comment-provider');

	// Move any entered text too
	var $text = $provider.find("textarea");
	if ($text.length && $oldText.length) $text.val($oldText.val());

	// Try to move the subscription box around (optional)
	var $box = $('.comment-provider p.subscribe-to-comments, .comment-provider p.wdcp-mcls-subscription');
	if (!$box.length) return true;

	if ($text.length && !$provider.attr('id').match(/twitter/) && !$provider.attr('id').match(/wordpress/)) $provider.append($box);
	else if ($provider.attr('id').match(/wordpress/)) $provider.find('form').append($box);

	return true;
}

/**
 * Try to parse comment id from the LI ID.
 */
function getCommentId ($li) {
	return parseInt($li.attr('id').replace(/^.*comment-/, ''), 10);
}

/**
 * Return last valid provider used.
 */
function getPreferredProvider () {
	var cks = document.cookie.split(";");
	var value = "";
	$.each(cks, function (idx, ck) {
		if ("wdcp_preferred_provider" != $.trim(ck.substr(0, ck.indexOf("=")))) return true;

		value = $.trim(ck.substr(ck.indexOf("=") + 1));
		return false;
	});
	return unescape(value);
}

/**
 * Set last valid provider used.
 */
function setPreferredProvider (provider) {
	var d = new Date();
	d.setDate(d.getDate() + 365);

	var value = escape("comment-provider-" + provider) + ";expires=" + d.toGMTString() + ";path=/";
	document.cookie = "wdcp_preferred_provider=" + value;
}

/**
 * Check cookies for last valid provider used.
 */
function isPreferredProvider (provider) {
	var used = getPreferredProvider();
	return (used == provider);
}

// Attempt to kill reply events and rebind them here
$("a.comment-reply-link").unbind('click').after('<a class="comment-plus-reply-link" href="#comments-plus-form">' + _wdcp_data.text.reply + '</a>');
$("a.comment-reply-link").remove();
// Kill WP HTML instructions
$('.no-instructions p.form-allowed-tags').remove();

if (_wdcp_data.fit_tabs) {
	fitProviderWidths();
	$(window).resize(fitProviderWidths);
}

// Handle comments moving up
$("a.comment-plus-reply-link").click(function () {
	var $li = $(this).parents('li');
	var parentId = getCommentId($li);
	if (!parentId) return false;

	var $providers = $("#comment-providers");

	$(this).parents('li').first().append($providers);
	$("#comment-providers").find('#comment_parent').val(parentId);
	if (!$providers.find(".comments-plus-reply-cancel").length) $providers.append('<a class="comments-plus-reply-cancel" href="#">' + _wdcp_data.text.cancel_reply + '</a>');
	$("#comment-providers").find('textarea:visible').focus();

	if (_wdcp_data.fit_tabs) fitProviderWidths();

	return false;
});
$(document).on('click', ".comments-plus-reply-cancel", function () {
	$(this).remove();
	var $providers = $("#comment-providers");
	$("#comment-providers").find('#comment_parent').val(0);
	$(".commentlist").after($providers);
	return false;
});

// Bind tab select events
$(document).on('click', "#all-comment-providers li a", function () {
	selectProviderFromListItem($(this));
	return false;
});

// Bind login button events
$(document).on('click', ".comment-provider-login-button a", function () {
	var $parent = $(this).parents(".comment-provider-login-button");
	if (!$parent.length) return false;

	var provider = $parent.attr('id').replace(/login-with-/, '');
	if (!provider) return false;

	// Trigger login attempt event
	$(document).trigger('wdcp_' + provider + '_login_attempt');
	return false;
});

// Bind logged in event handler
$(document).bind('wdcp_logged_in', function (e, provider, autoconnect) {
	// Start UI change
	var $parent = $("#login-with-" + provider).parents('.comment-provider');
	$("#login-with-" + provider).remove();
	if (!$parent.find(".comment-provider-waiting-response").length) $parent.append('<div class="comment-provider-waiting-response"></div>');
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_get_form",
		"provider": provider,
		"page": window.location.href
	}, function (data) {
		if (!data) return false;
		$parent.empty().html(data.html);

		// Attempt adding the name
		var $login = $("#comment-provider-" + provider + " .connected-as");
		if (!$login.length) return false;
		$("#comment-provider-" + provider + "-link span").html($login.text());

		// Attempt to switch to proper tab
		// for auto-connect
		if (autoconnect && isPreferredProvider("comment-provider-" + provider)) selectProviderFromListItem($("#comment-provider-" + provider + "-link"));
	});
});

// Bind comment action postprocessing
$(document).bind("wdcp_comment_sent", function (e, provider) {
	setPreferredProvider(provider);
});

// Initialize
// Try to select Facebook first, or first item if that fails
if ($("#all-comment-providers li a#comment-provider-facebook-link").length) selectProviderFromListItem($("#all-comment-providers li a#comment-provider-facebook-link"));
else selectProviderFromListItem($("#all-comment-providers li a:first"));
// Try selecting previously used "logged in" item, if possible
if ($(".connected-as").length) {
	$(".connected-as").each(function () {
		var $item = $(this).first().parents('div.comment-provider');
		if (!$item.length) return true;
		if (!isPreferredProvider($item.attr("id"))) return true;

		if ($item.length) selectProviderFromListItem($('#' + $item.attr('id') + '-link'));
		return false;
	});
}


});
})(jQuery);