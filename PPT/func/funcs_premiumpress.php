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


/*****************************************************************
	THIS FUNCTION IS USED TO CLEAN DATABASE INPUT
*******************************************************************/
function PPTOUTPUT($string,$key="")
{  
 
	if(is_string($string)) {
	
		if($key == "description" || $key == "short"){
		
			if($key == "description" && isset($_POST['htmlcode'])){ 
			 
			$string = stripslashes($string);
			
			}else{
				$string = stripslashes(strip_tags($string));				
			}
			
		}else{
		
			$string = stripslashes(strip_tags($string));
		 
		}		
	
	}elseif(is_array($string)){
	 
		$newArray = array();
		foreach($string as $key => $value) {
			$newArray[$key] = PPTOUTPUT($string[$key],$key);
		}
		
		return $newArray;
	}

	return $string;

}
function PPTCLEAN($string)
{ 
 

	if(is_string($string)) {
	 
		  if(get_magic_quotes_gpc())  // prevents duplicate backslashes
		  {
			$string = stripslashes($string);
		  }
		  if (phpversion() >= '4.3.0')
		  {
			$string = mysql_real_escape_string($string);
		  }
		  else
		  {
			$string = mysql_escape_string($string);
		  }
		
		return $string;
		
	}elseif(is_array($string)) {
		 
		foreach($string as $key => $value) {
			PPTCLEAN($string[$key]); 
		}
	} 
	
	return $string;
}



/*****************************************************************
	THIS FUNCTION IS USED TO STIP SLAHES FROM GLOBAL ARRAYS
*******************************************************************/
 
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {  // PHP6 safe 

	if(!function_exists('array_stripslashes')) 
	{ 
		function array_stripslashes(&$var) 
		{ 
		if(is_string($var)) 
		$var = stripslashes($var); 
		else 
		if(is_array($var)) 
		foreach($var as $key => $value) 
		array_stripslashes($var[$key]); 
		} 
	}
	
	array_stripslashes($_GET); 
	array_stripslashes($_POST); 
	array_stripslashes($_COOKIE); 
	array_stripslashes($_REQUEST); 
	array_stripslashes($_FILES); 

}






function GetThemeName(){

	global $wpdb;
	$tpath = get_bloginfo("template_url");
	$t = explode("themes/",$tpath);
	return $t[1];

}




/*
PREMIUM PRESS - LANGAGE TRANSACTION OPTIONS
*/

if(!function_exists("SPEC")){

	function SPEC($in_str){
	
	if(!function_exists('mb_check_encoding')){ return $in_str; }
	
		if(is_array($in_str)){
			$barray = array();
			foreach($in_str as $key => $word){
			  $cur_encoding = mb_detect_encoding($word);	
				  if($cur_encoding == "UTF-8" && mb_check_encoding($word,"UTF-8")){				
				  }elseif(isset($GLOBALS['premiumpress']['language']) && $GLOBALS['premiumpress']['language'] =="language_chinese"){
					$word = mb_convert_encoding($word, 'UTF-8','GB2312'); 
				  }else{
					$word = utf8_encode($word);
				  } 
	
				$barray[$key]=$word;
			}
	
		return $barray;
		
		}else{
		  $cur_encoding = mb_detect_encoding($in_str) ;
		  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
			return $in_str;
		 }elseif(isset($GLOBALS['premiumpress']['language']) && $GLOBALS['premiumpress']['language'] =="language_chinese"){
		  return mb_convert_encoding($in_str, 'UTF-8','GB2312'); 
		  }else{
		  return utf8_encode($in_str);
		  } 
		} 
	}
	
}


/*
		CATEGORY TOOLS FOR IMPORT
*/
if(!function_exists("wp_create_category1")){

	function wp_create_category1( $cat_name, $parent = 0 ) {
	if ( $id = category_exists1($cat_name) )
		return $id;

	return wp_insert_category1( array('cat_name' => $cat_name, 'category_parent' => $parent) );
}

}
if(!function_exists("category_exists1")){

	function category_exists1($cat_name, $parent = 0) {
		$id = term_exists($cat_name, 'category', $parent);
		if ( is_array($id) )
			$id = $id['term_id'];
		return $id;
	}

}

