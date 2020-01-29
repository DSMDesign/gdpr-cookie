# DSM Cookie 2.3

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/southcoastweb/gdpr-cookie/badges/quality-score.png)](https://scrutinizer-ci.com/g/southcoastweb/gdpr-cookie/?branch=2.2)
[![Build Status](https://scrutinizer-ci.com/g/southcoastweb/gdpr-cookie/badges/build.png?b=2.2)](https://scrutinizer-ci.com/g/southcoastweb/gdpr-cookie/build-status/2.3)

PHP cookie checker for Google Analytics, Tawk.To, Smartsupp and HotJar (GDPR Compliance)

This small class will add a cookie toggle window to any website.

To add this functionality within a site simply follow the below steps :

1. Upload the entire dsmCookie folder into the public_html (or similar) root folder of your website.

2. If done correctly you will then be able to visit [domain]/dsmCookie/install-check.txt and see the "Installed correctly" message.

3. Then add the following code at the very end / just before the closing body tag of each page:
```
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/dsmCookie/output/scwCookie.php'; ?>
```

4. Rename config.example.ini to config.ini

5. Then open config.ini in your chosen editor and set the values to match your site configuration

Thats it, a window will now display in the footer of your site allowing people to manage the cookies you use.

#### Remember to remove any previous cookie code, this will handle that from now on

## Demo
A live example is available here: [https://dsmdesign.co.uk](https://dsmdesign.co.uk)

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
