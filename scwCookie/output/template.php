<?php
require_once __DIR__.'/../scwCookie.class.php';

$scwCookie = new ScwCookie\ScwCookie();
$scwCookie->setGACode('UA-111289840-1');
$scwCookie->output();
