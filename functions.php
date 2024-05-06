<?php

/*
	Перечень всех функций:

	ФУНКЦИИ РАБОТЫ С БАЗОЙ ДАННЫХ

	db_connect()
	db_disconnect($con)


	ФУНКЦИИ РАБОТЫ С ПОЛЬЗОВАТЕЛЯМИ

	user_login($login = '', $pass = '')
	user_logout($login = '')
    user_register($name, $login, $email, $password1, $password2)
    user_confirm($login, $number)


	ФУНКЦИИ ДЛЯ РАБОТЫ С ДАТАМИ

	date_get_newest($format)
	date_convert_interval_to_string($time_interval = '6month' )


	ФУНКЦИИ ДЛЯ РАБОТЫ С ГРАФИКАМИ

	graph_index_fill($time_interval)
    graph_value_fill($idx)


    ФУНКЦИИ ДЛЯ РАБОТЫ С ИНДЕКСОМ ЦЕН

	index_add($value)
	index_get_newest()
	index_get_change()


	ФУНКЦИИ ДЛЯ РАБОТЫ С ГРУППАМИ ТОВАРОВ

	groups_get()
	groups_get_contents()
	group_get_name($idx)


	ФУНКЦИИ ДЛЯ РАБОТЫ С ТОВАРАМИ

	goods_update()
	goods_get()
	goods_get_num($group = '')
	goods_get_table($group, $filter='', $sortby = 'name', $sortdir='ASC')
	goods_get_popular($group)
	good_add($group, $good, $cost, $units)
	good_get_name($idx, $units = true)
	good_get_thumb($idx)
	good_get_value($idx)
	good_get_value_max($idx)
	good_get_value_min($idx)
	good_get_params($idx)
	good_get_group_index($good_idx)
	good_get_related($idx)
	good_get_edit_link($session)


	ФУНКЦИИ ДЛЯ РАБОТЫ С ЦЕНАМИ

	cost_add($good, $cost)
	cost_del($good)
	costs_get_table_change($idx)
 	costs_get_table($login)


 	ФУНКЦИИ ДЛЯ РАБОТЫ С ЕДИНИЦАМИ ИЗМЕРЕНИЯ

 	units_get()


	- register_user($login, $number)
	- confirm_user($login, $number)


	ФУНКЦИИ-УТИЛИТЫ

	rus_to_lat($rus_str)
	_error_log($message)
	runtime($time_start = 0, $is_get_runtime = false)
	kcal_to_kj ($kcal)

*/

require 'config.php';

/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С БАЗОЙ ДАННЫХ
*/

