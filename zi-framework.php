<?php 
/*
 *************************************************************************************
 * 
 *  Config file for zi framework 
 * 				(This file is a heart of zi framework never delete this file)
 * 
 ************************************************************************************* 
 */
 
/* zi functions and definitions */

/*-----------------------------------------------------------------------------------*/
/* Define Constants
/*-----------------------------------------------------------------------------------*/
define('SITE_URL', home_url());
define('THEME_PATH', get_template_directory().'/');
define('THEME_URI', get_template_directory_uri().'/');
define('THEME_FRAMEWORK', THEME_PATH.'framework/');
define('THEME_ADMIN', THEME_FRAMEWORK.'admin/');
define('THEME_FRONT', THEME_FRAMEWORK.'front/');
define('THEME_COMMON', THEME_FRAMEWORK.'common/');
define('themenamespace', get_current_theme());

/*-----------------------------------------------------------------------------------*/
/* TGM Plugin Activation
/*-----------------------------------------------------------------------------------*/
require_once THEME_ADMIN . 'tgm-plugin-activation/init.php';

/*-----------------------------------------------------------------------------------*/
/* Aqua Resizer
/*-----------------------------------------------------------------------------------*/
require_once THEME_COMMON . 'aq_resizer.php'; 

/*-----------------------------------------------------------------------------------*/
/* File for custom post type table columns
/*-----------------------------------------------------------------------------------*/
//require_once THEME_ADMIN . 'post_column.php'; 

/*-----------------------------------------------------------------------------------*/
/* File for predefine functions
/*-----------------------------------------------------------------------------------*/
require_once THEME_COMMON . 'functions.php'; 

/*-----------------------------------------------------------------------------------*/
/* File for all operations of front end
/*-----------------------------------------------------------------------------------*/
require_once THEME_FRONT . 'functions.php'; 

/*-----------------------------------------------------------------------------------*/
/* Register the Redux Framework config file
/*-----------------------------------------------------------------------------------*/
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' ) ) 
{
    require_once( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' );
}

if ( !isset( $redux_demo) ) 
{
    require_once( dirname( __FILE__ ) . '/admin/ReduxCore/turya-config.php' );
}

/*-----------------------------------------------------------------------------------*/
/* Register the post types config file
/*-----------------------------------------------------------------------------------*/
require_once THEME_ADMIN . 'cpt-metabox/init.php'; 
?>
