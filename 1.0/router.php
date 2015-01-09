<?php
Use com\v7v3\www\wordpress\CSTs\svn\system AS CSTS;
require \CST_C.'ini.c.php';
require \CST_C.'lib.f.php';
$cst = New CSTS\CST();
if( file_exists(CST_INC . 'OPT_' . md5(CST_O) . '.php') ){
	include CST_INC . 'OPT_' . md5(CST_O) . '.php';
}