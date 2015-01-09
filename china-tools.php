<?php
/*
Plugin Name: China Super ToolS
Plugin URI: http://www.v7v3.com
Description: 一款中文wordpress网站的集成工具插件（一个插件，搞定一切）。
Version: 1.0
Author: NaiZui
Author URI: http://www.v7v3.com
*/
define('P_N','Super ToolS');
define('CST_ROOT',plugin_dir_path(__FILE__));
define('CST_URI',plugins_url('/',__FILE__));
define('CST_C',CST_ROOT.'private/');
define('CST_S',CST_C.'system/');
define('CST_M',CST_C.'Mode/');
define('CST_U',CST_ROOT.'lib/');
define('CST_O',CST_ROOT.'option/');
define('CST_INC',CST_ROOT.'inc/');
if( is_admin() ){
	require CST_ROOT.'router.php';
}
if( file_exists(CST_INC . 'SYS_' . md5(CST_S) . '.php') ){
	include CST_INC . 'SYS_' . md5(CST_S) . '.php';
}
if( file_exists(CST_INC . 'USE_' . md5(CST_U) . '.php') ){
	include CST_INC . 'USE_' . md5(CST_U) . '.php';
}