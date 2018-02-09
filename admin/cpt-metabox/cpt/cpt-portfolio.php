<?php

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 300, 300, true );
}

add_action( 'init', 'portfolio_register' );  

function portfolio_register() {
    $labels = array(
        'name' => __( 'Portfolio', 'konvers' ),
        'add_new' => __( 'Add New', 'konvers' ),
        'add_new_item' => __( 'Add New Portfolio Item', 'konvers' ),
        'edit_item' => __( 'Edit Portfolio Item', 'konvers' ),
        'new_item' => __( 'New Portfolio Item', 'konvers' ),
        'view_item' => __( 'View Portfolio Item', 'konvers' ),
        'search_items' => __( 'Search Portfolio Items', 'konvers' ),
        'not_found' => __( 'No items found', 'konvers' ),
        'not_found_in_trash' => __( 'No items found in Trash', 'konvers' ), 
        'parent_item_colon' => '',
        'menu_name' => 'Portfolio'
        );
    
    $args = array(
        'labels' => $labels,
        'menu_icon' => 'dashicons-images-alt2',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'project', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
       );  

    register_post_type( 'portfolio' , $args );
}

add_action( 'contextual_help', 'portfolio_help_text', 10, 3 );
function portfolio_help_text( $contextual_help, $screen_id, $screen ) {
    if ( 'portfolio' == $screen->id ) {
        $contextual_help =
        '<h3>' . __( 'Things to remember when adding a Portfolio item:', 'konvers' ) . '</h3>' .
        '<ul>' .
        '<li>' . __( 'Give the item a title. The title will be used as the item\'s headline.', 'konvers' ) . '</li>' .
        '<li>' . __( 'You can choose to insert either single images, image slideshow, audio, or video.', 'konvers' ) . '</li>' .
        '<li>' . __( 'Enter your portfolio item overview into the Visual or HTML area. The text will appear for your project overview.', 'konvers' ) . '</li>' .
        '<li>' . __( 'Choose (or first create) a Project Type. You will need to use these to enable the filterable Portfolio option.', 'konvers' ) . '</li>' .
        '</ul>';
    }
    elseif ( 'edit-portfolio' == $screen->id ) {
        $contextual_help = '<p>' . __( 'A list of all Portfolio items appear below. To edit an item, click on the items\'s title.', 'konvers' ) . '</p>';
    }
    return $contextual_help;
}

add_filter( 'manage_edit-portfolio_columns', 'portfolio_edit_columns' );   

function portfolio_edit_columns( $columns ){
        $columns = array(
            'cb' => '<input type=\'checkbox\' />',
            'title' => 'Project Name',
            'type' => 'Project Type',
        );  

        return $columns;
}

add_action( 'manage_posts_custom_column',  'portfolio_custom_columns' ); 

function portfolio_custom_columns( $column ){
        global $post;
        switch ( $column )
        {

            case 'type':
                echo get_the_term_list( $post->ID, 'project-type', '', ', ','' );
                break;
        }
}
 
function portfolio_save_order() {
    global $wpdb;
 
    $order = explode(',', $_POST['order']);
    $counter = 0;
 
    foreach ( $order as $post_id ) {
        $wpdb->update( $wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $post_id) );
        $counter++;
    }
    die(1);
}

add_action('wp_ajax_post_sort', 'portfolio_save_order');

/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'gt_';
 
$meta_box_portfolio = array(
	'id' => 'gt-meta-box-portfolio',
	'title' =>  __( 'Portfolio Details', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
    	array(
			'name' =>  __( 'Project Type', 'konvers' ),
			'desc' => __( 'Choose the type of project you wish to display', 'konvers' ),
			'id' => $prefix . 'project_type',
			"type" => "select",
			'std' => 'Image',
			'options' => array( 'Images', 'Slideshow', 'Hosted Video', 'Video', 'Audio' )
		),
		array(
    	   'name' => __( 'Client Name', 'konvers' ),
    	   'desc' => __( 'Client who the project was for', 'konvers' ),
    	   'id' => $prefix . 'client_name',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => __( 'Project Date', 'konvers' ),
    	   'desc' => __( 'Enter the date the project was completed', 'konvers' ),
    	   'id' => $prefix . 'project_date',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => __( 'Project URL', 'konvers' ),
    	   'desc' => __( 'Enter the URL for this project', 'konvers' ),
    	   'id' => $prefix . 'project_url',
    	   'type' => 'text',
    	   'std' => ''
    	),
	)
);

