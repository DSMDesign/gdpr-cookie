<?php

namespace ScwCookie;

class ScwCookie
{
    public $config        = [];
    private $decisionMade = false;
    private $choices      = [];

    public function __construct()
    {
        $this->config = parse_ini_file("config.ini", true);

        $this->decisionMade = self::getCookie('scwCookieHidden') == 'true';
        $this->choices      = $this->getChoices();
    }

    public function getChoices()
    {
        if (self::getCookie('scwCookie') !== false) {
            $cookie = self::getCookie('scwCookie');
            $cookie = self::decrypt($cookie);
            return $cookie;
        }

        $return = [];
        foreach ($this->enabledCookies() as $name => $label) {
            $return[$name] = $this->config['unsetDefault'];
        }
        return $return;
    }

    public static function encrypt($value)
    {
        $return = json_encode($value);
        return $return;
    }

    public static function decrypt($value)
    {
        $value = str_replace('\"', '"', $value);
        $return = json_decode($value, true);
        return $return;
    }

    public function isAllowed($name)
    {
        $choices = $this->getChoices();
        return isset($choices[$name]) && $choices[$name] == 'allowed';
    }

    public function isEnabled($name)
    {
        $check = $this->config[$name];
        return is_array($check) && isset($check['enabled']) && $check['enabled'];
    }

    public function getConfig($name, $attribute)
    {
        return isset($this->config[$name]) && isset($this->config[$name][$attribute])
        ? $this->config[$name][$attribute]
        : false;
    }

    public function output()
    {
        echo $this->getOutput();
    }

    public function getOutput()
    {
        $return = [];

        // Get popup output
        $return[] = $this->getOutputHTML('popup');

        // Get embed codes
        foreach ($this->config as $configKey => $configValue) {
            if (!is_array($configValue) || !$configValue['enabled'] || !$this->isAllowed($configKey)) {
                continue;
            }
            $return[] = $this->getOutputHTML('/cookies/'.$configKey.'/output');
        }

        return implode("\n", $return);
    }

    public function getOutputHTML($filename)
    {
        if (!file_exists(__DIR__.'/output/'.$filename.'.php')) {
            return false;
        }

        ob_start();
        include __DIR__.'/output/'.$filename.'.php';
        return trim(ob_get_clean());
    }

    public function enabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {
            if (!$this->isEnabled($name)) {
                continue;
            }
            $return[$name] = $value['label'];
        }
        return $return;
    }

    public function disabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {
            if (!$this->isEnabled($name) || !is_array($value) || $this->isAllowed($name)) {
                continue;
            }
            $return[$name] = $value['label'];
        }
        return $return;
    }

    public static function setCookie(
        $name,
        $value,
        $lifetime = 30,
        $lifetimePeriod = 'days',
        $domain = '/',
        $secure = false
    ) {
        // Validate parameters
        self::validateSetCookieParams($name, $value, $lifetime, $domain, $secure);

        // Calculate expiry
        $expiry = strtotime('+'.$lifetime.' '.$lifetimePeriod);

        // Set cookie
        return setcookie($name, $value, $expiry, $domain, $secure);
    }

    public static function validateSetCookieParams($name, $value, $lifetime, $domain, $secure)
    {
        // Types of parameters to check
        $paramTypes = [
            // Type => Array of variables
            'string' => [$name, $value, $domain],
            'int'    => [$lifetime],
            'bool'   => [$secure],
        ];

        // Validate basic parameters
        $validParams = self::basicValidationChecks($paramTypes);

        // Ensure parameters are still valid
        if (!$validParams) {
            // Failed parameter check
            header('HTTP/1.0 403 Forbidden');
            throw new \Exception("Incorrect parameter passed to Cookie::set");
        }

        return true;
    }

    public static function basicValidationChecks($paramTypes)
    {
        foreach ($paramTypes as $type => $variables) {
            $functionName = 'is_'.$type;
            foreach ($variables as $variable) {
                if (!$functionName($variable)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function clearCookieGroup($groupName)
    {
        if (!file_exists(__DIR__.'/output/cookies/'.$groupName.'/cookies.php')) {
            return false;
        }
        $clearCookies = include __DIR__.'/output/cookies/'.$groupName.'/cookies.php';

        $defaults = [
            'path'   => '/',
            'domain' => $_SERVER['HTTP_HOST'],
        ];

        if (isset($clearCookies['defaults'])) {
            $defaults = array_merge($defaults, $clearCookies['defaults']);
            unset($clearCookies['defaults']);
        }

        $return = [];

        foreach ($clearCookies as $cookie) {
            $cookie['path'] = isset($cookie['path']) ? $cookie['path'] : $defaults['path'];
            $cookie['domain'] = isset($cookie['domain']) ? $cookie['domain'] : $defaults['domain'];
            self::destroyCookie($cookie['name'], $cookie['path'], $cookie['domain']);
            $return[] = $cookie;
        }

        return $return;
    }

    public static function getCookie($name)
    {
        // If cookie exists - return it, otherwise return false
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    public static function destroyCookie($name, $path = '', $domain = '')
    {
        // Set cookie expiration to 1 hour ago
        return setcookie($name, '', time() - 3600, $path, $domain);
    }
}
