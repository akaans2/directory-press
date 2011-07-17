<?php 
global $art_top_item_begin, $art_top_item_end;
$art_top_item_begin = "<span class='l'></span><span class='r'></span><span class='t'>";
$art_top_item_end = "</span>";

function art_get_menu($source='Pages', $depth = false, $menu = null)
{
	if (function_exists('get_nav_menu_locations') && $menu != null && is_string($menu)) { // theme location
		$location = art_get_array_value(get_nav_menu_locations(), $menu);
		if ($location) {
			$menu = wp_get_nav_menu_object($location);
			if($menu) $source = 'Custom Menu';
		} 
	}
	
	if ($source == 'Custom Menu' && function_exists('wp_nav_menu')  && $menu != null) {
		return art_get_list_menu( array( 'menu' => $menu, 'depth' => $depth)); 
    }
    
	if ($source == 'Pages') {
        return art_get_list_pages(array('depth' => $depth, 'sort_column' => 'menu_order, post_title'));
    } 
    
    if ($source == 'Categories') {
        return art_get_list_categories(array('title_li'=> false, 'depth' => $depth));
    }
	return null;
}

/* menus */
function art_get_list_menu($args = array()) {
  global $art_top_item_begin, $art_top_item_end;
  $menu = $args['menu'];
    
  $menu_items = wp_get_nav_menu_items($menu->term_id);

  if(empty($menu_items)) {
      return sprintf('<li><a>'
        . $art_top_item_begin
        . __("Empty menu (%s)", THEME_NS)
        . $art_top_item_end
        . '</a></li>', $menu->slug); 
  }
  $nav_menu = '';
  $items = '';
  _art_menu_item_classes_by_context($menu_items);
  $sorted_menu_items = array();
	foreach ((array) $menu_items as $key => $menu_item) {
		$sorted_menu_items[$menu_item->menu_order] = wp_setup_nav_menu_item($menu_item);
	}

  $walker = new art_MenuWalker();
  $items .= $walker->walk($sorted_menu_items, art_get_array_value($args, 'depth', 0), array());


  $items = apply_filters('wp_nav_menu_items', $items, $args);
  $items = apply_filters("wp_nav_menu_{$menu->slug}_items", $items, $args);
  $nav_menu .= $items;

  $nav_menu = apply_filters('wp_nav_menu', $nav_menu, $args);
  return $nav_menu;
}

function _art_menu_item_classes_by_context( &$menu_items ) {
	global $wp_query;
  $home_page_id = (int) get_option( 'page_for_posts' );
	$queried_object = $wp_query->get_queried_object();
	$queried_object_id = (int) $wp_query->queried_object_id;
    $active_ID  = null;
    $IdToKey = array();
    
    foreach ( (array) $menu_items as $key => $menu_item ) {
        
        $IdToKey[$menu_item->ID] = $key;
        if (
			$menu_item->object_id == $queried_object_id &&
			(
				( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && $wp_query->is_home && $home_page_id == $menu_item->object_id ) ||
				( 'post_type' == $menu_item->type && $wp_query->is_singular ) ||
				( 'taxonomy' == $menu_item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) )
			)
		) {
			
			$active_ID = $menu_item->ID;

		} elseif ( 'custom' == $menu_item->object ) {
		
			$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$item_url = strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url;
			
			if ( $item_url == $current_url ) {
				$active_ID = $menu_item->ID;
			}
		}
	}
    
        
    $currentID = $active_ID;
    while ($currentID !== null && isset($IdToKey[$currentID])) {
        $current_item = $menu_items[$IdToKey[$currentID]];
        $current_item->classes[] = 'active';
        $currentID = $current_item->menu_item_parent;
        
        if ($currentID === '0') break;
    
    }
}

class art_MenuWalker extends Walker {
  var $tree_type = array('post_type', 'taxonomy', 'custom');
  var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');
  var $is_active = false;
  
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul";
    if ($this->is_active){
        $output .= ' class="active" ';
    }
    $output .= ">\n";
    $this->is_active = false;
  }

  function end_lvl(&$output, $depth) {
   $indent = str_repeat("\t", $depth);
   $output .= "$indent</ul>\n";
  }

  function start_el(&$output, $item, $depth, $args) {
    global $wp_query, $art_top_item_begin, $art_top_item_end;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    
    
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
     
    $active = in_array('active', $classes);

    $output .= $indent . '<li';
    if ($active) {
      $this->is_active = true;
      $output .= ' class="active" ';
    }
    $output .= '>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target)     .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url)        .'"' : '';
    $attributes .= ! empty($class_names)      ? ' class="'  . esc_attr($class_names)      .'"' : '';

    $item_output = '<a'. $attributes .'>';
    if ($depth == 0) $item_output .= $art_top_item_begin;
    $item_output .= apply_filters('the_title', $item->title, $item->ID);
    if ($depth == 0) $item_output .= $art_top_item_end;
    $item_output .= '</a>';

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  function end_el(&$output, $item, $depth) {
    $output .= "</li>\n";
    $this->is_active = false;
  }
}