//refactored
function db_connect() {
	$connected = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$connected) {
        _error_log("Exiting from DB_CONNECT: ERROR MySQL not available");

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
	if (!isset($con)) {
        _error_log("Exiting from DB_DISCONNECT: ERROR no con supplied");

        return;
    }

	mysql_close($con) || die("Ошибка: не удалось разорвать соединение с БД. MYSQL_ERROR: ".mysql_error());
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ПОЛЬЗОВАТЕЛЯМИ
*/

//refactored
function user_login($login = '', $pass = '') {
	if ($login == '' || $pass == '') {
        _error_log("Exiting from USER_LOGIN: ERROR no login or password supplied");
        return;
    }

	$login = stripslashes($login);
    $login = htmlspecialchars($login);
	$login = trim($login);

	$pass = stripslashes($pass);
    $pass = htmlspecialchars($pass);
    $pass = trim($pass);

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

    _error_log("Exiting from USER_LOGIN");
}

//refactored
function user_logout($login = '') {
    if ($login == '') {
        _error_log("Exiting from USER_LOGOUT: ERROR no login supplied");
        return;
    }

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $login = trim($login);

    $con = db_connect();

        $sql = mysql_query("SELECT login FROM users WHERE login = '$login'");
        $row = mysql_fetch_array($sql);

        if (!(empty($row['login']))) {
            unset($_SESSION['login']);
            unset($_SESSION['id']);
            unset($_SESSION['role']);
        };

    db_disconnect($con);

    _error_log("Exiting from USER_LOGOUT");
}

//refactored
function user_register($name, $login, $email, $password1, $password2) {
    $name = stripslashes($name);
    $name = htmlspecialchars($name);
    $name = trim($name);

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $login = trim($login);

    $email = stripslashes($email);
    $email = htmlspecialchars($email);
    $email = trim($email);

    $password1 = stripslashes($password1);
    $password1 = htmlspecialchars($password1);
    $password1 = trim($password1);

    $password2 = stripslashes($password2);
    $password2 = htmlspecialchars($password2);
    $password2 = trim($password2);

    if (empty($name)) {
        _error_log("Exiting from USER_REGISTER: ERROR no name supplied");
        return "<font color='#FF0000'>Необходимо указать имя</font>";
    }

    if (empty($login)) {
        _error_log("Exiting from USER_REGISTER: ERROR no login supplied");
        return "<font color='#FF0000'>Необходимо указать логин</font>";
    }

    if (empty($email)) {
        _error_log("Exiting from USER_REGISTER: ERROR no email supplied");
        return "<font color='#FF0000'>Необходимо указать e-mail</font>";
    }

    if (empty($password1)) {
        _error_log("Exiting from USER_REGISTER: ERROR no password1 supplied");
        return "<font color='#FF0000'>Необходимо указать пароль</font>";
    }

    if (empty($password2)) {
        _error_log("Exiting from USER_REGISTER: ERROR no password2 supplied");
        return "<font color='#FF0000'>Необходимо указать пароль ещё раз</font>";
    }

    if ($password1 != $password2) {
        _error_log("Exiting from USER_REGISTER: ERROR password 1 and password2 are not equal");
        return "<font color='#FF0000'>Пароли не совпадают</font>";
    }

    $con = db_connect();

    $sql = mysql_query("SELECT login FROM users WHERE login = '$login'");
    $row = mysql_fetch_array($sql);

    $password = md5($password1);

    if (empty($row['login'])) {
        mysql_query("INSERT INTO users (login, pass, name, email, confirmed, role) VALUES ('$login', '$password', '$name', '$email', false, 'supplier')");
    } else {
        _error_log("Exiting from USER_REGISTER: ERROR login already exists");
        return "Пользователь с таким логином уже существует";
    }

    db_disconnect($con);

    $mail_password = md5(md5($login).md5($password1));

    $headers  = "Content-type: text/html; charset=utf8 \r\n";
    $headers .= "From: costindex.ru <register@costindex.ru>\r\n";

    $message  = 'Вы оставили заявку на регистрацию на сайте COSTINDEX.RU<br><br>';
    $message .= 'Для завершения регистрации нажмите на ссылку <b><a href="'. DIR_PAGES .'register-complete.php?login='.$login.'&number='.$mail_password.'">Подтвердить регистрацию</a></b><br><br>';
    $message .= 'Если ссылка неактивна скопируйте ссылку http://costindex'. DIR_PAGES .'register-complete.php?login='.$login.'&number='.$mail_password.' в строку адреса браузера и нажмите Enter.<br><br>';
    $message .= 'Для входа на сайт используйте следующие реквизиты:<br><br>';
    $message .= 'Логин: '.$login.'<br>';
    $message .= 'Пароль: '.$password1.'<br><br>';
    $message .= 'Это письмо было создано автоматически. Пожалуйста, не отвечайте на него.';

    mail($email, "Регистрация на сайте COSTINDEX.RU", $message, $headers);

    _error_log("Exiting from USER_REGISTER");
    return "Сообщение отправлено";
}

//refactored
function user_confirm($login, $number) {
    if(empty($login) || empty($number)) {
        _error_log("Exiting from USER_CONFIRM: ERROR no necessary parameters supplied");
        return false;
    }

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $login = trim($login);

    $number = stripslashes($number);
    $number = htmlspecialchars($number);
    $number = trim($number);

    $result = false;

    $con = db_connect();

    $sql = mysql_query("SELECT pass FROM users WHERE login='$login'");
    $row = mysql_fetch_array($sql);

    if (!empty($row['pass'])) {

        $calc_number = md5(md5($login).$row['pass']);

        if ($number == $calc_number){
            mysql_query("UPDATE users SET confirmed=true WHERE login='$login'");
            $result = true;
        };
    };

    db_disconnect($con);

    _error_log("Exiting from USER_CONFIRM: confirmed: ". $result);

    return $result;
}


/* 
	ФУНКЦИИ РАБОТЫ С ДАТАМИ
*/

//refactored
function date_get_newest($format) {
    if (!isset($format)) {
        _error_log("Exiting from DATE_GET_NEWEST: ERROR no format supplied");
        return;
    }

    $con = db_connect();

        $sql = mysql_query("SELECT time_added FROM indexvalue ORDER BY time_added DESC LIMIT 0, 1");
        $value = mysql_fetch_array($sql);

    db_disconnect($con);

    echo date($format, strtotime($value['time_added']));

    _error_log("Exiting from DATE_GET_NEWEST");
}

//refactored
function date_convert_interval_to_string($time_interval = '6month' ) {
	switch ($time_interval) {
		case 'month': 	return 'месяц';
		case '3months': return 'квартал';
		case '6months':	return 'полгода';
		case 'year': 	return 'год';
		case '2years':	return 'два года';
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

    $days = array();

    $con = db_connect();
	
        $sql = mysql_query("SELECT time_added, indexvalue FROM indexvalue WHERE time_added > '$lastdays' ORDER BY time_added ASC");
        while($row = mysql_fetch_array($sql)) {
            $day = date("d.m.Y", strtotime($row['time_added']));
            $days[$day] = $row['indexvalue'];
        }

	db_disconnect($con);

	foreach($days as $day => $count) echo "['$day', $count ], ";

    _error_log("Exiting from GRAPH_INDEX_FILL");
}

//created
function graph_value_fill($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GRAPH_VALUE_FILL: ERROR no idx provided");
    }

    $days = array();

    $con = db_connect();

        $sql=mysql_query("SELECT date, cost FROM costs WHERE goods='$idx' ORDER BY date ASC");

        while($row=mysql_fetch_array($sql)) {
            $key = date("d.m.Y", strtotime($row['date']));
            $days[$key] = $row['cost'];
        }

    db_disconnect($con);

    foreach($days as $day => $value) echo "['$day', $value ], ";
    _error_log("Exiting from GRAPH_VALUE_FILL");
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ИНДЕКСОМ ЦЕН
*/

//refactored
function index_add($value) {
    if (!isset($value)) {
        _error_log("Exiting from INDEX_ADD: ERROR no value provided");
        return;
    }

    $con = db_connect();

    $sql = mysql_query("INSERT INTO indexvalue (indexvalue) VALUES ('$value')");

    db_disconnect($con);

    _error_log("Exiting from INDEX_ADD");
}


//refactored
function index_get_newest() {
	$con = db_connect();
	
	    $sql = mysql_query("SELECT indexvalue FROM indexvalue ORDER BY time_added DESC LIMIT 0,1");
	    $value = mysql_fetch_array($sql);

	db_disconnect($con);

	echo $value['indexvalue'];

	_error_log("Exiting from INDEX_GET_NEWEST");
}


//refactored
function index_get_change() {
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
	
	if ($diff > 0)
        echo '<sup style="color: #f00" title="Последнее изменение">+' . $diff.' (+' . $diff_percents . '%)</sup>';
    else
        echo '<sup style="color: #0d0" title="Последнее изменение">' . $diff . ' (' . $diff_percents . '%)</sup>';

    _error_log("Exiting from INDEX_GET_CHANGE");
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ГРУППАМИ ТОВАРОВ
*/

//refactored
function groups_get() {
    $con = db_connect();

        $sql = mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");

        $groups = array();

        while($row = mysql_fetch_array($sql)) {
            $groups[$row['idx']] = $row['name'];
        }

    db_disconnect($con);

    _error_log("Exiting from GET_GROUPS");
    return $groups;
}

//refactored
function groups_get_contents() {
    $con = db_connect();

    echo '<div class="group_index">
		        <ul>
	    ';

    $sql = mysql_query("SELECT idx, name FROM groups ORDER BY name ASC");
    while ($row = mysql_fetch_array($sql)) {
        $idx = $row['idx'];
        $sql_count = mysql_query("SELECT COUNT(*) FROM goods WHERE grp = '$idx'");
        $row_count = mysql_fetch_array($sql_count);
        $count = $row_count['COUNT(*)'];

        echo '
						<li>
							<a href="'. DIR_PAGES . 'goods.php?group='. $row['idx'] .'">'. $row['name'] .' ('.$count.')</a>
						</li>';
    }

    echo '		</ul>
				</div>
			 ';

    db_disconnect($con);

    _error_log("Exiting from GROUP_GET_CONTENTS");
}

//refactored
function group_get_name($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GROUP_GET_NAME: ERROR no idx specified");
        return 'Все товары';
    }

    $con = db_connect();

    $sql = mysql_query("SELECT name FROM groups WHERE idx = '$idx'");


    $name = '';

    while ($row = mysql_fetch_array($sql)) {
        $name = $row['name'];
    }

    db_disconnect($con);

    if (empty($name)) {
        _error_log("Exiting from GET_GROUP_NAME: ERROR no group name found");
        return 'Все товары';
    } else {
        _error_log("Exiting from GET_GROUP_NAME");
        return $name;
    }
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ТОВАРАМИ
*/

//refactored
function goods_update() {
    $con = db_connect();

    $sql_costs = mysql_query("SELECT goods, cost FROM costs ORDER BY date ASC");

    while($row_costs = mysql_fetch_array($sql_costs)) {
        $idx = $row_costs['goods'];
        $value = $row_costs['cost'];
        mysql_query("UPDATE goods SET value = '$value' WHERE idx = '$idx'");
    }

    db_disconnect($con);

    _error_log("Exiting from COSTS_UPDATE");
}

//refactored
function goods_get() {
    $con = db_connect();

    $sql = mysql_query("SELECT idx, name, units FROM goods ORDER BY name ASC");

    $goods = array();

    while($row = mysql_fetch_array($sql)) {
        $unit = $row['units'];
        $sql_unit = mysql_query("SELECT value FROM units WHERE idx = '$unit'");
        $row_unit = mysql_fetch_array($sql_unit);
        $unit_name = $row_unit['value'];

        $goods[$row['idx']] = $row['name'].', '.$unit_name;
    }

    db_disconnect($con);

    _error_log("Exiting from GOODS_GET");
    return $goods;
}

//refactored
function goods_get_num($group = '') {
	$con = db_connect();
	
        if (empty($group))
            $sql = mysql_query("SELECT COUNT(*) FROM goods");
        else
            $sql = mysql_query("SELECT COUNT(*) FROM goods WHERE grp = '$group'");


        $row = mysql_fetch_array($sql);
        $count = $row['COUNT(*)'];

    db_disconnect($con);

    _error_log("Exiting from GOODS_GET_NUM");

	return $count;

}

function goods_get_table($group, $filter, $sortby, $sortdir) {
    if (!isset($sortby)) $sortby = 'name';
    if (!isset($sortdir)) $sortdir = 'ASC';

    if ($sortdir === 'ASC') $new_sortdir = 'DESC'; else $new_sortdir = 'ASC';

	if (($sortdir == 'ASC')  && ($sortby == 'name'))  $imgstr_name  = '<img src="'. DIR_IMAGES .'down_arrow.png" alt="">';
	if (($sortdir == 'DESC') && ($sortby == 'name'))  $imgstr_name  = '<img src="'. DIR_IMAGES .'up_arrow.png" alt="">';
	if (($sortdir == 'ASC')  && ($sortby == 'value')) $imgstr_value = '<img src="'. DIR_IMAGES .'up_arrow.png" alt="">';
	if (($sortdir == 'DESC') && ($sortby == 'value')) $imgstr_value = '<img src="'. DIR_IMAGES .'down_arrow.png" alt="">';
	
	$th_name_link  = DIR_PAGES .'goods.php?filter=' . $filter . '&group=' . $group . '&sortby=name&sortdir=' . $new_sortdir;
	$th_price_link = DIR_PAGES .'goods.php?filter=' . $filter . '&group=' . $group . '&sortby=value&sortdir=' . $new_sortdir;
	
	$con = db_connect();
		
        $query = 'SELECT idx, name, value, thumb, units FROM goods';

        if ( empty($group)  && !empty($filter)) $query .= ' WHERE name LIKE ' . $filter . '%';
        if (!empty($group)  &&  empty($filter)) $query .= ' WHERE grp = '. $group;
        if (!empty($group)  && !empty($filter)) $query .= ' WHERE name LIKE '. $filter .'% AND grp = '. $group;

        $query .= ' ORDER BY ' . $sortby . ' ' . $sortdir;

        $sql = mysql_query($query);

        if (mysql_num_rows($sql) == 0) {
            db_disconnect($con);
            echo '<h3>Товары отсутствуют</h3>';

            _error_log("Exiting from GET_GOODS_TABLE: no goods");
            return;
        }

        echo '<table class="goods">
				<tr style="background: #eee">
					<th width="50">п/п</th>
					<th width="510"><a href="' . $th_name_link . '" title="Сортировать по наименованию товара">Наименование товара' . $imgstr_name . '</a></th>
					<th width="80"><a href="' . $th_price_link . '" title="Сортировать по цене">Цена, р.' . $imgstr_value . '</a></th>
					<th width="80">Изменение</th>
					<th width="80">C даты</th>
				</tr>
		';

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

            $diffstr = '';
            if ($diffcost > 0) $diffstr = '<span style="color: #f00;  font-size:12px;" title="Последнее изменение">+' . $diffcost . '%</span>';
            if ($diffcost < 0) $diffstr = '<span style="color: #0d0;  font-size:12px;" title="Последнее изменение">' . $diffcost . '%</span>';

			$style = ($i % 2 === 0) ? 'style="background: #eee;"' : 'style="background: #fff"';

            echo '
                <tr '. $style . '>
                    <td align="center">' . $i . '</td>
					<td>
					 	<a href="'. DIR_PAGES .'good.php?idx=' . $row['idx'] . '">'. $row['name'] .', '. $unit_name .'</a>
					</td>
					<td align="center">' . $row['value'] . '</td>
					<td align="center">' . $diffstr . '</td>
					<td align="center">' . $lastdatestr . '</td>
				 </tr>
			';

            ++$i;
		}

    echo '</table>';

    db_disconnect($con);

	_error_log("Exiting from GOODS_GET_TABLE");
}

//refactored
function goods_get_popular($group) {
    $goods_freqs = array();

    $con = db_connect();

        if (isset($group))
            $sql = mysql_query("SELECT idx FROM goods WHERE grp='$group'");
        else
            $sql = mysql_query("SELECT idx FROM goods");

        if (mysql_num_rows($sql) == 0) {
            db_disconnect($con);

            _error_log("Exiting from GOODS_GET_POPULAR: no goods");
            return;
        }

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
        $related_name = good_get_name($idx, false);
        echo '
			<div class="related_item">
				<a href="'. DIR_PAGES .'good.php?idx='.$idx.'">
					<img src="'. DIR_THUMBS . good_get_thumb($idx).'" alt="'.$related_name.'" title="'.$related_name.'" width="165" height="165">
					<div class="related_name">'.$related_name.'</div>
					<div class="related_price">'.good_get_value($idx).' р.</div>
				</a>
			</div>
		';
    }

    echo '
			</div>
			<div class="clear">
		</div>
	';

    _error_log("Exiting from GOODS_GET_POPULAR");
}

//refactored
function good_add($group, $good, $cost, $units) {
    if (!isset($group) || !isset($good) || !isset($cost) || !isset($units))  {
        _error_log("Exiting from GOOD_ADD: ERROR no necessary parameters provided");
        return;
    }

    $user_login = $_SESSION['login'];
    $cost = str_replace(",",".", $cost);

    $con = db_connect();

    mysql_query("INSERT INTO goods (name, grp, value, units, lastedit) VALUES ('$good', '$group', '$cost', '$units', '$user_login')");

    db_disconnect($con);

    _error_log("Exiting from GOOD_ADD");
}

//refactored
function good_get_name($idx, $show_units = true) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_NAME: ERROR no idx provided");
        return '';
    }

    $con = db_connect();

    $sql = mysql_query("SELECT name, units FROM goods WHERE idx='$idx'");
    $row = mysql_fetch_array($sql);
    $name = $row['name'];

    if ($show_units) {
        $units = $row['units'];

        $sql_unit = mysql_query("SELECT value FROM units WHERE idx='$units'");
        $row_unit = mysql_fetch_array($sql_unit);
        $name .= ', ' . $row_unit['value'];
    }

    db_disconnect($con);

    _error_log("Exiting from GOOD_GET_NAME");
    return $name;
}

