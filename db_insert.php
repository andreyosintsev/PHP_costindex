<?php require 'functions.php';?>
<?php
  $db_login =  $_POST['db_login']; 
  $db_pass =  $_POST['db_pass']; 
  $db_value =  $_POST['db_value']; 
  
  $str = 'Логин: '.$db_login.' Пароль: '.$db_pass.' Значение: '.$db_value;
    
  /*echo "<script>alert(\"".$str."\");</script>";*/
  
  db_insertindex($db_value)
?>