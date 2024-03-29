<?php

/***************** DO NOT EDIT THIS FILE *************************
******************************************************************

INFORMATION:
------------

This is a core theme file, you should not need to edit 
this file directly. Code changes maybe lost during updates.

LAST UPDATED: June 26th 2011
EDITED BY: MARK FAIL
------------------------------------------------------------------

******************************************************************/

global $PPT, $PPTDesign, $ThemeDesign,$PPTFunction,$current_user; get_currentuserinfo();

 
/* ================ WEBSITE MAINTENANCE MODE =========================== */

if(get_option('maintenance_mode') == "yes" && !isset($_GET['redirect_to'])){	$msg = stripslashes(get_option("maintenance_mode_message")); if(strlen($msg)  < 1){ $msg ="Maintenance Mode On"; } die($msg);	}
 
/* ================ PREMIUMPRESS AMAZON/EBAY REDIRECT =========================== */
if(( strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "classifiedstheme" ) && is_single()){
	
		$redirect = get_post_meta($post->ID, "redirect", true);
		if($redirect == "yes"){
			$redirect_to = get_post_meta($post->ID, "buy_link", true);
			if($redirect_to == ""){
				$redirect_to = get_post_meta($post->ID, "link", true);
			}
			if($redirect_to == ""){
				$redirect_to = get_post_meta($post->ID, "amazon_link", true);
			}
			
			if($redirect_to != ""){
				header("location: ".$PPT->AffiliateLink($redirect_to));
				exit();
			}
		}
	
}
/* ================ PREMIUMPRESS FILE DOWNLOAD HASH =========================== */

if(isset($_POST) && isset($_POST['hash']) ){
	
		check_admin_referer('FileDownload');
		
		if(isset($_POST['fileID'])){
			$FILE_ID = $_POST['fileID'];
			
		}else{
			$FILE_ID = $post->ID;			
		}
		
		if(isset($_COOKIE['ItemDownload'.$FILE_ID]) || isset($_POST['force']) ){
		$IGNORECREDITDOWNLOAD =1;
		}else{
		$IGNORECREDITDOWNLOAD =0;
		}
		 
		$PPT->DownloadFile($FILE_ID,$IGNORECREDITDOWNLOAD);	
}	 

/* ================ PREMIUM PRESS CUSTOM OPTIONS =========================== */

// CORE OPTIONS
$GLOBALS['premiumpress']['theme'] 				= get_option('theme');
$GLOBALS['premiumpress']['theme_folder'] 		= strtolower(PREMIUMPRESS_SYSTEM);
$GLOBALS['premiumpress']['language'] 			= get_option('language');
$GLOBALS['premiumpress']['logo_url']			= get_option("logo_url");
$GLOBALS['premiumpress']['display_themecolumns']= get_option("display_themecolumns");
$GLOBALS['premiumpress']['excluded_articles']	= get_option("article_cats");

if($GLOBALS['premiumpress']['display_themecolumns'] == ""){ $GLOBALS['premiumpress']['display_themecolumns'] =2; }

// EXPIRED OPTIONS
$GLOBALS['premiumpress']['feature_expiry']		= get_option("feature_expiry");
$GLOBALS['premiumpress']['feature_expiry_do']	= get_option("feature_expiry_do");

// CURRENY OPTIONS
$GLOBALS['premiumpress']['currency_symbol']		= get_option("currency_code");
$GLOBALS['premiumpress']['currency_position'] 	= get_option("display_currency_position");
$GLOBALS['premiumpress']['currency_format'] 	= get_option("display_currency_format");

// IMAGE AND FILE PATHS
$GLOBALS['premiumpress']['imagestorage_link'] 	= get_option('imagestorage_link');
$GLOBALS['premiumpress']['thumbresize'] 		= get_option("thumbresize");
 
// PAGE LINKS
$GLOBALS['premiumpress']['submit_url']			= get_option('submit_url');
$GLOBALS['premiumpress']['manage_url']			= get_option('manage_url');
$GLOBALS['premiumpress']['dashboard_url']		= get_option('dashboard_url');
$GLOBALS['premiumpress']['contact_url']			= get_option('contact_url');

// IMAGE TYPES
$GLOBALS['premiumpress']['previewimage_type']  = get_option("display_previewimage_type");

// CUSTOM THEME SETTINGS
$GLOBALS['premiumpress']['linkcloak']			=  get_option("display_linkcloak");
$GLOBALS['premiumpress']['linkcloak_url'] 		= $GLOBALS['template_url']."/_link.php"; //get_option("tc_linkcloak");
$GLOBALS['premiumpress']['system'] 				=  get_option("system");
 

