<?php

use Colors\Color;

class HamlSilverStripeController extends CliController
{

    public function process()
    {

        $path = THEMES_PATH . '/' . (isset($_GET['theme']) ? $_GET['theme'] : SSViewer::current_theme());

        $hamlProcessor = new HamlSilverStripeProcessor(
            $path . '/haml',
            $path . '/templates'
        );

        $files = $hamlProcessor->process();

        $c = new Color('', true);

        if (is_array($files) && count($files) > 0) {

            echo $c('Haml files compiled:')->green(), PHP_EOL;

            foreach ($files as $haml => $ss) {

                echo $c($haml . ' => ' . $ss)->blue(), PHP_EOL;

            }

        } else {

            echo $c('No files compiled')->red(), PHP_EOL;

        }

    }

}
