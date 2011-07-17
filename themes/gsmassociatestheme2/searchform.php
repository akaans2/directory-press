 <form method="get" name="searchform" action="<?php bloginfo('url'); ?>/">
    <div class="search">
    	<label for="s"><?php _e('Search for:'); ?>
        <input type="text" value="<?php echo esc_attr(get_search_query()); ?>" name="s" style="width: 95%;" />
        </label>
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input class="art-button" type="submit" name="search" value="<?php  echo esc_attr(__('Search')); ?>" />
        </span>
    </div>
</form>
