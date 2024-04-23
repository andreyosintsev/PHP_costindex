<?php
    session_start();

    require 'functions.php';

    add_costs($_POST['goods'], $_POST['cost']);
    del_costs($_GET['delete']);
    update_costs();
    add_goods($_POST['groups'], $_POST['good_name'], $_POST['cost'], $_POST['units']);
 ?>
<!--addvalue.php-->

    <?php require 'template/header.php'; ?>
    <?php include('template/ads-top.php'); ?>

    <?php if (!empty($_SESSION['login']) and !empty($_SESSION['id']))
        require ('template/logged.php'); else require ('template/login.php');
    ?>
		
		<div class="wrapper">
		
			<header>
				<h1>Новый чек за</h1>
				<time datetime="<?php echo date('Y-m-d');?>"><?php echo date('d.m.Y');?></time>
			</header>
			
			<div class="goods">
					<?php get_tablecosts($_SESSION['login']); ?>
			</div>
			
			<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) {
				require ('addvalueform.php');}				
			?>
			
			<div class="pagelinkitem"><a href="addnewgood.php">Добавить новый товар</a></div>
			
<?php require ('temlate/footer.php')?>