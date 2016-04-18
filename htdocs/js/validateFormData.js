function validateLoginForm() {
    var login = document.forms["login_form"]["user_login"].value.trim();
    var password = document.forms["login_form"]["user_password"].value.trim();
    if (login === null || login === '') {
        document.getElementById("login_validation_message").innerHTML = "Введите логин";
        return false;
    }
    if (password === null || password === '') {
        document.getElementById("password_validation_message").innerHTML = "Введите пароль";
        return false;
    }
}

function showErrorStatusLoginForm() {
    document.getElementById('authorization_error_message').innerHTML = parseGetParameter("error");
}

function parseGetParameter(parameterName) {
    var params = window.location.search.toString().trim();
    if (params.length) {//Строка не пустая
        var pairs = new Array();
        var temp = new Array();
        var keyvalue = new Array();
        pairs = (params.substr(1)).split('&');
        for (var i = 0; i < pairs.length; i++) {
            temp = pairs[i].split('=');
            keyvalue[temp[0]] = temp[1];
        }
        return keyvalue[parameterName];
    } else {
        return "";
    }
}

function validateMainForm() {
    var iin = document.forms["search_form"]["client_iin"].value.trim();
    if (iin === null || iin === '' || strlen(trim(iin)) != 12) {
        document.getElementById("iin_validation_message").innerHTML = "ИИН введён неправильно, попробуйте снова";
        return false;
    } else {
        document.getElementById("iin_validation_message").innerHTML = "";
        return true;
    }
}

function validateClientForm(buttonName) {
    if (buttonName === "clientConfirm") {//Нажали кнопку "Подтвердить"
        var validStatus = true; //Статус валидации формы

        var iin = document.forms["client_form"]["iin"].value.trim();
        var lastname = document.forms["client_form"]["lastname"].value.trim();
        var name = document.forms["client_form"]["name"].value.trim();
        var surname = document.forms["client_form"]["surname"].value.trim();
        var mobile_phone = document.forms["client_form"]["mobile_phone"].value.trim();
        var birthdate = document.forms["client_form"]["birthdate"].value.trim();
        var gender = document.forms["client_form"]["gender"].value.trim();
        var document_number = document.forms["client_form"]["document_number"].value.trim();
        var document_issued_by = document.forms["client_form"]["document_issued_by"].value.trim();
        var document_issue_date = document.forms["client_form"]["document_issue_date"].value.trim();
        var document_valid_until = document.forms["client_form"]["document_valid_until"].value.trim();
        var salary = document.forms["client_form"]["salary"].value.trim();
        var spendings = document.forms["client_form"]["spendings"].value.trim();
        if (iin === null || iin === '') {
            document.getElementById("iin_validation_message").innerHTML = "Введите ИИН";
            validStatus = false;
        } else {
            document.getElementById("iin_validation_message").innerHTML = "";
        }
        if (lastname === null || lastname === '') {
            document.getElementById("lastname_validation_message").innerHTML = "Введите фамилию";
            validStatus = false;
        } else {
            if (get2Alphabets(lastname)) {
                document.getElementById("lastname_validation_message").innerHTML = "Символы из разных алфавитов";
                validStatus = false;
            } else {
                document.getElementById("lastname_validation_message").innerHTML = "";
            }
        }
        if (name === null || name === '') {
            document.getElementById("name_validation_message").innerHTML = "Введите имя";
            validStatus = false;
        } else {
            if (get2Alphabets(name)) {
                document.getElementById("name_validation_message").innerHTML = "Символы из разных алфавитов";
                validStatus = false;
            } else {
                document.getElementById("name_validation_message").innerHTML = "";
            }
        }
        if (surname === null || surname === '') {
            document.getElementById("surname_validation_message").innerHTML = "Введите отчество ";
            validStatus = false;
        } else {
            if (get2Alphabets(surname)) {
                document.getElementById("surname_validation_message").innerHTML = "Символы из разных алфавитов";
                validStatus = false;
            } else {
                document.getElementById("surname_validation_message").innerHTML = "";
            }
        }
        if (mobile_phone === null || mobile_phone === '') {
            document.getElementById("mobile_validation_message").innerHTML = "Введите мобильный телефон";
            validStatus = false;
        } else {
            if (isDefCodeValid(mobile_phone)) {//DEF-код введён правильно
                document.getElementById("mobile_validation_message").innerHTML = "";
            } else {//Ошибочный DEF-код
                document.getElementById("mobile_validation_message").innerHTML = "Неправильный код оператора";
                validStatus = false;
            }
        }
        if (birthdate === null || birthdate === '') {
            document.getElementById("birthdate_validation_message").innerHTML = "Введите дату рождения";
            validStatus = false;
        } else {
            if ((new Date() - new Date(birthdate)) <= (1000 * 60 * 60 * 24 * 365 * 14)) {//Несовершеннолетний
                document.getElementById("birthdate_validation_message").innerHTML = "Исправьте дату рождения";
                validStatus = false;
            } else {
                document.getElementById("birthdate_validation_message").innerHTML = "";
            }
        }

        if (gender === null || gender === '') {
            document.getElementById("gender_validation_message").innerHTML = "Введите пол";
            validStatus = false;
        } else {
            document.getElementById("gender_validation_message").innerHTML = "";
        }
        if (document_number === null || document_number === '') {
            document.getElementById("document_number_validation_message").innerHTML = "Введите номер документа";
            validStatus = false;
        } else {
            document.getElementById("document_number_validation_message").innerHTML = "";
        }
        if (document_issued_by === null || document_issued_by === '') {
            document.getElementById("issued_by_validation_message").innerHTML = "Укажите кем выдан документ";
            validStatus = false;
        } else {
            document.getElementById("issued_by_validation_message").innerHTML = "";
        }
        if (document_issue_date === null || document_issue_date === '') {
            document.getElementById("issue_date_validation_message").innerHTML = "Введите дату выдачи документа";
            validStatus = false;
        } else {
            document.getElementById("issue_date_validation_message").innerHTML = "";
        }
        if (document_valid_until === null || document_valid_until === '') {
            document.getElementById("valid_until_validation_message").innerHTML = "Введите срок действия документа";
            validStatus = false;
        } else {
            if (new Date(document_valid_until) < new Date()) {//Срок действия документа истёк
                document.getElementById("valid_until_validation_message").innerHTML = "Срок действия документа истёк";
                validStatus = false;
            } else {
                document.getElementById("valid_until_validation_message").innerHTML = "";
            }
        }
        if (salary === null || salary === '') {
            document.getElementById("salary_validation_message").innerHTML = "Введите заработную плату";
            validStatus = false;
        } else {
            document.getElementById("salary_validation_message").innerHTML = "";
        }
        if (spendings === null || spendings === '') {
            document.getElementById("spendings_validation_message").innerHTML = "Введите коммунальные платежи, аренду";
            validStatus = false;
        } else {
            document.getElementById("spendings_validation_message").innerHTML = "";
        }
        return validStatus;
    } else if (buttonName === "clientCancel") {//Нажали кнопку "Отмена"
        return true; //Валидировать не нужно
    }
}

