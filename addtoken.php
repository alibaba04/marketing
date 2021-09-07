<?php
require_once( 'config.php' );
global $dbLink;
$user = $_GET['user'];
$token = $_GET['token'];
$sql = "UPDATE `aki_user` SET `token`='$token' WHERE kodeUser='$user'";
if (!mysql_query( $sql, $dbLink))
	throw new Exception($sql.'Gagal ubah data KK. ');

echo 'test';?>