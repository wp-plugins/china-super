<?php
/*
Module Name: China Gravatar CDN
Module Effect: 替换Gravatar头像为可用源
Module Author: <a href='http://www.v7v3.com'>维7维3</a>
*/
function cst_get_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"cn.gravatar.com",$avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'cst_get_avatar', 10, 3 );