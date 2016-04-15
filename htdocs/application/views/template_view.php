<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <title>Main page template</title>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Kreon" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <script src="../../js/jquery-1.12.3.js" type="text/javascript"></script>
        <script src="../../js/jquery.maskedinput.js" type="text/javascript"></script>
        <script src="../../js/validateFormData.js"></script>
        <script src="../../js/jquery.price_format.2.0.js"></script>
    </head>
    <body>
        <div id="wrapper">
            <!--div id="header">
                <div id="logo">
                    <a href="/">kaspi</span> <span class="cms">bank</span></a>
                </div>
                <!--div id="menu">
                    <ul>
                        <li class="first active"><a href="/">Главная</a></li>
                    </ul>
                    <br class="clearfix" />
                </div>
            </div-->
            <div id="page">
                <!--div id="sidebar">
                    <div class="side-box">
                        <h3>Текст</h3>
                        <p align="justify" class="quote">
Ещё текст
                        </p>
                    </div>
                    <div class="side-box">
                        <h3>Основное меню</h3>
                        <ul class="list">
                            <li class="first "><a href="/">Главная</a></li>
                        </ul>
                    </div>
                </div-->
                <div id="content">
                    <div class="box">
<?php include 'application/views/' . $content_view; ?>
                        <!--
                        <h2>Welcome to </h2>
                        <img class="alignleft" src="images/pic01.jpg" width="200" height="180" alt="" />
                        <p>
                                This is text
                        </p>
                        -->
                    </div>
                    <br class="clearfix" />
                </div>
                <br class="clearfix" />
            </div>
            <!--div id="page-bottom">
                <div id="page-bottom-sidebar">
                    <h3>Текст page-bottom-sidebar</h3>
                    <ul class="list">
                        <li class="last">email: info@kaspibank.kz</li>
                    </ul>
                </div>
                <div id="page-bottom-content">
                    <h3>page-bottom-content</h3>
                </div>
                <br class="clearfix" />
            </div-->
        </div>
        <!--div id="footer">
            <a href="http://kaspibank.kz">kaspi bank</a> &copy; 2016</a>
        </div-->
    </body>
</html>