$meta_box_portfolio_portfolio_image = array(
	'id' => 'gt-meta-box-portfolio-image',
	'title' => __( 'Image Settings', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => '',
				"desc" => '',
				"id" => $prefix . "portfolio_upload_images",
				"type" => 'button',
				'std' => __( 'Upload Images', 'konvers' )
			)
    )
);

$meta_box_portfolio_portfolio_self_hosted_video = array(
	'id' => 'gt-meta-box-portfolio-self-hosted-video',
	'title' => __( 'Self Hosted Video', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'low',
	'fields' => array(
        array(
			'name' =>  __( 'MP4 file', 'konvers' ),
			'desc' => __( 'Add the URL to your self-hosted MP4 file', 'konvers' ),
			'id' => $prefix . 'video_mp4_file',
			"type" => "text",
			'std' => ''
		),
		array(
    	   'name' => __( 'WebM file', 'konvers' ),
    	   'desc' => __( 'Add the URL to your self-hosted WebM file', 'konvers' ),
    	   'id' => $prefix . 'video_wm_file',
    	   'type' => 'text',
    	   'std' => ''
    	),
        array(
    	   'name' => __( 'Ogg file', 'konvers' ),
    	   'desc' => __( 'Add the URL to your self-hosted Ogg/Ogv file', 'konvers' ),
    	   'id' => $prefix . 'video_ogg_file',
    	   'type' => 'text',
    	   'std' => ''
    	),
        array(
    	   'name' => __( 'Poster Image', 'konvers' ),
    	   'desc' => __( 'Add the URL to a poster image for your self-hosted video', 'konvers' ),
    	   'id' => $prefix . 'video_poster_image',
    	   'type' => 'text',
    	   'std' => ''
    	)
	)
);

$meta_box_portfolio_portfolio_video = array(
	'id' => 'gt-meta-box-portfolio-video',
	'title' => __( 'Video Settings', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __( 'Video Embed Code', 'konvers' ),
			'desc' => __( 'If you are using video from somewhere like YouTube, Vimeo etc... Please paste the embed code here. Width should be at least 1200px with any height.<br><br>', 'konvers' ),
			'id' => $prefix . 'video_embed_code',
			'type' => 'textarea',
			'std' => ''
		)
	)
);
	
$meta_box_portfolio_portfolio_audio = array(
	'id' => 'gt-meta-box-portfolio-audio',
	'title' => __( 'Audio Settings', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __( 'Audio Embed Code', 'konvers' ),
			'desc' => __( 'If you are using audio from somewhere like SoundCloud, please paste the embed code here. If you have multiple audio clips, you can paste them one under the other.', 'konvers' ),
			'id' => $prefix . 'audio_embed_code',
			'type' => 'textarea',
			'std' => ''
		)
	),
	
);

$meta_box_portfolio_extra = array(
	'id' => 'gt-meta-box-portfolio-extra',
	'title' =>  __( 'Additional Portfolio Details', 'konvers' ),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'low',
	'fields' => array(
        array(
			'name' => __( 'Brief Introduction', 'konvers' ),
		    'desc' => __( 'Add a brief introduction text block to your project', 'konvers' ),
		    'id' => $prefix . 'project_brief',
		    'type' => 'text',
		    'std' => ''
		),
        
        array(
			'name' => __( 'The Challenge', 'konvers' ),
		    'desc' => __( 'Add a brief text block of the challenge faced with the project', 'konvers' ),
		    'id' => $prefix . 'project_challenge',
		    'type' => 'textarea',
		    'std' => ''
		),
        array(
			'name' => __( 'The Solution', 'konvers' ),
		    'desc' => __( 'Add a brief text block of the solution you brought to the project', 'konvers' ),
		    'id' => $prefix . 'project_solution',
		    'type' => 'textarea',
		    'std' => ''
		),
        array(
			'name' => __( 'The Client\'s thoughts', 'konvers' ),
		    'desc' => __( 'Add a brief quote from your client on their thoughts about the finished project', 'konvers' ),
		    'id' => $prefix . 'project_client_quote',
		    'type' => 'textarea',
		    'std' => ''
		),
        array(
			'name' => __( 'Client Name', 'konvers' ),
		    'desc' => __( 'Enter the client name of the quote above', 'konvers' ),
		    'id' => $prefix . 'project_client_name',
		    'type' => 'text',
		    'std' => ''
		),
        array(
			'name' => __( 'Location of Photographs', 'konvers' ),
		    'desc' => __( 'Enter the location where the photographs were taken', 'konvers' ),
		    'id' => $prefix . 'project_photo_location',
		    'type' => 'text',
		    'std' => ''
		),
	)
);

