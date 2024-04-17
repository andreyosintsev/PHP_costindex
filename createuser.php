<?php require 'functions.php';?>
<?php
	db_connect();
		$pass=md5('Pokemon');
		$sql=mysql_query("INSERT INTO users (login, pass) VALUES ('kintaro_oe', '$pass')");
	db_disconnect();	
?>