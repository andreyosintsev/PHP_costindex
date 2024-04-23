<div class="abc-contents" style="text-align: center;">
	<table class="goods">
		<tr style="background: #eee">
			<td>
				<a href="../goods.php">ВСЕ (<?php echo get_goods_num();?>)</a> |

				<?php 
					$letters = [ 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я' ];				

					foreach($letters as $letter) echo '<a class="abc-contents__letter" href="../goods.php/?filter='. $letter. '">'. $letter .'</a>';
				?>
			</td>
		</tr>
	</table>
</div>
<div style="clear:both"></div>