<?php

$art_menu_source_options = array(
	'Pages'	=>	'Pages',
	'Categories'	=>	'Categories'
);

$art_sidebars_style_options = array(
	'block'	=>	'Block Style',
	'post'	=>	'Post Style',
	'simple'	=>	'Simple Text'
);

global $art_options;
$art_options = array (
	array(	
	'name'	=>	__('Header', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_header_show_headline',
	'name'	=>	__('Show Headline', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_header_show_slogan',
	'name'	=>	__('Show Slogan', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'name'	=>	__('Menu', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_menu_showHome',
	'name'	=>	__('Show Home Item', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_menu_homeCaption',
	'name'	=>	__('Home Item Caption', THEME_NS),
	'type'	=>	'text'
	),
	array(	
	'id'	=>	'art_menu_highlight_active_categories',
	'name'	=>	__('Highlight Active Categories', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_menu_source',
	'name'	=>	__('Default Menu Source', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_menu_source_options,
	'desc'	=>	__('Displayed when Appearance > Menu > Primary menu is not set', THEME_NS),
	),
	array(	
	'name'	=>	__('Post Thumbnails', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_metadata_thumbnail_auto',
	'name'	=>	__('Use Auto Thumbnails', THEME_NS),
	'desc'	=>	__('Generate post thumbnails automatically (use the first image from the post gallery)', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_metadata_thumbnail_width',
	'name'	=>	__('Thumbnail Width', THEME_NS),
	'desc'	=>	__('(px)', THEME_NS),
	'type'	=>	'numeric'
	),
	array(	
	'id'	=>	'art_metadata_thumbnail_height',
	'name'	=>	__('Thumbnail Height', THEME_NS),
	'desc'	=>	__('(px)', THEME_NS),
	'type'	=>	'numeric'
	),
	array(	
	'name'	=>	__('Post Excerpts', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_metadata_excerpt_auto',
	'name'	=>	__('Use Auto Excerpts', THEME_NS),
	'desc'	=>	__('Generate post excerpts automatically (When neither more-tag nor post excerpt is used)', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_metadata_excerpt_words',
	'name'	=>	__('Excerpt Length', THEME_NS),
	'desc'	=>	__('(words)', THEME_NS),
	'type'	=>	'numeric'
	),
	array(	
	'id'	=>	'art_metadata_excerpt_min_remainder',
	'name'	=>	__('Excerpt Balance', THEME_NS),
	'desc'	=>	__('(words)', THEME_NS),
	'type'	=>	'numeric'
	),
	array(	
	'id'	=>	'art_metadata_excerpt_use_tag_filter',
	'name'	=>	__('Apply Excerpt Tag Filter', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_metadata_excerpt_allowed_tags',
	'name'	=>	__('Allowed Excerpt Tags', THEME_NS),
	'desc'	=>	__('Used when "Apply Excerpt Tag Filter" is enabled', THEME_NS),
	'type'	=>	'widetext'
	),
	array(	
	'id'	=>	'art_single_pagination',
	'name'	=>	__('Show Single Post Pagination', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'name'	=>	__('Default Sidebar Style', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_sidebars_style_default',
	'name'	=>	__('Primary Sidebar', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_sidebars_style_options
	),
	array(	
	'id'	=>	'art_sidebars_style_secondary',
	'name'	=>	__('Secondary Sidebar', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_sidebars_style_options
	),
	array(	
	'id'	=>	'art_sidebars_style_top',
	'name'	=>	__('Top Sidebars', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_sidebars_style_options
	),
	array(	
	'id'	=>	'art_sidebars_style_bottom',
	'name'	=>	__('Bottom Sidebars', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_sidebars_style_options
	),
	array(	
	'id'	=>	'art_sidebars_style_footer',
	'name'	=>	__('Footer Sidebars', THEME_NS),
	'type'	=>	'select',
	'options'	=>	$art_sidebars_style_options
	),
	array(	
	'name'	=>	__('Footer', THEME_NS),
	'type'	=>	'heading'
	),
	array(	
	'id'	=>	'art_footer_content',
	'name'	=>	__('Footer Content', THEME_NS),
	'desc'	=>	sprintf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>', THEME_NS), 'a, abbr, acronym, em, b, i, strike, strong, span') . '<br />'
	   		. sprintf(__('<strong>ShortTags:</strong><code>%s</code>', THEME_NS), '[year], [top], [rss], [login-link], [blog-title], [xhtml], [css]'),
	'type'	=>	'textarea'
	),
	array(	
	'name'	=>	__('Advertisment', THEME_NS),
	'type'	=>	'heading',
	'desc' => sprintf(__('Use the %s short code to insert these ads into posts, text widgets or footer',THEME_NS),'<code>[ad]</code>') . '<br/>'
		. '<span>' . __('Example:',THEME_NS) .'</span><code>[ad code=4 align=center]</code>'
	),
	array(	
	'id'	=>	'art_ad_code_1',
	'name'	=>	sprintf(__('Ad code #%s:',THEME_NS),1),
	'type'	=>	'textarea'
	),
	array(	
	'id'	=>	'art_ad_code_2',
	'name'	=>	sprintf(__('Ad code #%s:',THEME_NS),2),
	'type'	=>	'textarea'
	),
	array(	
	'id'	=>	'art_ad_code_3',
	'name'	=>	sprintf(__('Ad code #%s:',THEME_NS),3),
	'type'	=>	'textarea'
	),
	array(	
	'id'	=>	'art_ad_code_4',
	'name'	=>	sprintf(__('Ad code #%s:',THEME_NS),4),
	'type'	=>	'textarea'
	),
	array(	
	'id'	=>	'art_ad_code_5',
	'name'	=>	sprintf(__('Ad code #%s:',THEME_NS),5),
	'type'	=>	'textarea'
	)
);



global $art_page_meta_options;
$art_page_meta_options = array (
	array(	
	'id'	=>	'art_show_in_menu',
	'name'	=>	__('Show in Menu', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	),
	array(	
	'id'	=>	'art_title_in_menu',
	'name'	=>	__('Title in Menu', THEME_NS),
	'type'	=>	'text'
	),
	array(	
	'id'	=>	'art_show_page_title',
	'name'	=>	__('Show Title on Page', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	)
);

global $art_post_meta_options;
$art_post_meta_options = array(
	array(	
	'id'	=>	'art_show_post_title',
	'name'	=>	__('Show Title on Single Page', THEME_NS),
	'desc'	=>	__('Yes', THEME_NS),
	'type'	=>	'checkbox'
	)
);
