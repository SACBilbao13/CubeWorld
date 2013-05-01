(function ($) {
$(function () {

// Bind local event handlers
$(document).bind('wdcp_twitter_login_attempt', function () {
	var url = $("#login-with-twitter a").attr('href');
	var twLogin = window.open('https://api.twitter.com', "twitter_login", "scrollbars=no,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_twitter_auth_url",
		"url": url
	}, function (data) {
		var href = data.url;//$("#login-with-twitter a").attr('href');
		twLogin.location = href;
		var tTimer = setInterval(function () {
			try {
				if (twLogin.location.hostname == window.location.hostname) {
					clearInterval(tTimer);
					twLogin.close();
					$(document).trigger('wdcp_logged_in', ['twitter']);
				}
			} catch (e) {}
		}, 300);
		return false;
	});
});

//Handle logout requests gracefully
$(document).on('click', "#comment-provider-twitter a.comment-provider-logout", function () {
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_twitter_logout"
    }, function (data) {
		window.location.reload(); // Refresh
    });
	return false;
});

//Handle post comment requests
$(document).on('click', "#send-twitter-comment", function () {
	var comment = $("#twitter-comment").val();
	var repost = $("#post-on-twitter").is(":checked") ? 1 : 0;
	var commentParent = $('#comment_parent').val();

	var to_send = {
		"action": "wdcp_post_twitter_comment",
		"post_id": _wdcp_data.post_id,
		"post_on_twitter": repost,
		"comment_parent": commentParent,
		"comment": comment
    };
    $(document).trigger('wdcp_preprocess_comment_data', [to_send]);
	// Start UI change...
	$(this).parents(".comment-provider").empty().append('<div class="comment-provider-waiting-response"></div>');

	$.post(_wdcp_ajax_url, to_send, function (data) {
		$(document).trigger('wdcp_comment_sent', ['twitter']);
		window.location.reload(); // Refresh
    });
	return false;
});

});
})(jQuery);