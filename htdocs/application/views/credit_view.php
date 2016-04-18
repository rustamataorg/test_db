<script>
    jQuery(function ($) {
        $('#amount').priceFormat({
            prefix: '',
            thousandsSeparator: '',
            limit: 9,
            centsLimit: 2
                    //suffix: 'KZT'
        });
    });
</script>

<script type="text/javascript">
    //Запрещаем submit формы по нажатию Enter, чтобы пользователь случайно не отправлял некорректную форму
    function stopReturnKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }
    document.onkeypress = stopReturnKey;
</script>

<input type="hidden" name="usd_rate" id="usd_rate" value="<?php extract($data);
echo $data['USD'];
?>">
<form method="post" action="confirmCredit" name="credit_form" id="credit_form" onsubmit="return validateCreditForm(this.submited);">
    <div class="credit_info">
        <h3>Данные о кредите</h3>
        <table>
            <tr>
                <td nowrap>Сумма</td><td nowrap><input type="text" name="amount" id="amount" value="" onfocusout="calcCreditInfo();" autofocus></td>
                <td nowrap><p style="color:red" id="amount_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Срок</td>
                <td nowrap>
                    <select style="width: 130px" name="period" id="period" onchange="calcCreditInfo()">
                        <option value=""></option>
                        <?php
                        if (isset($data['periods'])) {
                            foreach ($data['periods'] as $item) {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['value'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td nowrap><p style="color:red" id="period_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Ставка</td>
                <td nowrap>
                    <select style="width: 130px" name="rate" id="rate" onchange="calcCreditInfo()">
                        <option value=""></option>
                        <option value="9">9%</option>
                        <option value="10">10%</option>
                        <option value="11">11%</option>
                    </select>
                </td>
                <td nowrap><p style="color:red" id="rate_validation_message"></p></td>
            </tr>
            <tr><th colspan="2"><hr></th></tr>
            <tr><td nowrap>Еж. платёж</td><td nowrap><input type="text" name="monthly_payment" id="monthly_payment" value="" readonly></td></tr>
            <tr><td nowrap>Сумма в $</td><td nowrap><input type="text" name="usd_amount" id="usd_amount" value="" readonly></td></tr>
            <tr><td nowrap>Общая сумма выплат</td><td nowrap><input type="text" name="total_amount" id="total_amount" value="" readonly></td></tr>
            <tr><td nowrap>Переплата</td><td nowrap><input type="text" name="extra_payment" id="extra_payment" value="" readonly></td></tr>
        </table>
    </div>
    <br>
    <div class="form_submit" >
        <table>
            <tr>
                <td style="text-align: center"><input type="submit" name="creditCancel" style="width: 150px; height: 30px;" value="ОТМЕНА" onclick="this.form.submited = this.name;"></td>
                <td style="text-align: center"><input type="submit" name="creditConfirm" style="width: 150px; height: 30px;" value="ПОДТВЕРДИТЬ" onclick="this.form.submited = this.name;"></td>
            </tr>
        </table>
    </div>
</form>