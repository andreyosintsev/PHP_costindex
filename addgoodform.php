		<form id="addgood" method="post" action="addvalue.php" style="margin: 20px 0"></form>
			<table>
			<tr>
				<td>
					<div class="form_label">Группа товаров</div>
					<select name="groups" form="addgood" style="width: 510px">
					<?php
						$groups = get_groups();
						foreach($groups as $idx=>$name) {
							echo "<option value=\"".$idx."\">".$name."</option>\n";
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<div class="form_label">Наименование товара</div>
					<input type="text" name="good_name" form="addgood" required style="width: 506px" maxlength="255" value=""'>
				</td>
				<td>
					<div class="form_label">Размерность</div>
					<select name="units" form="addgood" style="width: 100px">
					<?php
						$units = get_units();
						foreach($units as $idx=>$value) {
							echo "<option value=\"".$idx."\">".$value."</option>\n";
						}
					?>
					</select>
				</td>
				<td>
					<div class="form_label">Цена, р.</div>
					<input type="text" name="cost" form="addgood" required pattern="\d{1,}+(\.\d{2})?" style="width: 85px" maxlength="25" value=""'>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submit" form="addgood" value="Добавить" style="width: 100px">
				</td>
			</tr>
			</table>