//refactored
function good_get_thumb($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_THUMB: ERROR no idx provided");
        return 'nophoto.jpg';
    }

    $con = db_connect();

    $sql = mysql_query("SELECT thumb FROM goods WHERE idx = '$idx'");
    $row = mysql_fetch_array($sql);

    db_disconnect($con);

    $thumb = empty($row['thumb']) ? 'nophoto.jpg' : $row['thumb'];

    _error_log("Exiting from GOOD_GET_THUMB");
    return $thumb;
}

//refactored
function good_get_value($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_VALUE: ERROR no idx provided");
        return 'Цена не определена';
    }

    $con = db_connect();

    $sql = mysql_query("SELECT value FROM goods WHERE idx = '$idx'");
    $row = mysql_fetch_array($sql);

    $price = empty($row['value']) ? 'Цена не определена' : $row['value'];

    db_disconnect($con);

    _error_log("Exiting from GOOD_GET_VALUE");
    return $price;
}

//refactored
function good_get_value_max($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_VALUE_MAX: ERROR no idx provided");
        return 'Не найдёна';
    }

    $maximum = 0;

    $con = db_connect();
    $sql = mysql_query("SELECT cost FROM costs WHERE goods='$idx'");

    while($row = mysql_fetch_array($sql)) {
        if ($row['cost'] > $maximum) $maximum = $row['cost'];
    }

    db_disconnect($con);

    _error_log("Exiting from GOOD_GET_VALUE_MAX");
    return $maximum;
}

