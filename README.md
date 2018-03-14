# SCW Cookie 2.1
PHP Cookie checker for Google Analytics, Tawk.To, Smartsupp and HotJar (GDPR Compliance)

This small class will add a Cookie Checker to any website.

To call the class within a site simply follow the below steps :

1. Upload the entire scwCookie folder into the public_html (or similar) root folder of your website.

2. If done correctly you will then be able to visit [domain]/scwCookie/install-check.txt and see the "Installed correctly" message.

3. Then add the following code before the closing body tag of each page:
```
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/scwCookie/output/scwCookie.php'; ?>
```

4. Then open config.ini in your chosen editor and set the values to match your sites configuration

Thats it, a window will now display in the footer of your site allowing people to manage the cookies you use.

## Updates
### 2.1
- Fix for sites when headers are sent before close of body tag e.g. Wordpress

### 2.0
- Full rewrite
- Add ability to toggle which cookie is allowed
