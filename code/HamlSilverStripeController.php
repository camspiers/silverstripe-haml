<?php

class HamlSilverStripeController extends CliController
{

    public function process()
    {

        $path = THEMES_PATH . '/' . SSViewer::current_theme();

        $hamlProcessor = new HamlSilverStripeProcessor(
            $path . '/haml',
            $path . '/templates'
        );

        $hamlProcessor->process();

        echo 'Haml files compiled', PHP_EOL;

    }

}
