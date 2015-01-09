<?php
/*
Module Name: 禁止日文评论
Module Effect: 使用该扩展后可以禁止日语评论。
Module Author: <a href='http://www.v7v3.com/wpjiaocheng/201401527.html'>维7维3</a>
*/
function cst_comment_jp( $incoming_comment ) {
	$jpattern ='/[ぁ-ん]+|[ァ-ヴ]+/u';
	if(preg_match($jpattern, $incoming_comment['comment_content'])){
		header("Content-type: text/html; charset=utf-8");
		wp_die( "日文滚粗！Japanese Get out！日本語出て行け！<a href='javascript:history.go(-1);'>返回文章页</a>");
	}
	return( $incoming_comment );
}
add_filter('preprocess_comment', 'cst_comment_jp');