// POST PRUNING
$GLOBALS['premiumpress']['post_prun'] 			= get_option("post_prun");
$GLOBALS['premiumpress']['prun_period'] 		= get_option("prun_period");
$GLOBALS['premiumpress']['prun_status'] 		= get_option("prun_status");

//
$GLOBALS['premiumpress']['analytics_tracking'] 	= get_option("analytics_tracking");
$GLOBALS['premiumpress']['nofollow'] 			= get_option('display_nofollow');

// HEADER FAV ICON
$GLOBALS['premiumpress']['faviconLink'] 			= get_option("faviconLink");
 
/* ================ PREMIUM PRESS CATEGORY ID =========================== */

if(isset($wp_query)){
	$category = $wp_query->get_queried_object();
 
	if(is_object($category) && isset($category->cat_ID) || isset($category->term_id) ){
		if(isset($category->term_id)){
		$GLOBALS['premiumpress']['catID'] = $category->term_id;
		}else{
		$GLOBALS['premiumpress']['catID'] = $category->cat_ID;
		}
		
		/* GET THE CATEGORY TEXT AND IMAGE FOR THIS CATEGORY */
		$GLOBALS['catText'] 	=  $PPT->CategoryExtras($GLOBALS['premiumpress']['catID'],"text"); 
		$GLOBALS['catImage'] 	=  $PPT->CategoryExtras($GLOBALS['premiumpress']['catID'],"image",1); 
		
		if(isset($category->taxonomy) && strlen($category->taxonomy) > 0 && $category->taxonomy !="category"){
		$GLOBALS['premiumpress']['taxonomy'] = $category->taxonomy;
		}
		$GLOBALS['premiumpress']['catName'] = $category->name;
		$GLOBALS['premiumpress']['catDesc'] = $category->description;
		$GLOBALS['premiumpress']['catCount'] = $category->count;	
		  
	}else{
	$GLOBALS['premiumpress']['catID'] = "";
	$GLOBALS['premiumpress']['catName'] = "";
	$GLOBALS['premiumpress']['catDesc'] = "";
	}
}

	


/* ================ PREMIUM PRESS LANGUAGE FILE =========================== */

$PPT->Language();


/* ================ SWITCH ANY POST DATA =========================== */

if(isset($_POST['action'])){

	if($_POST['action'] == "sidebarcontact"){
	
			//check_admin_referer('ContactForm');	
			 
			if(	$_POST['code'] != "" && $_POST['code'] == $_POST['code_value']){
		 
			$my_post = array();
			$my_post['post_title'] 		= $_POST['message_from']." (".strip_tags(strip_tags($_POST['message_subject'])).")";
			$my_post['post_content'] 	= strip_tags(strip_tags($_POST['message_message']));
			$my_post['post_excerpt'] 	= "";
			$my_post['post_status'] 	= "publish";
			$my_post['post_type'] 		= "ppt_message";
			$my_post['post_author'] 	= $current_user->ID;
			$POSTID 					= wp_insert_post( $my_post );
			add_post_meta($POSTID, "username", $_POST['message_name']);	
			add_post_meta($POSTID, "from", $_POST['message_from']);	
		
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "success"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= SPEC($GLOBALS['_LANG']['_tpl_messages_error1']);
			 
			// ADD REFERENCE LINK TO THE MESSAGE
			$ExtraMessage = "\r\n".get_permalink($post->ID)."\r\n"; 
			 
			// SEND EMAIL
			$emailID = get_option("email_message_new");					 
			if(is_numeric($emailID) && $emailID != 0 && isset($_POST['author_ID']) && is_numeric($_POST['author_ID']) ){		 
			 	SendMemberEmail($_POST['author_ID'], $emailID, $ExtraMessage);
				 
			}
 
		
		}else{
		
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= SPEC($GLOBALS['_LANG']['_tpl_contact_error2']);		
		}
		
	}elseif( $_POST['action'] == "claimlisting"){
	
		$em = get_post_meta($_POST['postID'], 'email', true); $em1 = explode("@",$em);

		if( ( $em == $current_user->user_email ) || ( strpos($current_user->user_email, $em1[1]) !== false )  ){	
				 
		$GLOBALS['claimlisting_result'] = true;
		$my_post = array(); 
		$my_post['ID'] 				= $_POST['postID'];
		$my_post['post_author'] 	= $current_user->ID;
		wp_update_post( $my_post );
		update_post_meta($_POST['postID'], "email", "");
		
		}else{
		
		$GLOBALS['claimlisting_result'] = false;
		
		}
 	
	
	} 	

} 
 

/* ================ LOAD TEMPLATE FILE =========================== */

if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_header.php")){
		
	include(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme'].'/_header.php');
		
}else{ 
	
	include("template_".strtolower(PREMIUMPRESS_SYSTEM)."/_header.php");
	
}
 

?>       