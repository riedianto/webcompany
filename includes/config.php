<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $requestedLang = (string)$_GET['lang'];
    if (in_array($requestedLang, ['id', 'en'], true)) {
        $_SESSION['lang'] = $requestedLang;
        setcookie('lang', $requestedLang, time() + 2592000, '/');
    }
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'webcompany');
define('DB_USER', 'root');
define('DB_PASS', '');

define('APP_ROOT', dirname(__DIR__));
define('UPLOAD_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$GLOBALS['LANG_ID'] = require APP_ROOT . '/lang/id.php';
$GLOBALS['LANG_EN'] = require APP_ROOT . '/lang/en.php';
$GLOBALS['LANG'] = lang_code() === 'en' ? $GLOBALS['LANG_EN'] : $GLOBALS['LANG_ID'];
