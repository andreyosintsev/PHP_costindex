<?php
    require 'functions.php';

    $con = db_connect();

		$summa = 0;
	
		$sql = mysql_query("SELECT COUNT(*), SUM(value) FROM goods");
		$row = mysql_fetch_array($sql);
		
		$summa = $row['SUM(value)'];
		$count = $row['COUNT(*)'];

    db_disconnect($con);

    if ($count == 0) $count = 1;
	$indexvalue = $summa/$count;

	index_add($indexvalue);