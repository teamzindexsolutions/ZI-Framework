<?php

include_once ( 'class-tgm-plugin-activation.php' );

function w3nuts_register_required_plugins() {

	$plugins = array(
        
      /*
        array(
			'name'     				=> 'Guu Custom Post Types',
            'version' 				=> '1.0',
			'slug'     				=> 'guu-cpt',
			'source'   				=> THEME_ADMIN . 'tgm-plugin-activation/plugins/guu-cpt.zip',
			'required' 				=> true,
			'force_activation' 		=> false,
			'force_deactivation' 	=> true,
			'external_url' 			=> '',
		),*/
     
        array(
			'name'     				=> 'Contact Form 7',
            'version' 				=> '4.3',
			'slug'     				=> 'contact-form-7',
			'source'   				=> THEME_ADMIN . 'tgm-plugin-activation/plugins/contact-form-7.zip',
			'required' 				=> true,
			'force_activation' 		=> false,
			'force_deactivation' 	=> true,
			'external_url' 			=> '',
		),
    
		/*
        array(
			'name'     				=> 'Page Loader (Custom)',
            'version' 				=> '1.7',
			'slug'     				=> 'pageloader',
			'source'   				=> THEME_ADMIN . 'tgm-plugin-activation/plugins/pageloader.zip',
			'required' 				=> false,
			'force_activation' 		=> false,
			'force_deactivation' 	=> true,
			'external_url' 			=> '',
		),
		*/
	);

	tgmpa( $plugins );

}

add_action( 'tgmpa_register', 'w3nuts_register_required_plugins' );
