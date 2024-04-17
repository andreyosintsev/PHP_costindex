<!--footer.php-->
			<nav>
				<ul>
					<li><a href="http://costindex.ru">Главная</a></li>
					<li><a href="http://costindex.ru/goods.php">Товары</a></li>
					<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id']) and ($_SESSION['role']=='admin')) {
							echo '<li><a href="http://costindex.ru/addvalue.php">Добавить чек</a></li>';
						  }				
					?>
				</ul>
				<div class="stats">
				<!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=26908203&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/26908203/3_0_E5E5E5FF_E5E5E5FF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:26908203,lang:'ru'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter26908203 = new Ya.Metrika({id:26908203,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/26908203" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
					<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t15.2;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->

				</div>
			</nav>
			<div class="clear"></div>
		</div>
		<footer>
			<p>&copy; Индекс потребительских цен</p>
		</footer>
	</body>
</html>
<!--Выполнено за: <?php runtime();?> -->
