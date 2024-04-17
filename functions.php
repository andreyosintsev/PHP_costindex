<?php
function do_date($format, $echo=true) {
	if ($echo) echo date($format); else return date($format);
}

function do_newestvalue() {
	$con=db_connect();
	
	$result='Не сработало';
	
	$sql=mysql_query("SELECT indexvalue FROM indexvalue ORDER BY time_added DESC LIMIT 0,1");
	$result=mysql_fetch_array($sql);
	echo $result['indexvalue'];
	
	error_log ("Exiting from DO_NEWESTVALUE"); 
	db_disconnect($con);
}

function do_changevalue() {
	$con=db_connect();
	
	$result='Не сработало';
	
	$sql=mysql_query("SELECT indexvalue FROM indexvalue ORDER BY time_added DESC LIMIT 0,2");
	$result=mysql_fetch_array($sql);
	$newestvalue=$result['indexvalue'];
	$result=mysql_fetch_array($sql);
	$prevvalue=$result['indexvalue'];

	$diff=round($newestvalue-$prevvalue,4);
	$diffproc=round(($newestvalue-$prevvalue)/$prevvalue*100,2);
	
	if ($diff>0) echo '<sup style="color: #f00" title="Последнее изменение">+'.$diff.' (+'.$diffproc.'%)</sup>';
		else echo '<sup style="color: #0d0" title="Последнее изменение">'.$diff.' ('.$diffproc.'%)</sup>';
	

	db_disconnect($con);
}

function db_newestdate($format, $echo=true) {
	$con=db_connect();
	
	$result='Не сработало';
	
	$sql=mysql_query("SELECT time_added FROM indexvalue ORDER BY time_added DESC LIMIT 0,1");
	$result=mysql_fetch_array($sql);	
		
	$formatted_time = date ($format, strtotime($result['time_added']));
		
	echo $formatted_time;
	
	error_log ("Exiting from DB_NEWESTDATE"); 
	db_disconnect($con);
}

function db_insertindex($value) {
	$con=db_connect();
	
	$result='Не сработало';
	
	$sql=mysql_query("INSERT INTO indexvalue (indexvalue) VALUES ('".$value."')");
	
	error_log ("Exiting from DB_INSERTINDEX"); 
	db_disconnect($con);
}

function db_connect() {
	$host="localhost";
	$user="pumpmeu7_costidx";
	$password="cstid123";
	$db="pumpmeu7_costindex";
	
	$connected = mysql_connect($host, $user, $password);
	if (!$connected) {
		error_log("MySQL сервер недоступен! ".mysql_error());
		die();
	};
	
	mysql_select_db($db) or die("Нет соединения с БД".mysql_error());
	mysql_query("SET NAMES 'utf8'"); 
	mysql_query("SET CHARACTER SET 'utf8'");
	mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
	
	return $connected;
}

function db_disconnect($con) {
	mysql_close($con);	
}

function runtime($type='0',$mark=NULL)
{
    global $_runtime_microsec;
    
    /* Надо вернуть разницу? */
    if( $mark!==NULL ) if( isset($_runtime_microsec[$type]) && isset($_runtime_microsec[$mark]) ) return sprintf("%f", $_runtime_microsec[$mark]-$_runtime_microsec[$type]);

    if( PHP_VERSION >= '5.0.0' )
    {
        $mtime = microtime(true);
    }
    else
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
    }

    /* Засекаем время */
    if( !is_array($_runtime_microsec) ) $_runtime_microsec = array();
    if( !isset($_runtime_microsec[$type]) )
    {
        $_runtime_microsec[$type] = $mtime;
    }

    /* Вычисляем время */
    $mtime -= $_runtime_microsec[$type];

    /* Форматируем вывод */
    $mtime = sprintf("%f", $mtime);
    return $mtime;
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

function get_numgoods($group) {
	$con=db_connect();
	
	if (!empty($group)) 
		$sql=mysql_query("SELECT COUNT(*) FROM goods WHERE grp='$group'");
	else
		$sql=mysql_query("SELECT COUNT(*) FROM goods");
	
	$row=mysql_fetch_array($sql);
	$count = $row['COUNT(*)'];
	
	return $count;
	
	db_disconnect($con);
}

