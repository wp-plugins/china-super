=== 中文超级工具箱(China Super ToolS) ===
Contributors: nocase
Donate link: http://www.v7v3.com/
Tags: functions.php,代码管理,admin,后台
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 2.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

很多站长喜欢在主题的functions.php添加代码来扩充网站功能，本插件提供一个在线管理代码片段的功能。

== Description ==

一款高效的可扩展功能的wordpress代码片段管理插件，用户可以将一些常用功能的代码片段保存为单独的一个php文件并放置到插件wp-content/plugins/wp-china-tools/lib/目录
在插件后台可以管理这些代码片段，并且插件会记录插件功能，时间长了用户也不会忘记自己加过什么代码。插件在载入用户 自定代码时会压缩代码合并所有代码并生成相关的集合文件，并以最高效的方式载入代码。

(例)添加自定义模块：
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
将代码保存为php文件放置到wp-content/plugins/wp-china-tools/lib/目录，然后登录后台扫描并生成引用文件即可使用自定义模块了。


官方网站：[www.v7v3.com](http://www.v7v3.com/ "维7维3")

= 特色 =

1. 记录代码片段功能
2. 功能可扩展
3. 高效的代码载入方式
4. 可在线安装插件作者预先定义好的代码片段
5. 可视化的代码片段操作控制台
6. 高效精简的代码保证插件的稳定型以及运行效率。

== Installation ==

1. 在WordPress 插件库中搜索"China Super ToolS", 下载并启用
2. 在WordPress “Super ToolS”扫描和生成压缩的代码块文件
3. 在WordPress  “Super ToolS -> CST在线扩展”可以在线添加各种预设代码片段
   
== Frequently Asked Questions ==

1. 我的网站想使用China Super ToolS，请问对网站系统有什么基本要求吗？

对于WordPress网站，建议WordPress版本在3.5或以上，这样能完整的实现所有功能。
Php程序必须5.3以上。

== Screenshots ==
1. Super ToolS下可以插件当前安装的扩展数量，并且可以生成代码引用块
2. 针对用户添加的代码，都进行压缩合并，并生成一个唯一的引用文件。
3. 记录代码功能
4. 可在线扩展功能
5. 系统内置两个中文网站加速模块(google fonts to fonts,china gravatar cdn)

== Changelog ==

= 0.1 (2015-1-6) =
完成插件制作，并且在多个线上网站进行测试
= 2.0 (2015-1-10) =
新增在线上传扩展以及扩展在线卸载功能。