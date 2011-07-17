<?php
/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>    

 
 
<?php /*------------------- JAVASCRIPT INCLUDES ----------------------*/ ?>
<?php wp_deregister_script( 'jquery' ); ?>
<script type="text/javascript" src="<?php echo PPT_PATH; ?>js/jquery.js"></script> 
<?php wp_enqueue_script( 'jquery' ); ?>
   
<?php /*------------------- HOME PAGE INCLUDES ----------------------*/  ?>


<?php if(is_home()){ ?>

<script type='text/javascript' src='<?php echo PPT_PATH; ?>js/jquery.siteFeature.js'></script>
<link rel="stylesheet" href="<?php echo PPT_PATH; ?>css/css.fslider.css" type="text/css" media="screen" />     
<script type="text/javascript">jQuery(document).ready(function(){jQuery('#preFeature').siteFeature();	}); </script> 

<?php } ?>


<?php /*------------------- PACKAGES PAGE ----------------------*/ ?>

<?php if(isset($GLOBALS['tpl-add'])){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.packages.css" media="screen" />  
<!--[if IE]>
	<script src="<?php echo PPT_PATH; ?>/js/html5.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/ajax/actions.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.wwiconified.js"></script>
<link rel="stylesheet" href="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.wwiconified1.css" media="screen" type="text/css" />

<?php } ?>
<?php /*------------------- SINGLE PAGE ----------------------*/ ?>
<?php if(is_single()){ ?>    
<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>js/lightbox/themes/default/jquery.lightbox.css" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>js/lightbox/themes/default/jquery.lightbox.ie6.css" /><![endif]-->   
<?php } ?>
<?php if(is_single()){ $GLOBALS['mapType'] = get_option("display_googlemaps"); if($GLOBALS['mapType'] == "yes2" && strlen(get_post_meta($post->ID, "map_location", true)) > 2){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.maps.css" media="screen" />  
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?php echo get_option("google_maps_api"); ?>" type="text/javascript"></script> 
    <script type='text/javascript' src='<?php echo PPT_PATH; ?>js/jquery.maps.js'></script> 
    
        <script type="text/javascript"> 
            window.onload = function(){
                var mymap = new MeOnTheMap({
                    container: "map_sidebar2",
                    html: '<center><?php echo $post->post_title ?></center>',
                    address: "<?php echo get_post_meta($post->ID, "map_location", true); ?>",
                    zoomLevel: 13
                });
 
                mymap.adjustMapCenter({
                    x: 0,
                    y: -80
                });
			 
				
            } 
        </script>
    
<?php  } } ?>
    
    
<?php /*------------------- LINK INCLUDES ----------------------*/ ?>

<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.premiumpress.css" media="screen" />
<link rel="stylesheet" href="<?php echo $GLOBALS['template_url']."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/styles.css"; ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $GLOBALS['template_url']; ?>/themes/<?php $ThisTheme = $GLOBALS['premiumpress']['theme'];  if(strlen($ThisTheme) < 2){ $ThisTheme = strtolower(PREMIUMPRESS_SYSTEM)."_default"; } echo $ThisTheme;  ?>/css/styles.css" type="text/css"  />
<?php if(isset($GLOBALS['premiumpress']['faviconLink']) && strlen($GLOBALS['premiumpress']['faviconLink']) > 5){ ?><link rel="shortcut icon" href="<?php echo $GLOBALS['premiumpress']['faviconLink']; ?>" type="image/x-icon" /><?php } ?>
<?php if($GLOBALS['premiumpress']['display_themecolumns'] =="3" && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) ){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.3columns.css" media="screen" />
<?php } ?>    
    
<?php echo stripslashes(get_option("google_webmaster_code")); ?>

<script src="<?php echo $GLOBALS['template_url']."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/js/"; ?>cufon-yui.js" type="text/javascript"></script>

<script src="<?php echo $GLOBALS['template_url']."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/js/"; ?>Verdana_400.font.js" type="text/javascript"></script>

<script src="<?php echo $GLOBALS['template_url']."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/js/"; ?>Trebuchet_MS_400.font.js" type="text/javascript"></script>

<script src="<?php echo $GLOBALS['template_url']."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/js/"; ?>Trebuchet_MS_italic_400.font.js" type="text/javascript"></script>

<script type="text/javascript">

			Cufon.replace('.menu li a', { fontFamily: 'Verdana' , hover: true });

			Cufon.replace('.heading-list h1', { fontFamily: 'Verdana'});

			Cufon.replace('#sub-banner p', { fontFamily: 'Trebuchet MS' , textShadow: '1px 1px 0 rgba(0,0,0,0.2)'});

			Cufon.replace('a.content-button, a.contact-button, a.pre-button, a.next-button, a.blog_number', { fontFamily: 'Trebuchet MS' , hover: true });

			Cufon.replace('#t-slider li p', { fontFamily: 'Trebuchet MS' });

</script>


<?php wp_head(); ?> 

</head> 

<body <?php if(is_home()){ print "id='PPTHomePage'"; }elseif(is_single()){ print "id='PPTSinglePage-".$post->post_type."'"; }elseif(is_page()){ print "id='PPTPage'"; } ?>>

 
	<div class="wrapper w_960"> 
	
		<div id="header" class="full">
        
            <div class="f_half left" id="logo"> 
            
             <a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>">
             
			 	<img src="<?php echo $PPT->Logo(); ?>" alt="<?php bloginfo('name'); ?>" />
                
			 </a>
            
            </div>        
        
            <div class="left padding5" id="banner"> 
            
           	 <?php echo $PPT->Banner("top"); ?>
             
            </div>        
        
        <div class="clearfix"></div>
        
        </div> 
		
        <div id="menudrop">
          
            <div class="full"> 
            
            <?php if(has_nav_menu('PPT-CUSTOM-MENU-PAGES')){ wp_nav_menu( array( 'theme_location' => 'PPT-CUSTOM-MENU-PAGES', 'depth'=>'1', 'before'=>'',  'after'=>'', 'menu_class' => 'menu' ) ); }else{ ?>
            
                <ul class="menu">           
                 
                 <li><b><a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>"><?php echo SPEC($GLOBALS['_LANG']['_home']) ?></a></b></li> 
                        
                    <?php echo $ThemeDesign->SYS_PAGES(); ?>            
                     
                </ul>
                
                <?php } ?>
                 
            </div> 
        </div>
        

    
 
		<div id="page" class="clearfix full">
        
        
        <?php if(get_option("display_advanced_search") ==1 ){  PPT_AdvancedSearch('preset-default');  } ?>
 
  
		<?php if(get_option("PPT_slider") =="s1"  && is_home() && !isset($_GET['s']) && !isset($_GET['search-class']) ){ echo $PPTDesign->SLIDER(); } ?> 
         
         
        
        <div id="content">


		<?php
 
            if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php") && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) ){
            
                include(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php");
            
            }elseif($GLOBALS['premiumpress']['display_themecolumns'] =="3" && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) ){
			
				include("_sidebar1.php");
			
			
			}
        
        ?>