add_action( 'admin_menu', 'gt_add_box_portfolio' );

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function gt_add_box_portfolio() {
	global $meta_box_portfolio, $meta_box_portfolio_extra, $meta_box_portfolio_portfolio_image, $meta_box_portfolio_portfolio_self_hosted_video, $meta_box_portfolio_portfolio_video, $meta_box_portfolio_portfolio_audio;
	
	add_meta_box( $meta_box_portfolio['id'], $meta_box_portfolio['title'], 'gt_show_box_portfolio', $meta_box_portfolio['page'], $meta_box_portfolio['context'], $meta_box_portfolio['priority'] );
    
    add_meta_box( $meta_box_portfolio_extra['id'], $meta_box_portfolio_extra['title'], 'gt_show_box_portfolio_extra', $meta_box_portfolio_extra['page'], $meta_box_portfolio_extra['context'], $meta_box_portfolio_extra['priority'] );

	add_meta_box( $meta_box_portfolio_portfolio_image['id'], $meta_box_portfolio_portfolio_image['title'], 'gt_show_box_portfolio_image', $meta_box_portfolio_portfolio_image['page'], $meta_box_portfolio_portfolio_image['context'], $meta_box_portfolio_portfolio_image['priority'] );
    
    add_meta_box( $meta_box_portfolio_portfolio_self_hosted_video['id'], $meta_box_portfolio_portfolio_self_hosted_video['title'], 'gt_show_box_portfolio_self_hosted_video', $meta_box_portfolio_portfolio_self_hosted_video['page'], $meta_box_portfolio_portfolio_self_hosted_video['context'], $meta_box_portfolio_portfolio_self_hosted_video['priority'] );

	add_meta_box( $meta_box_portfolio_portfolio_video['id'], $meta_box_portfolio_portfolio_video['title'], 'gt_show_box_portfolio_video', $meta_box_portfolio_portfolio_video['page'], $meta_box_portfolio_portfolio_video['context'], $meta_box_portfolio_portfolio_video['priority'] );
	
	add_meta_box( $meta_box_portfolio_portfolio_audio['id'], $meta_box_portfolio_portfolio_audio['title'], 'gt_show_box_portfolio_audio', $meta_box_portfolio_portfolio_audio['page'], $meta_box_portfolio_portfolio_audio['context'], $meta_box_portfolio_portfolio_audio['priority'] );  

}

/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function gt_show_box_portfolio() {
	global $meta_box_portfolio, $post;
	
	$wp_version = get_bloginfo('version');

	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce(basename( __FILE__ )), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:18px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="4" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				} 
				
				echo'</select>';
			
			break;
		}

	}
 
	echo '</table>';
}

function gt_show_box_portfolio_extra() {
	global $meta_box_portfolio_extra, $post;
	
	$wp_version = get_bloginfo('version');

	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce(basename( __FILE__ )), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio_extra['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:18px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="4" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				} 
				
				echo'</select>';
			
			break;
		}

	}
 
	echo '</table>';
}

