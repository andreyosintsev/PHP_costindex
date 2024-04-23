<?php
    session_start();
    require 'functions.php';

    $idx = $_GET['idx'];
?>

    <?php require 'template/header.php'; ?>

    <script src="https://www.google.com/jsapi"></script>
    <script src="scripts/chart.js"></script>
    <script>
        google.load('visualization', '1.0', {'packages':['corechart']});
        google.setOnLoadCallback(() => drawChart(
            [
                <?php graph_value_fill($idx); ?>
            ],
            'chart_div'),
            'Цена'
        );
    </script>

<!--good.php-->
	<body>
    <?php include('template/ads-top.php'); ?>

    <?php if (!empty($_SESSION['login']) and !empty($_SESSION['id']))
        require ('template/logged.php'); else require ('template/login.php');
    ?>
		
    <div class="wrapper">
        <nav>
            <span class="group">
                <a href="goods.php">Товары</a> > <a href="goods.php?group=<?php echo get_group($idx); ?>"><?php echo get_group_name(get_group($idx)); ?></a>
            </span>
            <?php get_edit_link($_SESSION);?>
        </nav>
        <header>
            <h1><?php echo get_good($idx);?></h1>
        </header>
        <section>
            <div class="thumb">
                <div class="thumb_img">
                    <?php
                        if (get_thumb($idx) !== 'nophoto.jpg') echo '<img src="thumbs/'.get_thumb($idx).'" alt="'.get_good($idx).'" title="'.get_good($idx).'" width="200" height="200">';
                        else {
                            if (!empty($_SESSION['login']) && !empty($_SESSION['id'])) echo '<a href="#" title="Добавить изображение"><img src="thumbs/addphoto.jpg" alt="Добавить фото"></a>';
                            else echo '<img src="thumbs/nophoto.jpg" title="Изображение пока отсутствует" alt="Изображение отсутствует">';
                        }
                    ?>
                </div>
				
			</div>
            <div class="desc">
                <div class="desc_price">
                    <?php echo get_price($idx).' р.';?>
                </div>
                <div class="desc_price_minmax">
                    <?php echo get_price_min($idx).' &mdash; '.get_price_max($idx).' р.';?>
                </div>
                <div class="desc_params">
                    <?php get_params($idx);?>
                </div>

                <h3>Изменение цены</h3>
                <div class="desc_pricechange">
                    <?php get_tablepricechange($idx);?>
                </div>
            </div>
    		<div class="clear">
        </section>
        <section>
                <div class="graph">
                    <div id="chart_div" title="График изменения цены на товар"></div>
                </div>
        </section>
        <section>
                <div class="related">
                    <?php get_related($idx); ?>
                </div>
        </section>

        <?php include 'template/comments.php';?>

<?php require ('template/footer.php')?>