function get_tablegoods($filter='', $sortby='name', $sortdir='ASC', $group) {
		
	if (empty($sortby)) $sortby='name';
	if (empty($sortdir)) $sortdir='ASC';
	
	if ($sortdir=='ASC') $new_sortdir='DESC'; else $new_sortdir='ASC';
	
	if (($sortdir=='ASC') and ($sortby=='name')) $imgstr_name='<img style="margin-left:2px" src="http://costindex.ru/addgraph/down_arrow.png">';
	if (($sortdir=='DESC') and ($sortby=='name')) $imgstr_name='<img style="margin-left:2px" src="http://costindex.ru/addgraph/up_arrow.png">';
	if (($sortdir=='ASC') and ($sortby=='value')) $imgstr_value='<img style="margin-left:2px" src="http://costindex.ru/addgraph/up_arrow.png">';
	if (($sortdir=='DESC') and ($sortby=='value')) $imgstr_value='<img style="margin-left:2px" src="http://costindex.ru/addgraph/down_arrow.png">';
	
	$th_name_link = 'goods.php?filter='.$filter.'&group='.$group.'&sortby=name'.'&sortdir='.$new_sortdir;
	$th_price_link = 'goods.php?filter='.$filter.'&group='.$group.'&sortby=value'.'&sortdir='.$new_sortdir;
	
	$con=db_connect();
		
		$i = 1;
		echo '<table class="goods">';
		echo '<tr style="background: #eee">';
		echo '<th width="50">п/п</th>';
		echo '<th width="510"><a href="'.$th_name_link.'" title="Сортировать по наименованию товара">Наименование товара'.$imgstr_name.'</a></th>';
		echo '<th width="80"><a href="'.$th_price_link.'" title="Cортировать по цене">Цена, р.'.$imgstr_value.'</a></th>';
		echo '<th width="80">Изменение</th>';
		echo '<th width="80">C даты</th>';
		echo '</tr>';
		
		if (!empty($group)) $mysql_query_str="SELECT idx, name, value, thumb, units FROM goods WHERE name LIKE '$filter%' AND grp='$group' ORDER BY ".$sortby." ".$sortdir; else
		$mysql_query_str="SELECT idx, name, value, thumb, units FROM goods WHERE name LIKE '$filter%' ORDER BY ".$sortby." ".$sortdir;
		
		$sql=mysql_query($mysql_query_str);
		while($row=mysql_fetch_array($sql)) {
		
			$unit = $row['units'];
			$good = $row['idx'];
		
			$sql_unit=mysql_query("SELECT value FROM units WHERE idx='$unit'");
			$row_unit=mysql_fetch_array($sql_unit);
			$unit_name=$row_unit['value'];
			
			$sql_cost = mysql_query("SELECT cost, date FROM costs WHERE goods='$good' ORDER BY date DESC");
			$row_cost = mysql_fetch_array($sql_cost);
			$now_cost = $row_cost['cost'];
			$row_cost=mysql_fetch_array($sql_cost);
			$prev_cost = $row_cost['cost'];
			$last_date = $row_cost['date'];
			
			$diffcost = round(($now_cost-$prev_cost)/$prev_cost*100,2);
			if (!($diffcost==0)) $lastdatestr=date ('d.m.Y', strtotime($last_date)); else $lastdatestr="";
			
			if ($diffcost>0) $diffstr = '<span style="color: #f00;  font-size:12px;" title="Последнее изменение">+'.$diffcost.'%</span>';
				else if ($diffcost<0) $diffstr =  '<span style="color: #0d0;  font-size:12px;" title="Последнее изменение">'.$diffcost.'%</span>';
				else $diffstr = '';
				
			
			$tr_str='<tr';
			if ($i%2==0) $tr_str=$tr_str.' style="background: #eee"';
			$tr_str=$tr_str.'>';
			echo $tr_str;
			echo '<td align="center">'.$i.'</td>';
			echo '<td><a href="http://costindex.ru/good.php?idx='.$row['idx'].'">'.$row['name'].', '.$unit_name.'</a></td>';
			echo '<td align="right">'.$row['value'].'</td>';
			echo '<td align="center">'.$diffstr.'</td>';
			echo '<td align="center">'.$lastdatestr.'</td>';
			echo '</tr>';
			$i=$i+1;
		}

		echo '</table>';		
	error_log ("Exiting from GET_TABLEGOODS"); 
	db_disconnect($con);
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

function get_good($idx, $units=true) {
	$con=db_connect();
	
		$sql=mysql_query("SELECT name, units FROM goods WHERE idx='$idx'");
		$row=mysql_fetch_array($sql);
		$name = $row['name'];
		$unit = $row['units'];
	
		if ($units) {
			$sql_unit=mysql_query("SELECT value FROM units WHERE idx='$unit'");
			$row_unit=mysql_fetch_array($sql_unit);
			$unit_name=', '.$row_unit['value'];
		} else $unit_name='';
				
	error_log ("Exiting from GET_GOOD"); 		
	db_disconnect($con);
	
	$result = $name.$unit_name;
	
	return $result;
}

function get_thumb($idx) {
	$con=db_connect();
	
		$sql=mysql_query("SELECT thumb FROM goods WHERE idx='$idx'");
		$row=mysql_fetch_array($sql);
		if (empty($row['thumb'])) return 'nophoto.jpg'; else $thumb = $row['thumb'];
				
	error_log ("Exiting from GET_THUMB"); 		
	db_disconnect($con);
	
	return $thumb;
}

function get_price($idx) {
	$con=db_connect();
	
		$sql=mysql_query("SELECT value FROM goods WHERE idx='$idx'");
		$row=mysql_fetch_array($sql);
		if (empty($row['value'])) $price='Цена не определена'; else $price = $row['value'];
				
	error_log ("Exiting from GET_PRICE"); 		
	db_disconnect($con);
	
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

function get_group_name($idx) {
	if (isset ($idx)) {
		$con=db_connect();
	
			$sql=mysql_query("SELECT name FROM groups WHERE idx='$idx'");
			while($row=mysql_fetch_array($sql)) {
				$name = $row['name'];				
			}
		db_disconnect($con);
		
		if (!empty($name)) {
			error_log ("Exiting from GET_GROUP_NAME success"); 		
			return $name;	
		} else {
			error_log ("Exiting from GET_GROUP_NAME no group name found"); 		
			return 'Все товары';
		}
	}
	error_log ("Exiting from GET_GROUP_NAME failed");
	return 'Все товары';
}

function get_group($idx) {
	if (isset ($idx)) {
		$con=db_connect();
			$sql=mysql_query("SELECT grp FROM goods WHERE idx='$idx'");
			while($row=mysql_fetch_array($sql)) {
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
	error_log ("Exiting from GET_GROUP no required param"); 		
	return 0;	
}

function get_group_index() {
	$con=db_connect();
		echo '<div class="group_index"><ul>';
		$sql=mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");
			while($row=mysql_fetch_array($sql)) {
				$idx = $row['idx'];
				$name = $row['name'];
				echo '<li><a href="http://costindex.ru/goods.php?group='.$idx.'">'.$name.' ('.get_numgoods($idx).')</a></li>';
			}
		echo '</ul></div>';
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
				echo '<a href="http://costindex.ru/goods.php?group='.$idx.'">';
				echo '<img src="/thumbs/'.$img.'" alt="'.$name.'" title="'.$name.'" width="165", height="165">';
				echo '<h3>'.$name.' ('.get_numgoods($idx).')</h3>';
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

function add_goods($groups, $goods, $cost, $units) {
  if (isset($groups) and isset($goods) and isset($cost) and isset($units)) {
	$user_login=$_SESSION['login'];
	$con=db_connect();
		$cost=str_replace(",",".",$cost);
		$sql=mysql_query("INSERT INTO goods (name, grp, value, units, lastedit) VALUES ('$goods', '$groups', '$cost', '$units', '$user_login')");	
		
	error_log ("Exiting from ADD_GOODS"); 		
	db_disconnect($con); 
  }
}

function login($login, $pass) {
	$login = stripslashes($login);
    $login = htmlspecialchars($login);
	$login = trim($login);
	$pass = stripslashes($pass);
    $pass = htmlspecialchars($pass);
    $pass = trim($pass);
	
	if ($login=='logout') {
		unset ($_SESSION['login']);
		unset ($_SESSION['id']);
		unset ($_SESSION['role']);
		
		return 0;
	}
	
	$con=db_connect();
			
		$sql = mysql_query("SELECT * FROM users WHERE login='$login'");
		$row = mysql_fetch_array($sql);
		
		if (!(empty($row['pass'])) and $row['confirmed']) {
			if ($row['pass']==md5($pass)) {
				$_SESSION['login']=$row['login']; 
				$_SESSION['id']=$row['idx'];
				$_SESSION['role']=$row['role'];
			};
		};

	error_log ("Exiting from LOGIN");
	db_disconnect($con);	
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
						echo '<a href="http://costindex.ru/good.php?idx='.$row['idx'].'">';
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
function get_popular($group) {
	$goods_freqs = array();
	
		$con=db_connect();
			if (!(empty($group))) {
				$sql=mysql_query("SELECT idx FROM goods WHERE grp='$group'");
			} else	$sql=mysql_query("SELECT idx FROM goods");
			while($row=mysql_fetch_array($sql)) {
				$idx=$row['idx'];
				$sql2=mysql_query("SELECT COUNT(goods) FROM costs WHERE goods='$idx'");
				$row2=mysql_fetch_array($sql2);
				$goods_freqs[$idx]=$row2['COUNT(goods)'];
			}				
		db_disconnect($con);  
		
		arsort($goods_freqs);
		
		$i=0;
		foreach ($goods_freqs as $idx => $count) {
			if ($i==0) echo '<div class="popular"><h3>Популярные товары</h3>';
			$i=$i+1;
			$related_name=get_good($idx, false);
			echo '<div class="related_item">';
			echo '<a href="http://costindex.ru/good.php?idx='.$idx.'">';
			echo '<img src="/thumbs/'.get_thumb($idx).'" alt="'.$related_name.'" title="'.$related_name.'" width="165", height="165">';
			echo '<div class="related_name">'.$related_name.'</div>';
			echo '<div class="related_price">'.get_price($idx).' р.</div>';
			echo '</a>';
			echo '</div>';
			if ($i>=4) break;
		}

		echo '</div><div class="clear"></div>';

	error_log ("Exiting from GET_POPULAR");
}

function conv_kcal_to_kj ($kcal) {
  if (is_numeric($kcal)) return round($kcal*4.1868,0); else return false;
}

function get_price_max($idx) {
	if (isset($idx)) {
		$maximum = 0;
		$con=db_connect();
			$sql=mysql_query("SELECT cost FROM costs WHERE goods='$idx'");
			while($row=mysql_fetch_array($sql)) {
				if ($row['cost']>$maximum) $maximum = $row['cost'];
			}	
		db_disconnect($con);
		return $maximum;
	};
	return 0;
}

function get_price_min($idx) {
	if (isset($idx)) {
		$first=true;
		$con=db_connect();
			$sql=mysql_query("SELECT cost FROM costs WHERE goods='$idx'");
			while($row=mysql_fetch_array($sql)) {
				if ($first) {$first=false; $minimum = $row['cost'];}
				if ($row['cost']<$minimum) $minimum = $row['cost'];
			}			
		db_disconnect($con);
		return $minimum;
	};
	return 0;
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

function get_edit_link () {
	if (!empty($_SESSION['login']) and !empty($_SESSION['id']) and (($_SESSION['role']=='editor') or ($_SESSION['role']=='admin')))
		echo '<span class="edit_link"><a href="#" titile="Редактировать сведения о товаре">Редактировать</a></span>';
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
?>