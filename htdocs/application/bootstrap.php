<?php

// подключаем файлы ядра
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

/*
Здесь обычно подключаются дополнительные модули, реализующие различный функционал:
	> аутентификацию
	> кеширование
	> работу с формами
	> абстракции для доступа к данным
	> ORM
	> Unit тестирование
	> Benchmarking
	> Работу с изображениями
	> Backup
	> и др.
*/
require_once 'database.php';//Класс с параметрами подключения к БД

require_once 'include/log4php/Logger.php';//Подключаем логгер Apache log4php
Logger::configure("logs/config.xml");//Грузим настройки логгера из xml-файла

require_once 'core/route.php';
Route::start(); // запускаем маршрутизатор
