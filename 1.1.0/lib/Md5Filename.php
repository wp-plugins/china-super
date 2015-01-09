<?php
/*
Module Name: 上传文件防乱码
Module Effect: 当使用wordpress后台上传文件时自动用md5函数加密文件名，解决中文文件名乱码问题
Module Author: <a href='http://www.v7v3.com/wpjiaocheng/201402602.html'>维7维3</a>
*/
function cst_rename_filename($filename) {
    $info = pathinfo($filename);
    $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);
    return substr(md5($name), 0, 16) . $ext;
}
add_filter('sanitize_file_name', 'cst_rename_filename', 10);