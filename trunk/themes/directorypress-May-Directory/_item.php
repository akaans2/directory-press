<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

?>
<div class="itembox <?php if(get_post_meta($post->ID, "featured", true) == "yes"){?>hightlighted<?php }else{ ?><?php } ?>">
    
     
 
        <div class="itemboxinner">    
    
            <div class="post clearfix"> 
                                          
                <div class="thumbnail-large"> 
                
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <img src="<?php echo $PPT->Image($post->ID,"url"); ?>&amp;w=200" class="listImage" alt="<?php the_title(); ?>" />
                      
                        <span class="time-video"><?php echo get_post_meta($post->ID, "duration", true); ?></span>
                        <span class="add-playlist">Add</span> 
                    </a>                 	   
                    
                </div> 
                
               
                <div class="text">
                
                	<h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php if(function_exists('wp_gdsr_render_article')){ wp_gdsr_render_article(5);}  ?></h1> 
                
                    <p><?php echo strip_tags($post->post_excerpt); ?> </p>
                    
                    <div class="tags clearfix"> </div>
                    
                
                    <div class="meta clearfix"> 
					
                           <a href="<?php the_permalink(); ?>"><?php echo $GLOBALS['_LANG']['_mored']; ?></a>
                           
            				<?php if($GLOBALS['directorypress']['vps'] == "yes" && strlen($link) > 2){ ?>
                            <a href="<?php echo $link; ?>" title="<?php the_title(); ?>" target="_blank" <?php if($GLOBALS['premiumpress']['nofollow'] =="yes"){ ?>rel="nofollow"<?php } ?>><?php echo $GLOBALS['_LANG']['_vps']; ?></a> 
                            <?php } ?>
            
            				<?php if(get_option("display_search_comments") =="yes"){ comments_popup_link($GLOBALS['_LANG']['_nocomments'], $GLOBALS['_LANG']['_1comment'], '% '.$GLOBALS['_LANG']['_comments']); } ?>
                    <?php the_tags( '', '', ''); ?>
                    </div>  
                    
                </div>
            
            </div> 
     
    
        </div>
    
</div>