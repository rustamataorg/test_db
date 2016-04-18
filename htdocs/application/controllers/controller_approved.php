<?php

class Controller_Approved extends Controller {

    function __construct() {
        $this->model = new Model_Approved();
        $this->view = new View();
    }

    function action_index() {
        if ($this->model->isUserAuthorized()) {//Пользователь авторизован
            $this->view->generate('approved_view.php', 'template_view.php');
        } else {//Пользователь не авторизован
            $this->model->logout(); //Выходим из системы
        }
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

