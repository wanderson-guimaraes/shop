<?php
/* [DATABASE] */
// ! MUDE A STRING PARA SUA CONEXÃO!
define('DB_HOST', 'sql212.freesite.vip');
define('DB_NAME', 'frsiv_24906612_shop');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'frsiv_24906612');
define('DB_PASSWORD', 'senha');

/* [SEM NOTIFICAÇÕES] */
error_reporting(E_ALL & ~E_NOTICE);

/* [PATH] */
// Manualmente define o caminho absoluto se você 
//tiver problema com path
define('PATH_LIB', __DIR__ . DIRECTORY_SEPARATOR);

/* [INICIA SESSÃO] */
session_start();
if (!is_array($_SESSION['cart'])) { $_SESSION['cart'] = []; }
?>