//refactored
function good_get_value_min($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_VALUE_MIN: ERROR no idx provided");
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

    _error_log("Exiting from GOOD_GET_VALUE_MIN");
    return $minimum;
}

function good_get_params($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_PARAMS: ERROR no idx provided");
        return;
    }

    $con = db_connect();

    $sql = mysql_query("SELECT producer, importer, ingredients, proteins, fats, carbohydrates, energy, expdate FROM goods WHERE idx='$idx'");

    $row = mysql_fetch_array($sql);
    if (!empty($row['producer']))
        echo '
                <h3>Производитель</h3>
			    <p>'. $row['producer'] .'</p>
			';

    if (!empty($row['importer']))
        echo '
                <h3>Импортер</h3>
			    <p>'. $row['importer'] .'</p>
			';

    if (!empty($row['ingredients']))
        echo '
                <h3>Состав</h3>
			    <p>'. $row['ingredients']. '</p>
            ';

    if ((!empty($row['proteins'])) || (!empty($row['fats'])) || (!empty($row['carbohydrates']))) {

        echo '
                <h3>Пищевая ценность на 100 г</h3>
			        <table class="pricechange" style="text-align: center;">
			            <tr style="background: #eee">
            ';

        if (!empty($row['proteins']))
            echo '<th width="33%">Белки</th>';

        if (!empty($row['fats']))
            echo '<th width="33%">Жиры</th>';

        if (!empty($row['carbohydrates']))
            echo '<th width="33%">Углеводы</th>';

        echo '
                        </tr>
                        <tr>
                    ';

        if (!empty($row['proteins']))
            echo '<td width="33%">'. $row['proteins'] .'</td>';

        if (!empty($row['fats']))
            echo '<td width="33%">'. $row['fats'] .'</td>';

        if (!empty($row['carbohydrates'])) {
            echo '<td width="33%">'. $row['carbohydrates'] .'</td>';
        };

        echo '
                        </tr>
                    </table>';
    };

    if (!empty($row['energy'])) {
        $kj = kcal_to_kj($row['energy']);
        echo '
                <table class="pricechange" style="text-align: center;">
			        <tr style="background: #eee"">
			            <th colspan="2">Энергетическая ценность</th>
			        </tr>
			        <tr>
			            <td width="50%">'. $row['energy'] .' ккал</td>
			            <td>'. $kj .' кДж</td>
			        </tr>
			    </table>
			';
    };

    if (!empty($row['expdate']))
        echo '
                <h3>Срок хранения</h3>
			    <p>'. $row['expdate']. '</p>
			';

    db_disconnect($con);

    _error_log("Exiting from GOOD_GET_PARAMS");
}

