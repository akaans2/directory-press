<?php
// tokens
  global $art_widget_tokens;
  $art_widget_tokens = array(
	  'end_widget' => '<!-- end_widget -->',
	  'begin_title' => '<!-- begin_title -->',  
	  'end_title' => '<!-- end_title -->', 
	  'begin_id' => '<!-- begin_id -->', 
	  'end_id' => '<!-- end_id -->', 
	  'begin_class' => '<!-- begin_class -->',
	  'end_class' => '<!-- end_class -->'
	); 
	
  global $art_sidebars;
  $art_sidebars = array(
  
   'default' => array(
      'name' => __('Primary Widget Area',THEME_NS),
      'id' => 'primary-widget-area',
      'description' => __("This is the default sidebar, visible on 2 or 3 column layouts. If no widgets are active, the default theme widgets will be displayed instead.", THEME_NS)
    ),
    
    'secondary' =>  array(
      'name' => __('Secondary Widget Area',THEME_NS),
      'id' => 'secondary-widget-area',
      'description' => __("This sidebar is active only on a 3 column setup.", THEME_NS)
    ),
    
    'top' => array(
      'name' => __('First Top Widget Area',THEME_NS),
      'id' => 'first-top-widget-area',
      'description' => __("This sidebar is displayed above the main content.", THEME_NS)
    ),
    
    'top2' => array(
      'name' => __('Second Top Widget Area',THEME_NS),
      'id' => 'second-top-widget-area',
      'description' => __("This sidebar is displayed above the main content.", THEME_NS)
    ),
    
   
    'bottom' => array(
      'name' => __('First Bottom Widget Area',THEME_NS),
      'id' => 'first-bottom-widget-area',
      'description' => __("This sidebar is displayed below the main content.", THEME_NS)
    ),
    
    'bottom2' => array(
      'name' => __('Second Bottom Widget Area',THEME_NS),
      'id' => 'second-bottom-widget-area',
      'description' => __("This sidebar is displayed below the main content.", THEME_NS)
    ),
    
   
    'footer' => array(
      'name' => __('First Footer Widget Area',THEME_NS),
      'id' => 'first-footer-widget-area',
      'description' => __("The first footer widget area. You can add a text widget for custom footer text.", THEME_NS) 
    ),
    
    'footer2' => array(
      'name' => __('Second Footer Widget Area',THEME_NS),
      'id' => 'second-footer-widget-area',
      'description' => __("The second footer widget area.", THEME_NS) 
    ),
    
    'footer3' => array(
      'name' => __('Third Footer Widget Area',THEME_NS),
      'id' => 'third-footer-widget-area',
      'description' => __("The third footer widget area.", THEME_NS) 
    ),
    
    'footer4' => array(
      'name' => __('Fourth Footer Widget Area',THEME_NS),
      'id' => 'fourth-footer-widget-area',
      'description' => __("The fourth footer widget area.", THEME_NS) 
    ),

  );

  
if (function_exists('register_sidebar')) {
	
	$art_widget_args = array(
		'before_widget' => $art_widget_tokens['begin_id'] . '%1$s' . $art_widget_tokens['end_id'] . $art_widget_tokens['begin_class'] . 'widget %2$s' .$art_widget_tokens['end_class'],
		'before_title' => $art_widget_tokens['begin_title'],
		'after_title' => $art_widget_tokens['end_title'],
		'after_widget' => $art_widget_tokens['end_widget']
		);

	foreach ($art_sidebars as $sidebar)
    {
		register_sidebar( array_merge($sidebar, $art_widget_args));
    }
}

 function art_extract_widget_param(&$widget, $startToken, $endToken){
  if (!$widget) return "";
  $stPos = strpos($widget, $startToken);
  $etPos = strpos($widget, $endToken);
  $result = "";
  if( $stPos !== false &&  $etPos !== false){
      $start = $stPos + strlen($startToken);
      $result= substr($widget, $start, $etPos - $start);
      $widget = substr($widget, 0, $start) . substr($widget, $etPos);
  }
  $widget = str_replace($startToken, '', $widget);
  $widget = str_replace($endToken, '', $widget);
  return $result;
 }
 
function art_get_dynamic_sidebar_data($name){
	global $art_widget_tokens, $art_sidebars;
	if (!function_exists('dynamic_sidebar')) return false;
	ob_start();
	$success = dynamic_sidebar($art_sidebars[$name]['id']);
	$content = ob_get_clean();
	if (!$success) return false;
	extract($art_widget_tokens);
	$data = explode($end_widget, $content);
	$widgets = array();
	for($i = 0; $i < count($data)-1; $i++){
		$widget = $data[$i];
		if(art_is_empty_html($widget)) continue;
		$widgets[] = array(
		  'id' => art_extract_widget_param($widget, $begin_id, $end_id),
		  'class' => art_extract_widget_param($widget, $begin_class, $end_class),
		  'title' => art_extract_widget_param($widget, $begin_title, $end_title),
		  'content' => $widget
		);
	}
    return $widgets;
}

 function art_print_widgets($widgets, $style){
    if (!is_array($widgets) || count($widgets) < 1) return false;
    for($i = 0; $i < count($widgets); $i++){
      $widget = $widgets[$i];
      if ($widget['id']) {
		$widget_style = art_get_widget_style($widget['id'], $style);
		art_wrapper($widget_style, $widget);
      } else {
          echo $widget['content'];
      }    
    }
    return true;
 }

 function art_dynamic_sidebar($name){
	global $art_sidebars;
    $style = art_get_option('art_sidebars_style_'.$name);
    if (in_array($name, array('default', 'secondary'))) {
		    $widgets = art_get_dynamic_sidebar_data($name);
			return art_print_widgets($widgets, $style);
	}
    $places = array();
    $sum_count = 0;
    foreach ($art_sidebars as $key => $sidebar)
    {
      if (strpos($key, $name) !== false){
        $widgets = art_get_dynamic_sidebar_data($key);
		
        if (is_array($widgets)){
          $count = count($widgets);
          if ($count > 0){
            $sum_count += $count;
            $places[$key] = $widgets;
          }
        }
      }
    }
    if ($sum_count == 0) {
      return false;
    }
	
	?>
<div class="art-content-layout">
    <div class="art-content-layout-row">
		<?php
		$place_count = count($places);
        foreach ($places as $place)
        {
			?>
			<div class="art-layout-cell art-layout-cell-size<?php echo $place_count; ?>">
			<?php if($name == 'footer'): ?>
				<div class="art-center-wrapper">
				<div class="art-center-inner">
			<?php endif; ?>			
			<?php
            art_print_widgets($place, $style); 
			?>
			<?php if($name == 'footer'): ?>
				</div>
				</div>
			<?php endif; ?>	
				<div class="cleared"> </div>
			</div>
			<?php
        }
		?>		
    </div>
</div>
	<?php
    return true;
 }
 