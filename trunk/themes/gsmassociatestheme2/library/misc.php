<?php

function art_get_array_value($arr = array(), $key = null, $def = false){
	if (is_array($arr) && isset($arr[$key])){
		return $arr[$key];
	}
	return $def;
}

function art_is_empty_html($str){
	return (!is_string($str) || strlen(str_replace(array('&nbsp;', ' ', "\n", "\r", "\t"), '', $str)) == 0);
}

function art_is_vmenu_widget($id){
	 return (strpos($id, 'art_vmenu') !== false);
}


function art_get_current_url() {
	$pageURL = 'http';
	if (is_ssl()) {
		$pageURL .= 's';
	}
	$pageURL .= '://' . $_SERVER['SERVER_NAME'];
	if ($_SERVER['SERVER_PORT'] != '80') {
		$pageURL .= ':' . $_SERVER["SERVER_PORT"];
	} 
	$pageURL .= $_SERVER["REQUEST_URI"];
	return $pageURL;
}
