#SilverStripe Haml

##Dependancies

SilverStripe haml depends directly on numerous libraries others have developed. The work of the authors of the following projects is greatly appreciated:

* `MtHaml`
* `grunt`
* `grunt-contrib-watch`
* `colors.php`
* `composer`
* `optimist`
* `Pimple`

##Installation

###Composer

Create or edit a `composer.json` file in the root of your SilverStripe project, and make sure the following is present.

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
        }
    ],
    "require": {
        "camspiers/autoloader-composer-silverstripe": "1.0.*",
        "camspiers/silverstripe-haml": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Currently SilverStripe haml is in development so it isn't available through packagist (nor are my customisations needed from `MtHaml`).

After completing this step, navigate in Terminal or similar to the SilverStripe root directory and run `composer install` or `composer update` depending on whether or not you have composer already in use.

##Overview

SilverStripe haml is implemented by compiling `ss.haml` files into `.ss` template files. In haml you can implement anything you can in straight `.ss` files.

SilverStripe haml provides two main mechanisms for compilation:

* A grunt build process which uses `grunt-contrib-watch` to watch your `.ss.haml` files for changes and compile when changed.
* A SilverStripe controller executable via `sake` or url.

##Getting started

To get started, create a folder called `haml` in your current theme (SS haml compiles from your current theme by default).

	themes/mytheme/haml

In this folder you should replicate the same folder and file structure as you would in `themes/mytheme/templates`

For example:

	themes/mytheme/haml
	themes/mytheme/haml/Layout
	themes/mytheme/haml/Includes

In this folder place your haml files, the default extension is `.ss.haml`

Every haml file will be compiled into the appropriate location in `themes/mytheme/templates`

###Configuration

SilverStripe haml uses a dependency injection container (an extension of `Pimple`) to allow configuration and DI for all objects used.

**Options**

* processor.class
* processor.input_directory
* processor.output_directory
* processor.extension
* processor.header
* processor.strip_whitespace
* environment.escape_attrs
* environment.enable_escaper

`mysite/_config.php`

```
HamlSilverStripeContainer::extendConfig(array(
	'processor.strip_whitespace' => false
));
```

Any service provided by SilverStripe haml can be accessed by instantiating the Container (see `HamlSilverStripeController` for an example).

```
$dic = new HamlSilverStripeContainer;

$processor = $dic['processor'];
$colors = $dic['colors'];
$compiler = $dic['compiler'];
```

See [Pimple](http://pimple.sensiolabs.org/) for more information.

##Haml Compilation

###Compile by grunt

Grunt is the preferred method of compilation. To set up, first make sure you have grunt install globally (this step requires `node` and `npm`).

This can be done via `npm`

	npm install -g grunt

Once grunt is installed and you have SilverStripe haml in your project, from the `silverstripe-haml` folder run:

	npm install

This will download SilverStripe haml's required npm dependancies.

Once this is done, you are good to go. From the `silverstripe-haml` directory run:

	grunt
	
This will launch the grunt haml task and watch your files for any changes.

###Compile by controller

If you are logged in as an admin, you can compile all `.ss.haml` templates in the current theme by running 

	http://my.host/haml/process/

Or alternatively via `sake`

	sake haml/process

##Contributing

###Code guidelines

This project follows the standards defined in:

* [PSR-1](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-1-basic.md)
* [PSR-2](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-2-advanced.md)

---
##License

SilverStripe Haml is released under the [MIT license](http://camspiers.mit-license.org/)