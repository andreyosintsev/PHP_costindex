<?php

/*
	Перечень всех функций:

	db_connect()
	db_disconnect($con)
	db_insertindex($value)
	login($login = '', $pass = '')
	get_date_newest($format, $echo = true)


	- do_date($format, $echo=true)

	convert_date_interval_to_string($time_interval = '6month')
	graph_index_fill($time_interval)
    graph_value_fill($idx)
	get_index_newest() 
	get_index_change()
	runtime($time_start = 0, $is_get_runtime = false)

	- do_graph()
	get_goods_num($group = '')
	get_goods_table($group, $filter='', $sortby='name', $sortdir='ASC')
	- get_tablepricechange($idx)
	- get_tablecosts($login)
	- get_goods() 
	get_good($idx, $units=true)
	- get_units()
    get_group()
	- get_groups()
	
	get_thumb($idx)
	get_price($idx)
	get_price_max($idx)
	get_price_min($idx)
	- get_params($idx)
	
	get_group_name($idx)

	- get_group($idx)
	get_group_contents()
	- get_group_index_img()
	- del_costs($goods)
	- add_costs($goods, $cost)
	- update_costs()
	- add_indexvalue()
	add_goods($groups, $goods, $cost, $units)
	- get_related($idx)
	get_popular($group)
	- conv_kcal_to_kj ($kcal)

	- is_logged($session)
	- confirm_user($login, $number)
	get_edit_link($session)
	- rus_to_lat($rus_str)

	*/

require 'config.php';

/*
	ФУНКЦИИ РАБОТЫ С БАЗОЙ ДАННЫХ
*/

//refactored
function db_connect() {
	$connected = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$connected) {
		die("Ошибка: MySQL сервер недоступен! MYSQL_ERROR: ".mysql_error());
	};
	
	mysql_select_db(DB_NAME) || die("Ошибка: не удалось установить соединение с БД. MYSQL_ERROR: ".mysql_error());
	mysql_query("SET NAMES 'utf8mb4'"); 
	mysql_query("SET CHARACTER SET 'utf8mb4'");
	mysql_query("SET SESSION collation_connection = 'utf8mb4_unicode_ci'");
	
	return $connected;
}

//refactored
function db_disconnect($con) {
	if (!isset($con)) return;

	mysql_close($con) || die("Ошибка: не удалось разорвать соединение с БД. MYSQL_ERROR: ".mysql_error()); ;	
}

//refactored
function login($login = '', $pass = '') {
	if ($login == '') return;

	$login = stripslashes($login);
    $login = htmlspecialchars($login);
	$login = trim($login);

	$pass = stripslashes($pass);
    $pass = htmlspecialchars($pass);
    $pass = trim($pass);

	if ($login == 'logout') {
		echo 'login == logout';
		unset($_SESSION['login']);
		unset($_SESSION['id']);
		unset($_SESSION['role']);
		
		return;
	}

	if ($pass == '') return;
	
	$con = db_connect();
			
		$sql = mysql_query("SELECT * FROM users WHERE login = '$login'");
		$row = mysql_fetch_array($sql);
		
		if (!(empty($row['pass'])) && $row['confirmed']) {
			if (md5($pass) == $row['pass']) {
				$_SESSION['login'] = $row['login']; 
				$_SESSION['id']    = $row['idx'];
				$_SESSION['role']  = $row['role'];
			};
		};

	db_disconnect($con);	
}

//refactored
function get_date_newest($format, $echo = true) {
	$con = db_connect();
	
	$sql = mysql_query("SELECT time_added FROM indexvalue ORDER BY time_added DESC LIMIT 0, 1");
	$value = mysql_fetch_array($sql);	
	db_disconnect($con);
		
	echo date($format, strtotime($value['time_added']));

	error_log ("Exiting from DB_NEWESTDATE"); 	
}

//refactored
function db_insertindex($value) {
    if (!isset($value)) {
        error_log("Exiting from DB_INSERTINDEX: no value provided");
        return;
    }

	$con = db_connect();
	
	    $sql = mysql_query("INSERT INTO indexvalue (indexvalue) VALUES ('$value')");
	
	db_disconnect($con);

    error_log ("Exiting from DB_INSERTINDEX");
}


/* 
	ФУНКЦИИ РАБОТЫ С ДАТАМИ
*/


function do_date($format, $echo=true) {
	if ($echo) echo date($format); else return date($format);
}


//refactored
function convert_date_interval_to_string($time_interval = '6month' ) {
	switch ($time_interval) {
		case 'month': 	return 'месяц';		break;
		case '3months': return 'квартал';	break;
		case '6months':	return 'полгода';	break;
		case 'year': 	return 'год';		break;
		case '2years':	return 'два года';	break;							
	}

	return 'полгода';
}


