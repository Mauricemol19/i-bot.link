<?php

require("includes/settings.php");

require("classes/database.php");
require("classes/session.php");
require("classes/core.php");
require("classes/url.php");

require("includes/simplehtmldom/simple_html_dom.php");

/*
require("phpmailer/phpmailer/src/PHPMailer.php");
require("phpmailer/phpmailer/src/SMTP.php");
require("phpmailer/phpmailer/src/Exception.php");
require("classes/mail_handler.php");
*/

$db = new Database;
$session = new Session;
$core = new Core;
$url = new Url;
//$email_h = new mail_handler;

$get = $url->getGET();

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">

    <link rel="icon" href="" type="image/png"/>
    <link rel="shortcut icon" href="" type="image/png"/>

    <title>Inori's Warbot</title>

    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/style.css" rel="stylesheet">
</head>
<body>
