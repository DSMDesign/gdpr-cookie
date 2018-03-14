<?php

namespace ScwCookie;

class ScwCookie
{
    private $config       = [];
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
            $return[$name] = 'allowed';
        }
        self::setCookie('scwCookie', self::encrypt($return), 52, 'weeks');
        return $return;
    }

    public static function encrypt($value)
    {
        $return = json_encode($value);
        return $return;
    }

    public static function decrypt($value)
    {
        $return = json_decode($value, true);
        return $return;
    }

    public function isAllowed($name)
    {
        $choices = $this->getChoices();
        return isset($choices[$name]) && $choices[$name] == 'allowed';
    }

    public function setGACode($code)
    {
        $this->gaCode = $code;
    }

    public function getGACode()
    {
        return $this->gaCode;
    }

    public function output()
    {
        echo $this->getOutput();
    }

    public function getOutput()
    {
        $return = [];

        // Get decision window output
        $return[] = $this->getOutputHTML('decision');

        // Get Google Analytics output
        if ($this->config['Google_Analytics']['enabled'] && $this->isAllowed('Google_Analytics')) {
            $return[] = $this->getOutputHTML('googleAnalytics');
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
        foreach ($this->config as $name => $value) {
            if (!is_array($value) || !isset($value['enabled']) || !$value['enabled']) {
                continue;
            }
            $return[$name] = $value['label'];
        }
        return $return;
    }

    public static function setCookie($name, $value, $lifetime = 30, $lifetimePeriod = 'days', $domain = '/', $secure = false)
    {
        // Set allowed time periods
        $lifetimePeriods = array('minutes', 'hours', 'days', 'weeks');

        // Check values passed
        $validParams = is_string($name);
        $validParams = is_string($value) ? $validParams : false;
        $validParams = is_int($lifetime) ? $validParams : false;
        $validParams = in_array($lifetimePeriod, $lifetimePeriods) ? $validParams : false;
        $validParams = is_string($domain) ? $validParams : false;
        $validParams = is_bool($secure) ? $validParams : false;

        // If correct values are passed
        if ($validParams) {
            // Calculate expiry time
            switch ($lifetimePeriod) {
                case 'minute':
                case 'minutes':
                    $expiry = time() + (60 * $lifetime);        // 60 = 1 minute
                    break;

                case 'hour':
                case 'hours':
                    $expiry = time() + (3600 * $lifetime);      // 3600 = 1 hour
                    break;

                case 'day':
                case 'days':
                    $expiry = time() + (86400 * $lifetime);     // 86400 = 1 day
                    break;

                case 'week':
                case 'weeks':
                    $expiry = time() + (604800 * $lifetime);    // 604800 = 1 week
                    break;
                
                default:
                    header('HTTP/1.0 403 Forbidden');
                    throw new Exception("Lifetime not recognised");
                    break;
            }

            // Set cookie
            return setcookie($name, $value, $expiry, $domain, $secure);
        } else {
            // Failed parameter check
            header('HTTP/1.0 403 Forbidden');
            throw new Exception("Incorrect parameter passed to Cookie::set");
        }
    }

    public static function getCookie($name)
    {
        // If cookie exists - return it, otherwise return false
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    public static function destroyCookie($name)
    {
        // Set cookie expiration to 1 hour ago
        return setcookie($name, '', time() - 3600);
    }
}