//refactored
function good_get_group_index($good_idx) {
    if (!isset($good_idx)) {
        error_log("Exiting from GOOD_GET_GROUP_INDEX: ERROR no good_idx provided");
        return -1;
    }

    $con = db_connect();

    $sql = mysql_query("SELECT grp FROM goods WHERE idx='$good_idx'");

    $group = '';

    while($row = mysql_fetch_array($sql)) {
        $group = $row['grp'];
    }

    db_disconnect($con);

    if (empty($group)) {
        error_log("Exiting from GOOD_GET_GROUP_INDEX: ERROR no group found");
        return -1;
    }

    error_log("Exiting from GOOD_GET_GROUP_INDEX");
    return $group;
}

//refactored
function good_get_related($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from GOOD_GET_RELATED: ERROR no idx provided");
        return;
    }

    $name = good_get_name($idx, false);
    if (empty($name)) {
        _error_log("Exiting from GOOD_GET_RELATED: ERROR no good name found");
        return;
    }

    $string = explode(" ", $name);

    $con = db_connect();
    $sql = mysql_query("SELECT idx FROM goods WHERE name LIKE '$string[0]%' ORDER BY RAND()");

    if (mysql_num_rows($sql) > 1) {
        echo '<h3>Похожие товары</h3>';

        $i = 0;
        while($row = mysql_fetch_array($sql)) {
            if ($idx != $row['idx']){
                $related_name = good_get_name($row['idx'], false);
                echo '
                        <div class="related_item">
                            <a href="'. DIR_PAGES .'good.php?idx='.$row['idx'].'">
                                <img src="'. DIR_THUMBS . good_get_thumb($row['idx']).'" alt="'. $related_name .'" title="'. $related_name .'" width="165" height="165">
                                '. $related_name .' 
                                <div class="related_price">'.good_get_value($row['idx']).' р.</div>
                            </a>
                        </div>
                     ';
                if (++$i > 3) break;
            }
        }
    }

    db_disconnect($con);

    _error_log("Exiting from GOOD_GET_RELATED");
}

