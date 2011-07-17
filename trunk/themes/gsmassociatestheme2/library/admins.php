<?php

function art_print_options() {
	global $art_options;
	$br = "\n";
?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2><?php _e('Artisteer Options', THEME_NS); ?></h2>
<?php 
	if ( isset($_REQUEST['Submit']) )	{ 
		foreach ($art_options as $value) {
			$id = art_get_array_value($value, 'id');
			$val = stripslashes(art_get_array_value($_REQUEST, $id));
			$type = art_get_array_value($value, 'type');
			switch($type){
				case 'checkbox':
					$val = ($val  ? 1 : 0);
				break;
				case 'numeric':
					$val = (int) $val;
				break;
			}
			update_option( $id, $val); 
		}
		echo '<div id="message" class="updated fade"><p><strong>'. __('Settings saved.', THEME_NS) .'</strong></p></div>'.$br; 
	} 
	if ( isset($_REQUEST['Reset']) )	{ 
	foreach ($art_options as $value) {
		delete_option(art_get_array_value($value, 'id')); 
	}
	echo '<div id="message" class="updated fade"><p><strong>'. __('Settings restored.', THEME_NS) . '</strong></p></div>'.$br;
	} 
	echo '<form method="post">'.$br;
	$in_form_table = false;
	foreach ($art_options as $op) {
		$type = art_get_array_value($op, 'type');
		$name = art_get_array_value($op, 'name');
		$desc = art_get_array_value($op, 'desc');
		if ($type == 'heading'){
			if ($in_form_table) {
				echo '</table>'.$br;
				$in_form_table = false;
			}
			echo '<h3>'.$name.'</h3>'.$br;
			if ($desc) {
				echo "\n".'<p class="description">'.$desc.'</p>'.$br;
			}
		} else {
			if (!$in_form_table) {
				echo '<table class="form-table">'.$br;
				$in_form_table = true;
			}
			echo '<tr valign="top">'.$br;
			echo '<th scope="row">'.$name.'</th>'.$br;
			echo '<td>'.$br;
			$id = art_get_array_value($op, 'id');
			$val = art_get_option($id);
			art_print_option_control($op, $val);
			if ($desc) {
			echo '<span class="description">'.$desc.'</span>'.$br;
			}
			echo '</td>'.$br;
			echo '</tr>'.$br;
		}
	}
	if ($in_form_table) {
	echo '</table>'.$br;
	}
?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php echo esc_attr(__('Save Changes', THEME_NS)) ?>" />
		<input name="Reset" type="submit" class="button-secondary" value="<?php echo esc_attr(__('Reset to Default', THEME_NS)) ?>" />
	</p>
	</form>
	</div>
<?php
}


function art_print_option_control($op, $val){
	$id = art_get_array_value($op, 'id');
	$type = art_get_array_value($op, 'type');
	$options = art_get_array_value($op, 'options');
	$br = "\n";
	switch ( $type) {
		case 'numeric':
		echo '<input	name="'.$id.'" id="'.$id.'" type="text" value="'.absint($val).'" class="small-text" />'.$br;
		break;
		case 'select':
		echo '<select name="'.$id.'" id="'.$id.'">'.$br;
			foreach ($op['options'] as $key	=>	$option) { 
				$selected = ($val == $key ? ' selected="selected"' : '');
				echo '<option'.$selected.' value="'.$key.'">'.esc_html(__($option, THEME_NS)).'</option>'.$br; 
			}
		echo '</select>'.$br;
		break;
		case 'textarea':
		echo '<textarea name="'.$id.'" id="'.$id.'" rows="10" cols="50" class="large-text code">'.esc_html($val).'</textarea><br />'.$br;
		break;
		case "radio":
			foreach ($op['options'] as $key=>$option) {
				$checked = ( $key == $val ? 'checked="checked"' : '');
				echo '<input type="radio" name="'.$id.'" id="'.$id.'" value="'.esc_attr($key).'" '.$checked.'/>'.esc_html($option).'<br />'.$br;
			}
		break;
		case "checkbox":
			$checked =	($val ? 'checked="checked" ' : ''); 
			echo '<input type="checkbox" name="'.$id.'" id="'.$id.'" value="1" '.$checked.'/>'.$br;
		break;
		default:
		$class = 'regular-text';
		if ($type == 'numeric'){
			$type = 'text';
			$class = 'small-text';
			$val = absint($val);
		}
		if ($type == 'widetext') {
			$class = 'large-text';
		}
		echo '<input	name="'.$id.'" id="'.$id.'" type="'.$type.'" value="'.esc_attr($val).'" class="'.$class.'" />'.$br;
		break;
	}
}

// Not support old wp version
if (WP_VERSION < 3.0) return;
 


function art_add_meta_boxes() {
    add_meta_box( 'art_meta_box',
                  __('Theme Options', THEME_NS),
                  'art_print_page_meta_box',
                  'page',
                  'side',
                  'low'
                 );
    add_meta_box( 'art_meta_box',
                  __('Theme Options', THEME_NS),
                  'art_print_post_meta_box',
                  'post',
                  'side',
                  'low'
                 );
}

/* Prints meta box content */
function art_print_page_meta_box($post) {
	global $art_page_meta_options;
	art_print_meta_box($post, $art_page_meta_options);
}

function art_print_post_meta_box($post) {
	global $art_post_meta_options;
	art_print_meta_box($post, $art_post_meta_options);
}

function art_print_meta_box($post, $meta_options) {
    // Use nonce for verification
    wp_nonce_field('art_meta_options', 'art_meta_noncename');
	if (!isset($post)) return;
	foreach ($meta_options as $option) {
		$id = art_get_array_value($option, 'id');
		$name = art_get_array_value($option, 'name');
		$desc = art_get_array_value($option, 'desc');
		$value = art_get_meta_option($post->ID, $id);
		$necessary = art_get_array_value($option, 'necessary');
		if ($necessary && !current_user_can($necessary)) continue;
        echo '<p class="meta-options"><label class="selectit" for="'.$id.'"><strong>'.$name .'</strong><br />';
		art_print_option_control($option, $value);
		echo  '</label>';
		if ($desc) {
			echo '<em>'.$desc.'</em>';
		}
		echo'</p>';
    }
}



// post metadata
/* When the post is saved, saves our data */
function art_save_post($post_id) {
	global $art_post_meta_options, $art_page_meta_options;
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if (!isset($_POST['art_meta_noncename']) || !wp_verify_nonce($_POST['art_meta_noncename'], 'art_meta_options' )) {
        return $post_id;
    }

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;
	
	$meta_options = null;//posts
	if ( 'page' == $_POST['post_type']) {
		// Check permissions
		if (!current_user_can( 'edit_page', $post_id ) ){
			return $post_id;
		}
		$meta_options = $art_page_meta_options;
	}
	
	if ( 'post' == $_POST['post_type']) {
		$meta_options = $art_post_meta_options;
	}
	
	if (!$meta_options) return $post_id;
	// OK, we're authenticated: we need to find and save the data
	foreach ($meta_options as $value) {
		$id = art_get_array_value($value, 'id');
		$val = stripslashes(art_get_array_value($_REQUEST, $id));
		$type = art_get_array_value($value, 'type');
		$necessary = art_get_array_value($value, 'necessary');
		if ($necessary && !current_user_can($necessary)) continue;
		switch($type){
			case 'checkbox':
				$val = ($val  ? 1 : 0);
			break;
			case 'numeric':
				$val = (int) $val;
			break;
		}
		art_set_meta_option($post_id, $id, $val); 
	}
	return $post_id;
}