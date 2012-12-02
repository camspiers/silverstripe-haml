<?php

class HamlSilverStripeContainer extends Pimple
{

    protected static $config = array(
        'processor.class'             => 'HamlSilverStripeProcessor',
        'processor.input_directory'   => false,
        'processor.output_directory'  => false,
        'processor.watch_extension'   => false,
        'processor.compile_extension' => false,
        'processor.header'            => false,
        'processor.strip_whitespace'  => true,
        'environment.escape_attrs'    => false,
        'environment.enable_escaper'  => false,
        'environment.type'            => 'silverstripe',
        'environment.extra_options'   => array()
    );

    public function __construct()
    {

        parent::__construct();

        $this['processor'] = function ($c) {
            return new $c['processor.class'](
                $c['processor.input_directory'] ? $c['processor.input_directory'] : THEMES_PATH . '/' . SSViewer::current_theme() . '/haml',
                $c['processor.output_directory'] ? $c['processor.output_directory'] : THEMES_PATH . '/' . SSViewer::current_theme() . '/templates',
                $c['compiler'],
                $c['processor.watch_extension'],
                $c['processor.compile_extension'],
                $c['processor.header'],
                $c['processor.strip_whitespace']
            );
        };

        $this['colors'] = $this->share(function ($c) {
            return new Colors\Color('');
        });

        $this['environment'] = $this->share(function ($c) {
            return new MtHaml\Environment(
                $c['environment.type'],
                array_merge(array(
                    'escape_attrs' => $c['environment.escape_attrs'],
                    'enable_escaper' => $c['environment.enable_escaper']
                ), $c['environment.extra_options'])
            );
        });

        foreach (self::$config as $key => $value) {

            $this[$key] = $value;

        }

    }

    public static function extendConfig($config)
    {
        if (is_array($config)) {
            self::$config = array_merge(self::$config, $config);
        }
    }

}
