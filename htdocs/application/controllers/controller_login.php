<?php

class Controller_Login extends Controller {

    function __construct() {
        $this->model = new Model_Login();
        $this->view = new View();
    }

    function action_index() {
        if (filter_input(INPUT_POST, 'user_login') != NULL && filter_input(INPUT_POST, 'user_password') != NULL) {//Логин и пароль заданы на форме авторизации
            $auth = $this->model->getAuthorizationStatus(filter_input(INPUT_POST, 'user_login'), filter_input(INPUT_POST, 'user_password'));

            if ($auth['status'] == "access_granted") {
                $this->model->setUserAuthorized($auth['user_id']); //Сохраняем id авторизованного пользователя системы
                $this->model->updateLastActivityTime(); //Сохраняем время последнего запроса
                header('Location:/main/'); //Переходим к главной форме поиска
            } else {
                $this->view->generate('login_view.php', 'template_view.php', $auth); //Генерируем форму авторизации и передаём данные об ошибке входа
            }
        } else {//Логин или пароль не указаны на форме авторизации (новый пользователь ещё не авторизовался)
            $auth["status"] = "";
            $this->view->generate('login_view.php', 'template_view.php', $auth); //Генерируем форму авторизации
        }
    }

}
