<script>
jQuery(function($){
   $("#client_iin").mask("999999999999",{placeholder:" "});
});
</script>

<div id="search_form_div">
    <form name="search_form" class="center" id="search_form" method="post" action="search" onsubmit="return validateMainForm();">
        <input type="text" id="client_iin" name="client_iin" maxlength="12" placeholder="ИИН" autofocus>
        <input type="submit" name="client_search_button" id="client_search_button" value="">
    </form>
    <p>Номер ИИН?</p>
    <p id="iin_validation_message"></p>
    <td nowrap><p style="color:red" id="iin_validation_message"></p></td>
</div>
<br><br>

<?php if (isset($data)) { ?>
    <div class="application_list">
        <table>
            <tr>
                <th colspan="5">ЗАЯВКИ</th>
            </tr>
            <tr>
                <td nowrap>Номер заявки</td>
                <td nowrap>Дата обращения</td>
                <td nowrap>Сумма</td>
                <td nowrap>Срок</td>
                <td nowrap>Статус</td>
            </tr>

            <?php
            extract($data);
            foreach ($data['applications'] as $rec) {
                ?>
                <tr>
                    <td nowrap><?php echo $rec['rank']; ?></td>
                    <td nowrap><?php echo $rec['application_date']; ?></td>
                    <td nowrap><?php echo $rec['application_amount']; ?></td>
                    <td nowrap><?php echo $rec['application_period_value']; ?></td>
                    <td nowrap><?php echo $rec['application_status']; ?></td>
                </tr>
                <?php
            }
            #}
            ?>
        </table>
    </div>
<?php } ?>

<br><br>

<div class="form_submit" >
    <table>
        <tr>
        <form method="post" action="submit">
            <td nowrap>
                <input type="submit" class="form_button" name="main_exit" value="ВЫХОД">
            </td>
            <td nowrap>
                <?php
                if (isset($_SESSION['current_client_id'])) {//Пользователь уже найден поиском
                    echo '<input type="submit" class="form_button" name="main_create" value="НОВАЯ ЗАЯВКА">';
                } else {//Пользователя ещё не искал
                    echo '<input type="submit" class="form_button" name="main_create" disabled value="НОВАЯ ЗАЯВКА">';
                }
                ?>
            </td>
        </form>
        </tr>
    </table>

</div>