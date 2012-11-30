<?php

use Colors\Color;
use MtHaml\Exception\SyntaxErrorException;

class HamlSilverStripeController extends CliController
{

    public function process()
    {

        $path = THEMES_PATH . '/' . (isset($_GET['theme']) ? $_GET['theme'] : SSViewer::current_theme());
        $files = isset($_GET['files']) ? explode($_GET['files']) : false;

        $hamlProcessor = new HamlSilverStripeProcessor(
            $path . '/haml',
            $path . '/templates'
        );

        $c = new Color('');

        if (Director::is_cli() && !isset($_GET['nocolor'])) {
            $c->setForceStyle(true);
        }

        try {

            $files = $hamlProcessor->process();

        } catch (SyntaxErrorException $e) {

            file_put_contents('php://stderr', $c($e->getMessage())->red);
            exit;

        }

        if (is_array($files) && count($files) > 0) {

            echo PHP_EOL, $c('Haml files compiled')->green->underline->bold, PHP_EOL, PHP_EOL;

            $length = max(array_map('strlen', array_keys($files)));

            foreach ($files as $haml => $ss) {

                echo $c(str_pad($haml, $length, ' '))->blue;
                echo $c(' => ')->red->bold;
                echo $c($ss)->blue;
                echo PHP_EOL;

            }

        } else {

            echo $c('No files compiled')->red(), PHP_EOL;

        }

    }

}
