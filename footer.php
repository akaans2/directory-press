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

global $PPT,$PPTDesign,$ThemeDesign; 

/* ================ LOAD TEMPLATE FILE =========================== */

if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_footer.php")){
		
	include(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme'].'/_footer.php');
		
}else{ 
	
	include("template_".strtolower(PREMIUMPRESS_SYSTEM)."/_footer.php");
	
}
 
?>