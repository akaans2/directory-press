<?php

	/***************** DO NOT EDIT THIS FILE *************************
	******************************************************************
	
	INFORMATION:
	------------
	
	This is a core configuration file for the PremiumPress framework
	developed by Mark Fail, CEO of PremiumPress www.premiumpress.com
	
	LAST UPDATED: June 26th 2011
	EDITED BY: MARK FAIL
	------------------------------------------------------------------
	
	******************************************************************/
	
	// Defines the current version of the Framework.
	define( 'PPT_VERSION', '0.1' );
	
	//load framework
	require_once (TEMPLATEPATH ."/PPT/framework/ppt_api.php");
	require_once (TEMPLATEPATH ."/PPT/framework/ppt.php");
	require_once (TEMPLATEPATH ."/PPT/framework/ppt_core.php");
	require_once (TEMPLATEPATH ."/PPT/framework/ppt_admin.php"); 
	require_once (TEMPLATEPATH ."/PPT/framework/ppt_widgets.php"); 
	require_once (TEMPLATEPATH ."/PPT/framework/ppt_mobile.php");
			
	// Define a few default constants.
	ppt_initial_constants();
	
	// Define the templating constants.
	ppt_templating_constants();	
	
	if(!file_exists(PPT_CHILD_DIR."/system_customlogins.php") )
	die("<h1>Theme Install Error</h1> No theme folder found ".PPT_CHILD_DIR." ");
	
	// Load all PremiumPress classes.
	require_once (TEMPLATEPATH ."/PPT/class/class_design.php");
	require_once (TEMPLATEPATH ."/PPT/class/class_function.php");
	require_once (TEMPLATEPATH ."/PPT/class/class_member.php");
	require_once (TEMPLATEPATH ."/PPT/class/class_premiumpress.php");
	require_once (TEMPLATEPATH ."/PPT/class/class_import.php");
	
	// Load all custom theme classes.
	require_once (PPT_CHILD_DIR."/system_customlogins.php");	 
	require_once (PPT_CHILD_DIR."/system_customdesign.php");

	// CUSTOM THEME FUNCTIONS
	require_once (TEMPLATEPATH ."/PPT/func/funcs_premiumpress.php");		

	// ADMIN AREA INCLUDES
	require_once (TEMPLATEPATH . '/admin/admin-save.php');	
 
	// Load in any custom child theme functionality
	if(file_exists(PPT_CUSTOM_CHILD_DIR."/_functions.php") ){				 
		include(PPT_CUSTOM_CHILD_DIR.'/_functions.php');					
	}	
		
	$ThemeDesign 	= new Theme_Design;		
	$PPT 			= new PremiumPressTheme;
	$PPTFunction 	= new PremiumPressTheme_Function; 	
	$PPTDesign 		= new PremiumPressTheme_Design;	
	$PPTImport 		= new PremiumPressTheme_Import;			
	$PPTMobile 		= new PPT_MOBILE;
	
	// Registers the theme backbone class.
	ppt_register_class( 'theme', 'PPT' ); 	
	
	// Registers the Theme Options admin class.
	ppt_register_admin_class( 'options', 'PPT_Admin' );

	
	/**
	 * Framework theme object
	 * @global object $ppt_theme
	 * @since 0.3.0
	 */
	 
	$ppt_theme = PPT();	
		
	// Load all autoload classes.
	//add_action( 'after_setup_theme', 'ppt_autoload_classes' );
	
	// Load all contextual classes.
	//add_action( 'wp', 'ppt_load_contextual_classes', 1 );
	
	// Load admin pages.
	add_action( 'init', 'ppt_load_admin_pages', 100 ); 
	 
 
	$GLOBALS['bloginfo_url'] = HOME_URI;
	$GLOBALS['template_url'] = PPT_THEME_URI;
	
?>