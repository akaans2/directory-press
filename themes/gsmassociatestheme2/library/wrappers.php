<?php
// $style = 'post' or 'block' or 'vmenu' or 'simple'
function art_wrapper($style, $args){
	$func_name = "art_{$style}_wrapper";
	if (!function_exists($func_name)) return;
	call_user_func($func_name, $args);
}

function art_post_wrapper($args) {
	$id = art_get_array_value($args, 'id');
	$class = art_get_array_value($args, 'class');
	$title = art_get_array_value($args, 'title');
	$thumbnail = art_get_array_value($args, 'thumbnail');
	$before = art_get_array_value($args, 'before');
	$content = art_get_array_value($args, 'content');
	$after = art_get_array_value($args, 'after');
	if (art_is_empty_html($title) && art_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
		$args['id'] = $id;
	}
	if ($class) {
		$class = ' ' . $class; 
	}
	?>
<div class="art-post<?php echo $class; ?>"<?php echo $id; ?>>
	    <div class="art-post-tl"></div>
	    <div class="art-post-tr"></div>
	    <div class="art-post-bl"></div>
	    <div class="art-post-br"></div>
	    <div class="art-post-tc"></div>
	    <div class="art-post-bc"></div>
	    <div class="art-post-cl"></div>
	    <div class="art-post-cr"></div>
	    <div class="art-post-cc"></div>
	    <div class="art-post-body">
	            <div class="art-post-inner art-article">
	            <?php
	                echo $thumbnail;
	                if (!art_is_empty_html($title)){
	                    echo '<h2 class="art-postheader">'.$title.'</h2>';
	                }
	                 echo $before;?>
	                <div class="art-postcontent">
	                    <!-- article-content -->
	                    <?php echo $content; ?>
	                    <!-- /article-content -->
	                </div>
	                <div class="cleared"></div>
	                <?php
	                echo $after;
	            ?>
	            </div>
			<div class="cleared"></div>
	    </div>
	</div>
	
	<?php
}

function art_simple_wrapper($args) {
	$id = art_get_array_value($args, 'id');
	$class = art_get_array_value($args, 'class');
	$title = art_get_array_value($args, 'title');
	$content = art_get_array_value($args, 'content');
	if (art_is_empty_html($title) && art_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
		$args['id'] = $id;
	}
	if ($class) {
		$class = ' ' . $class; 
	}
	echo "<div class=\"art-widget{$class}\"{$id}>";
	if ( !art_is_empty_html($title)) echo '<h5  class="art-widget-title">' . $title . '</h5>';
	echo '<div class="art-widget-content">' . $content . '</div>';
	echo '</div>';
}

function art_block_wrapper($args) {
	$id = art_get_array_value($args, 'id');
	$class = art_get_array_value($args, 'class');
	$title = art_get_array_value($args, 'title');
	$content = art_get_array_value($args, 'content');
	if (art_is_empty_html($title) && art_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
		$args['id'] = $id;
	}
	if ($class) {
		$class = ' ' . $class; 
	}

	$begin = <<<EOL
<div class="art-block{$class}"{$id}>
    <div class="art-block-body">
EOL;
	$begin_title  = <<<EOL
<div class="art-blockheader">
    <div class="l"></div>
    <div class="r"></div>
    <h3 class="t">
EOL;
	$end_title = <<<EOL
</h3>
</div>
EOL;
	$begin_content = <<<EOL
<div class="art-blockcontent">
    <div class="art-blockcontent-body">
EOL;
	$end_content = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	$end = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	echo $begin;
	if ($begin_title && $end_title && !art_is_empty_html($title)) {
		echo $begin_title . $title . $end_title;
	}
	echo $begin_content;
	echo $content;
	echo $end_content;
	echo $end;	
}


function art_vmenu_wrapper($args) {
	$id = art_get_array_value($args, 'id');
	$class = art_get_array_value($args, 'class');
	$title = art_get_array_value($args, 'title');
	$content = art_get_array_value($args, 'content');
	if (art_is_empty_html($title) && art_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
		$args['id'] = $id;
	}
	if ($class) {
		$class = ' ' . $class; 
	}

	$begin = <<<EOL

EOL;
	$begin_title  = <<<EOL

EOL;
	$end_title = <<<EOL

EOL;
	$begin_content = <<<EOL

EOL;
	$end_content = <<<EOL

EOL;
	$end = <<<EOL

EOL;
	echo $begin;
	if ($begin_title && $end_title && !art_is_empty_html($title)) {
		echo $begin_title . $title . $end_title;
	}
	echo $begin_content;
	echo $content;
	echo $end_content;
	echo $end;	
}
