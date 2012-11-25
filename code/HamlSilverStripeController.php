<?php

class HamlSilverStripeController extends CliController
{

	public function process()
	{

		$hamlProcessor = new HamlSilverStripeProcessor(
			BASE_PATH . '/' . THEME_DIR . '/haml',
			BASE_PATH . '/' . THEME_DIR . '/templates'
		);

		$hamlProcessor->process();

	}

}