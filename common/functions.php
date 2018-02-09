<?php 
/**
 * 
 * File define all custome functions  
 * 
 */

// @ get post meta of type plugin
function w3nuts_gpm($slug)
{
	return get_post_meta(get_the_ID(),'w3n_'.$slug.'',true);
}

// @ function for favicone 
function ab_favicon()
{
	if(ot_get_option('site_favicon'))
	{
		return '<link rel="icon" type="image/png" href="'.ot_get_option('site_favicon').'" sizes="32x32">';	
	}
}

function ab_logo($type)
{
	$logo = (ot_get_option($type))?ot_get_option($type):THEME_URI.'images/main-logo.png';
	$logo_class = ($type=="logo" || $type=="inner_logo")?'class="btnlogo logo"':'';
	
	$theme_logo='<a href="'.esc_url( home_url( '/' ) ).'" '.$logo_class.'>';
	$theme_logo.='<img src="'.$logo.'" alt="Madeira London" class="img-responsive"></a>';
	
	return $theme_logo;
}

function w3nuts_fimg()
{
	return wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
}

?>
