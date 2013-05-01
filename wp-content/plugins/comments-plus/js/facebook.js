(function ($) {
$(function () {

// Bind local event handlers
$(document).bind('wdcp_facebook_login_attempt', function () {
	FB.login(function (resp) {
		if (resp.authResponse && resp.authResponse.userID) $(document).trigger('wdcp_logged_in', ['facebook']);
	}, {scope: 'read_stream,publish_stream,email'});
});
// Attempt auto-connect
if ($("#login-with-facebook").length) {
	if (typeof FB != "undefined") FB.getLoginStatus(function (resp) {
		if (resp.authResponse && resp.authResponse.userID) $(document).trigger('wdcp_logged_in', ['facebook', true]);
	});
}

// Handle logout requests gracefully
$(document).on('click', "#comment-provider-facebook a.comment-provider-logout", function () {
	var href = $(this).attr('href');
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_facebook_logout"
    }, function (data) {
		FB.logout(function (resp) {
			window.location.reload(); // Refresh
		});
    });
	return false;
});

// Handle post comment requests
$(document).on('click', "#send-facebook-comment", function () {
	var comment = $("#facebook-comment").val();
	var repost = $("#post-on-facebook").is(":checked") ? 1 : 0;
	var commentParent = $('#comment_parent').val();
	var subscribe = ($("#subscribe").length && $("#subscribe").is(":checked")) ? 'subscribe' : '';

	var to_send = {
		"action": "wdcp_post_facebook_comment",
		"post_id": _wdcp_data.post_id,
		"post_on_facebook": repost,
		"comment_parent": commentParent,
		"subscribe": subscribe,
		"comment": comment
    };
    $(document).trigger('wdcp_preprocess_comment_data', [to_send]);
	// Start UI change...
	$(this).parents(".comment-provider").empty().append('<div class="comment-provider-waiting-response"></div>');

	$.post(_wdcp_ajax_url, to_send, function (data) {
		$(document).trigger('wdcp_comment_sent', ['facebook']);
		window.location.reload(); // Refresh
    });
	return false;
});

});
})(jQuery);