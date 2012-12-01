<?php

class HamlSilverStripeContainer extends Pimple
{

    protected static $config = array(
        'processor.class'            => 'HamlSilverStripeProcessor',
        'processor.input_directory'  => false,
        'processor.output_directory' => false,
        'processor.extension'        => false,
        'processor.header'           => false,
        'processor.strip_whitespace' => true,
        'environment.escape_attrs'   => false,
        'environment.enable_escaper' => false,
    );

    public function __construct()
    {

        parent::__construct();

        $this['processor'] = function ($c) {
            return new $c['processor.class'](
                $c['processor.input_directory'],
                $c['processor.output_directory'],
                $c['processor.compiler'],
                $c['processor.extension'],
                $c['processor.header'],
                $c['processor.strip_whitespace']
            );
        };

        $this['colors'] = $this->share(function ($c) {
            return new Colors\Color('');
        });

        $this['compiler'] = $this->share(function ($c) {
            return new MtHaml\Environment(
                'silverstripe',
                array(
                    'escape_attrs' => $c['environment.escape_attrs'],
                    'enable_escaper' => $c['environment.enable_escaper']
                )
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