//refactored
function good_get_edit_link($session) {
    if (!isset($session)) {
        _error_log("Exiting from GOOD_GET_EDIT_LINK: ERROR no session provided");
        return;
    }

    if (!empty($session['login']) && !empty($session['id']) && (($session['role']=='editor') || ($session['role']=='admin')))
        echo '
            <span class="edit_link">
                <a href="#" title="Редактировать сведения о товаре">
                    Редактировать
                </a>
            </span>
        ';

    _error_log("Exiting from GOOD_GET_EDIT_LINK");
}

/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ЦЕНАМИ
*/

//refactored
function cost_add($good, $cost) {
    if (!(isset($good) && isset($cost))) {
        _error_log("Exiting from COST_ADD: ERROR no good or cost provided");
        return;
    }

    $cost = str_replace(",",".",$cost);
    $con = db_connect();

    $sql = mysql_query("INSERT INTO costs (goods, cost) VALUES ('$good', '$cost')");

    db_disconnect($con);

    _error_log("Exiting from COST_ADD");
}

//refactored
function cost_del($good) {
    if (!isset($good)) {
        _error_log("Exiting from COST_DEL: ERROR no good provided");
        return;
    }

    $fromdate = date("Y-m-d");

    $con = db_connect();

    $sql = mysql_query("DELETE FROM costs WHERE goods = '$good' AND date > '$fromdate'");

    db_disconnect($con);

    _error_log("Exiting from COST_DEL");
}

