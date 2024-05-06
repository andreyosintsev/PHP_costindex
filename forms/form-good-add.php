<!--form-good-add.php-->
<form id="good-add" method="post" action="<?php echo DIR_API; ?>good-add.php" style="margin: 20px 0"></form>
<table>
    <tr>
        <td>
            <div class="form_label">Группа товаров</div>
            <select name="group" form="good-add" style="width: 510px">
            <?php
                $groups = groups_get();
                foreach($groups as $idx => $name) {
                    echo "<option value=\"".$idx."\">".$name."</option>\n";
                }
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <div class="form_label">Наименование товара</div>
            <input type="text" name="good_name" form="good-add" required style="width: 506px" maxlength="255" value=""'>
        </td>
        <td>
            <div class="form_label">Размерность</div>
            <select name="units" form="good-add" style="width: 100px">
            <?php
                $units = units_get();
                foreach($units as $idx=>$value) {
                    echo "<option value=\"".$idx."\">".$value."</option>\n";
                }
            ?>
            </select>
        </td>
        <td>
            <div class="form_label">Цена, р.</div>
            <input type="text" name="cost" form="good-add" required pattern="\d{1,}+(\.\d{2})?" style="width: 85px" maxlength="25" value="">
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>
            <input type="submit" name="submit" form="good-add" value="Добавить" style="width: 100px">
        </td>
    </tr>
</table>