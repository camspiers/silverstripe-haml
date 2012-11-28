<?php

use MtHaml\Environment;

class HamlSilverStripeProcessor
{

    protected $inputDirectory;
    protected $outputDirectory;
    protected $compiler;
    protected $extension = '.ss.haml';
    protected $header = "<%%-- This template was automatically compiled from '%s', do not edit directly --%%>";

    public function __construct($inputDirectory, $outputDirectory, Environment $compiler = null, $extension = false, $header = false)
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

        if ($header) {
            $this->header = $header;
        }

    }

    public function process($files = false)
    {

        $files = is_array($files) ? $files : $this->glob($this->inputDirectory . '/*' . $this->extension);
        $mapping = array();

        if (is_array($files)) {

            foreach ($files as $file) {

                $basename = basename($file, $this->extension);
                $dirname = str_replace(
                    $this->inputDirectory,
                    $this->outputDirectory,
                    dirname($file)
                );
                $ssName = $dirname . '/' . $basename . '.ss';

                if (!file_exists($dirname)) {

                    mkdir($dirname, 0755, true);

                }

                $mapping[str_replace($this->inputDirectory, '', $file)] = str_replace($this->outputDirectory, '', $ssName);

                file_put_contents(
                    $ssName,
                    sprintf($this->header, $file) . PHP_EOL .
                    $this->compiler->compileString(
                        file_get_contents($file),
                        $file
                    )
                );

            }

            return $mapping;

        } else {

            return false;

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
