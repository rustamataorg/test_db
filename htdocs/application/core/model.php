<?php

class Model {
    /*
      Модель обычно включает методы выборки данных, это могут быть:
      > методы нативных библиотек pgsql или mysql;
      > методы библиотек, реализующих абстракицю данных. Например, методы библиотеки PEAR MDB2;
      > методы ORM;
      > методы для работы с NoSQL;
      > и др.
     */

    // метод выборки данных
    public function get_data() {
        // todo
    }

    function logout() {
        if (!session_id()) {
            session_start();
        }
        session_destroy(); //Убиваем сессию
        header('Location:/login'); //Идём на форму авторизацию
    }

    function isUserAuthorized() {
        /*
          Пока для простоты используется id пользователя и сессия, не самый безопасный метод.
          В случае необходимости могу доработать контроль сессий с использованием БД, хэшей и т.п.
         */

        if (!session_id()) {
            session_start();
        }
        if (isset($_SESSION['id'])) {//Пользователь авторизован
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function checkTimeOutExpired() {
        if (!session_id()) {
            session_start();
        }
        if (isset($_SESSION['last_activity_time'])) {//Время последнего запроса было сохранено ранее
            if (time() - $_SESSION['last_activity_time'] > 15/* минут */ * 60/* секунд */) {//Таймаут превышен
                $this->logout(); //Выходим из системы
                return TRUE;
            } else {//Тайм-аут не истёк
                $this->updateLastActivityTime(); //Обновляем время последнего действия
                return FALSE;
            }
        }
    }

    function updateLastActivityTime() {
        if (!session_id()) {
            session_start();
        }
        $_SESSION['last_activity_time'] = time(); //Обновляем время последнего запроса
    }

    function setCurrentClient($currentClient) {
        if (!session_id()) {
            session_start();
        }
        $_SESSION['current_client_id'] = $currentClient; //Запоминаем id клиента в сессии для выполнения дальнейших запросов по id
    }

    function getCurrentClient() {
        if (!session_id()) {
            session_start();
        }
        if (isset($_SESSION['current_client_id'])) {
            return $_SESSION['current_client_id'];
        } else {//Текущий клиент не выбран
            return 0;
        }
    }

    function unsetCurrentClient() {
        if (!session_id()) {
            session_start();
        }
        if (isset($_SESSION['current_client_id'])) {//Стираем $_SESSION['current_client_id']
            unset($_SESSION['current_client_id']);
        }
    }

}
