jQuery(document).ready(function($)
{
	$("#past-missions li a").click(function(event)
	{
		event.preventDefault();
		var link = $(this);
		$("#past-missions li article").remove();
		$.post('http://www.cubeworld.co/wp-content/themes/cubeworld/includes/scraping.php',
			{ref: $(this).attr('href')},
			function(data)
			{
				link.after('<article>'+data+'</article>');
			});
	});
});