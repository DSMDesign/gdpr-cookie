# SCWCookie
Php Cookie checker for Analytics and Tawk.To (GDPR Compliance)

This small class will add a Cookie Checker to any website.

To call the class within a site simply use :

Place the code in the header file where you would normally put the analytics code.

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