if(!function_exists("wp_insert_category1")){ 

	function wp_insert_category1($catarr, $wp_error = false) {
	
	$cat_defaults = array('cat_ID' => 0, 'cat_name' => '', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
	$catarr = wp_parse_args($catarr, $cat_defaults);
	extract($catarr, EXTR_SKIP);

	if ( trim( $cat_name ) == '' ) {
		if ( ! $wp_error )
			return 0;
		else
			return new WP_Error( 'cat_name', __('You did not enter a category name.') );
	}

	$cat_ID = (int) $cat_ID;

	// Are we updating or creating?
	if ( !empty ($cat_ID) )
		$update = true;
	else
		$update = false;

	$name = $cat_name;
	$description = $category_description;
	$slug = $category_nicename;
	$parent = $category_parent;

	$parent = (int) $parent;
	if ( $parent < 0 )
		$parent = 0;

	if ( empty($parent) || !category_exists1( $parent ) || ($cat_ID && cat_is_ancestor_of($cat_ID, $parent) ) )
		$parent = 0;

	$args = compact('name', 'slug', 'parent', 'description');

	if ( $update )
		$cat_ID = wp_update_term($cat_ID, 'category', $args);
	else
		$cat_ID = wp_insert_term($cat_name, 'category', $args);

	if ( is_wp_error($cat_ID) ) {
		if ( $wp_error )
			return $cat_ID;
		else
			return 0;
	}

	return $cat_ID['term_id'];
	}

}


if(!function_exists("parse_csv_file")){

function parse_csv_file($file, $columnheadings = false, $delimiter = ',', $enclosure = "\"", $removeQuotes=true, $CATa="",$type="posts") {

	global $wpdb;
	
	if($type =="users"){	 
		require_once(ABSPATH . WPINC . '/registration.php');
		require_once(ABSPATH . WPINC . '/pluggable.php');
	}
		 
	$post_fields = array('SKU','category','tags','post_author','post_date','post_date_gmt','post_content','post_title','post_excerpt','post_status','comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_type','post_mime_type','comment_count');
	$bad_fields = array('ID');
	
	$AddtoCats = array();
	$user_fields = array('fname','lname','email','password','website');	
	$description="";
	$ShopperPressID=0; $ShopperPressUpdatedID =0;
	$row = 1;
	$rows = array();
	$handle = fopen($file, 'r');
	$customString = array();
	// GET TITLES
	$titles = fgetcsv($handle,1000,$delimiter);

	$cats=""; if(is_array($_POST['csv']['cat'])){ foreach($_POST['csv']['cat'] as $cat){ $cats .= $cat.",";	} }
 

	while(!feof($handle)) {	
	
	$line = fgetcsv($handle, 4096);

		if($type =="posts"){
	
			 if(is_array($line)){ foreach ($line as $key => $value) {
 		
				unset($data[$key]);
				
				if(!in_array(trim($titles[$key]), $post_fields)){	
				
					 if(!in_array($titles[$key],$bad_fields)){								 
					 
						$customString[$titles[$key]] = $value;
					
					}
	
				}else{
	  
					if(trim($titles[$key]) =="post_title"){								
						$title = $value;													
					}elseif(trim($titles[$key]) =="post_content"){					
						$description = $value;					
					}elseif(trim($titles[$key]) =="post_excerpt"){					
						$excerpt = $value;					
					}elseif(trim($titles[$key]) =="post_author"){					
						$author = $value;		
					}elseif(trim($titles[$key]) =="post_status"){					
						$newstatus = $value;	
					}elseif(trim($titles[$key]) =="post_type"){					
						$newtype = $value;								
					}elseif(trim($titles[$key]) =="tags"){					
						$tags = $value;									
					}elseif(trim($titles[$key]) =="category"){					
						$category = $value;									
					}elseif(trim($titles[$key]) =="SKU"){					
						$SKU = $value;			 		
					}
					
					if($author =="" ){ $author=1; }
	
				} 			
				
		   } }

				// CHECK THIS PRODUCT DOESNT ALREADY EXIST	
				$CanContinue = true;			
				if(isset($SKU) && $SKU !=""){
				$SQL = "SELECT $wpdb->postmeta.post_id AS total FROM $wpdb->postmeta
				WHERE $wpdb->postmeta.meta_value = ('".PPTCLEAN($SKU)."') AND $wpdb->postmeta.meta_key = 'SKU'
				LIMIT 1";
			 
				$result = mysql_query($SQL, $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);						
				$array = mysql_fetch_assoc($result);
				if(!empty($array['total'])) { $CanContinue = false;  }
				}
					 
				if ($CanContinue) {
				

					if(isset($category) && strlen($category) > 2){	
					$cats_array = explode(",",$category);
					foreach($cats_array as $Addcat){ 
 
						$catzID = wp_create_category1($Addcat);						
						array_push($AddtoCats,$catzID);
 						
					}		
			
					}
					 
					if($title != ""){			  
					  $my_post = array();
					  $my_post['post_title'] 	= $title;
					  $my_post['post_content'] 	= $description;
					  $my_post['post_excerpt'] 	= $excerpt;
					  $my_post['post_author'] 	= $author;
					  
					  if($newstatus != ""){	 $my_post['post_status'] 	= $newstatus;}else{ $my_post['post_status'] 	= "publish"; }					  
					  if($newtype != ""){ $my_post['post_type'] 	= $newtype; }else{ $my_post['post_type'] 	= "post"; }
					 
					  if(isset($tags)){
					   $my_post['tags_input'] 	= $tags;
					  }
					  // CATEGORY SETUP
					  if(empty($AddtoCats)){
						$my_post['post_category'] = $CATa;
					  }else{
						$my_post['post_category'] = $AddtoCats;
					  }
					  $POSTID = wp_insert_post( $my_post );	
					  
					 // print_r($POSTID);print_r($my_post);	  
					  $AddtoCats = array();
					  if(is_array($customString)){ 
						foreach($customString as $key=>$value){	
						if($key == "featured"){ $flagme=1; }					
							update_post_meta($POSTID,$key,$value);		 
					  }}

 					  if(isset($SKU) && $SKU !=""){
					  update_post_meta($POSTID,"SKU",$SKU);
 					  }
					  
					  
					  if(!isset($flagme)){ update_post_meta($POSTID,"featured","no"); }
				      // UPDATE PRODUCT COUNTER
					  $ShopperPressID++; 
				} 

							  
			  }else{

				// CATEGORY SETUP
				if(isset($category) && strlen($category) > 2){	
				$cats_array = explode(",",$category);
				foreach($cats_array as $Addcat){			

					$catzID = wp_create_category1($Addcat);						
					array_push($AddtoCats,$catzID);
				}					
				}

				// UPDATE PRODUCT RATHER THAN ADDING ONE	
				$my_post = array();
				$my_post['ID'] 				= $array['total'];
				$my_post['post_title'] 		= $title;
				$my_post['post_content'] 	= $description;
				$my_post['post_excerpt'] 	= $excerpt;
				$my_post['post_author'] 	= $author;
				
				if($newstatus != ""){	 $my_post['post_status'] 	= $newstatus;}else{ $my_post['post_status'] 	= "publish"; }					  
				if($newtype != ""){ $my_post['post_type'] 	= $newtype; }else{ $my_post['post_type'] 	= "post"; }
					  
				if(isset($tags)){
					$my_post['tags_input'] 	= $tags;
				}
			   // CATEGORY SETUP
				if(empty($AddtoCats)){
						$my_post['post_category'] = $CATa;
			   }else{
						$my_post['post_category'] = $AddtoCats;
				}
				wp_update_post( $my_post );		
				$AddtoCats = array();
				if(is_array($customString)){ 
						foreach($customString as $key=>$value){		
						if($key == "featured"){ $flagme=1; }					
							update_post_meta($array['total'],$key,$value);		 
				}}
				if(!isset($flagme)){ update_post_meta($array['total'],"featured","no"); }
				$ShopperPressUpdatedID++; 
			  }
				
	 
		}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
 	if($type =="users"){
	
	
	if(is_array($line)){ foreach ($line as $key => $value) {
 
        unset($data[$key]);
        $value = $value;
 
		// REMOVE QUOTES
		if($removeQuotes){ $titles[$key] = str_replace('"','',$titles[$key]);  $value = str_replace('"','',$value); } //

		// IF NOT IN POST FIELDS ADD TO CUSTOM META TAGS
		if(!in_array($titles[$key], $user_fields)){		

			if(!in_array($titles[$key],$bad_fields)){								 
				$description .= $titles[$key].=" - ".$value."\r";
			}
		
		}else{
		
			if(trim($titles[$key]) =="email"){
			
			$email = $value;
			$username_bits = explode("@",$value);
			$username = $username_bits[0];
			
			}elseif(trim($titles[$key]) =="password"){
			
			$password = $value;	
			
			}elseif(trim($titles[$key]) =="fname"){
			
			$fname = $value;
			
			}elseif(trim($titles[$key]) =="lname"){
			
			$lname = $value;
			
			}elseif(trim($titles[$key]) =="website"){
			
			$website = $value;
			
			}	
		
		
		}	
	
	} }
	
	
	$userdata = array(	
	//'ID' => $USERID,
	'user_email' => $email,
	'display_name' => $fname, 
	'user_pass' => $password,
	'user_login' => $username,
	'first_name' => $fname,
	'last_name' => $lname,
	'description' => $description,
	'user_url' => $website
	);
	 
	
	$USERID = wp_create_user($userdata['user_login'], $userdata['user_pass'], $userdata['email']);
 	$userdata['ID'] = $USERID;
	wp_update_user($userdata);
	
	$description="";
	
	$ShopperPressID++; 
	
	}
 
 
	
	 
	} /* end while */
 
	fclose($handle);
	return $ShopperPressID."**".$ShopperPressUpdatedID;
}

}


if(!function_exists("AmazonSearchSave")){

 function AmazonSearchSave($result){

	global $wpdb;

	$count=0; $importcounter=0;

	foreach($result->Items->Item as $val){
	
	
	 $SQL = "SELECT count($wpdb->postmeta.meta_key) AS total
	 FROM $wpdb->postmeta
	 WHERE $wpdb->postmeta.meta_key='amazon_guid' AND $wpdb->postmeta.meta_value = '".$val->ASIN."'
	 LIMIT 1";	
		
		 
	 $result = mysql_query($SQL);			 
	 $array = mysql_fetch_assoc($result);
	
	 if($array['total'] > 0){
	 
	 
	 
	 }else{
 
 
	$data['title'] 		= str_replace("!!aaqq","",$val->ItemAttributes->Title);
	$data['asin'] 		= str_replace("!!aaqq","",$val->ASIN);
	$data['url'] 		= str_replace("!!aaqq","",$val->DetailPageURL);
 
	 
		if($_POST['amazon']['country'] =="co.uk" || $_POST['amazon']['country'] =="jp"){	
			$data['price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,2,10);
			if( $data['price'] =="" ){
				$data['price'] 		= substr($val->OfferSummary->LowestNewPrice->FormattedPrice,2,10);
			}
		}elseif($_POST['amazon']['country'] =="de" || $_POST['amazon']['country'] =="fr"){
			$data['price'] 	=  substr($val->OfferSummary->LowestNewPrice->FormattedPrice,4,10);
		}elseif($_POST['amazon']['country'] =="ca"){
			$data['price'] 	=  substr($val->OfferSummary->LowestNewPrice->FormattedPrice,5,10);
		}else{	
			if( isset($val->OfferSummary->LowestNewPrice->Amount) ){	
			$data['price'] 		= substr($val->OfferSummary->LowestNewPrice->FormattedPrice,1,10);
			$data['old_price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,1,10);
			}else{
			$data['price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,1,10);
			}
		}
	
		$data['qty']		= str_replace("!!aaqq","",$val->ItemAttributes->NumberOfItems);
		$data['desc']		= nl2br(str_replace(".",",<br/>",$val->EditorialReviews->EditorialReview->Content));
		$data['image'] 		= str_replace("!!aaqq","",$val->LargeImage->URL);
		$data['thumbnail']	= str_replace("!!aaqq","",$val->MediumImage->URL);
		$data['images'] ="";
		$data['warranty']	= str_replace("!!aaqq","",$val->ItemAttributes->Warranty);
		
		// IMAGE SETS	
		if(isset($val->ImageSets->ImageSet)){
			foreach($val->ImageSets->ImageSet as $img){
				$data['images'] .= $img->MediumImage->URL.",";
			}
		}
		// GET PRODUCT FEATURES
		$excerpt="<ul>";
		foreach($val->ItemAttributes->Feature as $feature){
		$excerpt .="<li>".$feature."</li>";
		}
		$excerpt.="</ul>";
		//	GET ATTRIBUTES
		$extra_data = "<ul class=ExtraData>";
		foreach($val->ItemAttributes as $at1){foreach($at1 as $key => $att){
		$extra_data .= "<li><span>".$key."</span>:";
			if(is_array($att)){
				foreach($att as $in){
					$extra_data .= $in;
				}
			}else{
				$extra_data .= $att;
			}
		$extra_data .= "</li>";
		}}
	
		$extra_data .="</ul>";
	
		// SWITCH VALUES IF EMPTY	
		if(strlen($excerpt) < 10){ $excerpt = $extra_data; $extra_data=""; }
	
	
		$cc = explode("/",str_replace("http://www.amazon.".$_POST['amazon']['country']."/","",$val->DetailPageURL));
		$data['nicename'] =$cc[0];
	
		if($_POST['amazon_ID'] ==""){ $_POST['amazon_ID'] ="YOURUSERID"; }
	
	
		$AFFLINK = "http://www.amazon.".$_POST['amazon']['country']."/o/ASIN/%asin%/%amazon_id%";
		$AFFLINK = str_replace("%asin%",$data['asin'],$AFFLINK);
		$AFFLINK = str_replace("%amazon_id%",$_POST['amazon_ID'],$AFFLINK);
	 
		// CHECK THIS PRODUCT DOESNT ALREADY EXIST		
		/*$result = mysql_query("SELECT count($wpdb->posts.ID) AS total FROM $wpdb->posts
		INNER JOIN $wpdb->postmeta ON
		($wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_value='".$data['asin']."')
		LIMIT 1", $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
				
		$array = mysql_fetch_assoc($result);*/
		
		if ($data['price'] > 0 && strlen($data['title']) > 3 ) { //$array['total'] ==0 && 
	
				if(strlen($excerpt) < 10){ $excerpt = $extra_data; }		
				
				$data['desc'] 	= str_replace(":"," : ",$data['desc']);
				$excerpt 		= str_replace(":"," : ",$excerpt);
				
				
				if(strlen($data['title']) > 45){
				$ssss = explode(" ",str_replace("with","",$data['title']));
				$name = ""; $i=0;
					 while($i < count($ssss)){
						 if(strlen($name) < 45){
							$name .= $ssss[$i]." ";
							}
					  $i++;		 
					 }		 
				}else{
				$name  = $data['title'];
				}
			 
				$my_post = array();
				$my_post['post_title'] 	= $name;	  
				$my_post['post_content'] 	= $data['desc'].$extra_data;
				$my_post['post_excerpt'] 	= substr($data['desc'],0,200);
				$my_post['post_author'] 	= 1;
				$my_post['post_status'] 	= "publish";
				$my_post['post_category'] 	= $_POST['amazon']['cat'];
				$my_post['tags_input'] = str_replace(" ",",",str_replace("-","",str_replace("/","",str_replace("&","",$data['title']))));
				$POSTID = wp_insert_post( $my_post );	  
				  
				$data['price'] 		= str_replace(",","",$data['price']);
				$data['old_price']  = str_replace(",","",$data['old_price']);
				  
				// EXTRA FIELDS
				add_post_meta($POSTID, "amazon_link", $AFFLINK);
				add_post_meta($POSTID, "amazon_guid", $data['asin']);
				add_post_meta($POSTID, "price", $data['price']);
				if(isset($data['old_price']) && strlen($data['old_price']) > 1){ add_post_meta($POSTID, "old_price", $data['old_price']); }
				add_post_meta($POSTID, "warranty", $data['warranty']);
				  
				add_post_meta($POSTID, "image", $data['image']);
				add_post_meta($POSTID, "images", $data['images']);	  
				add_post_meta($POSTID, "qty", 1);	
				add_post_meta($POSTID, "featured", "no");
	
				$importcounter++;
				$emailString .= "Product ".$importcounter.": ".$data['title']." \n\n";
				  
				  
				// CHECK FOR COMMENTS	  
				$time = current_time('mysql', $gmt = 0);	
					
				  if(isset($val->CustomerReviews->Review)){ foreach($val->CustomerReviews->Review as $review){
			
					$data = array(
						'comment_post_ID' => $POSTID,
						'comment_author' => $review->Reviewer->Name,
						'comment_author_email' => 'admin@admin.com',
						'comment_author_url' => 'http://',
						'comment_content' => nl2br($review->Content),
						//'comment_type' => ,
						'comment_parent' => 0,
						'user_ID' => 1,
						'comment_author_IP' => '127.0.0.1',
						'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
						'comment_date' => $review->Date,
						'comment_date_gmt' => $time,
						'comment_approved' => 1,
					);
					
					wp_insert_comment($data);
					
				  } 
			}
	 
		}
	
	  }

	}
return $importcounter;
 
}

}

if(!function_exists("FilterPath")){

	function FilterPath(){
		$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
		$path_parts = pathinfo($path); 
		if($path == ""){
			return $_SERVER['DOCUMENT_ROOT'];
		}else{
			$path = $path_parts['dirname'];
			if($path_parts['basename'] != ""){ $path .= "/".$path_parts['basename']; }
			return $path;
		}
	}
}



function PPTUsersByRole( $roles, $data="ID" ) {
	global $wpdb;
	if ( ! is_array( $roles ) ) {
		$roles = explode( ",", $roles );
		array_walk( $roles, 'trim' );
	}
	$sql = '
		SELECT	'.$data.'
		FROM		' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
		ON		' . $wpdb->users . '.ID				=		' . $wpdb->usermeta . '.user_id
		WHERE	' . $wpdb->usermeta . '.meta_key		=		\'' . $wpdb->prefix . 'capabilities\'
		AND		(
	';
	$i = 1;
	foreach ( $roles as $role ) {
		$sql .= ' ' . $wpdb->usermeta . '.meta_value	LIKE	\'%"' . $role . '"%\' ';
		if ( $i < count( $roles ) ) $sql .= ' OR ';
		$i++;
	}
	$sql .= ' ) ';
	$sql .= ' ORDER BY ID ASC ';
	$userIDs = $wpdb->get_col( $sql );
	return $userIDs;
}

function SendMemberEmail($user_id, $email_id, $extraMessage=""){
 
	global $wpdb; $subject = ""; $message = "";
	
	if(!function_exists('get_userdata')){ 
	require_once( ABSPATH . WPINC . '/pluggable.php'); 
	}
 
	$user_info = get_userdata($user_id);
  
	if(is_numeric($email_id)){
	 
		$email_info = $wpdb->get_results("SELECT * FROM premiumpress_emails WHERE ID= ('".$email_id."') LIMIT 1");
		$subject 	 	 = FindAndReplaceMe($email_info[0]->email_title,$user_info);
		$message 		 = FindAndReplaceMe($email_info[0]->email_description,$user_info);
		
	}else{
		
		$subject 	 	 = FindAndReplaceMe($email_id['subject'],$user_info);
		$message 		 = FindAndReplaceMe($email_id['description'],$user_info);
	}

	// REPLACE ../ WITH WEBSITE URL
	$message	= str_replace("../",get_home_url()."/",$message);
	
	// ADD EXTRA POST VALUES FROM FORM DATA
	$message	= FindAndReplaceMe($message,$_POST);
	 
	// ADD EXTRA POST VALUES FROM FORM DATA
	$message	= FindAndReplaceMe($message,$_POST['form']);

	// ADD EXTRA POST VALUES FROM FORM DATA
	if(isset($_POST['shipping'])){
	$message	= FindAndReplaceMe($message,$_POST['shipping']);
	}
			
	// ADD ANY EXTRA MESSAGE DATA TO THE MESSAGE
	$message .=  $extraMessage;	
	

	// GET ARRAY OF ADMIN EMAIL ADDRESSES
 	$AdminEmails = PPTUsersByRole('administrator','user_email');
 
	// OVERRIDE TO SEND TO ADMINS
	if($user_id == "admin"){	
	
		$SendRoles = "";
		$PPTroles = array('administrator' => 'Super Admin','editor' => 'Site Manager','contributor' => 'Employee','subscriber' => 'Client');
		$r=1;foreach($PPTroles as $key=>$name){  if(get_option('emailrole'.$r) == $key){  $SendRoles .= $key.","; } $r++;	}
		$SendRoles = substr( $SendRoles,0,-1);		
	
		$SendToEmail = PPTUsersByRole($SendRoles,'user_email');
		
	}else{
		$SendToEmail = $user_info->user_email;
	}	
  
	$headers  		 = "From: " . strip_tags($AdminEmails[0]) . "\r\n";
	$headers 		.= "Reply-To: " . strip_tags($AdminEmails[0]) . "\r\n";
	$headers 		.= "Return-Path: " . strip_tags($AdminEmails[0]) . "\r\n"; 
	
	// STRIP HTML TAGS TO SEND PLAIN TEXT
	if( ( isset($email_id['email_html']) && $email_id['email_html'] == 2 ) || ( isset($email_info[0]->email_html) && $email_info[0]->email_html == 2) ){
	
	$message	= strip_tags(br2nl($message));
	
	}else{
	
	$headers 	.=  "Content-Type: text/html; charset=\"" .get_option('blog_charset') . "\"\n"; 
	add_filter('wp_mail_content_type','set_contenttype');	
	apply_filters( 'wp_mail_content_type', "text/html" );
		
	}
 
 
 	//die($SendToEmail." -- ".stripslashes($subject)." -- ".stripslashes($message)); 
 	wp_mail($SendToEmail,stripslashes($subject),stripslashes($message),$headers);


}
function br2nl($string)
{
	$a = $string;
    return preg_replace('/<br\\s*?\/??>/i', "\n", $a);
}
function set_contenttype($content_type){
return 'text/html';
}


function FindAndReplaceMe($text, $myarray=array()){
 
	if(is_array($myarray) || is_object($myarray)){ 
	
	foreach($myarray as $key=>$name){  
 	
		if($key == "user_login"){
		$text = str_replace("(username)",$name,$text);
		}	
		if($key == "first_name"){
		$text = str_replace("(firstname)",$name,$text);
		}
		if($key == "last_name"){
		$text = str_replace("(lastname)",$name,$text);
		}	
		if($key == "user_email"){
		$text = str_replace("(email)",$name,$text);
		}	
		if($key == "user_url"){
		$text = str_replace("(website)",$name,$text);
		}		
		if($key == "user_registered"){
		$text = str_replace("(created)",$name,$text);
		}	
	  	
		if($key == "tags"){
		 
		$text = str_replace("(tags)",$name,$text);
		}	
		
				
		$text = str_replace("(".$key.")",$name,$text);
		
		} 
	
	}
 
	return $text;

}

function CouponDiscount($code){
	
		$ArrayCoupon = get_option("coupon_array");
	
		if(is_array($ArrayCoupon)){
			foreach($ArrayCoupon as $value){
				if($code ==$value['name']){
				
					return $value;
			
				}
			}
		}	
}
	
	
function DoSubscription($userID,$packageID,$type="add"){

	global $wpdb;

	if(is_numeric($userID)){
	
		if(!function_exists('get_userdata')){ 
			require_once( ABSPATH . WPINC . '/pluggable.php'); 
		}
		
		$data = new WP_User($userID);
 		$user_login = $data->user_login;
	}else{
		$user_login = $userID;
	}

	switch($type){
	
		case "add": {
	
			$pdetails = $wpdb->get_results("SELECT * FROM premiumpress_packages WHERE ID= ('".$packageID."') LIMIT 1");
		
			$ENDDATE = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$pdetails[0]->package_durationA,   date("Y")));
 
			$wpdb->get_results("INSERT INTO `premiumpress_subscriptions` (
			`user_login` ,
			`package_ID` ,
			`start_date` ,
			`end_date` ,
			`subscription_status` ,
			`email_last_interval` ,
			`email_last_date` ,
			`paid_amount` ,
			`paid_recurring`
			) VALUES ( '".strip_tags($user_login)."', '".$packageID."', NOW(), '".$ENDDATE."', '0', '', '', '".$pdetails[0]->package_price."', '".$pdetails[0]->package_recurring."')");
		
		} break;		
		
		
		case "update": {
 
			$FF = $wpdb->get_results("SELECT count(*) AS total FROM `premiumpress_subscriptions` WHERE package_ID='".$packageID."' AND user_login='".$user_login."' LIMIT 1 ");
			if($FF[0]->total ==0){
			
			
					$pdetails = $wpdb->get_results("SELECT * FROM premiumpress_packages WHERE ID= ('".$packageID."') LIMIT 1");
		
					$ENDDATE = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$pdetails[0]->package_durationA,   date("Y")));
 
					$wpdb->get_results("INSERT INTO `premiumpress_subscriptions` (
					`user_login` ,
					`package_ID` ,
					`start_date` ,
					`end_date` ,
					`subscription_status` ,
					`email_last_interval` ,
					`email_last_date` ,
					`paid_amount` ,
					`paid_recurring`
					) VALUES ( '".strip_tags($user_login)."', '".$packageID."', NOW(), '".$ENDDATE."', '1', '', '', '".$pdetails[0]->package_price."', '".$pdetails[0]->package_recurring."')");
			
			}else{
 
				$wpdb->get_results("UPDATE `premiumpress_subscriptions` SET subscription_status=1 WHERE package_ID='".$packageID."' AND user_login='".$user_login."' LIMIT 1 ");
				
			}
			
		
		} break;
	
	
	}

}

 

function send_download($data){

	global $wpdb;
	
 
	$wpdb->get_results("UPDATE premiumpress_files SET file_downloads=file_downloads+1 WHERE file_name = ('".$data['file_name']."') LIMIT 1");

	$file_path = $data['file_path'] . $data['file_name'];

 	header('Content-Description: File Transfer');
	header("Content-Type: ".$data['file_type']."");
	header('Content-Type: application/octet-stream');
	header("Content-disposition: attachment; filename=".$data['file_name']."");
	header("Content-Length: ".$data['file_size']."");
    ob_clean();
    flush();
	readfile($file_path);
	exit;

}

    function returnMIMEType($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }

            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }

 
