<?php
/*
Module Name: 七牛cdn防镜像
Module Effect: 在使用七牛cdn镜像功能时会造成网站被七牛镜像从而导致降权，该扩展可以防止正常网页被七牛镜像。
Module Author: <a href='http://www.v7v3.com/wpjiaocheng/2014111307.html'>维7维3</a>
*/
if( strpos($_SERVER['HTTP_USER_AGENT'],'qiniu-imgstg-spider') !== false) {
header('HTTP/1.1 503 Service Temporarily Unavailable');
exit('防七牛镜像');
}