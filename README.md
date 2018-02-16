# SCWCookie
Php Cookie checker for Analytics and Tawk.To (GDPR Compliance)

This small class will add a Cookie Checker to any website.

To call the class within a site simply use :

    require('SCWCookie/SCWCookie.php');
    $cookie = new SCWCookie();
    $cookie->cookieWarning([
        'analytics'  => 'UA-xxxxxxx-xx',
        'tawkto'     => '',
        'policy'     => '/cookie-policy'
    ]);
    
All values in array are optional.

If cookies are accepted it will allow :
Google Analytics
Tawk.to
Both are optional.

Policy will display a link to cookie policy page on your website.

Feel free to change it/improve it.
