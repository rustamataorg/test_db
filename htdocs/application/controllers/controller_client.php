<?php

class Controller_Client extends Controller {

    function __construct() {
        $this->model = new Model_Client();
        $this->view = new View();
    }

    function action_index() {
        if ($this->model->isUserAuthorized()) {//Пользователь авторизован
            $this->model->updateLastActivityTime();
            $currentClient = $this->model->getCurrentClient();
            if ($currentClient) {//Пользователь в базе уже есть
                $clientInfo = $this->model->getClientInfo($currentClient); //Грузим информацию по клиенту из БД
                $this->view->generate('client_view.php', 'template_view.php', $clientInfo); //Грузим карточку клиента и заполняем поля из БД
            } else {//Информации о пользователе в БД нет
                $this->view->generate('client_view.php', 'template_view.php'); //Грузим пустую карточку клиента
            }
        } else {//Пользователь не авторизован
            $this->model->logout(); //Выходим из системы
        }
    }

    function action_submit() {//Отправка формы пользователем
        $data = $_POST;
        if (isset($_POST['clientConfirm'])) {//Нажата кнопка "Подтвердить"
            $validationResults = $this->model->validateIIN($data['iin']);
            if ($validationResults->status == "SUCCESS") {//Запрос успешно отработал
                if ($validationResults->validation == "VALID") {//ИИН введён корректно
                    $currentClient = $this->model->getCurrentClient();
                    if ($currentClient) {//Пользователь в базе уже есть
                        $data['id'] = $currentClient;
                        if ($this->model->updateClientInfo($data) == 'error') {//Обновляем информацию по клиенту
                            echo 'Ошибка при работе с базой данных, обратитесь к администратору';
                        } else {//Данные по клиенту в базе успешно обновились
                            header('Location:/credit/'); //Грузим форму заявки на кредит
                        }
                    } else {//Пользователя в базе нету
                        $execStatus = $this->model->addClient($data); //Добавляем нового пользователя
                        if ($execStatus['status'] == "ok") {//Новый пользователь был успешно добавлен
                            $this->model->setCurrentClient($execStatus['user_id']); //Сохраняем id пользователя
                            header('Location:/credit/'); //Грузим форму заявки на кредит
                        } elseif ($execStatus['status'] == "error") {
                            echo 'Ошибка при работе с базой данных, обратитесь к администратору';
                        }
                    }
                } else if ($validationResults->validation == "INVALID") {//ИИН введён неправильно
                    $clientInfo['status'] = 'client_found'; //Для заполнения полей карточки из $_POST (чтобы повторно БД не дёргать)
                    $clientInfo['client'] = $_POST;
                    $clientInfo['error'] = "incorrect_iin";
                    $this->view->generate('client_view.php', 'template_view.php', $clientInfo);
                }
            } elseif ($validationResults->status == "SUCCESS") {
                echo 'Сервер проверки ИИН вернул ошибку. Обратитесь к администратору';
            }
        } elseif (isset($_POST['clientCancel'])) {//Нажата кнопка "Отмена"
            $currentClient = $this->model->getCurrentClient();
            if ($currentClient) {//Пользователь в базе уже есть, отменяем заявку
                $appInfo['client_id'] = $currentClient;
                $this->model->unsetCurrentClient(); //Затираем текущего клиента из сессии
                $execStatus = $this->model->cancelCreditApplication($appInfo); //Добавляем заявку в БД со статусом "Отказано"
                if ($execStatus == "ok") {//Заявка отменена успешно
                    header('Location:/main/'); //Переадресация на главную страницу
                } elseif ($execStatus == "error") {
                    echo 'Ошибка при работе с базой данных. Обратитесь к администратору';
                }
            } else {//Пользователя в базе нет, сохранять нечего
                header('Location:/main/'); //Переадресация на главную страницу
            }
        }
    }

}
