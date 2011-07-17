<?php
define('THEME_NAME',"gsmassociatestheme2");
define('WP_VERSION', $wp_version);

define('THEME_NS', 'twentyten');
define('THEME_LANGS_FOLDER','/languages');
if (class_exists('xili_language')) {
	define('THEME_TEXTDOMAIN',THEME_NS);
} else {
	load_theme_textdomain(THEME_NS, TEMPLATEPATH . THEME_LANGS_FOLDER);
}

if (WP_VERSION < 3.0){
	require_once(TEMPLATEPATH . '/library/legacy.php');
}

art_include_lib('defaults.php');
art_include_lib('misc.php');
art_include_lib('wrappers.php');
art_include_lib('sidebars.php');
art_include_lib('navigation.php');
art_include_lib('shortcodes.php');
if (WP_VERSION >= 3.0) {
	art_include_lib('widgets.php');
}

if ( function_exists('add_theme_support') ) {
	add_theme_support('post-thumbnails');
	add_theme_support('nav-menus');
	add_theme_support('automatic-feed-links');
}
 
if (function_exists('register_nav_menus')) {
	register_nav_menus(array('primary-menu'	=>	__( 'Primary Menu', THEME_NS)));
}


if(is_admin()){
	art_include_lib('options.php');
	art_include_lib('admins.php');
	
	function art_add_option_page() {
		add_theme_page(__('Artisteer Options', THEME_NS), __('Artisteer Options', THEME_NS), 'edit_themes', basename(__FILE__), 'art_print_options');
	} 
	add_action('admin_menu', 'art_add_option_page');
	if (WP_VERSION >= 3.0) {
		/* Add widgets extra option */
		add_action('sidebar_admin_setup', 'art_widget_process_control');
		
		/* Define the art page title box */
		add_action('add_meta_boxes', 'art_add_meta_boxes');

		/* Save art page title show status */
		add_action('save_post', 'art_save_post');
	}
	return;
}

function art_get_option($name){
	global $art_default_options;
	$result = get_option($name);
	if ($result === false) {
		$result = art_get_array_value($art_default_options, $name);
	}
	return $result;
}

function art_get_meta_option($id, $name){
	global $art_default_meta_options;
	return art_get_array_value(get_option($name), $id, art_get_array_value($art_default_meta_options, $name));
}

function art_set_meta_option($id, $name, $value){
	$meta_option = get_option($name);
	if (!$meta_option || !is_array($meta_option)) {
		$meta_option = array();
	}
	$meta_option[$id] = $value;
	update_option($name, $meta_option);
}

function art_get_post_id(){
	$post_id = get_the_ID();
	if($post_id != ''){
		$post_id = 'post-' . $post_id;
	}
	return $post_id;
}

function art_get_post_class(){
	if (!function_exists('get_post_class')) return '';
	return implode(' ', get_post_class());
}

function art_include_lib($name){
	locate_template(array('library/'.$name), true);
}

function art_get_meta_icon($icon, $width, $height){
	return '<img src="'.get_bloginfo('template_url').'/images/'.$icon.'.png" width="'.$width.'" height="'.$height.'" alt="" />';
}

