<!--footer.php-->
        <nav>
            <ul>
                <li>
                    <a href="/" title="На главную страницу">Главная</a>
                </li>
                <li>
                    <a href="<?php echo DIR_PAGES . 'goods.php'; ?>" title="К перечню товаров">Товары</a>
                </li>

                <?php if (!empty($_SESSION['login']) && !empty($_SESSION['id']) && ($_SESSION['role']=='admin'))
                    {
                ?>
                        <li>
                            <a href="<?php echo DIR_PAGES . 'receipt-add.php'; ?>" title="Добавить новый чек">Добавить чек</a>
                        </li>
                <?php
                    }
                ?>
            </ul>

            <div class="stats">
                <!-- Yandex.Metrika informer -->
                <a href="https://metrika.yandex.ru/stat/?id=97060391&amp;from=informer"
                target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/97060391/3_0_FFFFFFFF_EFEFEFFF_0_pageviews"
                style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="97060391" data-lang="ru" /></a>
                <!-- /Yandex.Metrika informer -->

                <!-- Yandex.Metrika counter -->
                <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                ym(97060391, "init", {
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true,
                        trackHash:true
                });
                </script>
                <noscript><div><img src="https://mc.yandex.ru/watch/97060391" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter -->
            </div>
        </nav>

		<div class="clear"></div>
	</div>
	<footer>
		<p>&copy; Индекс потребительских цен</p>
	</footer>
</body>
</html>
<!-- Выполнено за: <?php runtime(0, true);?> -->
