<?php

class Model_Login extends Model {

    public function getAuthorizationStatus($login, $password) {
        $conn = new mysqli(Database::$dbservername,Database::$dbusername, Database::$dbpassword, Database::$dbname);
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $data['status'] = "error";
            Logger::getLogger('model_login_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            //Вызываем хранимую процедуру
            if (!$conn->query("SET @in_login = '" . $login . "'") ||
                    !$conn->query("SET @in_password = '" . md5($password) . "'") ||
                    !$conn->query("SET @out_id = ''") ||
                    !$conn->query("CALL getAuthorizationStatus(@in_login,@in_password,@out_id)")) {
                $data['status'] = "error";
                Logger::getLogger('model_login_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                if (!($res = $conn->query("SELECT @out_id as _p_out"))) {//Не удалось получить out-параметр
                    $data['status'] = "error";
                    Logger::getLogger('model_login_logger')->error("Не удалось получить out-параметр: (" . $conn->errno . ") " . $conn->error);
                } else {//out-параметр получен успешно
                    $row = $res->fetch_assoc();
                    if ($row['_p_out']) {//Авторизация прошла успешно
                        $data['status'] = "access_granted";
                        $data['user_id']=$row['_p_out'];
                    } else {//Логин и/или пароль неверные
                        $data['status'] = "access_denied";
                    }
                }//out-параметр получен успешно
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $data;
    }

}