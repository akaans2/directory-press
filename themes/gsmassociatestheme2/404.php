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
			
			art_post_wrapper(
				array(
						'title' => __('Not Found', THEME_NS),
						'content' => '<p class="center">' 
						.__( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', THEME_NS) 
						. '</p>' . "\r\n" . art_get_search()
				)
			);

			get_sidebar('bottom'); 
			?>
          <div class="cleared"></div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<?php get_footer();