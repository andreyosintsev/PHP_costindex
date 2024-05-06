<!--form-receipt-add.php-->
<form id="cost-add" method="post" action="<?php echo DIR_API; ?>cost-add.php"></form>
<table id="form">
    <tr>
        <td colspan="3">
            <select name="good" form="cost-add" style="width: 580px; margin: 10px 2px 5px 10px">
            <?php
                $goods = goods_get();
                foreach($goods as $idx => $name) {
                    echo "<option value=\"".$idx."\">".$name."</option>\n";
                }
            ?>
            </select>
        </td>
        <td>
            <input type="text" name="cost" id="cost" form="cost-add" required pattern="\d{1,}+(\.\d{2})?" style="width: 85px;  margin: 10px 10px 5px 2px" maxlength="25" value="Цена, р" onfocus='if (this.value == "Цена, р") this.value=""' onblur='if (this.value == "") this.value="Цена, р"'>
        </td>
    </tr>
    <tr>
        <td>
            <input type="submit" name="db_submit" form="cost-add" value="Добавить" style="width: 100px; margin: 5px 0 10px 10px">
        </td>
        <td width="105" style="padding: 0 0 0 5px;">Вес, кг<input style="width: 55px; margin: 0 0 0 5px" id="weight" onchange="price1kg()"></td>
        <td width="145" style="padding: 0 0 0 5px;">Стоимость, р.<input style="width: 55px;  margin: 0 0 0 5px" id="price" onchange="price1kg()"></td>
    </tr>
</table>