function gt_show_box_portfolio_image() {
	global $meta_box_portfolio_portfolio_image, $post;
	
	$wp_version = get_bloginfo( 'version' );
 	
	echo '<p style="padding:10px 0 0 0; font-weight: normal; color:#666;">'.__( 'Upload images to be used for this project item (images should be at least 1200px wide).<br />Set a Featured Image (to the box on the right) that will be displayed on the homepage, and portfolio index pages. Then upload the main images to be used in your showcase using the Upload button below.', 'konvers' ).'</p>';
	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__) ), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio_portfolio_image['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin: 20px; float:left;" />';
			
			break;
 
			//If Button	
			case 'button':
				if( version_compare($wp_version, '3.4.2', '>') ) {
					// Using WP3.5+
			?>
				<script>
				jQuery(function($) {
					var frame,
					    images = '<?php echo get_post_meta( $post->ID, 'gt_image_ids', true ); ?>',
					    selection = loadImages(images);

					$('#gt_images_upload').on('click', function(e) {
						e.preventDefault();

						// Set options for 1st frame render
						var options = {
							title: '<?php _e( "Create Featured Gallery", "konvers" ); ?>',
							state: 'gallery-edit',
							frame: 'post',
							selection: selection
						};

						// Check if frame or gallery already exist
						if( frame || selection ) {
							options['title'] = '<?php _e( "Edit Featured Gallery", "konvers" ); ?>';
						}

						frame = wp.media(options).open();
						
						// Tweak views
						frame.menu.get('view').unset('cancel');
						frame.menu.get('view').unset('separateCancel');
						frame.menu.get('view').get('gallery-edit').el.innerHTML = '<?php _e( "Edit Featured Gallery", "konvers" ); ?>';
						frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

						// When we are editing a gallery
						overrideGalleryInsert();
						frame.on('toolbar:render:gallery-edit', function() {
    						overrideGalleryInsert();
						});
						
						frame.on('content:render:browse', function(browser) {
						    if (!browser) return;
						    // Hide Gallery Setting in sidebar
						    browser.sidebar.on('ready', function(){
						        browser.sidebar.unset('gallery');
						    });
						    // Hide filter/search as they don't work 
 							browser.toolbar.on('ready', function(){ 
								if(browser.toolbar.controller._state == 'gallery-library'){ 
									browser.toolbar.$el.hide(); 
								} 
 							}); 
						});
						
						// All images removed
						frame.state().get('library').on( 'remove', function() {
						    var models = frame.state().get('library');
							if(models.length == 0){
							    selection = false;
    							$.post(ajaxurl, { ids: '', action: 'gt_save_images', post_id: gt_ajax.post_id, nonce: gt_ajax.nonce });
							}
						});
						
						// Override insert button
						function overrideGalleryInsert() {
    						frame.toolbar.get('view').set({
								insert: {
									style: 'primary',
									text: '<?php _e( "Save Featured Gallery", "konvers" ); ?>',

									click: function() {
										var models = frame.state().get('library'),
										    ids = '';

										models.each( function( attachment ) {
										    ids += attachment.id + ','
										});

										this.el.innerHTML = '<?php _e( "Saving...", "konvers" ); ?>';
										
										$.ajax({
											type: 'POST',
											url: ajaxurl,
											data: { 
												ids: ids, 
												action: 'gt_save_images', 
												post_id: gt_ajax.post_id, 
												nonce: gt_ajax.nonce 
											},
											success: function(){
    											selection = loadImages(ids);
    											$('#gt_image_ids').val( ids );
    											frame.close();
											},
											dataType: 'html'
										}).done( function( data ) {
											$('.gt-gallery-thumbs').html( data );
										}); 
									}
								}
							});
						}
					});
					
					// Load images
					function loadImages(images) {
						if( images ){
						    var shortcode = new wp.shortcode({
            					tag:    'gallery',
            					attrs:   { ids: images },
            					type:   'single'
            				});
				
						    var attachments = wp.media.gallery.attachments( shortcode );

            				var selection = new wp.media.model.Selection( attachments.models, {
            					props:    attachments.props.toJSON(),
            					multiple: true
            				});
            
            				selection.gallery = attachments.gallery;
            
            				// Fetch the query's attachments, and then break ties from the
            				// query to allow for sorting.
            				selection.more().done( function() {
            					// Break ties with the query.
            					selection.props.set({ query: false });
            					selection.unmirror();
            					selection.props.unset('orderby');
            				});
            				
            				return selection;
						}
						
						return false;
					}
					
				});
				</script>
			<?php
				// SPECIAL CASE:
				// std controls button text; unique meta key for image uploads
				$meta = get_post_meta( $post->ID, 'gt_image_ids', true );
				$thumbs_output = '';
				$button_text = ($meta) ? __( 'Edit Gallery', 'konvers' ) : $field['std'];
				if( $meta ) {
					$field['std'] = __( 'Edit Gallery', 'konvers' );
					$thumbs = explode(',', $meta);
					$thumbs_output = '';
					foreach( $thumbs as $thumb ) {
						$thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, array(32,32) ) . '</li>';
					}
				}

			    echo 
			    	'<td>
			    		<input type="button" class="button" name="' . $field['id'] . '" id="gt_images_upload" value="' . $button_text .'" />
			    		
			    		<input type="hidden" name="gt_image_ids" id="gt_image_ids" value="' . ($meta ? $meta : 'false') . '" />

			    		<ul class="gt-gallery-thumbs">' . $thumbs_output . '</ul>
			    	</td>';
		    } else {
				// Using pre WP3.5
				echo '<tr><td><input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			}
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style=" display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			
				echo'<select name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				} 
				
				echo'</select>';
			
			break;
		}

	}
 
	echo '</table>';
}

