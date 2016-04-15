<?php

class Controller_Approved extends Controller {

    function __construct() {
        #$this->model = new Model_Approved();
        $this->view = new View();
    }

    function action_index() {
        $this->view->generate('approved_view.php', 'template_view.php');
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

