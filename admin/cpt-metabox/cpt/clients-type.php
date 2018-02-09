<?php

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 300, 300, true );
}

add_action( 'init', 'clients_register' );  

function clients_register() {
    $labels = array(
        'name' => __( 'Clients', 'konvers' ),
        'add_new' => __( 'Add New', 'konvers' ),
        'add_new_item' => __( 'Add New Client', 'konvers' ),
        'edit_item' => __( 'Edit Client Item', 'konvers' ),
        'new_item' => __( 'New Client Item', 'konvers' ),
        'view_item' => __( 'View Client Item', 'konvers' ),
        'search_items' => __( 'Search Client Items', 'konvers' ),
        'not_found' => __( 'No items found', 'konvers' ),
        'not_found_in_trash' => __( 'No items found in Trash', 'konvers' ), 
        'parent_item_colon' => '',
        'menu_name' => 'Clients'
        );
    
    $args = array(
        'labels' => $labels,
        'menu_icon' => 'dashicons-groups',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => true,
        'exclude_from_search' => true,
        'supports' => array( 'title', 'page-attributes', 'thumbnail' )
       );  

    register_post_type( 'clients' , $args );
}

add_action( 'contextual_help', 'clients_help_text', 10, 3 );

function clients_help_text( $contextual_help, $screen_id, $screen ) {
    if ( 'clients' == $screen->id ) {
        $contextual_help =
        '<h3>' . __( 'Things to remember when adding a Client:', 'konvers' ) . '</h3>' .
        '<ul>' .
        '<li>' . __( 'Enter the client name into the title field (ie; Acme Company).', 'konvers' ) . '</li>' .
        '<li>' . __( 'Add a client logo via the Client Logo section.', 'konvers' ) . '</li>' .
        '<li>' . __( 'Enter a URL for the client logo to link to.', 'konvers' ) . '</li>' .
        '</ul>';
    }
    elseif ( 'edit-clients' == $screen->id ) {
        $contextual_help = '<p>' . __( 'A list of all clients appear below. To edit a client, click on the client name.', 'konvers' ) . '</p>';
    }
    return $contextual_help;
}

function clients_image_box() {
	remove_meta_box( 'postimagediv', 'clients', 'side' );
	add_meta_box( 'postimagediv', __( 'Client Logo', 'konvers' ), 'post_thumbnail_meta_box', 'clients', 'side', 'low' );
}
add_action( 'do_meta_boxes', 'clients_image_box' );

add_filter( 'manage_edit-clients_columns', 'clients_edit_columns' );   

function clients_edit_columns( $columns ) {
        $columns = array(
            'cb' => '<input type=\'checkbox\' />',
            'title' => 'Client Name',
            'text' => 'Client URL'
        );  

        return $columns;
}

add_action( 'manage_posts_custom_column',  'clients_custom_columns' ); 

function clients_custom_columns( $column ) {
        global $post;
        switch ( $column )
        {
                case 'text':
                $custom = get_post_custom();
                if(!empty($custom['gt_client_url'][0]))
                echo $custom['gt_client_url'][0];
                break;
        }
}
 
function clients_save_order() {
    global $wpdb;
 
    $order = explode( ',', $_POST['order'] );
    $counter = 0;
 
    foreach ( $order as $post_id ) {
        $wpdb->update( $wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $post_id) );
        $counter++;
    }
    die( 1 );
}
add_action( 'wp_ajax_post_sort', 'clients_save_order' );

/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'gt_';
 
$meta_box_clients = array(
		'id' => 'client_details',
        'title' => __('Client Details', 'konvers'),
        'page' => 'clients',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
        	array(
        		'name' => __( 'Client URL', 'konvers' ),
        	    'desc' => __( 'Enter a URL for your client logo to link to <em>(if applicable)</em>', 'konvers' ),
        	    'id' => $prefix . 'client_url',
        	    'type' => 'text',
        	    'std' => ''
        	)
    )
);

add_action( 'admin_menu', 'gt_add_box_clients' );

/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function gt_show_box_clients() {
    global $meta_box_clients, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="gt_add_box_clients_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';

	echo '<table class="form-table">';
		
	foreach ( $meta_box_clients['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES )), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			echo '</td></tr>';
		
		}
		
		echo '</table>';
}

add_action( 'save_post', 'gt_save_data_clients' );

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function gt_add_box_clients() {
	global $meta_box_clients;
	
	add_meta_box( $meta_box_clients['id'], $meta_box_clients['title'], 'gt_show_box_clients', $meta_box_clients['page'], $meta_box_clients['context'], $meta_box_clients['priority'] );
}

// Save data from meta box
function gt_save_data_clients( $post_id ) {
    global $meta_box_clients;

    // verify nonce
    if ( !isset( $_POST['gt_add_box_clients_nonce'] ) || !wp_verify_nonce( $_POST['gt_add_box_clients_nonce'], basename( __FILE__ ) ) ) {
    	return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    foreach ( $meta_box_clients['fields'] as $field ) { // save each option
        $old = get_post_meta( $post_id, $field['id'], true );
        $new = $_POST[$field['id']];

        if ( $new && $new != $old ) { // compare changes to existing values
            update_post_meta( $post_id, $field['id'], $new );
        } elseif ( '' == $new && $old ) {
            delete_post_meta( $post_id, $field['id'], $old );
        }
    }
}

?>