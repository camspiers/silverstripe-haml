<?php

use MtHaml\Environment;

class HamlSilverStripeProcessor
{

    protected $inputDirectory;
    protected $outputDirectory;
    protected $compiler;
    protected $extension = '.ss.haml';
    protected $header = "<%%--\nCompiled from '%s'\nDo not edit\n--%%>";

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
        $compiled = array();

        if (is_array($files)) {

            foreach ($files as $file) {

                if (file_exists($file)) {

                    $basename = basename($file, $this->extension);

                    $dirname = str_replace(
                        $this->inputDirectory,
                        $this->outputDirectory,
                        dirname($file)
                    );

                    if (!file_exists($dirname)) {

                        mkdir($dirname, 0755, true);

                    }

                    $templateName = $dirname . '/' . $basename . '.ss';
                    $prettyHamlName = str_replace($this->inputDirectory, basename($this->inputDirectory), $file);
                    $prettyTemplateName = str_replace($this->outputDirectory, basename($this->outputDirectory), $templateName);

                    $compiledString =
                        sprintf($this->header, $prettyHamlName) .
                        $this->compiler->compileString(
                            file_get_contents($file),
                            $file
                        );

                    if ($compiledString != file_get_contents($templateName)) {

                        file_put_contents($templateName, $compiledString);

                        $compiled[$prettyHamlName] = $prettyTemplateName;

                    }

                }

            }

            return $compiled;

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