function isDefCodeValid(mobile) {//Проверка корректности DEF-кода мобильного телефона (формат '+7 (XXX) XXX-XXXX')
    var defCodes = ["700", "701", "702", "705", "707", "708", "747", "771", "775", "776", "777", "778"]; //DEF-коды мобильных операторов
    return (defCodes.indexOf(mobile.substr(4, 3)) === -1 ? false : true);
}

function validateCreditForm(buttonName) {
    if (buttonName === "creditConfirm") {//Нажали кнопку "Подтвердить"
        var validStatus = true; //Статус валидации формы

        var amount = document.forms["credit_form"]["amount"].value.trim();
        var period = document.forms["credit_form"]["period"].value.trim();
        var rate = document.forms["credit_form"]["rate"].value.trim();
        if (amount === '' || amount === "0" || amount === "0.00") {
            document.getElementById("amount_validation_message").innerHTML = "Введите сумму";
            validStatus = false;
        } else {
            document.getElementById("amount_validation_message").innerHTML = "";
        }
        if (period === '') {
            document.getElementById("period_validation_message").innerHTML = "Введите период";
            validStatus = false;
        } else {
            document.getElementById("period_validation_message").innerHTML = "";
        }
        if (rate === '') {
            document.getElementById("rate_validation_message").innerHTML = "Введите ставку";
            validStatus = false;
        } else {
            document.getElementById("rate_validation_message").innerHTML = "";
        }

        return validStatus;
    } else if (buttonName === "creditCancel") {//Нажали кнопку "Отмена"
        return true; //Валидировать не нужно
    }
}

function calcCreditInfo() {//Вычисление дополнительных полей по кредиту
    var amount = document.forms["credit_form"]["amount"].value.trim();
    var period = document.forms["credit_form"]["period"].value.trim();
    var rate = document.forms["credit_form"]["rate"].value.trim();
    var rateUsd = parseInt(document.getElementById("usd_rate").value);
    if (!(amount === '' || amount === "0" || amount === "0.00")) {//Если сумма заполнена
//Расчитываем сумму в долларах
        var amountUsdString = (amount / rateUsd).toFixed(2).toString();
        document.getElementById("usd_amount").value = amountUsdString;
        if (!(period === '') && !(rate === '')) {//Если также заполнены период и ставка
//Расчитываем и заполняем остальные поля
            var annuity = getAnnuity(amount, period, rate);
            var totalAmount = annuity * period;
            var extraPay = totalAmount - amount;
            document.getElementById("monthly_payment").value = annuity.toFixed(2).toString();
            document.getElementById("total_amount").value = totalAmount.toFixed(2).toString();
            document.getElementById("extra_payment").value = extraPay.toFixed(2).toString();
        }
    }
}

function getAnnuity(amount, period, yearlyRate) {//Расчёт аннуитетного платежа
    montlyRate = yearlyRate / 12 / 100;
    return amount * (montlyRate * Math.pow(1 + montlyRate, period)) / (Math.pow(1 + montlyRate, period) - 1);
}

function get2Alphabets(str) {
    if (str.search(/[A-z]/i) !== -1 && str.search(/[А-яЁёӘІҢҒҮҰҚӨҺәіңғүұқөһ]/i) !== -1) {
        return true;
    } else {
        return false;
    }
}