/*
	ФУНКЦИИ РАБОТЫ С ГРАФИКАМИ
*/

//refactored
function graph_index_fill($time_interval) {
	$lastdays = date("c",strtotime("-24 weeks"));

	if ($time_interval == 'year') 		$lastdays = date("c", strtotime("-1 year"));
	if ($time_interval == '6months')	$lastdays = date("c", strtotime("-24 weeks"));
	if ($time_interval == 'month')		$lastdays = date("c", strtotime("-4 weeks"));
	if ($time_interval == '2years')		$lastdays = date("c", strtotime("-2 year"));
	if ($time_interval == '3months')	$lastdays = date("c", strtotime("-12 weeks"));

	$con = db_connect();
	
	$sql = mysql_query("SELECT time_added, indexvalue FROM indexvalue WHERE time_added > '$lastdays' ORDER BY time_added ASC");
	while($row = mysql_fetch_array($sql)) {
		$day = date("d.m.Y", strtotime($row['time_added']));
		$days[$day] = $row['indexvalue'];
	}

	db_disconnect($con);

	foreach($days as $day => $count) echo "['$day', $count ], ";
}

//created
function graph_value_fill($idx) {
    if (!isset($idx)) {
        error_log('Exiting from GRAPH_VALUE_FILL: No idx provided');
    }

    $iter_in = 0;
    $iter_out = 0;

    $days = array();

    $con = db_connect();

        $sql=mysql_query("SELECT date, cost FROM costs WHERE goods='$idx' ORDER BY date ASC");

        while($row=mysql_fetch_array($sql)) {
            $key = date("d.m.Y", strtotime($row['date']));
            $days[$key] = $row['cost'];
        }

    db_disconnect($con);

    foreach($days as $day => $value) echo "['$day', $value ], ";
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ИНДЕКСОМ ЦЕН
*/

//refactored
function get_index_newest() {
	$con = db_connect();
	
	$sql = mysql_query("SELECT indexvalue FROM indexvalue ORDER BY time_added DESC LIMIT 0,1");
	$value = mysql_fetch_array($sql);

	db_disconnect($con);

	echo $value['indexvalue'];
	error_log ("Exiting from DO_NEWESTVALUE"); 	
}

//refactored
function get_index_change() {
	$con = db_connect();
		
	$sql = mysql_query("SELECT indexvalue FROM indexvalue ORDER BY time_added DESC LIMIT 0,2");
	
	$result = mysql_fetch_array($sql);
	$newest_value = $result['indexvalue'];
	
	$result = mysql_fetch_array($sql);
	$prev_value = $result['indexvalue'];

	db_disconnect($con);

	$diff = round($newest_value - $prev_value, 4);
	if ($prev_value == 0) $prev_value = 1;

	$diff_percents = round(($newest_value - $prev_value)/$prev_value * 100, 2);
	
	if ($diff > 0) echo '<sup style="color: #f00" title="Последнее изменение">+' . $diff.' (+' . $diff_percents . '%)</sup>';
		else echo '<sup style="color: #0d0" title="Последнее изменение">' . $diff . ' (' . $diff_percents . '%)</sup>';	
}


//refactored

function runtime($time_start = 0, $is_get_runtime = false) {
    global $_runtime;
	
	$time_current = microtime(true);
    
    /* Надо вернуть разницу? */

	if ($is_get_runtime && isset($_runtime[$time_start])) {
		echo sprintf("%f", $time_current - $_runtime[$time_start]) . ' секунды';
		
		return;
	}

    /* Засекаем время */

	if (!is_array($_runtime)) {
		$_runtime = array();
	}

    if (!isset($_runtime[$time_start])) {
        $_runtime[$time_start] = $time_current;
    }
}

function do_graph() {
	$con=db_connect();
		$last30d=date("c",strtotime("-30 day"));
		$sql=mysql_query("SELECT time_added, indexvalue FROM indexvalue WHERE time_added>'$last30d' ORDER BY time_added DESC");
		while($row=mysql_fetch_array($sql)) {
			$key=date("Y-n-d",strtotime($row['time_added']));
			$days[$key]=$row['indexvalue'];
		}
				
	error_log ("Exiting from DO_GRAPH"); 
	db_disconnect($con);
	
	echo sizeof($days);
}

//refactored
function get_goods_num($group = '') {
	$con = db_connect();
	
	if (!empty($group)) 
		$sql = mysql_query("SELECT COUNT(*) FROM goods WHERE grp = '$group'");
	else
		$sql = mysql_query("SELECT COUNT(*) FROM goods");
	
	$row = mysql_fetch_array($sql);
	return $row['COUNT(*)'];
	
	db_disconnect($con);
}

function get_goods_table($group, $filter , $sortby, $sortdir) {

    if (!isset($sortby)) $sortby = 'name';
    if (!isset($sortdir)) $sortdir = 'ASC';

    if ($sortdir === 'ASC') $new_sortdir = 'DESC'; else $new_sortdir = 'ASC';

	if (($sortdir == 'ASC')  && ($sortby == 'name'))  $imgstr_name  = '<img src="addgraph/down_arrow.png" alt="">';
	if (($sortdir == 'DESC') && ($sortby == 'name'))  $imgstr_name  = '<img src="addgraph/up_arrow.png" alt="">';
	if (($sortdir == 'ASC')  && ($sortby == 'value')) $imgstr_value = '<img src="addgraph/up_arrow.png" alt="">';
	if (($sortdir == 'DESC') && ($sortby == 'value')) $imgstr_value = '<img src="addgraph/down_arrow.png" alt="">';
	
	$th_name_link  = 'goods.php?filter=' . $filter . '&group=' . $group . '&sortby=name&sortdir=' . $new_sortdir;
	$th_price_link = 'goods.php?filter=' . $filter . '&group=' . $group . '&sortby=value&sortdir=' . $new_sortdir;
	
	$con = db_connect();
		
		$i = 1;
		echo '<table class="goods">
				<tr style="background: #eee">
					<th width="50">п/п</th>
					<th width="510"><a href="' . $th_name_link . '" title="Сортировать по наименованию товара">Наименование товара' . $imgstr_name . '</a></th>
					<th width="80"><a href="' . $th_price_link . '" title="Cортировать по цене">Цена, р.' . $imgstr_value . '</a></th>
					<th width="80">Изменение</th>
					<th width="80">C даты</th>
				</tr>
			';

		if (!empty($group))
			$sql = mysql_query("SELECT idx, name, value, thumb, units FROM goods WHERE name LIKE '$filter%' AND grp = '$group' ORDER BY '$sortby' '$sortdir'");
		else
			$sql = mysql_query("SELECT idx, name, value, thumb, units FROM goods WHERE name LIKE '$filter%' ORDER BY '$sortby' '$sortdir'");
		

		$i = 1;

        while ($row = mysql_fetch_array($sql)) {
		
			$unit = $row['units'];
			$good = $row['idx'];
		
			$sql_unit = mysql_query("SELECT value FROM units WHERE idx = '$unit'");
			$row_unit = mysql_fetch_array($sql_unit);
			$unit_name = $row_unit['value'];
			
			$sql_cost = mysql_query("SELECT cost, date FROM costs WHERE goods='$good' ORDER BY date DESC");
			$row_cost = mysql_fetch_array($sql_cost);
			$now_cost = $row_cost['cost'];
			
			$row_cost = mysql_fetch_array($sql_cost);
			$prev_cost = $row_cost['cost'];
			$last_date = $row_cost['date'];
			
			if ($prev_cost == 0) {
				$prev_cost = 1;
			}

			$diffcost = round(($now_cost - $prev_cost)/$prev_cost * 100, 2);
			if (!($diffcost == 0)) $lastdatestr = date ('d.m.Y', strtotime($last_date)); else $lastdatestr = "";
			
			if ($diffcost > 0) 
				$diffstr = '<span style="color: #f00;  font-size:12px;" title="Последнее изменение">+' . $diffcost . '%</span>';
			else if ($diffcost < 0)
				$diffstr =  '<span style="color: #0d0;  font-size:12px;" title="Последнее изменение">' . $diffcost . '%</span>';
			else $diffstr = '';
				
			$style = ($i % 2 === 0) ? 'style="background: #eee;"' : 'style="background: #fff"';

            echo '<tr '. $style . '>' .
				   '<td align="center">' . $i . '</td>
					<td>
					 	<a href="good.php?idx=' . $row['idx'] . '">'. $row['name'] .', ' . $unit_name.'</a>
					</td>
					<td align="center">' . $row['value'] . '</td>
					<td align="center">' . $diffstr . '</tdn=>
					<td align="center">' . $lastdatestr . '</td>
				 </tr>';

            $i += 1;
		}

    echo '</table>';

    db_disconnect($con);

	error_log ("Exiting from GET_GOODS_TABLE");
}

function get_tablepricechange($idx) {
	$price = get_price($idx);
	$con=db_connect();
	
		$i = 1;
		echo '<table class="pricechange">';
		echo '<tr style="background: #eee">';
		echo '<th width="30">п/п</th>';
		echo '<th width="380">Дата</th>';
		echo '<th width="80">Цена, р.</th>';
		echo '<th width="80">Изменение</th>';
		echo '</tr>';
		
		$sql=mysql_query("SELECT date, cost FROM costs WHERE goods='$idx' ORDER BY date ASC");
		while($row=mysql_fetch_array($sql)) {
			if ($i==1) $prevvalue=$row['cost']; else {
				$diff=round((($row['cost']-$prevvalue)/$prevvalue*100),2);
				$prevvalue=$row['cost'];
			};
			if (($diff==0) & ($i<>1)) {if ($i==1) $i=$i+1; continue;};
			$tr_str='<tr';
			if ($i%2==0) $tr_str=$tr_str.' style="background: #eee"';
			$tr_str=$tr_str.'>';
			echo $tr_str;
			echo '<td align="center">'.$i.'</td>';
			echo '<td>'.date ('d.m.Y', strtotime($row['date'])).'</td>';
			echo '<td align="right">'.$row['cost'].'</td>';
			echo '<td align="center">';
			if ($diff>0) echo '<span style="color: #f00; font-size:12px;" title="Последнее изменение">+'.number_format($diff,2).'%</span>';
			if ($diff<0) echo '<span style="color: #0d0; font-size:12px;" title="Последнее изменение">'.number_format($diff,2).'%</span>';
			echo '</td>';
			
			echo '</tr>';
			$i=$i+1;
		}
		echo '</table>';

	error_log ("Exiting from GET_TABLEPRICECHANGE");
	db_disconnect($con);
}

function get_tablecosts($login) {
	$con=db_connect();
		$fromdate = date("Y-n-d");
		
		$i = 1;
		echo '<table class="goods">';
		echo '<tr style="background: #eee">';
		echo '<th width="30">п/п</th>';
		echo '<th width="540">Наименование товара</th>';
		echo '<th width="90">Цена, р.</th>';
		echo '<th width="60"></th>';
		echo '</tr>';
		
		
		$sql_goods=mysql_query("SELECT goods, cost FROM costs WHERE date>'$fromdate'");
		while($row_goods=mysql_fetch_array($sql_goods)) {
			$idx=$row_goods['goods'];
			$sql_name=mysql_query("SELECT name, thumb FROM goods WHERE idx='$idx'");
			$row_name=mysql_fetch_array($sql_name);	
			$tr_str='<tr';
			if ($i%2==0) $tr_str=$tr_str.' style="background: #eee"';
			$tr_str=$tr_str.'>';
			echo $tr_str;
			echo '<td>'.$i.'</td>';		
			echo '<td><a href="good.php?idx='.$idx.'">'.$row_name['name'].'</a></td>';
			echo '<td align="center">'.$row_goods['cost'].' р.</td>';
			if (!empty ($login)) echo '<td align="center"><a href="addvalue.php?delete='.$idx.'">Удалить</a></td>'; else echo '<td></td>';
			echo '</tr>';
			$i=$i+1;
		}

		echo '</table>';		
		
	error_log ("Exiting from GET_TABLECOSTS"); 		
	db_disconnect($con);
}

function get_units() {
	$con=db_connect();
	
		$sql=mysql_query("SELECT idx, value FROM units");
		while($row=mysql_fetch_array($sql)) {
		
			$key = $row['idx'];
			$units[$key] = $row['value'];
		}
		
	error_log ("Exiting from GET_UNITS"); 		
	db_disconnect($con);
	
	return $units;
}

function get_groups() {
	$con=db_connect();
	
		$sql=mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");
		while($row=mysql_fetch_array($sql)) {
		
			$key = $row['idx'];
			$groups[$key] = $row['name'];
		}
		
	error_log ("Exiting from GET_GROUPS"); 		
	db_disconnect($con);
	
	return $groups;
}

function get_goods() {
	$con=db_connect();
	
		$sql=mysql_query("SELECT idx, name, units FROM goods ORDER BY name ASC");
		while($row=mysql_fetch_array($sql)) {
			$unit = $row['units'];
			$sql_unit=mysql_query("SELECT value FROM units WHERE idx='$unit'");
			$row_unit=mysql_fetch_array($sql_unit);
			$unit_name=$row_unit['value'];
			
			$key = $row['idx'];
			$goods[$key] = $row['name'].', '.$unit_name;
		}
		
	error_log ("Exiting from GET_GOODS"); 		
	db_disconnect($con);
	
	return $goods;
}

//refactored
function get_good($idx, $show_units = true) {
	if (!isset($idx)) return;

    $con = db_connect();
	
		$sql = mysql_query("SELECT name, units FROM goods WHERE idx='$idx'");
		$row = mysql_fetch_array($sql);
		$name = $row['name'];
		$units = $row['units'];



        $unit_name = '';

		if ($show_units) {
			$sql_unit = mysql_query("SELECT value FROM units WHERE idx='$units'");
			$row_unit = mysql_fetch_array($sql_unit);
			$unit_name = ', ' . $row_unit['value'];
		}

        $result = $name . $unit_name;
	
	db_disconnect($con);
	
	error_log ("Exiting from GET_GOOD");
	return $result;
}

//refactored
function get_thumb($idx) {
    if (!isset($idx)) {
        error_log ("Exiting from GET_THUMB: no idx provided");

    }

	$con = db_connect();
	
		$sql = mysql_query("SELECT thumb FROM goods WHERE idx = '$idx'");
		$row = mysql_fetch_array($sql);

	db_disconnect($con);

    if (empty($row['thumb'])) $thumb = 'nophoto.jpg'; else $thumb = $row['thumb'];

    error_log ("Exiting from GET_THUMB");
	return $thumb;
}

//refactored
function get_price($idx) {
    if (!isset($idx)) {
        error_log('Exiting from GET_PRICE: no idx provided');
        return 'Цена не определена';
    }

	$con = db_connect();
	
		$sql = mysql_query("SELECT value FROM goods WHERE idx = '$idx'");
		$row = mysql_fetch_array($sql);
		if (empty($row['value'])) $price = 'Цена не определена'; else $price = $row['value'];
				
	db_disconnect($con);

    error_log ("Exiting from GET_PRICE");
	return $price;
}

function get_params($idx) {
	$con=db_connect();
	
		$sql=mysql_query("SELECT producer, importer, ingredients, proteins, fats, carbohydrates, energy, expdate FROM goods WHERE idx='$idx'");
		$row=mysql_fetch_array($sql);
		if (!empty($row['producer'])) {
			echo '<h3>Производитель</h3>';
			echo '<p>'.$row['producer'].'</p>';
		};
		if (!empty($row['importer'])) {
			echo '<h3>Импортер</h3>';
			echo '<p>'.$row['importer'].'</p>';
		};
		if (!empty($row['ingredients'])) {
			echo '<h3>Состав</h3>';
			echo '<p>'.$row['ingredients'].'</p>';
		};
		
		if ((!empty($row['proteins'])) or (!empty($row['fats'])) or (!empty($row['carbohydrates']))) {
		
			echo '<h3>Пищевая ценность на 100 г</h3>';
			echo '<table class="pricechange" style="text-align: center;">';
			echo '<tr style="background: #eee"">';
			if (!empty($row['proteins'])) {
				echo '<th width="33%">Белки</th>';
			};
			if (!empty($row['fats'])) {
				echo '<th width="33%">Жиры</th>';
			};
			if (!empty($row['carbohydrates'])) {
				echo '<th width="33%">Углеводы</th>';
			};
			echo '</tr>';
			echo '<tr>';
			if (!empty($row['proteins'])) {
				echo '<td width="33%">'.$row['proteins'].'</td>';
			};
			if (!empty($row['fats'])) {
				echo '<td width="33%">'.$row['fats'].'</td>';
			};
			if (!empty($row['carbohydrates'])) {
				echo '<td width="33%">'.$row['carbohydrates'].'</td>';
			};
			echo '</tr>';
			echo '</table>';			
		};
		
		if (!empty($row['energy'])) {
			echo '<table class="pricechange" style="text-align: center;">';
			echo '<tr style="background: #eee""><th colspan="2">Энергетическая ценность</th></tr>';
			echo '<tr width="50%"><td>';
			$kj=conv_kcal_to_kj($row['energy']);
			echo $row['energy'].' ккал</td>';
			echo '<td>';
			echo $kj.' кДж';
			echo '</td></tr></table>';
		};
		
		if (!empty($row['expdate'])) {
			echo '<h3>Срок хранения</h3>';
			echo '<p>'.$row['expdate'].'</p>';
		};
			
		
	
	error_log ("Exiting from GET_PARAMS"); 		
	db_disconnect($con);
}


//refactored
function get_group_name($idx) {
	if (!isset($idx)) {
		error_log ("Exiting from GET_GROUP_NAME: no group idx specified");
		return 'Все товары';
	}

	$con = db_connect();

		$sql = mysql_query("SELECT name FROM groups WHERE idx = '$idx'");
		
		while ($row = mysql_fetch_array($sql)) {
			$name = $row['name'];
		}

	db_disconnect($con);
	
	if (!empty($name)) {
		error_log("Exiting from GET_GROUP_NAME success"); 		
		return $name;	
	} else {
		error_log("Exiting from GET_GROUP_NAME no group name found"); 		
		return 'Все товары';
	}	
}

//refactored
function get_group($idx) {
    if (!isset($idx)) {
        error_log ("Exiting from GET_GROUP: no idx provided");
        return 0;
    }

    $con = db_connect();

        $sql = mysql_query("SELECT grp FROM goods WHERE idx='$idx'");

        while($row = mysql_fetch_array($sql)) {
            $group = $row['grp'];
        }

    db_disconnect($con);
		
    if (!empty($group)) {
        error_log ("Exiting from GET_GROUP success");
        return $group;
    } else {
        error_log ("Exiting from GET_GROUP no group found");
        return 0;
    }
}

//refactored
function get_group_contents() {
	$con = db_connect();

		echo '<div class="group_index">
					<ul>
			 ';

		$sql = mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");
			while ($row = mysql_fetch_array($sql)) {
				echo '
						<li>
							<a href="goods.php?group='. $row['idx'] .'">'. $row['name'] .' ('.get_goods_num($row['idx']).')</a>
						</li>';
			}

		echo '		</ul>
				</div>
			 ';

	db_disconnect($con);

	error_log ("Exiting from GET_GROUP_INDEX no required param"); 
}

function get_group_index_img() {
	$con=db_connect();
	
		echo '<div>';
		
		$sql=mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");
			$i=0;
			while($row=mysql_fetch_array($sql)) {
				$idx = $row['idx'];
				$name = $row['name'];
					
				$sql_img=mysql_query("SELECT name, thumb FROM goods WHERE grp='$idx' AND thumb<>''");
				$row_img=mysql_fetch_array($sql_img);
				$img = $row_img['thumb'];
				$good = $row_img['name'];
				
				echo '<div class="related_item">';
				echo '<a href="goods.php?group='.$idx.'">';
				echo '<img src="thumbs/'.$img.'" alt="'.$name.'" title="'.$name.'" width="165", height="165">';
				echo '<h3>'.$name.' ('.get_goods_num($idx).')</h3>';
				echo '</a>';
				echo '</div>';
				$i=$i+1;
				if ($i>=4) {
					$i=0;
					echo '<div class="clear"></div>';
				}
			}
		echo '</div>';
		echo '<div class="clear"></div>';
	
	db_disconnect($con);
	error_log ("Exiting from GET_GROUP_INDEX no required param"); 
	
}

function del_costs($goods) {
	$fromdate = date("Y-n-d");
	
	if (isset ($goods)) {
		$con=db_connect();
			$sql=mysql_query("DELETE FROM costs WHERE goods='$goods' AND date>'$fromdate'");
		error_log ("Exiting from DEL_COSTS"); 		
		db_disconnect($con);
	}
}

function add_costs($goods, $cost) {
  if (isset($goods) and isset($cost)) {
	$cost=str_replace(",",".",$cost);
	$con=db_connect();
		$sql=mysql_query("INSERT INTO costs (goods, cost) VALUES ('$goods', '$cost')");	
		
	error_log ("Exiting from ADD_COSTS"); 		
	db_disconnect($con);  
  }
}

function update_costs() {
	$con=db_connect();
		$fromdate = date("Y-n-d");
		$sql_costs=mysql_query("SELECT goods, cost FROM costs WHERE date>'$fromdate'");
		while($row_costs=mysql_fetch_array($sql_costs)) {
			$idx=$row_costs['goods'];
			$value=$row_costs['cost'];
			$sql_goods=mysql_query("UPDATE goods SET value='$value' WHERE idx='$idx'");
		}
		
	error_log ("Exiting from UPDATE_COSTS"); 		
	db_disconnect($con);
}

function add_indexvalue() {
	$con=db_connect();
		$sum = 0;
	
		$sql=mysql_query("SELECT COUNT(*), value FROM goods");
		while($row=mysql_fetch_array($sql)) {
			$sum = $sum+$row['value'];
			$count = $row['COUNT(*)'];
		}
		if ($count=0) $count=1;
		
		$indexvalue = $sum/$count;
	
	error_log ("Exiting from ADD_INDEXVALUE"); 		
	db_disconnect($con);
}

//refactored
function add_goods($groups, $goods, $cost, $units) {
  if (!isset($groups) || !isset($goods) || !isset($cost) || !isset($units))  {
      error_log ("Exiting from ADD_GOODS: some params missed");
      return;
  }

  $user_login = $_SESSION['login'];
  $cost=str_replace(",",".", $cost);

  $con = db_connect();

        $sql=mysql_query("INSERT INTO goods (name, grp, value, units, lastedit) VALUES ('$goods', '$groups', '$cost', '$units', '$user_login')");

  db_disconnect($con);

  error_log ("Exiting from ADD_GOODS");
}

function get_related($idx) {
  if (isset($idx)) {
	$name=get_good($idx, false);
	
	if (!empty($name)) {
		$string = explode(" ",$name);
		if ($string) {
			$con=db_connect();
			$sql=mysql_query("SELECT idx FROM goods WHERE name LIKE '$string[0]%' ORDER BY RAND()");
				$i=0;
				while($row=mysql_fetch_array($sql)) {
					if (!($idx==$row['idx'])){
						if ($i==0) echo '<h3>Похожие товары</h3>';
						$i=$i+1;
						$related_name=get_good($row['idx'], false);
						echo '<div class="related_item">';
						echo '<a href="good.php?idx='.$row['idx'].'">';
						echo '<img src="/thumbs/'.get_thumb($row['idx']).'" alt="'.$related_name.'" title="'.$related_name.'" width="165", height="165">';
						echo $related_name;
						echo '<div class="related_price">'.get_price($row['idx']).' р.</div>';
						echo '</a>';
						echo '</div>';
						if ($i>=4) break;
					}
				}				
			echo '<div class="clear"></div>';
		}
	}
		
	error_log ("Exiting from GET_RELATED"); 		
	db_disconnect($con);  
  }
}

//refactored
function get_popular($group) {
	$goods_freqs = array();
	
	$con = db_connect();
		
		if (isset($group)) 
			$sql = mysql_query("SELECT idx FROM goods WHERE grp='$group'");
		else
			$sql = mysql_query("SELECT idx FROM goods");
			
		while ($row = mysql_fetch_array($sql)) {
			$idx = $row['idx'];
			$sql2 = mysql_query("SELECT COUNT(goods) FROM costs WHERE goods='$idx'");
			$row2 = mysql_fetch_array($sql2);
			$goods_freqs[$idx] = $row2['COUNT(goods)'];
		}

	db_disconnect($con);
	
	arsort($goods_freqs);
	
	echo '
		<div class="popular">
			<h3>Популярные товары</h3>
	';

	foreach (array_slice($goods_freqs, 0, 4, true) as $idx => $count) {
		$related_name = get_good($idx, false);
		echo '
			<div class="related_item">
				<a href="good.php?idx='.$idx.'">
					<img src="thumbs/'.get_thumb($idx).'" alt="'.$related_name.'" title="'.$related_name.'" width="165" height="165">
					<div class="related_name">'.$related_name.'</div>
					<div class="related_price">'.get_price($idx).' р.</div>
				</a>
			</div>
		';
	}

	echo '
			</div>
			<div class="clear">
		</div>
	';

	error_log ("Exiting from GET_POPULAR");
}

function conv_kcal_to_kj ($kcal) {
  if (is_numeric($kcal)) return round($kcal*4.1868,0); else return false;
}

//refactored
function get_price_max($idx) {
	if (!isset($idx)) {
        error_log("Exiting from GET_PRICE_MAX: no idx provided");
        return 'Не найдёна';
    }

    $maximum = 0;

    $con = db_connect();
        $sql = mysql_query("SELECT cost FROM costs WHERE goods='$idx'");

        while($row = mysql_fetch_array($sql)) {
            if ($row['cost'] > $maximum) $maximum = $row['cost'];
        }

    db_disconnect($con);

    error_log("Exiting from GET_PRICE_MAX");
    return $maximum;
}

//refactored
function get_price_min($idx) {
	if (!isset($idx)) {
        error_log("Exiting from GET_PRICE_MIN: no idx provided");
        return 'Не найдёна';
    }

    $first = true;
    $minimum = 0;

    $con = db_connect();
        $sql = mysql_query("SELECT cost FROM costs WHERE goods = '$idx'");

        while($row = mysql_fetch_array($sql)) {
            if ($first) {
                $first = false;
                $minimum = $row['cost'];
            } else {
                if ($row['cost'] < $minimum) $minimum = $row['cost'];
            }

        }
    db_disconnect($con);

    error_log("Exiting from GET_PRICE_MIN");
    return $minimum;

}

function is_logged ($session) {
//05.08.2015 Не работает, но и не используется

  if (!empty($session['login']) and !empty($session['id'])) return true; else return false;
  error_log ("Exiting from IS_LOGGED");
}

function register_user($name, $nick, $reg_email, $password1, $password2) {
	$name = stripslashes($name);
	$name = htmlspecialchars($name);
	$name = trim($name);
	
	$nick = stripslashes($nick);
	$nick = htmlspecialchars($nick);
	$nick = trim($nick);
	
	$reg_email = stripslashes($reg_email);
	$reg_email = htmlspecialchars($reg_email);
	$reg_email = trim($reg_email);	
	
	$password1 = stripslashes($password1);
	$password1 = htmlspecialchars($password1);
	$password1 = trim($password1);	
		
	$password2 = stripslashes($password2);
	$password2 = htmlspecialchars($password2);
	$password2 = trim($password2);	
	
	if (empty($name) and empty($nick) and empty($reg_email) and empty($password1) and empty($password2)) return "";
	
	if (empty($name)) return "<font color='#FF0000'>Необходимо указать имя</font>";
	if (empty($nick)) return "<font color='#FF0000'>Необходимо указать логин</font>";
	if (empty($reg_email)) return "<font color='#FF0000'>Необходимо указать e-mail</font>";
	if (empty($password1)) return "<font color='#FF0000'>Необходимо указать пароль</font>";
	if (empty($password2)) return "<font color='#FF0000'>Необходимо указать пароль ещё раз</font>";
	if (!($password1==$password2)) return "<font color='#FF0000'>Пароли не совпадают</font>";
	
	$con=db_connect();
			
		$sql = mysql_query("SELECT login FROM users WHERE login='$nick'");
		$row = mysql_fetch_array($sql);
		
		$password=md5($password1);
		
		if (empty($row['login'])) {
			
			$sql=mysql_query("INSERT INTO users (login, pass, name, email, confirmed, role) VALUES ('$nick', '$password', '$name', '$reg_email', false, 'supplier')");	
			
		} else return "Пользователь с таким логином уже существует";

	db_disconnect($con);
	
	$mail_password=md5(md5($nick).md5($password1));
	
	$headers  = "Content-type: text/html; charset=utf8 \r\n"; 
	$headers .= "From: costindex.ru <register@costindex.ru>\r\n"; 
	
	$message = 'Вы оставили заявку на регистрацию на сайте COSTINDEX.RU<br><br>';
	$message .= 'Для завершения регистрации нажмите на ссылку <a href="http://costindex.ru/register_complete.php?login='.$nick.'&number='.$mail_password.'">http://costindex.ru/register_complete.php?number='.$mail_password.'</a><br><br>Если ссылка неактивна скопируйте ссылку http://costindex.ru/register_complete.php?login='.$nick.'&number='.$mail_password.' в строку адреса браузера и нажмите Enter.<br><br>';
	$message .= 'Для входа на сайт используйте следующие реквизиты:<br><br>';
	$message .= 'Логин: '.$nick.'<br>';
	$message .= 'Пароль: '.$password1.'<br><br>';
	$message .= 'Это письмо было создано автоматически. Пожалуйста, не отвечайте на него.<br><br>';
	
	mail($reg_email, "Регистрация на сайте COSTINDEX.RU", $message, $headers);
	
	return "Сообщение отправлено";
	
	error_log ("Exiting from REGISTER_USER"); 
}

function confirm_user($login, $number) {
	$login = stripslashes($login);
	$login = htmlspecialchars($login);
	$login = trim($login);
	
	$number = stripslashes($number);
	$number = htmlspecialchars($number);
	$number = trim($number);
	$result = 'unconfirmed';
	
	$con=db_connect();
			
		$sql = mysql_query("SELECT pass FROM users WHERE login='$login'");
		$row = mysql_fetch_array($sql);
		
		if (!(empty($row['pass']))) {
				
			$calc_number = md5(md5($login).$row['pass']);
			
			if ($number==$calc_number){
			
				$sql=mysql_query("UPDATE users SET confirmed=true WHERE login='$login'");			
				$result = 'confirmed';
			};
		};

	db_disconnect($con);
	
	return $result;
	
	error_log ("Exiting from CONFIRM_USER"); 
}

//refactored
function get_edit_link ($session) {
    if (!isset($session)) {
        error_log('Exiting from GET_EDIT_LINK: no session provided');
    }

	if (!empty($session['login']) && !empty($session['id']) && (($session['role']=='editor') || ($session['role']=='admin')))
		echo '
            <span class="edit_link">
                <a href="#" title="Редактировать сведения о товаре">
                    Редактировать
                </a>
            </span>
        ';
}

function rus_to_lat ($rus_str) {
$translit = array(
   "Є"=>"EH","І"=>"I","і"=>"i","№"=>"#","є"=>"eh",
   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
   "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
   "З"=>"Z","И"=>"I","Й"=>"I","К"=>"K","Л"=>"L",
   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H",
   "Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SH","Ъ"=>"",
   "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"yo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
   "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sh","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","«"=>"","»"=>"","—"=>"-"
  );
	return strtr($rus_str, $translit);
}