function gt_show_box_portfolio_self_hosted_video() {
	global $meta_box_portfolio_portfolio_self_hosted_video, $post;
	
	$wp_version = get_bloginfo('version');
 	
	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio_portfolio_self_hosted_video['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:20px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:18px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="8" cols="5" style="width:100%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				if( version_compare($wp_version, '3.4.2', '>') ) {
			?> 
				<script>
				jQuery(function($) {
					var frame;

					$('#<?php echo $field['id']; ?>').on('click', function(e) {
						e.preventDefault();

						// Set options for 1st frame render
						var options = {
							state: 'insert',
							frame: 'post'
						};

						frame = wp.media(options).open();
						
						// Tweak views
						frame.menu.get('view').unset('gallery');
						frame.menu.get('view').unset('featured-image');
												
						frame.toolbar.get('view').set({
							insert: {
								style: 'primary',
								text: '<?php _e("Insert", "konvers"); ?>',

								click: function() {
									var models = frame.state().get('selection'),
										url = models.first().attributes.url
										field = '<?php echo $field['id']; ?>';
										field = field.replace('_button', '');

									$('#'+field).val( url ); 

									frame.close();
								}
							}
						});
						

					});
					
				});
				</script>
			<?php
				} // if version compare

				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
		}

	}
 
	echo '</table>';
}

function gt_show_box_portfolio_video() {
	global $meta_box_portfolio_portfolio_video, $post;
	
	$wp_version = get_bloginfo('version');
 	
	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio_portfolio_video['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:20px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:18px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="8" cols="5" style="width:100%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				if( version_compare($wp_version, '3.4.2', '>') ) {
			?> 
				<script>
				jQuery(function($) {
					var frame;

					$('#<?php echo $field['id']; ?>').on('click', function(e) {
						e.preventDefault();

						// Set options for 1st frame render
						var options = {
							state: 'insert',
							frame: 'post'
						};

						frame = wp.media(options).open();
						
						// Tweak views
						frame.menu.get('view').unset('gallery');
						frame.menu.get('view').unset('featured-image');
												
						frame.toolbar.get('view').set({
							insert: {
								style: 'primary',
								text: '<?php _e("Insert", "konvers"); ?>',

								click: function() {
									var models = frame.state().get('selection'),
										url = models.first().attributes.url
										field = '<?php echo $field['id']; ?>';
										field = field.replace('_button', '');

									$('#'+field).val( url ); 

									frame.close();
								}
							}
						});
						

					});
					
				});
				</script>
			<?php
				} // if version compare

				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
		}

	}
 
	echo '</table>';
}