function ppt_metabox() {
	global $post;

	// Disallows things like attachments, revisions, etc...
	$safe_filter =				array( 'public' => true, 'show_ui' => true );

	// Allow to be filtered, just incase you really need to switch between
	// those crazy types of posts
	$args =						apply_filters( 'pts_metabox', $safe_filter );

	// Get the post types based on the above arguments
	$post_types =				get_post_types( (array)$args );

	// Populate necessary post_type values
	$cur_post_type =			$post->post_type;
	$cur_post_type_object =		get_post_type_object( $cur_post_type );

	// Make sure the currently logged in user has the power
	$can_publish =				current_user_can( $cur_post_type_object->cap->publish_posts );
?>

<div class="misc-pub-section misc-pub-section-last post-type-switcher">
	<label for="pts_post_type">Post Type:</label>
	<span id="post-type-display"><?php echo $cur_post_type_object->label; ?></span>
<?php	if ( $can_publish ) : ?>
	<a href="#pts_post_type" class="edit-post-type hide-if-no-js">Edit</a>
	<div id="post-type-select" class="hide-if-js">
		<select name="pts_post_type" id="pts_post_type">
<?php
		foreach ( $post_types as $post_type ) {
			$pt = get_post_type_object( $post_type );
			if ( current_user_can( $pt->cap->publish_posts ) ) :
?>
			<option value="<?php echo $pt->name; ?>"<?php if ( $cur_post_type == $post_type ) : ?>selected="selected"<?php endif; ?>><?php echo $pt->label; ?></option>
<?php
			endif;
		}
?>
		</select>
		<input type="hidden" name="hidden_post_type" id="hidden_post_type" value="<?php echo $cur_post_type; ?>" />
		<a href="#pts_post_type" class="save-post-type hide-if-no-js button">OK</a>
		<a href="#pts_post_type" class="cancel-post-type hide-if-no-js">Cancel</a>
	</div>
</div>
<?php
	endif;
}

 
	
?>