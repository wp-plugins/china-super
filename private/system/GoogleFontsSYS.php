<?php
/*
Module Name: Google Fonts
Module Effect: 替换后台与登录界面的谷歌字体链接
Module Author: <a href='http://www.v7v3.com'>维7维3</a>
*/
function cst_replace_open_sans() {
      wp_deregister_style('open-sans');
      wp_register_style( 'open-sans', '//fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600' );
      wp_enqueue_style( 'open-sans');
}
add_action( 'init', 'cst_replace_open_sans' );