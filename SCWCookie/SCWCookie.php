<?php
// Class to create a cookie policy for GDPR compliance.
// This includes preventing access to Analytics and Tawk.to Chat
// This does not include essential Cart/login session cookies.
// Usage Instructions
// require (SCWCookie/SCWCookie);
// $cookie = new SCWCookie();
// $cookie->cookieWarning(PARAMETERS);

class SCWCookie
{
    public function cookieWarning($data = [])
    {
        extract($data);

        // check for a post
        if ($_POST['allow']) {
            $this->setCookie($_POST['allow']);
            header('location:'. $_SERVER['HTTP_REFERER']);
        }

        // check for a destroy post
        if ($_POST['removeCookie']) {
            $this->destroyCookie();
        }

        // set cookie
        $cookie = $this->checkCookie();
        
        // check if cookie is set
        if (!$cookie) {
            $this->displayWarning($policy);
        } else {
            // if cookies are permitted
            if ($cookie == 'allowed') {
                // add google analytics
                if ($analytics != '') {
                    $this->analytics($analytics);
                }
                // add tawk.to
                if ($tawkto != '') {
                    $this->tawkTo($tawkto);
                }
            } else {
                // if not do not display any
                return false;
            }
        }
    }

    /**
     * Function to display the cookie warning bar
     * @param  string $policy If policy page is sent display link
     */
    public function displayWarning($policy = '')
    {
        ?>
        <style>
            .cookieWarning {
                position: fixed; z-index: 999999; bottom: 0; background: #2D3436; color: white; width: 100%; padding: 10px; line-height: 30px; font-size: 15px;
            }
            .no {
                float: right; background: #E55039; height: 34px; padding: 0 20px; color: white; margin: 0; cursor: pointer; border: none;
            }
            .yes {
                float: right; background: #20BF6B; height: 34px; padding: 0 20px; color: white; margin: 0 10px 0 0; cursor: pointer; border: none;
            }
            .policy {
                display: inline-block; background: #F39C12; height: 34px; padding: 0 20px; color: white; margin: 0 10px 0 0; cursor: pointer; border: none;
            }
            .no:hover,
            .yes:hover,
            .policy:hover {
                color: white;
            }
            .no:hover {background: #D1422C;}
            .yes:hover {background: #1CB162;}
            .policy:hover {background: #E2900F;}
        </style>
        <div class="cookieWarning animated slideInUp">
            We use cookies to track usage on our site, allow?
            <form method="post" action="" class="pull-right">
                <?= $policy != '' ?
                    '<a href="'.$policy.'" class="policy">Cookie Policy</a>'
                    : false;
                ?>
                <button type="submit" name="allow" value="not allowed" class="no">No</button>
                <button type="submit" name="allow" value="allowed" class="yes">Yes</button>
            </form>
        </div>
        <?php
    }

    /**
     * Function to place google analytics javascript on page
     * @param  string $analytics The sites site google analytics id
     */
    public function analytics($analytics = '')
    {
        ?>
        <!-- Google Analytics -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '<?= $analytics; ?>', 'auto');
            ga('send', 'pageview');
        </script>
        <?php
    }

    /**
     * Function to display Tawk.to Live Chat
     * @param  string $tawkto Tawk.to id for live chat window
     */
    public function tawkTo($tawkto = '')
    {
        ?>
        <!-- Tawk To -->
        <script type="text/javascript">
            var Tawk_API = Tawk_API||{}, Tawk_LoadStart = new Date();
            (function(){
                var s1 = document.createElement("script"),s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/<?= $tawkto; ?>/default';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
        <?php
    }

    /**
     * Function to display HotJar Web Tracking
     * @param  string $hotjar Hotjar Id
     */
    public function hotJar($hotjar = '')
    {
        ?>
        <!-- HotJar Tracking -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:<?= $hotjar; ?>,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
        <?php
    }

    /**
     * Function to check if cookie has been set or not
     * @return string Value stored in the cookie
     */
    private function checkCookie()
    {
        if (!isset($_COOKIE['allowCookies'])) {
            return false;
        } else {
            return $_COOKIE['allowCookies'];
        }
    }

    /**
     * Function to Set the cookie
     * @param boolean Whether to set the cookie to true or false
     */
    private function setCookie($value = '0')
    {
        if ($value == 0) {
            // if no set cookie to expire in 24 hours
            $expire = time() + (24 * 60 * 60);
        } else {
            // if yes set cookie to expire in 1 year
            $expire = time() + (365 * 24 * 60 * 60);
        }
        setcookie('allowCookies', $value, $expire, "/");
    }

    /**
     * Function to remove the cookie and re-show the warning
     */
    private function destroyCookie()
    {
        setcookie('allowCookies', '', time()-3600, "/");
    }
}
