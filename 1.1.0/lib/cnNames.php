<?php
/*
Module Name: WP Chinese Name
Module Effect: 默认的wordpress不支持中文名称，该扩展为wordpress添加中文名称支持。
Module Author: <a href='http://www.v7v3.com/wpjiaocheng/2014111322.html'>维7维3</a>
*/
function cst_sanitize_user($username, $raw_username, $strict){
  $username = wp_strip_all_tags( $raw_username );
  $username = remove_accents( $username );
  $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
  $username = preg_replace( '/&.+?;/', '', $username );
  if ($strict) {
    $username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
  }
  $username = trim( $username );
  $username = preg_replace( '|\s+|', ' ', $username);
  return $username;
}
add_filter ('sanitize_user', 'cst_sanitize_user', 10, 3);