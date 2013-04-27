jQuery(document).ready(function($)
{
	$("#past-missions li a").click(function(event)
	{
		event.preventDefault();
		var link = $(this);
		$("#past-missions li article").remove();
		$.post('http://cubeworld.razican.com/wp-content/themes/cubeworld/scraping.php',
			{ref: $(this).attr('href')},
			function(data)
			{
				link.after('<article>'+data+'</article>');
			});
	});
});