<?php

class Controller_Main extends Controller {

    function __construct() {
        $this->model = new Model_Main();
        $this->view = new View();
    }

    function action_index() {
        session_start();
        /*
          Пока для простоты используется id пользователя и сессия, не самый безопасный метод.
          В случае необходимости могу доработать контроль сессий с использованием БД, хэшей и т.п.
         */
        if (isset($_SESSION['id'])) {//Пользователь авторизован
            if (isset($_SESSION['last_activity_time'])) {//Время последнего запроса было сохранено ранее
                if (time() - $_SESSION['last_activity_time'] > 15/* минут */* 60/* секунд */) {//Таймаут превышен
                    $this->model->logout(); //Выходим из системы
                }
            }
            $this->view->generate('main_view.php', 'template_view.php');
        } else {//Пользователь не авторизован
            session_destroy();
            header('Location:/'); //Идём в корень
        }
    }

    //При нажатии кнопки поиска
    function action_search() {
        session_start();
        if (filter_input(INPUT_POST, 'client_iin') != NULL && filter_input(INPUT_POST, 'client_iin') != "") {//ИИН в форме поиска задан
            $data = $this->model->getClientCreditApplications(filter_input(INPUT_POST, 'client_iin'));
            if ($data['applications_number'] > 0) {//У клиента уже существуют заявки
                $_SESSION['current_client_id'] = $data['client']['id']; //Запоминаем id клиента в сессии для выполнения дальнейших запросов по id
                //Отображаем заявки на форме поиска
                $this->view->generate('main_view.php', 'template_view.php', $data); //Загружаем заявки в таблицу
            } else {//У клиента заявок нет (следовательно и клиента такого нет)
                if (isset($_SESSION['current_client_id'])) {//Стираем $_SESSION['current_client_id']
                    unset($_SESSION['current_client_id']);
                }
                header('Location:/client/'); //Запускаем карточку клиента
            }
        } else {//ИИН в форме поиска не задан
            if (isset($_SESSION['current_client_id'])) {//Стираем $_SESSION['current_client_id']
                unset($_SESSION['current_client_id']);
            }
        }
    }

    function action_submit() {
        if (isset($_POST['main_create'])) {//Нажали кнопку "Новая заявка"
            header('Location:/client/'); //Запускаем карточку клиента
        } elseif (isset($_POST['main_exit'])) {//Нажали кнопку "Выход"
            $this->model->logout(); //Выходим из системы
        }
    }

}
