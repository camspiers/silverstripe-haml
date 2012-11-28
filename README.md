#SilverStripe Haml

##License

SilverStripe Haml is licensed under an [MIT license](http://camspiers.mit-license.org/)

##Installation

###Composer

Installing from composer is easy, 

Create or edit a `composer.json` file in the root of your SilverStripe project, and make sure the following is present. Currently `silverstripe-haml` is in development so it isn't available through packagist.

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/camspiers/silverstripe-haml.git"
        },
        {
            "type": "git",
            "url": "https://github.com/camspiers/MtHaml.git"
        },
        {
            "type": "git",
            "url": "https://github.com/camspiers/colors.php.git"
        }
    ],
    "require": {
        "camspiers/autoloader-composer-silverstripe": "1.0.*",
        "camspiers/silverstripe-haml": "dev-master"
    },
    "minimum-stability": "dev"
}
```

After completing this step, navigate in Terminal or similar to the SilverStripe root directory and run `composer install` or `composer update` depending on whether or not you have composer already in use.

##Usage

Create a folder called `haml` in your current theme.

	themes/mytheme/haml

In this folder you should replicate the same folder and file structure as in `themes/mytheme/templates`

For example:

	themes/mytheme/haml
	themes/mytheme/haml/Layout
	themes/mytheme/haml/Includes

In this folder place your haml files, the default extension is `.ss.haml`

Every haml file will be compiled into `themes/mytheme/templates` in the appropriate directory

###Compile by flush=1


###Compile by grunt


##Contributing

###Code guidelines

This project follows the standards defined in:

* [PSR-1](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-1-basic.md)
* [PSR-2](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-2-advanced.md)