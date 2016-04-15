<?php

class Model_Main extends Model {

    public function getClientCreditApplications($iin) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $data['status'] = "error";
            Logger::getLogger('model_main_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            //Вызываем хранимую процедуру
            if (!$conn->multi_query("CALL getClientCreditApplications(" . $iin . ")")) {
                $data['status'] = "error";
                Logger::getLogger('model_main_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $res = $conn->store_result();
                if ($res) {//Записи найдены
                    $data['status'] = "ok";
                    $i = 0;
                    while ($row = $res->fetch_assoc()) {
                        if (!$i) {//Берём клиента в первой записи
                            $data['client'] = $row;
                        }
                        //Пишем все заявки по очереди
                        $data['applications'][$i] = $row;
                        $i++;
                    }
                    $data['applications_number'] = $i;
                    $res->free();
                } else {//Store failed
                    if ($conn->errno) {
                        $data['status'] = "error";
                        Logger::getLogger('model_main_logger')->error("Не удалось получить результат через store_result: (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $data;
    }

    public function logout() {
        session_start();
        session_destroy(); //Убиваем сессию
        header('Location:/'); //Идём в корень
    }

}