/* pages */
function art_get_list_pages($args = array()) {
  global $wp_query, $art_top_item_begin, $art_top_item_end;
  $pages = &get_pages($args);
  
  $IdToKey = array();
  $currentID = null;
  
  foreach ($pages as $key => $page)
  {
    $IdToKey[$page->ID] = $key;
  }

  if ($wp_query->is_page) {
    $currentID = $wp_query->get_queried_object_id();
  }

  $frontID = null;
  $blogID = null;
  if ('page' == get_option('show_on_front')) {
  
    $frontID = get_option('page_on_front');
    if ($frontID && isset($IdToKey[$frontID])) {
      $frontKey = $IdToKey[$frontID];
      $frontPage = $pages[$frontKey];
      unset($pages[$frontKey]);
      $frontPage->post_parent = '0';
      $frontPage->menu_order = '0';
      array_unshift($pages, $frontPage);
      $IdToKey = array();
      foreach ($pages as $key => $page)
      {
        $IdToKey[$page->ID] = $key;
      }
    }
    
    if (is_home()) {
      $blogID = get_option('page_for_posts');
      if ($blogID && isset($IdToKey[$blogID])) 
      {
          $currentID = $blogID;
      }
    }
  }

  
	$activeIDs = array();
	
  $activeID = $currentID;
	while($activeID && isset($IdToKey[$activeID]))
	{
		$activeIDs[] = $activeID;
		$activePage = $pages[$IdToKey[$activeID]];
		
		if ($activePage && $activePage->post_status == 'private') 
		{
			break;
		}
		
		$activeID = $activePage->post_parent;
		
	}

  $result = '';
  if (art_get_option('art_menu_showHome') && ('page' != get_option('show_on_front') || (!get_option('page_on_front') && !get_option('page_for_posts'))))
  {
		$result = '<li><a' 
			. (is_home() ? ' class="active"' : '') 
			. ' href="' 
			. get_option('home') 
			. '">'
			.$art_top_item_begin
			. art_get_option('art_menu_homeCaption')
          .$art_top_item_end 
          . '</a></li>';
  }
  
  if (!empty($pages)) {
    $walker = new art_PageWalker('list', 'ul', $activeIDs, $frontID);
    $result .= $walker->walk($pages,art_get_array_value($args, 'depth', 0), array(), $currentID);
  }
  
  return $result; 
}

class art_PageWalker extends Walker {
  var $db_fields = array('parent' => 'post_parent', 'id' => 'ID');
  var $activeIDs = array();
  var $frontID = array();
  var $is_active = false;

  function art_PageWalker($type='list', $tag='ul', $activeIDs=array(), $frontID=null){
    $this->tag = $tag;
    $this->activeIDs = $activeIDs;
    $this->frontID = $frontID;
    $this->type = $type;
  }

  function start_lvl(&$output) {
    $output .= "\n<".$this->tag;
    if($this->is_active){
      $output .= ' class="active" ';
    }
    $output .= ">\n";
    $this->is_active = false;
  }

  function end_lvl(&$output) {
    $output .= "</".$this->tag.">\n";
  }

  function start_el(&$output, $page, $depth, $args, $current_page) {
		global $art_top_item_begin, $art_top_item_end;
    $active = in_array($page->ID, $this->activeIDs);
    $output .= '<li'; 
    if ($active) {
      $this->is_active = true;
      $output .= ' class="active" ';
    }
    $output .= '><a';
    if ($active) {
      $output .= ' class="active"';
    }
    
    $href = get_page_link($page->ID);
    if ($this->frontID && $this->frontID == $page->ID) {
      $href = get_option('home');
    }
    $title = apply_filters( 'the_title', $page->post_title, $page->ID ); 
    $output .= ' href="'.$href.'" title="'.esc_attr($title).'">';
    if ($depth == 0) $output .= $art_top_item_begin;
    $output .= $title;
    if ($depth == 0) $output .= $art_top_item_end;
    $output .= '</a>';
  }

