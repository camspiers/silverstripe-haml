<?php

class HamlSilverStripeController extends CliController
{

    public function process()
    {

        $path = THEMES_PATH . '/' . (isset($_GET['theme']) ? $_GET['theme'] : SSViewer::current_theme());

        $hamlProcessor = new HamlSilverStripeProcessor(
            $path . '/haml',
            $path . '/templates'
        );

        $hamlProcessor->process();

        echo 'Haml files compiled', PHP_EOL;

    }

}
