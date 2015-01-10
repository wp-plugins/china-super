<?php
/*
Plugin Name: China Super ToolS
Plugin URI: http://www.v7v3.com
Description: 一款中文wordpress网站的集成工具插件（一个插件，搞定一切）。cst插件用户交流群：315579148
Version: 2.0
Author: NaiZui
Author URI: http://www.v7v3.com
*/
define('P_N','Super ToolS');
define('CST_ROOT',plugin_dir_path(__FILE__));
define('CST_URI',plugins_url('/',__FILE__));
define('CST_C',CST_ROOT.'private/');
define('CST_S',CST_C.'system/');
define('CST_M',CST_C.'Mode/');
define('CST_D',WP_CONTENT_DIR.'/CstPort/');
define('CST_U',CST_D.'lib/');
define('CST_O',CST_D.'option/');
define('CST_INC',CST_D.'inc/');
if( is_admin() ){
	require CST_ROOT.'router.php';
	register_activation_hook( __FILE__,'cst_start');
}
if( file_exists(CST_INC . 'SYS_' . md5(CST_S) . '.php') ){
	include CST_INC . 'SYS_' . md5(CST_S) . '.php';
}
if( file_exists(CST_INC . 'USE_' . md5(CST_U) . '.php') ){
	include CST_INC . 'USE_' . md5(CST_U) . '.php';
}
function cst_start(){
	
 	function copy_files($src,$dst) {  
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if(( $file != '.' ) && ( $file != '..' )) {
				if( is_dir($src . '/' . $file) ) {
					copy_files($src . '/' . $file,$dst . '/' . $file);
					continue;
				}else{
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	
	if( !file_exists(CST_D) ){
		copy_files(CST_C.'CstPort/',CST_D);
	}
	return;
}