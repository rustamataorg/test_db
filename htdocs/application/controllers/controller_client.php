<?php

class Controller_Client extends Controller {

    function __construct() {
        $this->model = new Model_Client();
        $this->view = new View();
    }

    function action_index() {
        session_start();
        if (isset($_SESSION['current_client_id'])) {//Пользователь в базе уже есть
            //Грузим информацию по клиенту из БД
            $clientInfo = $this->model->getClientInfo($_SESSION['current_client_id']);
            //Грузим карточку клиента и заполняем поля из БД
            $this->view->generate('client_view.php', 'template_view.php', $clientInfo);
        } else {//Информации о пользователе в БД нет
            //Грузим пустую карточку клиента
            $this->view->generate('client_view.php', 'template_view.php');
        }
    }

    function action_submit() {//Отправка формы пользователем
        session_start();
        $data = $_POST;

        if (isset($_POST['clientConfirm'])) {//Нажата кнопка "Подтвердить"
            if ($this->model->validateIIN($data['iin']) == "true") {//ИИН введён корректно
                if (isset($_SESSION['current_client_id'])) {//Пользователь в базе уже есть
                    $data['id'] = $_SESSION['current_client_id'];
                    if ($this->model->updateClientInfo($data) == 'error') {//Обновляем информацию по клиенту
                        echo 'Ошибка при работе с базой данных, обратитесь к администратору';
                    } else {//Данные по клиенту в базе успешно обновились
                        header('Location:/credit/'); //Грузим форму заявки на кредит
                    }
                } else {//Пользователя в базе нету
                    $execStatus = $this->model->addClient($data); //Добавляем нового пользователя
                    if ($execStatus['status'] == "ok") {//Новый пользователь был успешно добавлен
                        $_SESSION['current_client_id'] = $execStatus['user_id']; //Сохраняем id пользователя
                        header('Location:/credit/'); //Грузим форму заявки на кредит
                    } elseif ($execStatus['status'] == "error") {
                        echo 'Ошибка при работе с базой данных, обратитесь к администратору';
                    }
                }
            } else {//ИИН введён неправильно
                $clientInfo['status'] = 'client_found'; //Для заполнения полей карточки из $_POST (чтобы повторно БД не дёргать)
                $clientInfo['client'] = $_POST;
                $clientInfo['error'] = "incorrect_iin";
                $this->view->generate('client_view.php', 'template_view.php', $clientInfo);
            }
        } elseif (isset($_POST['clientCancel'])) {//Нажата кнопка "Отмена"
            if (isset($_SESSION['current_client_id'])) {//Пользователь в базе уже есть, отменяем заявку
                $appInfo['client_id'] = $_SESSION['current_client_id'];
                $this->model->resetCurrentClient();//Затираем текущего клиента из сессии
                $execStatus = $this->model->cancelCreditApplication($appInfo); //Добавляем заявку в БД со статусом "Отказано"
                if ($execStatus == "ok") {//Заявка отменена успешно
                    header('Location:/main/'); //Переадресация на главную страницу
                } elseif ($execStatus == "error") {
                    echo 'Ошибка при работе с базой данных. Обратитесь к администратору';        
                }
                unset($_SESSION['current_client_id']);
            } else {//Пользователя в базе нет, сохранять нечего
                header('Location:/main/'); //Переадресация на главную страницу
            }
        }
    }

}
