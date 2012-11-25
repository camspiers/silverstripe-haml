<?php

if (isset($_GET['flush']) && $_GET['flush']) {
	
	$path = THEMES_PATH . '/' . SSViewer::current_theme();

	$hamlProcessor = new HamlSilverStripeProcessor(
		$path . '/haml',
		$path . '/templates'
	);

	$hamlProcessor->process();

}