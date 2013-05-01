(function ($) {
$(function () {
	
function toggle_discussion_service (service) {
	if (!service) return false;
	var $service = $("#wdcp-sd-discussion-" + service);
	if (!$service.length) return false;
	
	var $switch = $("#wdcp-sd-discussion-" + service + "-switch");
	if (!$switch) return false;
	
	$(".wdcp-sd-discussion").hide();
	$service.show();
	
	$("#wdcp-sd-discussion_switcher li").removeClass("wdcp-sd-service_selected");
	$switch.parents('li').addClass('wdcp-sd-service_selected');
	
	return false;
}

$("#wdcp-sd-discussion_switcher a").click(function (){
	var $me = $(this);
	if (!$me.length) return false;
	
	var service = $me.attr('data-discussion_service');
	if (!service) return false;
	
	toggle_discussion_service(service);
	
	return false;
});

var initial_service = $("#wdcp-sd-discussion-" + _wdcp_sd.default_service).length ? _wdcp_sd.default_service : 'comments';
toggle_discussion_service(initial_service);
	
});
})(jQuery);