//refactored
function costs_get_table_change($idx) {
    if (!isset($idx)) {
        _error_log("Exiting from COST_GET_TABLE_CHANGE: ERROR no idx provided");
        return;
    }

    echo '
        <table class="pricechange">
            <tr style="background: #eee">
                <th width="30">п/п</th>
                <th width="380">Дата</th>
                <th width="80">Цена, р.</th>
                <th width="80">Изменение</th>
            </tr>
    ';

    $con = db_connect();

        $sql = mysql_query("SELECT date, cost FROM costs WHERE goods='$idx' ORDER BY date ASC");

        $i = 1;
        $diff = 0;

        while($row = mysql_fetch_array($sql)) {
            if ($i > 1) {
                $diff = round((($row['cost'] - $prevvalue)/$prevvalue * 100),2);
            };

            $prevvalue = $row['cost'];

            if (($diff == 0) && ($i > 1)) continue;

            $style = ($i % 2 === 0) ? 'style="background: #eee;"' : 'style="background: #fff"';
            $span  = '';
            if ($diff > 0) $span = '<span style="color: #f00; font-size: 12px;" title="Последнее изменение">+'. number_format($diff,2). '%</span>';
            if ($diff < 0) $span = '<span style="color: #0d0; font-size: 12px;" title="Последнее изменение">'. number_format($diff,2). '%</span>';


            echo '
                <tr style="'. $style . '">
                    <td align="center">'. $i .'</td>
                    <td align="center">'. date ('d.m.Y', strtotime($row['date'])) .'</td>
                    <td align="right">' . $row['cost'] .'</td>
                    <td align="center">'. $span . '</td>
                </tr>';

            ++$i;
        }
        echo '</table>';

	db_disconnect($con);

    _error_log("Exiting from COST_GET_TABLE_CHANGE");
}

