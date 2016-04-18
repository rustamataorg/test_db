<?php

class Controller_Main extends Controller {

    function __construct() {
        $this->model = new Model_Main();
        $this->view = new View();
    }

    function action_index() {
        if ($this->model->isUserAuthorized()) {//Пользователь авторизован
            if (!$this->model->checkTimeOutExpired()) {//Тайм-аут не истёк
                $this->model->updateLastActivityTime();
                $this->view->generate('main_view.php', 'template_view.php'); //Грузим главную форму
            } else {//Истёк тайм-аут сессии пользователя
                $this->model->logout(); //Выходим из системы
            }
        } else {//Пользователь не авторизован
            $this->model->logout(); //Выходим из системы
        }
    }

    //При нажатии кнопки поиска
    function action_search() {
        if (!$this->model->checkTimeOutExpired()) {//Тайм-аут не истёк
            $this->model->updateLastActivityTime();
            if (filter_input(INPUT_POST, 'client_iin') != NULL && filter_input(INPUT_POST, 'client_iin') != "") {//ИИН в форме поиска задан
                $data = $this->model->getClientCreditApplications(filter_input(INPUT_POST, 'client_iin'));
                if ($data['applications_number'] > 0) {//У клиента уже существуют заявки
                    $this->model->setCurrentClient($data['client']['id']); //Запоминаем id клиента в сессии для выполнения дальнейших запросов по id
                    $this->view->generate('main_view.php', 'template_view.php', $data); //Загружаем заявки в таблицу
                } else {//У клиента заявок нет (следовательно и клиента такого нет)
                    $this->model->unsetCurrentClient();
                    header('Location:/client/'); //Запускаем карточку клиента
                }
            } else {//ИИН в форме поиска не задан
                $this->model->unsetCurrentClient();
            }
        } else {//Истёк тайм-аут сессии пользователя
            $this->model->logout(); //Выходим из системы
        }
    }

    function action_submit() {
        if (!$this->model->checkTimeOutExpired()) {//Тайм-аут не истёк
            if (isset($_POST['main_create'])) {//Нажали кнопку "Новая заявка"
                $this->model->updateLastActivityTime();
                header('Location:/client/'); //Запускаем карточку клиента
            } elseif (isset($_POST['main_exit'])) {//Нажали кнопку "Выход"
                $this->model->logout(); //Выходим из системы
            }
        } else {//Истёк тайм-аут сессии пользователя
            $this->model->logout(); //Выходим из системы
        }
    }

}