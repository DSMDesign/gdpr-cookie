<?php
require_once('scwCookie.class.php');

if (!isset($_POST['action'])) {
    header('HTTP/1.0 403 Forbidden');
    throw new Exception("Action not specified");
}

switch ($_POST['action']) {
    case 'hide':
        // Set cookie
        ScwCookie\ScwCookie::setCookie('scwCookieHidden', 'true', 52, 'weeks');
        header('Content-Type: application/json');
        die(json_encode(['success' => true]));
        break;

    case 'toggle':
        // Update if cookie allowed or not
        $choices = ScwCookie\ScwCookie::getCookie('scwCookie');
        $choices = ScwCookie\ScwCookie::decrypt($choices);
        $choices[$_POST['name']] = $_POST['value'] == 'true' ? 'allowed' : 'blocked';
        $choices = ScwCookie\ScwCookie::encrypt($choices);
        ScwCookie\ScwCookie::setCookie('scwCookie', $choices, 52, 'weeks');
        echo '<hr><pre>';
        print_r($choices);
        echo '</pre><hr>';
        break;

    default:
        header('HTTP/1.0 403 Forbidden');
        throw new Exception("Action not recognised");
        break;
}
