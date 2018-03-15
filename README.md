# SCW Cookie 2.2
PHP cookie checker for Google Analytics, Tawk.To, Smartsupp and HotJar (GDPR Compliance)

This small class will add a cookie toggle window to any website.

To add this functionality within a site simply follow the below steps :

1. Upload the entire scwCookie folder into the public_html (or similar) root folder of your website.

2. If done correctly you will then be able to visit [domain]/scwCookie/install-check.txt and see the "Installed correctly" message.

3. Then add the following code before the closing body tag of each page:
```
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/scwCookie/output/scwCookie.php'; ?>
```

4. Then open config.ini in your chosen editor and set the values to match your site configuration

Thats it, a window will now display in the footer of your site allowing people to manage the cookies you use.

## Updates
### 2.2
- Add Tawk.to embed code
- Add tooltips to icons
- Expand on label for cookies
- Bug fixes

### 2.1
- Fix for sites when headers are sent before close of body tag e.g. Wordpress
- Add ability to set default value (allowed | blocked)
- Add Hotjar and Smartsupp embed codes
- Add ability to specify location of panel toggle (left | center | right)
- Bug fixes


### 2.0
- Full rewrite
- Add ability to toggle which cookie is allowed