function gt_show_box_portfolio_audio() {
	global $meta_box_portfolio_portfolio_audio, $post;
	
	$wp_version = get_bloginfo('version');
 	
	// Use nonce for verification
	echo '<input type="hidden" name="gt_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
 
	echo '<table class="form-table">';
 
	foreach ( $meta_box_portfolio_portfolio_audio['fields'] as $field ) {
		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		switch ( $field['type'] ) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:20px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%; font-weight: normal;"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><p style="line-height:18px; display:block; color:#666; margin:5px 0 0 0;">'. $field['desc'].'</p></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="8" cols="5" style="width:100%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				if( version_compare($wp_version, '3.4.2', '>') ) {
			?> 
				<script>
				jQuery(function($) {
					var frame;

					$('#<?php echo $field['id']; ?>').on('click', function(e) {
						e.preventDefault();

						// Set options for 1st frame render
						var options = {
							state: 'insert',
							frame: 'post'
						};

						frame = wp.media(options).open();
						
						// Tweak views
						frame.menu.get('view').unset('gallery');
						frame.menu.get('view').unset('featured-image');
												
						frame.toolbar.get('view').set({
							insert: {
								style: 'primary',
								text: '<?php _e("Insert", "konvers"); ?>',

								click: function() {
									var models = frame.state().get('selection'),
										url = models.first().attributes.url
										field = '<?php echo $field['id']; ?>';
										field = field.replace('_button', '');

									$('#'+field).val( url ); 

									frame.close();
								}
							}
						});
						

					});
					
				});
				</script>
			<?php
				} // if version compare

				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
		}

	}
 
	echo '</table>';
}
 
add_action( 'save_post', 'gt_save_data_portfolio' );

/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function gt_save_data_portfolio( $post_id ) {
	global $meta_box_portfolio, $meta_box_portfolio_extra, $meta_box_portfolio_portfolio_image, $meta_box_portfolio_portfolio_self_hosted_video, $meta_box_portfolio_portfolio_video, $meta_box_portfolio_portfolio_audio ;
 
	// verify nonce
	if ( !isset( $_POST['gt_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['gt_meta_box_nonce'], basename( __FILE__ ) ) ) {
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
 
	foreach ( $meta_box_portfolio['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = $_POST[$field['id']];
 
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
    
    foreach ( $meta_box_portfolio_extra['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = $_POST[$field['id']];
 
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

	foreach ( $meta_box_portfolio_portfolio_image['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = ( isset( $_POST[$field['id']] ) ) ? $_POST[$field['id']] : false;
 
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}
    
    foreach ( $meta_box_portfolio_portfolio_self_hosted_video['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = $_POST[$field['id']];
 
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}
	
	foreach ( $meta_box_portfolio_portfolio_video['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = $_POST[$field['id']];
 
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}
	
	foreach ( $meta_box_portfolio_portfolio_audio['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = $_POST[$field['id']];

		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}

}

// Save Image IDs
function gt_save_images() {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	if ( !isset( $_POST['ids'] ) || !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'gt-ajax' ) )
		return;
	
	if ( !current_user_can( 'edit_posts' ) ) return;
 
	$ids = strip_tags( rtrim( $_POST['ids'], ',' ) );
	update_post_meta( $_POST['post_id'], 'gt_image_ids', $ids );

	// update thumbs
	$thumbs = explode( ',', $ids );
	$thumbs_output = '';
	foreach( $thumbs as $thumb ) {
		$thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, array(32,32) ) . '</li>';
	}

	echo $thumbs_output;

	die();
}

add_action( 'wp_ajax_gt_save_images', 'gt_save_images' );

// Save data from meta box
function mytheme_save_data($post_id) {
	global $meta_box;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

//theme template tag
function ionicons_icon(){
	global $prefix;
	global $post;
	$icon = get_post_meta($post->ID, $prefix.'icon', true); 
	if($icon != ''): ?>
		<i class="ion-<?php echo $icon; ?>"></i>
	<?php endif;
}

/*-----------------------------------------------------------------------------------*/
/*	Queue Scripts
/*-----------------------------------------------------------------------------------*/

function gt_admin_scripts_portfolio() {
	global $post;
	$wp_version = get_bloginfo( 'version' );

	// enqueue scripts
	wp_enqueue_script( 'media-upload' );
	if( version_compare( $wp_version, '3.4.2', '<=' ) ) {

		wp_enqueue_script( 'thickbox' );

		wp_enqueue_style( 'thickbox' );
	}

	if( isset( $post ) ) {
		wp_localize_script( 'jquery', 'gt_ajax', array(
		    'post_id' => $post->ID,
		    'nonce' => wp_create_nonce( 'gt-ajax' )
		) );
	}

}
add_action( 'admin_enqueue_scripts', 'gt_admin_scripts_portfolio' );