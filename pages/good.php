<?php
    session_start();
    require '../functions.php';

    $idx = $_GET['idx'];
?>

    <?php require DIR_TEMPLATE . 'header.php'; ?>

    <script src="https://www.google.com/jsapi"></script>
    <script src="<?php echo DIR_SCRIPTS . 'chart.js'; ?>"></script>
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
    <?php include DIR_TEMPLATE . 'ads-top.php'; ?>

    <?php if (!(empty($_SESSION['login']) || empty($_SESSION['id'])))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>
		
    <div class="wrapper">
        <nav>
            <span class="group">
                <a href="<?php echo DIR_PAGES; ?>goods.php">Товары</a> > <a href="<?php echo DIR_PAGES; ?>goods.php?group=<?php echo good_get_group_index($idx); ?>"><?php echo group_get_name(good_get_group_index($idx)); ?></a>
            </span>
            <?php good_get_edit_link($_SESSION);?>
        </nav>
        <header>
            <h1><?php echo good_get_name($idx);?></h1>
        </header>
        <section>
            <div class="thumb">
                <div class="thumb_img">
                    <?php
                        if (good_get_thumb($idx) !== 'nophoto.jpg') {
                            echo '<img src="'. DIR_THUMBS . good_get_thumb($idx).'" alt="'. good_get_name($idx) .'" title="'. good_get_name($idx) .'" width="200" height="200">';
                        } else {
                            if (!empty($_SESSION['login']) && !empty($_SESSION['id'])) {
                                echo '<a href="#" title="Добавить изображение"><img src="'. DIR_IMAGES .'addphoto.jpg" alt="Добавить фото"></a>';
                            }
                            else {
                                echo '<img src="'. DIR_IMAGES .'nophoto.jpg" title="Изображение пока отсутствует" alt="Изображение отсутствует">';
                            }
                        }
                    ?>
                </div>
				
			</div>
            <div class="desc">
                <div class="desc_price">
                    <?php echo good_get_value($idx).' р.';?>
                </div>
                <div class="desc_price_minmax">
                    <?php echo good_get_value_min($idx).' &mdash; '. good_get_value_max($idx).' р.';?>
                </div>
                <div class="desc_params">
                    <?php good_get_params($idx);?>
                </div>

                <h3>Изменение цены</h3>
                <div class="desc_pricechange">
                    <?php costs_get_table_change($idx);?>
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
                    <?php good_get_related($idx); ?>
                    <div class="clear"></div>
                </div>
        </section>

        <?php include DIR_TEMPLATE . 'comments.php';?>

<?php require (DIR_TEMPLATE . 'footer.php')?>