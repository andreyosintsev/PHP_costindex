<?php
    session_start();

    require '../functions.php';
 ?>

<?php require DIR_TEMPLATE . 'header.php'; ?>

<!--receipt-add.php-->
<body>
    <?php include(DIR_TEMPLATE . 'ads-top.php'); ?>

    <?php if (!(empty($_SESSION['login']) || empty($_SESSION['id'])))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>
		
		<div class="wrapper">
		
			<header>
				<h1>Новый чек за</h1>
				<time datetime="<?php echo date('Y-m-d');?>"><?php echo date('d.m.Y');?></time>
			</header>
			
			<div class="goods">
				<?php costs_get_table($_SESSION['login']); ?>
			</div>
			
			<?php
                if (!(empty($_SESSION['login']) || !empty($_SESSION['id'])))
                    require(DIR_FORMS . 'form-receipt-add.php');
			?>
			
			<div class="pagelinkitem"><a href="good-add.php">Добавить новый товар</a></div>
			
<?php require (DIR_TEMPLATE . 'footer.php')?>