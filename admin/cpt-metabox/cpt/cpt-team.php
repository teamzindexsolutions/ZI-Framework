<?php
/**
 * File for team custom post type and meta data
 *
 * @package WordPress
 * @subpackage turya
 * @since turya 1.0
 */
 
/*-----------------------------------------------------------------------------------*/
/* Initialize the team post type
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'tw3nuts_team_register' );  
function tw3nuts_team_register() 
{
	$labels = array(
		'name' => __( 'Team', themenamespace ),
		'add_new' => __( 'Add New', themenamespace ),
		'add_new_item' => __( 'Add New Team Member', themenamespace),
		'edit_item' => __( 'Edit Team Member', themenamespace),
		'new_item' => __( 'New Team Member', themenamespace ),
		'view_item' => __( 'View Team Member', themenamespace ),
		'search_items' => __( 'Search Team Members', themenamespace ),
		'not_found' => __( 'No items found', themenamespace ),
		'not_found_in_trash' => __( 'No items found in Trash', themenamespace ), 
		'parent_item_colon' => '',
		'menu_name' => 'Team'
	);
    
    $args = array(
        'labels' => $labels,
        'menu_icon' => 'dashicons-businessman',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'profile', 'with_front' => false ),
        'exclude_from_search' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
       );  

    register_post_type( 'team' , $args );
}

/*-----------------------------------------------------------------------------------*/
/* Add the help desk of team
/*-----------------------------------------------------------------------------------*/
add_action( 'contextual_help', 'team_help_text', 10, 3 );
function team_help_text( $contextual_help, $screen_id, $screen ) 
{
    if ( 'team' == $screen->id )
    {
        $contextual_help =
        '<h3>' . __( 'Things to remember when adding a Team Member:', themenamespace ) . '</h3>' .
        '<ul>' .
        '<li>' . __( 'Add your team member name. (ie; John Doi).', themenamespace ) . '</li>' .
        '<li>' . __( 'Upload a featured image (headshot possibly) for your team member.', themenamespace ) . '</li>' .
        '<li>' . __( 'Add a designation title for your team member. (ie; CEO).', themenamespace ) . '</li>' .
        '<li>' . __( 'Add any Social Networks your Team Member belongs to. (ie; Twitter, facebook. Linkedin etc...).', themenamespace ) . '</li>' .
        '</ul>';
    }
    elseif ( 'edit-team' == $screen->id ) 
    {
        $contextual_help = '<p>' . __( 'A list of all team members appear below. To edit a member, click on the Team Member name.', themenamespace ) . '</p>';
    }
    return $contextual_help;
}

/*-----------------------------------------------------------------------------------*/
/* Team featured Images
/*-----------------------------------------------------------------------------------*/
add_action( 'do_meta_boxes', 'team_image_box' );
function team_image_box() 
{
	remove_meta_box( 'postimagediv', 'team', 'side' );
	add_meta_box( 'postimagediv', __( 'Team Member Image', themenamespace ), 'post_thumbnail_meta_box', 'team', 'side', 'low' );
}

