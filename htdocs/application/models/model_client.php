<?php

class Model_Client extends Model {

    public function getClientInfo($clientId) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $data['status'] = "error";
            Logger::getLogger('model_client_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            if (!$conn->multi_query("CALL getClientById(" . $clientId . ")")) {
                $data['status'] = "error";
                Logger::getLogger('model_client_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $res = $conn->store_result();
                if ($res) {//Записи найдены
                    $data['status'] = "client_not_found";
                    while ($row = $res->fetch_assoc()) {//Клиент найден
                        $data['status'] = "client_found";
                        $data['client'] = $row;
                    }
                    $res->free();
                } else {//Store failed
                    if ($conn->errno) {
                        $data['status'] = "error";
                        Logger::getLogger('model_client_logger')->error("Не удалось получить результат через store_result: (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $data;
    }

    public function updateClientInfo($clientInfo) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        $execStatus = "ok";
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $execStatus = "error";
            Logger::getLogger('model_client_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему

            $queryString = "CALL updateClientInfo("
                    . $clientInfo['id'] . ",'"
                    . $clientInfo['iin'] . "','"
                    . $clientInfo['lastname'] . "','"
                    . $clientInfo['name'] . "','"
                    . $clientInfo['surname'] . "','"
                    . $clientInfo['mobile_phone'] . "','"
                    . $clientInfo['birthdate'] . "',"
                    . $clientInfo['gender'] . ",'"
                    . $clientInfo['document_number'] . "','"
                    . $clientInfo['document_issued_by'] . "','"
                    . $clientInfo['document_issue_date'] . "','"
                    . $clientInfo['document_valid_until'] . "',"
                    . $clientInfo['salary'] . ","
                    . $clientInfo['spendings'] . ")";

            if (!$conn->multi_query($queryString)) {//При вызове процедуры произошла ошибка
                $execStatus = "error";
                Logger::getLogger('model_client_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $execStatus = "ok";
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $execStatus;
    }

    public function addClient($clientInfo) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        $execStatus['status'] = "ok";
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $execStatus['status'] = "error";
            Logger::getLogger('model_client_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            //Вызываем хранимую процедуру
            if (!$conn->query("SET @in_iin = '" . $clientInfo['iin'] . "'") ||
                    !$conn->query("SET @in_lastname = '" . $clientInfo['lastname'] . "'") ||
                    !$conn->query("SET @in_name = '" . $clientInfo['name'] . "'") ||
                    !$conn->query("SET @in_surname = '" . $clientInfo['surname'] . "'") ||
                    !$conn->query("SET @in_mobile_phone = '" . $clientInfo['mobile_phone'] . "'") ||
                    !$conn->query("SET @in_birthdate = '" . $clientInfo['birthdate'] . "'") ||
                    !$conn->query("SET @in_gender = " . $clientInfo['gender']) ||
                    !$conn->query("SET @in_document_number = '" . $clientInfo['document_number'] . "'") ||
                    !$conn->query("SET @in_document_issued_by = '" . $clientInfo['document_issued_by'] . "'") ||
                    !$conn->query("SET @in_document_issue_date = '" . $clientInfo['document_issue_date'] . "'") ||
                    !$conn->query("SET @in_document_valid_until = '" . $clientInfo['document_valid_until'] . "'") ||
                    !$conn->query("SET @in_salary = '" . $clientInfo['salary'] . "'") ||
                    !$conn->query("SET @in_spendings = '" . $clientInfo['spendings'] . "'") ||
                    !$conn->query("SET @out_id = ''") ||
                    !$conn->query("CALL addClient(@in_iin,@in_lastname,@in_name,@in_surname,@in_mobile_phone,"
                            . "@in_birthdate,@in_gender,@in_document_number,@in_document_issued_by,@in_document_issue_date,"
                            . "@in_document_valid_until,@in_salary,@in_spendings,@out_id)")) {
                //Не удалось вызвать процедуру                
                $execStatus['status'] = "error";
                Logger::getLogger('model_client_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                if (!($res = $conn->query("SELECT @out_id as _p_out"))) {//Не удалось получить out-параметр
                    $execStatus['status'] = "error";
                    Logger::getLogger('model_client_logger')->error("Не удалось получить out-параметр: (" . $conn->errno . ") " . $conn->error);
                } else {//out-параметр получен успешно
                    $row = $res->fetch_assoc();
                    if ($row['_p_out']) {//Клиент успешно добавлен
                        $execStatus['status'] = "ok";
                        $execStatus['user_id'] = $row['_p_out'];
                    }
                }//out-параметр получен успешно
            }//Продцедура вызвана успешно            

            $conn->close();
        }
        return $execStatus;
    }

    public function cancelCreditApplication($appInfo) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        $execStatus = "ok";
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $execStatus = "error";
            Logger::getLogger('model_client_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            $queryString = "CALL cancelCreditApplication(" . $appInfo['client_id'] . ")";
            if (!$conn->multi_query($queryString)) {//При вызове процедуры произошла ошибка
                $execStatus = "error";
                Logger::getLogger('model_client_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $execStatus = "ok";
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $execStatus;
    }

    public function validateIIN($iin) {//Валидация ИИН через обращение к Java RESTful сервису
        $URL = "http://localhost:8080/CreditAppServices/webresources/identity/validateIIN/";
        $curl = curl_init($URL);
        #curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        #curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, true);// Сообщение curl, что нужно использовать HTTP POST
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $iin);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);// Обращение к REST, получение результата
        curl_close($curl);// Закрытие сеанса
        $result = json_decode($response);//Преобразование результата из формата JSON в массив PHP 
        if ($result->status == "ERROR") {//Сервис проверки ИИН вернул ошибку, пишем в лог
            Logger::getLogger('model_client_logger')->error("Сервис проверки ИИН вернул ошибку: (" . $result->errorMsg . ")");
        }
        return $result;
    }

}
