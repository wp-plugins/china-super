<?php
/*
Module Name: 评论文字数量限制
Module Effect: 该插件可以限制wordpress的评论字符长度。
Module Author: <a href='http://www.v7v3.com/wpjiaocheng/201401527.html'>维7维3</a>
*/
function cst_comment_length( $commentdata ) {
	$minCommentlength = 3; //最少字数限制
	$maxCommentlength = 220; //最多字数限制
	$pointCommentlength = mb_strlen($commentdata['comment_content'],'UTF8'); //mb_strlen 1个中文字符当作1个长度
	if( $pointCommentlength < $minCommentlength ){
		header("Content-type: text/html; charset=utf-8");
		wp_die('抱歉，您的评论太短了，请至少输入' . $minCommentlength .'个字（已输入'. $pointCommentlength .'个字）<a href="javascript:history.go(-1);">返回文章页</a>');
	}
	if( $pointCommentlength > $maxCommentlength ){
		header("Content-type: text/html; charset=utf-8");
		wp_die('抱歉，您的评论太长了，请少于' . $maxCommentlength .'个字（已输入'. $pointCommentlength .'个字）<a href="javascript:history.go(-1);">返回文章页</a>');
	}
	return $commentdata;
}
add_filter( 'preprocess_comment', 'cst_comment_length' );