//refactored
function costs_get_table($login) {
    $con = db_connect();

		$fromdate = date("Y-m-d");

		echo '
            <table class="goods">
                <tr style="background: #eee">
                    <th width="30">п/п</th>
                    <th width="540">Наименование товара</th>
                    <th width="90">Цена, р.</th>
                    <th width="60"></th>
                </tr>
        ';
		
		$sql_goods = mysql_query("SELECT goods, cost FROM costs WHERE date > '$fromdate'");

        $i = 1;

        while ($row_goods = mysql_fetch_array($sql_goods)) {
			$idx = $row_goods['goods'];
			$sql_name = mysql_query("SELECT name, thumb FROM goods WHERE idx = '$idx'");
			$row_name = mysql_fetch_array($sql_name);

            $style = ($i % 2 === 0) ? 'style="background: #eee;"' : 'style="background: #fff"';
            $delete = isset($login) ? '<a href="'. DIR_API .'cost-delete.php?good='. $idx .'">Удалить</a>' : '';

            echo '
                <tr style="'. $style . '">
			        <td align="center">'. $i .'</td>		
			        <td align="left"><a href="'. DIR_PAGES .'good.php?idx='. $idx .'">'. $row_name['name']. '</a></td>
			        <td align="center">'. $row_goods['cost'] .' р.</td>
			        <td align="center">'. $delete .'</td>
                </tr>';

            ++$i;
		}

		echo '</table>';

    db_disconnect($con);
		
	_error_log("Exiting from COSTS_GET_TABLE");
}


/*
	ФУНКЦИИ ДЛЯ РАБОТЫ С ЕДИНИЦАМИ ИЗМЕРЕНИЯ
*/

//refactored
function units_get() {
	$con = db_connect();
	
		$sql = mysql_query("SELECT idx, value FROM units");

        $units = array();

        while($row = mysql_fetch_array($sql)) {
			$units[$row['idx']] = $row['value'];
		}
		
	db_disconnect($con);

    _error_log("Exiting from UNITS_GET");
	return $units;
}


/*
    ФУНКЦИИ-УТИЛИТЫ
*/

function rus_to_lat($rus_str) {
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

//created
function _error_log($message) {
    if (DEBUG) error_log($message);
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

//refactored
function kcal_to_kj($kcal) {
    return (is_numeric($kcal))  ? round($kcal * 4.1868,0) : '';
}