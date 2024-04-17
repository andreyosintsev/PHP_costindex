<?php
	$host="localhost";
	$user="pumpmeu7_costidx";
	$password="cstid123";
	$db="pumpmeu7_costindex";
	
	$connected = mysql_connect($host, $user, $password);
	if (!$connected) die ("MySQL сервер недоступен!".mysql_error());
	
	mysql_select_db($db) or die("Нет соединения с БД".mysql_error());
	mysql_query("SET NAMES 'utf8'"); 
	mysql_query("SET CHARACTER SET 'utf8'");
	mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");

		$summa = 0;
	
		$sql=mysql_query("SELECT COUNT(*), SUM(value) FROM goods");
		$row=mysql_fetch_array($sql);
		
		$summa = $row['SUM(value)'];
		$count = $row['COUNT(*)'];
		if ($count==0) $count=1;
		
		$indexvalue = $summa/$count;
		$sql=mysql_query("INSERT INTO indexvalue (indexvalue) VALUES ('$indexvalue')");
	
	
	mysql_close($connected);	
?>