(function ($) {
$(function () {

// Bind local event handlers
$(document).bind('wdcp_google_login_attempt', function () {
	var url = $("#login-with-google a").attr('href');
	var googleLogin = window.open('https://www.google.com/accounts', "google_login", "scrollbars=no,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_google_auth_url",
		"url": url
	}, function (data) {
		var href = data.url;
		googleLogin.location = href;
		var gTimer = setInterval(function () {
			try {
				if (googleLogin.location.hostname == window.location.hostname) {
					clearInterval(gTimer);
					googleLogin.close();
					$(document).trigger('wdcp_logged_in', ['google']);
				}
			} catch (e) {}
		}, 300);
		return false;
	});
});

//Handle logout requests gracefully
$(document).on('click', "#comment-provider-google a.comment-provider-logout", function () {
	$.post(_wdcp_ajax_url, {
		"action": "wdcp_google_logout"
    }, function (data) {
		window.location.reload(); // Refresh
    });
	return false;
});

// Handle post comment requests
$(document).on('click', "#send-google-comment", function () {
	var comment = $("#google-comment").val();
	var commentParent = $('#comment_parent').val();
	var subscribe = ($("#subscribe").length && $("#subscribe").is(":checked")) ? 'subscribe' : '';

	var to_send = {
		"action": "wdcp_post_google_comment",
		"post_id": _wdcp_data.post_id,
		"comment_parent": commentParent,
		"subscribe": subscribe,
		"comment": comment
    };
    $(document).trigger('wdcp_preprocess_comment_data', [to_send]);
	// Start UI change...
	$(this).parents(".comment-provider").empty().append('<div class="comment-provider-waiting-response"></div>');

	$.post(_wdcp_ajax_url, to_send, function (data) {
		$(document).trigger('wdcp_comment_sent', ['google']);
		window.location.reload(); // Refresh
    });
	return false;
});

});
})(jQuery);