<?php
/*
 *  
 * File for admin Colums
 * 
 */

// Table column: Slider
add_filter( 'manage_edit-slider_columns', 'w3nuts_edit_slider_columns' ) ;
function w3nuts_edit_slider_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Slide Title', themenamespace ),
		'img' => __( 'Image', themenamespace ),
		'first_caption' => __( 'First Caption', themenamespace ),
		'second_caption' => __( 'Second Caption', themenamespace ),
		'content' => __( 'Content', themenamespace ),
		'date' => __( 'Date', themenamespace )
	);
	return $columns;
}

add_action( 'manage_slider_posts_custom_column', 'w3nuts_manage_slider_columns', 10, 2 );
function w3nuts_manage_slider_columns( $column, $post_id )
{
	global $post;
	switch( $column ) 
	{	
		case 'img' :
			$img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
			if ( empty( $img_url ) )
				echo __( 'No Images', themenamespace );
			else
                echo '<img src="'.aq_resize($img_url,100,160,false).'" alt="'.get_the_title($post->ID).'" />';
			break;
			
		case 'first_caption' :
			$caption1 = w3nuts_gpm('first-caption-text');
			if ( $caption1 )
				echo __( $caption1, themenamespace );
			else
                echo __( 'No Caption Added', themenamespace );
			break;
			
		case 'second_caption' :
			$caption2 = w3nuts_gpm('second-caption-text');
			if ( $caption2 )
				echo __( $caption2, themenamespace );
			else
                echo __( 'No Caption Added', themenamespace );
			break;
		
		case 'content' :
			$slide_content = w3nuts_gpm('slide-content');
			if ( $slide_content )
				echo __( $slide_content, themnamespace );
			else
                echo __( 'No content Added', themnamespace );
			break;
			
		default :
			break;
	}
}

// Table column: Slider
add_filter( 'manage_edit-features_columns', 'w3nuts_edit_features_columns' ) ;
function w3nuts_edit_features_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'securitypress' ),
		'img' => __( 'Image', 'securitypress' ),
		'content' => __( 'Content', 'securitypress' ),
		'date' => __( 'Date', 'securitypress' )
	);
	return $columns;
}

add_action( 'manage_features_posts_custom_column', 'w3nuts_manage_features_columns', 10, 2 );
function w3nuts_manage_features_columns( $column, $post_id )
{
	global $post;
	switch( $column ) 
	{	
		case 'img' :
			$img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
			if ( empty( $img_url ) )
				echo __( 'No Images', 'securitypress' );
			else
                echo '<img src="'.aq_resize($img_url,100,160,false).'" alt="'.get_the_title($post->ID).'" />';
			break;
			
		case 'content' :
			$content = get_the_content();
			if ( $content )
				echo __( $content	, 'securitypress' );
			else
                echo __( 'No content Added', 'securitypress' );
			break;
			
		default :
			break;
	}
}




add_filter( 'manage_edit-faq_columns', 'w3nuts_edit_faq_columns' ) ;
function w3nuts_edit_faq_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'FAQ Title', 'securitypress' ),
		'content' => __( 'FAQ Content', 'securitypress' ),
		'date' => __( 'Date', 'securitypress' )
	);
	return $columns;
}

add_action( 'manage_faq_posts_custom_column', 'manage_specifications_columns', 10, 2 );
function manage_specifications_columns( $column, $post_id )
{
	global $post;
	switch( $column ) 
	{	
		case 'content' :
			$content = get_the_content();
			if($content) :
				echo __(substr($content,0,250), 'securitypress' );
			else :
				echo __( 'No Content', 'securitypress' );
			endif;	
			break;
			
		default :
			break;
	}
}

add_filter( 'manage_edit-testimonial_columns', 'w3nuts_edit_testimonial_columns' ) ;
function w3nuts_edit_testimonial_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'securitypress' ),
		'content' => __( 'Content', 'securitypress' ),
		'date' => __( 'Date', 'securitypress' )
	);
	return $columns;
}

add_action( 'manage_testimonial_posts_custom_column', 'manage_testimonial_columns', 10, 2 );
function manage_testimonial_columns( $column, $post_id )
{
	global $post;
	switch( $column ) 
	{	
		case 'content' :
			$content = get_the_content();
			if($content) :
				echo __(substr($content,0,250), 'securitypress' );
			else :
				echo __( 'No Content', 'securitypress' );
			endif;	
			break;
			
		default :
			break;
	}
}?>