function art_get_metadata_icons($icons = '', $class=''){
	global $post;
	if (!is_string($icons) || strlen($icons) == 0) return;
	$icons = explode(",", str_replace(' ', '', $icons));
	if (!is_array($icons) || count($icons) == 0) return;
	$result = array();
	for($i = 0; $i < count($icons); $i++){
		$icon = $icons[$i];
		switch($icon){
			case 'date':
				$result[] = art_get_meta_icon('postdateicon', 17, 18) . sprintf( __('<span class="%1$s">Published</span> %2$s', THEME_NS),
								'date',
								sprintf( '<span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span>',
									esc_attr( get_the_time() ),
									get_the_date()
								)
							);
			break;
			case 'author':
				$result[] = art_get_meta_icon('postauthoricon', 32, 13) . sprintf(__('<span class="%1$s">By</span> %2$s', THEME_NS),
								'author',
								sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
									get_author_posts_url( get_the_author_meta( 'ID' ) ),
									sprintf( esc_attr(__( 'View all posts by %s', THEME_NS )), get_the_author() ),
									get_the_author()
								)
							);
			break;
			case 'category':
				$categories = get_the_category_list(', ');
				if(strlen($categories) == 0) break;
				$result[] = art_get_meta_icon('postcategoryicon', 18, 18) . sprintf(__('<span class="%1$s">Posted in</span> %2$s', THEME_NS), 'categories', get_the_category_list(', '));
			break;
			case 'tag':
				$tags_list = get_the_tag_list( '', ', ' );
				if(!$tags_list) break;
				$result[] = art_get_meta_icon('posttagicon', 18, 18) . sprintf( __( '<span class="%1$s">Tagged</span> %2$s', THEME_NS ), 'tags', $tags_list );
			break;
			case 'comments':
				if(!comments_open()) break;
				ob_start();
				comments_popup_link( __( 'Leave a comment', THEME_NS ), __( '1 Comment', THEME_NS ), __( '% Comments', THEME_NS ) );
				$result[] = art_get_meta_icon('postcommentsicon', 18, 18) . ob_get_clean();
			break;
			case 'edit':
				if (!current_user_can('edit_post', $post->ID)) break;
				ob_start();
				edit_post_link(__('Edit', THEME_NS), '');
				$result[] = ob_get_clean();
			break;
		}
	}
	$result = implode(art_get_option('art_metadata_separator'), $result);
	if (art_is_empty_html($result)) return;
	return "<div class=\"art-post{$class}icons art-metadata-icons\">{$result}</div>";
}

 function art_get_post_thumbnail($args = array()){
	global $post;
	$size = art_get_array_value($args, 'size', array(art_get_option('art_metadata_thumbnail_width'), art_get_option('art_metadata_thumbnail_height')));
	$auto = art_get_array_value($args, 'auto', art_get_option('art_metadata_thumbnail_auto'));
	$title = art_get_array_value($args, 'title', get_the_title());

	$result = '';

	if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
		ob_start();
		the_post_thumbnail($size, array('alt'	=>	'', 'title'	=>	$title));
		$result = ob_get_clean();
	} elseif ($auto) {
		$attachments = get_children(array('post_parent'	=>	$post->ID, 'post_status'	=>	'inherit', 'post_type'	=>	'attachment', 'post_mime_type'	=>	'image', 'order'	=>	'ASC', 'orderby'	=>	'menu_order ID'));
		if($attachments) {
			$attachment = array_shift($attachments);
			$img = wp_get_attachment_image_src($attachment->ID, $size);
			if (isset($img[0])) {
				$result = '<img src="'.$img[0].'" alt="" width="'.$img[1].'" height="'.$img[2].'" title="'.$title.'" class="wp-post-image" />';
			}
		}
	}	
	if($result !== ''){
		$result = '<div class="avatar alignleft"><a href="'.get_permalink($post->ID).'" title="'.$title.'">'.$result.'</a></div>';
	}
	return $result;
}

function art_get_the_content($more_link_text = null, $stripteaser = 0) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('the_content', $content);
	return $content;
}

function art_get_content($args = array()) {
	$more_tag = art_get_array_value($args, 'more_tag', __('Continue reading <span class="meta-nav">&rarr;</span>', THEME_NS));
	return art_get_the_content($more_tag . wp_link_pages(array('echo' => 0)));
}

