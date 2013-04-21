<?php

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) OR
	strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest' OR
	! isset($_POST['ref']))
{
	header('Location: cubeworld.razican.com/404', 404);
	exit;
}

include('../wp-includes/simple_html_dom.php');
$html = file_get_html($_POST['ref']);

//$title = $html->find('h2.contentheading');
//$date = new DateTime($html->find('span.createdate'), new DateTimezone('America/New_York'));

$content = $html->find('div.article-content');
$content = str_replace(array('<p><br /></p>', 'src="/images'), array('', 'src="http://www.cubesat.org/images'), $content[0]);

echo $content;