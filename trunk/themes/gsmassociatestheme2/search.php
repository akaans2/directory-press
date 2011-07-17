<?php get_header(); ?>
<div class="art-content-layout">
    <div class="art-content-layout-row">
        <div class="art-layout-cell art-sidebar1">
          <?php get_sidebar('default'); ?>
          <div class="cleared"></div>
        </div>
        <div class="art-layout-cell art-content">
			<?php 
			  get_sidebar('top'); 
			  global $post;
			  if (have_posts()){
			  	art_pagination(
			  		array('title' => 
			  			sprintf( __( 'Search Results for: %s', THEME_NS ), 
			  				'<span>' . get_search_query() . '</span>' )));// previous_posts_link | next_posts_link
				while (have_posts())  
				{
					the_post();
					art_post_wrapper(
						array(
								'id' => art_get_post_id(), 
								'class' => art_get_post_class(),
								'thumbnail' => art_get_post_thumbnail(),
								'title' => '<a href="'.get_permalink($post->ID).'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a>', 
								'before' => art_get_metadata_icons('date,author', 'header'),
								'content' => art_get_excerpt(), // 'content' => 'My post content',
								'after' => art_get_metadata_icons('category,tag,comments', 'footer')
						)
					);
				}
				art_pagination();// previous_posts_link | next_posts_link
			  } else {    
				art_post_wrapper(
					array(
							'title' =>__('Nothing Found', THEME_NS),
							'content' => '<p class="center">' .  __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', THEME_NS) . '</p>'
										.  "\r\n" . art_get_search()
					)
				);
			  } 
			  get_sidebar('bottom'); 
			?>
          <div class="cleared"></div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<?php get_footer();