<?php

class Model_Credit extends Model {

    public function getCreditPeriods() {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $data['status'] = "error";
            Logger::getLogger('model_credit_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            //Вызываем хранимую процедуру
            if (!$conn->multi_query("CALL getCreditPeriods()")) {
                $data['status'] = "error";
                Logger::getLogger('model_credit_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $res = $conn->store_result();
                if ($res) {//Записи найдены
                    $data['status'] = "ok";
                    $i = 0;
                    while ($row = $res->fetch_assoc()) {//Пишем все периоды по очереди
                        $data['items'][$i] = $row;
                        $i++;
                    }
                    $res->free();
                } else {//Store failed
                    if ($conn->errno) {
                        $data['status'] = "error";
                        Logger::getLogger('model_credit_logger')->error("Не удалось получить результат через store_result: (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $data;
    }

    public function getClientInfo($clientId) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $data['status'] = "error";
            Logger::getLogger('model_credit_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            if (!$conn->multi_query("CALL getClientById(" . $clientId . ")")) {
                $data['status'] = "error";
                Logger::getLogger('model_credit_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
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
                        Logger::getLogger('model_credit_logger')->error("Не удалось получить результат через store_result: (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $data;
    }

    public function addCreditApplication($appInfo) {
        $conn = new mysqli(Database::$dbservername, Database::$dbusername, Database::$dbpassword, Database::$dbname);
        $execStatus = "ok";
        if ($conn->connect_error) {//При подключении к базе возникли ошибки
            $execStatus = "error";
            Logger::getLogger('model_credit_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему

            $queryString = "CALL addCreditApplication("
                    . $appInfo['client_id'] . ","
                    . $appInfo['amount'] . ","
                    . $appInfo['period'] . ","
                    . $appInfo['rate'] . ","
                    . $appInfo['status_id'] . ",'"
                    . $appInfo['reason'] . "')";
            if (!$conn->multi_query($queryString)) {//При вызове процедуры произошла ошибка
                $execStatus = "error";
                Logger::getLogger('model_credit_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $execStatus = "ok";
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
            Logger::getLogger('model_credit_logger')->error("Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
        } else {//Соединение с базой успешно
            mysqli_select_db($conn, Database::$dbname); //Выбираем схему
            $queryString = "CALL cancelCreditApplication(" . $appInfo['client_id'] . ")";
            if (!$conn->multi_query($queryString)) {//При вызове процедуры произошла ошибка
                $execStatus = "error";
                Logger::getLogger('model_credit_logger')->error("Не удалось вызвать хранимую процедуру: (" . $conn->errno . ") " . $conn->error);
            } else {//Продцедура вызвана успешно
                $execStatus = "ok";
            }//Продцедура вызвана успешно
            $conn->close();
        }
        return $execStatus;
    }

    public function getExchangeRate($currencyCode) {//Возвращает курс валюты по заданному коду с RSS нацбанка
        $url = "http://www.nationalbank.kz/rss/rates.xml"; //В этом файле только USD,EUR,RUR
        $data = simplexml_load_file($url);
        if ($data) {//Данные загружены
            foreach ($data->channel->item as $item) {//Перебираем валюты
                if ($item->title == $currencyCode) {
                    return $item->description;
                }
            }//Перебираем валюты
        }//Данные загружены
    }

    public function requestCreditApproval($creditInfo) {//Здесь обращаемся к Java RESTful сервису скоринга
        $url = 'http://localhost:8080/CreditAppServices/webresources/scoring/requestCreditApproval';
        $curl = curl_init($url); // Открытие сеанса curl для выполнения вызова 
        curl_setopt($curl, CURLOPT_POST, true); // Сообщение curl, что нужно использовать HTTP POST       
        $parameters = array(// Подготовка аргументов POST для передачи серверу 
            'amount' => $creditInfo['amount'],
            'period' => $creditInfo['period'],
            'rate' => $creditInfo['rate'],
            'salary' => $creditInfo['salary'],
            'spendings' => $creditInfo['spendings']);

        $json = json_encode($parameters);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)));
        $response = curl_exec($curl); // Обращение к REST, получение результата
        curl_close($curl); // Закрытие сеанса
        $result = json_decode($response);
        if ($result->status == "ERROR") {//Сервис скоринга вернул ошибку
            //Пишем в лог
            Logger::getLogger('model_credit_logger')->error("Сервис скоринга вернул ошибку: (" . $result->errorMsg . ")");
        }

        return $result; //Преобразование результата из формата JSON в массив PHP 
    }

}
