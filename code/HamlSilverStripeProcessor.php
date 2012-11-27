<?php

use MtHaml\Environment;

class HamlSilverStripeProcessor
{

    protected $inputDirectory;
    protected $outputDirectory;
    protected $compiler;
    protected $extension = '.ss.haml';

    public function __construct($inputDirectory, $outputDirectory, Environment $compiler = null, $extension = false)
    {

        $msg = 'Directory does not exist or is not writable: %s';

        if (file_exists($inputDirectory) && is_writable($inputDirectory)) {
            $this->inputDirectory = $inputDirectory;
        } else {
            throw new \Exception(sprintf($msg, $inputDirectory));
        }

        if (file_exists($outputDirectory) && is_writable($outputDirectory)) {
            $this->outputDirectory = $outputDirectory;
        } else {
            throw new \Exception(sprintf($msg, $outputDirectory));
        }

        $this->compiler = $compiler ? $compiler : new Environment(
            'silverstripe',
            array(
                'escape_attrs' => false,
                'enable_escaper' => false
            )
        );

        if ($extension) {
            $this->extension = $extension;
        }

    }

    public function process($files = false)
    {

        $files = is_array($files) ? $files : $this->glob($this->inputDirectory . '/*' . $this->extension);

        foreach ($files as $file) {

            $basename = basename($file, $this->extension);
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

    public function glob($pattern)
    {

        $files = glob($pattern);
        $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        foreach ($dirs as $dir) {
            $files = array_merge($files, $this->glob($dir . '/' . basename($pattern)));
        }

        return $files;

    }

}
