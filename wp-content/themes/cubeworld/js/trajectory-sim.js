jQuery(document).ready(function($)
{
	$('#toggle-form').click(function(event)
	{
		event.preventDefault();

		if ($('#traj-form').is(':hidden'))
		{
			$('#traj-form').show(1000);
			$(this).text('Hide from ▲');
		}
		else
		{
			$('#traj-form').hide(1000);
			$(this).text('Show from ▼');
		}
	});
});