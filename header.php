<?php require 'functions.php';?>
<?php runtime(); ?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<title>Индекс потребительских цен - costindex.ru</title>
		<meta charset="utf-8">
		<meta name="description" content="Сервис отслеживания изменения уровня цен на потребительские товары повседневного спроса">
		<link rel="stylesheet" href="http://costindex.ru/style.css">
		<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="http://works-profs.ru/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	
		<script type="text/javascript" src="/vk.com/js/api/openapi.js?115"></script>
		<script type="text/javascript">
			VK.init({apiId: 4548704, onlyWidgets: true});
		</script>
		<script type="text/javascript">
			function price1kg () {
				price_value = price.value.replace(",",".");
				weight_value = weight.value.replace(",",".");
				cost.value=(price_value/weight_value).toFixed(2);
			};
		</script>
	</head>