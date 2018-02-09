<?php
/*
 * *********************************************************************
 *  File for used in front
 * *********************************************************************
 */

// Add js and css
function framework_scripts()
{	
	$scripts = array(
		'files' => array(
			// include css
			array( 'file_name' => 'bootstrap', 'type' =>'css'),
			array( 'file_name' => 'style', 'type' => 'css'),
			array( 'file_name' => 'animate.min', 'type' => 'css'),
			array( 'file_name' => 'owl.carousel', 'type' => 'css'),
			array( 'file_name' => 'fonts','type' => 'fonts'),
			array( 'file_name' => 'style','type' => 'css'),
			array( 'file_name' => 'font-awesome.min','type' => 'css'),
			array( 'file_name' => 'menu', 'type' => 'css'),
			array( 'file_name' => 'masterslider', 'type' => 'css'),
			array( 'file_name' => 'ms-fullscreen','type' => 'css'),
			array( 'file_name' => 'ms-default','type' => 'css'),
			array( 'file_name' => 'news-filter','type' => 'css'),
			array( 'file_name' => 'tabs','type' => 'css'),
			array( 'file_name' => 'responsive','type' => 'css'),
			array( 'file_name' => 'bootstrap-select','type' => 'css'),
			array( 'file_name' => 'faq','type' => 'css'),
			array( 'file_name' => 'jquery-ui','type' => 'css'),
			
			// include js
			array( 'file_name' => 'jquery.min','type' => 'js'),
			array( 'file_name' => 'bootstrap.min','type' => 'js'),
			array( 'file_name' => 'owl.carousel.min','type' => 'js'),
			array( 'file_name' => 'browser_selector','type' => 'js'),
			array( 'file_name' => 'script','type' => 'js'),		
			array( 'file_name' => 'cbpFWTabs','type' => 'js'),
			array( 'file_name' => 'jquery.easing.min','type' => 'js'),
			array( 'file_name' => 'masterslider.min','type' => 'js'),
			array( 'file_name' => 'classie','type' => 'js'),
			array( 'file_name' => 'demo1','type' => 'js'),
			array( 'file_name' => 'AnimOnScroll','type' => 'js'),		
		)		
	);
	
	foreach ($scripts['files'] as $scripts)
	{
		$extension = ($scripts['type']=="fonts")?'css':$scripts['type'];
		$src = THEME_URI.$scripts['type']."/".$scripts['file_name'].".".$extension;
		
		if($scripts['type'] == "js")
		{			
			wp_register_script($file_box['file_name'], $src,'','', true);
			wp_enqueue_script($file_box['file_name']);
		}
		else
		{
			wp_enqueue_style( $scripts['file_name'], $src, true);
		}
	}
}
//add_action( 'wp_enqueue_scripts', 'framework_scripts' );

function new_excerpt_more($more) 
{
	global $post;
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');
?>
