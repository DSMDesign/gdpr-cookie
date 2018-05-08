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
        $scwCookie = new ScwCookie\ScwCookie();
        $return    = [];

        // Update if cookie allowed or not
        $choices = $scwCookie->getCookie('scwCookie');
        if ($choices == false) {
            $choices = [];
            $enabledCookies = $scwCookie->enabledCookies();
            foreach ($enabledCookies as $name => $label) {
                $choices[$name] = $scwCookie->config['unsetDefault'];
            }
            $scwCookie->setCookie('scwCookie', $scwCookie->encrypt($choices), 52, 'weeks');
        } else {
            $choices = $scwCookie->decrypt($choices);
        }
        $choices[$_POST['name']] = $_POST['value'] == 'true' ? 'allowed' : 'blocked';

        // Remove cookies if now disabled
        if ($choices[$_POST['name']] == 'blocked') {
            $removeCookies = $scwCookie->clearCookieGroup($_POST['name']);
            $return['removeCookies'] = $removeCookies;
        }

        $choices = $scwCookie->encrypt($choices);
        $scwCookie->setCookie('scwCookie', $choices, 52, 'weeks');

        header('Content-Type: application/json');
        die(json_encode($return));
        break;

    case 'load':
        $scwCookie = new ScwCookie\ScwCookie();
        $return    = [];

        $removeCookies = [];

        foreach ($scwCookie->disabledCookies() as $cookie => $label) {
            $removeCookies = array_merge($removeCookies, $scwCookie->clearCookieGroup($cookie));
        }
        $return['removeCookies'] = $removeCookies;

        header('Content-Type: application/json');
        die(json_encode($return));
        break;

    default:
        header('HTTP/1.0 403 Forbidden');
        throw new Exception("Action not recognised");
        break;
}