function art_get_excerpt($args = array()) {
	global $post;
	$more_tag = art_get_array_value($args, 'more_tag', __('Continue reading <span class="meta-nav">&rarr;</span>', THEME_NS));
	$auto = art_get_array_value($args, 'auto', art_get_option('art_metadata_excerpt_auto'));
	$all_words = art_get_array_value($args, 'all_words', art_get_option('art_metadata_excerpt_words'));
	$min_remainder = art_get_array_value($args, 'min_remainder', art_get_option('art_metadata_excerpt_min_remainder'));
	$allowed_tags = art_get_array_value($args, 'allowed_tags', 
		(art_get_option('art_metadata_excerpt_use_tag_filter') 
			? explode(',',str_replace(' ', '', art_get_option('art_metadata_excerpt_allowed_tags'))) 
			: null));
	$perma_link = get_permalink($post->ID);
	$more_token = '%%art-more%%';
	$show_more_tag = false;
	if (function_exists('post_password_required') && post_password_required($post)){
		return get_the_excerpt();
	}
	if ($auto && has_excerpt($post->ID)) {
		$the_contents = get_the_excerpt();
		$the_contents = apply_filters('the_content', $the_contents);
		$show_more_tag = strlen($post->post_content) > 0;
	} else {
		$the_contents = art_get_the_content($more_token);
		if(art_is_empty_html($the_contents)) return $the_contents;
		if ($allowed_tags !== null) {
			$allowed_tags = '<' .implode('><',$allowed_tags).'>';
			$the_contents = strip_tags($the_contents, $allowed_tags);
		}
		$the_contents = strip_shortcodes($the_contents);
		if (strpos($the_contents, $more_token) !== false) {
			return str_replace($more_token, $more_tag, $the_contents);
		}
		if($auto && is_numeric($all_words)) {
			$token = "%art_tag_token%";
			$content_parts = explode($token, str_replace(array('<', '>'), array($token.'<', '>'.$token), $the_contents));
			$content = array();
			$word_count = 0;
			foreach($content_parts as $part)
			{
				if (strpos($part, '<') !== false || strpos($part, '>') !== false){
					$content[] = array('type'=>'tag', 'content'=>$part);
				} else {
					$all_chunks = preg_split('/([\s])/u', $part, -1, PREG_SPLIT_DELIM_CAPTURE);
					foreach($all_chunks as $chunk) {
						if('' != trim($chunk)) {
							$content[] = array('type'=>'word', 'content'=>$chunk);
							$word_count += 1;
						} elseif($chunk != '') {
							$content[] = array('type'=>'space', 'content'=>$chunk);
						}
					}
				}
			}

			if(($all_words < $word_count) && ($all_words + $min_remainder) <= $word_count) {
				$show_more_tag = true;
				$current_count = 0;
				$the_contents = '';
				foreach($content as $node) {
					if($node['type'] == 'word') {
						$current_count++;
					} 
					$the_contents .= $node['content'];
					if ($current_count == $all_words){
						break;
					}
				}
			}
			$the_contents = force_balance_tags($the_contents);			
		}
	}
	if ($show_more_tag) {
		$the_contents = $the_contents.'<p><a class="more-link" href="'.$perma_link.'">'.$more_tag.'</a></p>';
	}
	
	
	
	return $the_contents;
}


function art_get_search(){
	ob_start();
	get_search_form();
	return ob_get_clean();
}

function art_pagination($args = array()) {
	$title = art_get_array_value($args, 'title', '');
	$prev_link = art_get_array_value($args, 'prev_link');
	$next_link = art_get_array_value($args, 'next_link');
	if (!$prev_link && !$next_link) {
		if (function_exists('wp_pagenavi')) {
			ob_start();
			wp_pagenavi();
			$content = ob_get_clean();
			art_post_wrapper(array('title' => $title, 'content' => $content));
			return;
		} 
		//posts
		$prev_link = get_previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', THEME_NS));
		$next_link = get_next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', THEME_NS));
	}
	$content = '';
	if ($prev_link || $next_link) {

		$content = <<<EOL
<div class="navigation">
    <div class="alignleft">{$next_link}</div>
    <div class="alignright">{$prev_link}</div>
 </div>
EOL;
	}
	art_post_wrapper(array('title' => $title, 'content' => $content));
}


function art_get_previous_post_link($label)
{
	ob_start();
	previous_post_link($label);
	return ob_get_clean();
}

function art_get_next_post_link($label)
{
	ob_start();
	next_post_link($label);
	return ob_get_clean();
}

if (!function_exists('get_previous_comments_link')) {
	function get_previous_comments_link($label)
	{
		ob_start();
		previous_comments_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_next_comments_link')) {
	function get_next_comments_link($label)
	{
		ob_start();
		next_comments_link($label);
		return ob_get_clean();
	}
}

function art_comment($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
<?php ob_start(); ?>		
		<div class="comment-author vcard">
			<div class="avatar"><?php echo get_avatar($comment, $size='48'); ?></div>
			<cite class="fn"><?php comment_author_link(); ?>:</cite>
		</div>
		<?php if ($comment->comment_approved == '0'): ?>
		<em><?php _e('Your comment is awaiting moderation.', THEME_NS); ?></em><br />
		<?php endif; ?>
		<div class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
				<?php printf(__('%1$s at %2$s', THEME_NS), get_comment_date(), get_comment_time()); ?>
			</a>
			<?php edit_comment_link('('.__('Edit', THEME_NS).')','	',''); ?>
		</div>
		<?php comment_text(); ?>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth'	=>	$depth, 'max_depth'	=>	$args['max_depth']))); ?>
		</div>
<?php art_post_wrapper(array('content' => ob_get_clean())); ?>
	</div>
<?php
}
