<script>
jQuery(function($){
   $.mask.definitions['б']='[А-яЁёA-zӘІҢҒҮҰҚӨҺәіңғүұқөһ]';//Символы латиницы, кириллицы + спецсимволы казахского языка
   $("#mobile_phone").mask("+7 (999) 999-9999");
   $("#iin").mask("999999999999");
   $("#name").mask("б?бббббббббббббббббббббббббббббббббббббббббббб",{placeholder:" "});
   $("#lastname").mask("б?бббббббббббббббббббббббббббббббббббббббббббб",{placeholder:" "});
   $("#surname").mask("б?бббббббббббббббббббббббббббббббббббббббббббб",{placeholder:" "});
   $("#salary").mask("9?999999",{placeholder:" "});
   $("#spendings").mask("9?999999",{placeholder:" "});
});
</script>

<form method="post" action="submit" name="client_form" onsubmit="return validateClientForm(this.submited);">
    <div class="client_info">
        <h3>Данные о клиенте</h3>
        <table>
            <tr>
                <td nowrap>ИИН</td>
                <td nowrap><input type="text" name="iin" id="iin" maxlength="12" placeholder="ИИН" value="<?php echo $data['client']['iin'] ?>"></td>
                <?php
                if ($data != NULL) {//Данные по клиенту есть
                    extract($data);
                    if ($data['status'] == 'error') {//Ошибка при получении данных из базы
                        echo 'Ошибка при работе с базой данных, обратитесь к администратору';
                    } elseif ($data['status'] == 'client_found') {
                        if (isset($data['error'])) {
                            if ($data['error'] == "incorrect_iin") {
                                ?><!--В случаях, когда нажата кнопка подтверждения с некорректным ИИН-->
                                <td nowrap><p style="color:red" id="iin_validation_message">Введите корректный ИИН</p></td>
                                <?php
                            }
                        } else {
                            ?>
                            <td nowrap><p style="color:red" id="iin_validation_message"></p></td>
                            <?php
                        }
                        ?>
                        <?php
                    }// elseif ($data['status']=='client_not_found') {}//Пока не используется  
                } else {//Данных нет, клиент новый
                    ?>
                    <td nowrap><p style="color:red" id="iin_validation_message"></p></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td nowrap>Фамилия</td>
                <td nowrap><input type="text" name="lastname" id="lastname" placeholder="Фамилия" value="<?php echo ($data != NULL) ? $data['client']['lastname'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="lastname_validation_message"></p></td>
            </tr>

            <tr>
                <td nowrap>Имя</td>
                <td nowrap><input type="text" name="name" id="name" placeholder="Имя" value="<?php echo ($data != NULL) ? $data['client']['name'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="name_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Отчество</td>
                <td nowrap><input type="text" name="surname" id="surname" placeholder="Отчество" value="<?php echo ($data != NULL) ? $data['client']['surname'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="surname_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Мобильный телефон</td>
                <td nowrap><input type="text" name="mobile_phone" id="mobile_phone" placeholder="Мобильный телефон" value="<?php echo ($data != NULL) ? $data['client']['mobile_phone'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="mobile_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Дата рождения</td>
                <td nowrap><input type="date" style="width: 126px" name="birthdate" placeholder="Дата рождения" value="<?php echo ($data != NULL) ? $data['client']['birthdate'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="birthdate_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Пол</td>
                <td nowrap>
                    <input type="radio" name="gender" value="1" <?php echo ($data != NULL) ? ($data['client']['gender'] == '1' ? 'checked' : '') : ""; ?> >M&nbsp;
                    <input type="radio" name="gender" value="0" <?php echo ($data != NULL) ? ($data['client']['gender'] == '0' ? 'checked' : '') : ""; ?> >Ж&nbsp;
                </td>
                <td nowrap><p style="color:red" id="gender_validation_message"></p></td>
            </tr>
            <tr><th colspan="2"><hr></th></tr>
            <tr>
                <td nowrap>Номер документа</td>
                <td nowrap><input type="text" name="document_number" placeholder="Номер документа" maxlength="45" value="<?php echo ($data != NULL) ? $data['client']['document_number'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="document_number_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Кем выдан</td>
                <td nowrap><input type="text" name="document_issued_by" placeholder="Кем выдан" maxlength="64" value="<?php echo ($data != NULL) ? $data['client']['document_issued_by'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="issued_by_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Дата выдачи</td>
                <td nowrap><input type="date" style="width: 126px" name="document_issue_date" placeholder="Дата выдачи" value="<?php echo ($data != NULL) ? $data['client']['document_issue_date'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="issue_date_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Действителен до</td>
                <td nowrap><input type="date" style="width: 126px" name="document_valid_until" placeholder="Действителен до" value="<?php echo ($data != NULL) ? $data['client']['document_valid_until'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="valid_until_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Заработная плата</td>
                <td nowrap><input type="text" name="salary" id="salary" placeholder="Заработная плата" value="<?php echo ($data != NULL) ? $data['client']['salary'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="salary_validation_message"></p></td>
            </tr>
            <tr>
                <td nowrap>Коммунальные<br> платежи, аренда</td>
                <td nowrap><input type="text" name="spendings" id="spendings" placeholder="Коммунальные платежи, аренда" value="<?php echo ($data != NULL) ? $data['client']['spendings'] : "" ?>"></td>
                <td nowrap><p style="color:red" id="spendings_validation_message"></p></td>
            </tr>
        </table>
    </div>
    <br>
    <div class="form_submit" >
        <table>
            <tr>
                <td style="text-align: center"><input type="submit" name="clientCancel" style="width: 150px; height: 30px;" value="ОТМЕНА" onclick="this.form.submited=this.name;"></td>
                <td style="text-align: center"><input type="submit" name="clientConfirm" style="width: 150px; height: 30px;" value="ПОДТВЕРДИТЬ" onclick="this.form.submited=this.name;"></td>
            </tr>
        </table>
    </div>
</form>