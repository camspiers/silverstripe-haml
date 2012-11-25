<?php

use MtHaml\Environment;

class HamlSilverStripeProcessor
{

	protected $inputDirectory;
	protected $outputDirectory;
	protected $compiler;

	public function __construct($inputDirectory, $outputDirectory)
	{

		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->compiler = new Environment('silverstripe');

	}

	public function process()
	{

		$files = $this->glob($inputDirectory . '*.ss.haml');

		foreach ($files as $file) {

			$basename = basename($file, '.ss.haml');
			$dirname = str_replace(
				$this->inputDirectory,
				$this->outputDirectory,
				dirname($file)
			);

			if (!file_exists($dirname)) {

				mkdir($dirname, 0755, true);

			}

			file_put_contents(
				$dirname . '/' . $basename . '.ss',
				$this->compiler->compileString(
					file_get_contents($file),
					$file
				)
			);

		}

	}

	function glob($pattern)
	{

		$files = glob($pattern);
		$dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

		foreach ($dirs as $dir) {
			$files = array_merge($files, $this->glob($dir . '/' . basename($pattern)));
		}

		return $files;

	}

}