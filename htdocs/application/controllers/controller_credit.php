<?php

class Controller_Credit extends Controller {

    function __construct() {
        $this->model = new Model_Credit();
        $this->view = new View();
    }

    function action_index() {
        if ($this->model->isUserAuthorized()) {//Пользователь авторизован
            $this->model->updateLastActivityTime();
            $currentClient = $this->model->getCurrentClient();
            if ($currentClient) {//Пользователь в базе уже есть
                $periods = $this->model->getCreditPeriods();
                if ($periods['status'] == "ok") {//Данные по периодам получены успешно
                    $data['USD'] = $this->model->getExchangeRate("USD");
                    $data['periods'] = $periods['items'];
                    $this->view->generate('credit_view.php', 'template_view.php', $data); //Грузим кредитную форму
                }
            } else {//Нет информации по текущему выбранному клиенту, но на форму кредита мы как-то попали
                echo 'Сбой в работе приложения, текущий клиент неизвестен. Обратитесь к администратору';
            }
        } else {//Пользователь не авторизован
            $this->model->logout(); //Выходим из системы
        }
    }

    function action_confirmCredit() {
        if (isset($_POST['creditConfirm'])) {//Нажата кнопка "Подтвердить"
            $scoringParams['amount'] = $_POST['amount'];
            $scoringParams['period'] = $_POST['period'];
            $scoringParams['rate'] = $_POST['rate'];
            $currentClient = $this->model->getCurrentClient();
            $clientData = $this->model->getClientInfo($currentClient);
            $scoringParams['spendings'] = $clientData['client']['spendings'];
            $scoringParams['salary'] = $clientData['client']['salary'];
            $appInfo = $scoringParams; //Информация о заявке для добавления в БД
            $appInfo['client_id'] = $currentClient;
            $this->model->unsetCurrentClient(); //Затираем текущего клиента из сессии
            //Запрашиваем одобрение кредита в скоринге
            $scoringResults = $this->model->requestCreditApproval($scoringParams);
            $appInfo['reason'] = $scoringResults->reason; //Причина отказа по результатам скоринга
            if ($scoringResults->status == "SUCCESS") {//Запрос успешно отработал
                if ($scoringResults->result == "APPROVED") {//Кредит одобрен
                    $appInfo['status_id'] = "1"; //Статус "Одобрено" в базе
                    $execStatus = $this->model->addCreditApplication($appInfo); //Добавляем заявку в БД со статусом "Одобрено"
                    if ($execStatus == "ok") {//Заявка добавлена успешно
                        header('Location:/approved/'); //Переадресация на страницу со статусом "Одобрено"
                    } elseif ($execStatus == "error") {
                        echo 'Ошибка при работе с базой данных. Обратитесь к администратору';
                    }
                } else if ($scoringResults->result == "REJECTED") {//В выдаче кредита отказано
                    $appInfo['status_id'] = "0"; //Статус "Отказано" в базе
                    $execStatus = $this->model->addCreditApplication($appInfo); //Добавляем заявку в БД со статусом "Отказано"
                    if ($execStatus == "ok") {//Заявка добавлена успешно
                        header('Location:/rejected/'); //Переадресация на страницу со статусом "Отказано"
                    } elseif ($execStatus == "error") {
                        echo 'Ошибка при работе с базой данных. Обратитесь к администратору';
                    }
                }
            } else if ($scoringResults->status == "ERROR") {//Сервис вернул ошибку
                echo 'Сервер скоринга вернул ошибку. Обратитесь к администратору';
            }
        } elseif (isset($_POST['creditCancel'])) {//Нажата кнопка "Отмена"
            $currentClient = $this->model->getCurrentClient();
            if ($currentClient) {//Пользователь в базе есть, отменяем заявку
                $appInfo['client_id'] = $currentClient;
                $this->model->unsetCurrentClient();
                $execStatus = $this->model->cancelCreditApplication($appInfo); //Добавляем заявку в БД со статусом "Отказано"
                if ($execStatus == "ok") {//Заявка отменена успешно
                    header('Location:/main/'); //Переадресация на главную страницу
                } elseif ($execStatus == "error") {
                    echo 'Ошибка при работе с базой данных. Обратитесь к администратору';
                }
            } else {//Пользователя в базе нет
                echo 'Ошибка: не указан текущий пользователь. Обратитесь к администратору';
            }
        }
    }

}