  function end_el(&$output, $page) { 
    $output .= "</li>\n"; 
    $this->is_active = false;
  }
}

/* categories */
function art_get_list_categories($args = array()) {
  global $wp_query, $post;
  $categories = &get_categories($args);

  $IdToKey = array();
  foreach ($categories as $key => $category){
    $IdToKey[$category->term_id] = $key;
  }
  
  $currentID = null;
	if ($wp_query->is_category)
	{
    $currentID = $wp_query->get_queried_object_id();
  }
	
	$activeID = $currentID;
	$activeIDs = array();
	while ($activeID && isset($IdToKey[$activeID])) {
      $activeIDs[] = $activeID;
      $activeCategory = $categories[$IdToKey[$activeID]];
      $activeID = $activeCategory->parent;
  }

	if(art_get_option('art_menu_highlight_active_categories') && is_single()){
		foreach((get_the_category($post->ID)) as $cat) { 
			$activeIDs[] = $cat->term_id;
		} 
	}

  $result = '';
  if (!empty($categories)) {
    $walker = new art_CategoryWalker('list','ul', $activeIDs);
    $result .= $walker->walk($categories, art_get_array_value($args, 'depth', 0), array('count' => false, 'current_category' =>$currentID));
  }
  return $result;
}

class art_CategoryWalker extends Walker {
  var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); 
  var $activeIDs = array();
  var $is_active = false;

  function art_CategoryWalker($type='list', $tag='ul', $activeIDs=array()){
    $this->tag = $tag;
    $this->activeIDs = $activeIDs;
    $this->type = $type;
  }

  function start_lvl(&$output){
    $output .= "\n<".$this->tag;
    if ($this->is_active) {
      $output .= ' class="active" ';
    }
    $output .= ">\n";
    $this->is_active = false;
  }

  function end_lvl(&$output) {
    $output .= "</".$this->tag.">\n";
  }

  function start_el(&$output, $category, $depth, $args) {
		global $art_top_item_begin, $art_top_item_end;
    $count = intval($category->count);
    $count_text = sprintf(__('%s posts', THEME_NS), $count);

    $active = in_array($category->term_id, $this->activeIDs);

    $output .= '<li';
    if ($active) {
      $this->is_active = true;
      $output .= ' class="active" ';
    }
    $output .= '>';

    if ($category->description) {
      $title = $category->description;
    } else {
     $title = $count_text;
    }
    
    $output .= '<a';
    if ($active) {
      $output .= ' class="active"';
    }
	$output .= ' href="'.get_category_link($category->term_id).'" title="'.esc_attr($title).'">';
    if ($depth == 0) $output .= $art_top_item_begin;
    $output .= esc_attr($category->name);
    if ($depth == 0) $output .= $art_top_item_end;
    $output .= '</a>';

  }

  function end_el(&$output, $page) { 
    $output .= "</li>\n"; 
    $this->is_active = false;
  }
}

// Not support old wp version
if (WP_VERSION < 3.0) return;
function art_get_pages( $pages ) {
	if(is_admin()) return $pages;

	$excluded_option = get_option('art_show_in_menu');
	if (!$excluded_option || !is_array($excluded_option)) return $pages;
	$excluded_ids = array();
	foreach ($excluded_option as $id => $show)
	{
		if(!$show) {
			$excluded_ids[] = $id;		
		}
	}
	$excluded_parent_ids = array();
	foreach ($pages as $page) {
		
		$title = art_get_meta_option($page->ID, 'art_title_in_menu');
		if ($title) {
			$page->post_title = $title;
		}
		
		if (in_array($page->ID, $excluded_ids)){
			$excluded_parent_ids[$page->ID] = $page->post_parent;
		}
	}

	$length = count($pages);
	for ( $i=0; $i<$length; $i++ ) {
		$page = & $pages[$i];
		if (in_array($page->post_parent, $excluded_ids)) {
			$page->post_parent = art_get_array_value($excluded_parent_ids, $page->post_parent, $page->post_parent);
		}
		if (in_array($page->ID, $excluded_ids)) {
			unset( $pages[$i] );
		}
	}

	if ( ! is_array( $pages ) ) $pages = (array) $pages;
	$pages = array_values( $pages );

	return $pages;
}
add_filter('get_pages','art_get_pages');