/*
add_action( 'wp_ajax_post_sort', 'team_save_order' );
function team_save_order() 
{
    global $wpdb;
 
    $order = explode( ',', $_POST['order'] );
    $counter = 0;
 
    foreach ( $order as $post_id ) {
        $wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $post_id) );
        $counter++;
    }
    die( 1 );
}*/


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields for team member
/*-----------------------------------------------------------------------------------*/
$prefix = 'w3n_';
$meta_box_team = array(
	'id' => 'team_member',
    'title' => __( 'Team Member Details', themenamespace ),
    'page' => 'team',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    	array(
    		'name' =>  __( 'Member Designation', themenamespace ),
    	    'desc' => __( 'Enter a Designation for the Team Member <br />(ie; CEO)', themenamespace ),
    	    'id' => $prefix . 'member_designation',
    	    'type' => 'text',
    	    'std' => 'CEO'
    	),
    	
    	array(
    		'name' =>  __( 'Email Address', themenamespace ),
    	    'desc' => __( 'Enter a email address for the Team Member <br />(ie; johan@w3nuts.co.in)', themenamespace ),
    	    'id' => $prefix . 'member_email',
    	    'type' => 'text',
    	    'std' => 'johan@w3nuts.co.in'
    	),
    	
    	array(
           'name' => __( 'Facebook', themenamespace ),
           'desc' => __( 'Enter your Facebook Profile URL <br />(ie; http://facebook.com/w3nuts)', themenamespace ),
           'id' => $prefix . 'member_facebook',
           'type' => 'text',
           'std' => '#'
        ),
    	
        array(
           'name' => __( 'Twitter', themenamespace ),
           'desc' => __( 'Enter your Twitter Profile URL <br />(ie; http://twitter.com/w3nuts)', themenamespace ),
           'id' => $prefix . 'member_twitter',
           'type' => 'text',
           'std' => '#'
        ),
		
		array(
           'name' => __( 'Dribbble', themenamespace ),
           'desc' => __( 'Enter your Dribbble Profile URL <br />(ie; http://dribbble.com/w3nuts)', themenamespace ),
           'id' => $prefix . 'member_dribbble',
           'type' => 'text',
           'std' => '#'
        ),
		
		array(
           'name' => __( 'Pinterest', themenamespace ),
           'desc' => __( 'Enter your Pinterest Profile URL <br />(ie; http://pinterest.com/w3nuts)', themenamespace ),
           'id' => $prefix . 'member_pinterest',
           'type' => 'text',
           'std' => '#'
        ),
        
       array(
           'name' => __( 'Instagram', themenamespace ),
           'desc' => __( 'Enter your Instagram Profile URL <br />(ie; http://instagram.com/w3nuts)', themenamespace ),
           'id' => $prefix . 'member_instagram',
           'type' => 'text',
           'std' => '#'
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_menu', 'w3nuts_add_box_team' );
function w3nuts_add_box_team() 
{
	global $meta_box_team;	
	add_meta_box( $meta_box_team['id'], $meta_box_team['title'], 'w3nuts_show_box_team', $meta_box_team['page'], $meta_box_team['context'], $meta_box_team['priority'] );	
}

/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function w3nuts_show_box_team() 
{
    global $meta_box_team, $post;	
	// Use nonce for verification
	echo '<input type="hidden" name="w3nuts_add_box_team_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';

	echo '<table class="form-table">';
	foreach ( $meta_box_team['fields'] as $field ) 
	{
			// get current post meta data
			$meta = get_post_meta( $post->ID, $field['id'], true );
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
					'<th style="width:25%; font-weight: normal;">
						<label for="', $field['id'], '">
							<strong>', $field['name'], '</strong>
							<p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p>
						</label>
					</th>',
				
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			echo '</td></tr>';		
		}
		
		echo '</table>';
}

/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/

// Save data from meta box
add_action( 'save_post', 'w3nuts_save_data_team' );
function w3nuts_save_data_team( $post_id ) 
{
    global $meta_box_team;

    // verify nonce
    if ( !isset( $_POST['w3nuts_add_box_team_nonce'] ) || !wp_verify_nonce( $_POST['w3nuts_add_box_team_nonce'], basename( __FILE__ ) ) ) 
    {
    	return $post_id;
    }

    // check autosave
    if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) 
    {
        return $post_id;
    }

    // check permissions
    if ( 'page' == $_POST['post_type'] ) 
    {
        if ( !current_user_can( 'edit_page', $post_id ) ) 
        {
            return $post_id;
        }
    } 
    elseif ( !current_user_can( 'edit_post', $post_id ) ) 
    {
        return $post_id;
    }

    foreach ( $meta_box_team['fields'] as $field ) 
    { 
		// save each option
        $old = get_post_meta( $post_id, $field['id'], true );
        $new = $_POST[$field['id']];

        if ( $new && $new != $old ) 
        { 
			// compare changes to existing values
            update_post_meta( $post_id, $field['id'], $new );
        } 
        elseif ( '' == $new && $old ) 
        {
            delete_post_meta( $post_id, $field['id'], $old );
        }
    }
    
}

/*-----------------------------------------------------------------------------------*/
/* set post image size
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ) 
{
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 300, 300, true );
}

/*-----------------------------------------------------------------------------------*/
/* Team manage columns
/*-----------------------------------------------------------------------------------*/
// edit team columns
add_filter( 'manage_edit-team_columns', 'w3nuts_edit_team_columns' ) ;
function w3nuts_edit_team_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Member Name', themenamespace ),
		'img' => __( 'Member Image', themenamespace ),
		'team_member_designation' => __( 'Member Designation', themenamespace ),
		'bio' => __( 'Bio Details', themenamespace ),
		'member-email' => __( 'Email', themenamespace ),
		'date' => __( 'Date', themenamespace )
	);
	return $columns;
}

// Show team details
add_action( 'manage_team_posts_custom_column', 'w3nuts_manage_team_columns', 10, 2 );
function w3nuts_manage_team_columns( $column, $post_id )
{
	global $post;
	switch( $column ) 
	{	
		case 'img' :
			$img_url = w3nuts_fimg();;
			if ( empty( $img_url ) )
				echo __( 'No Images', themenamespace );
			else
                echo '<img src="'.aq_resize($img_url,150,160,true,true,true).'" alt="'.get_the_title($post->ID).'" />';
			break;
			
		case 'team_member_designation' :
			$designation = w3nuts_gpm('member_designation');
			if ( $designation )
				echo __( $designation, themenamespace );
			else
                echo __( 'No designation added', themenamespace );
			break;
	
		case 'bio' :
			$content = wp_strip_all_tags(get_the_content());
			echo $ccount = count($content);
			echo $fdata = ($ccount>150)?substr("abcdef", 0, -1)."...":$content; 
			/*if ( $content )
				echo __( $fdata, themenamespace );
			else
                echo __( 'No content Added',themenamespace);*/
			break;
			
		case 'member-email' :
			$member_email = w3nuts_gpm('member_email');
			if ( $member_email )
				echo '<a target="_new" href="mailto:'.$member_email.'">'.$member_email.'</a>';
			else
                echo __( 'Email Address not added', themenamespace);
			break;
			
		default :
			break;
	}
}
?>
