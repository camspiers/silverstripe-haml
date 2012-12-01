<?php

class HamlSilverStripeProcessor
{

    protected $inputDirectory;
    protected $outputDirectory;
    protected $compiler;
    protected $extension = '.ss.haml';
    protected $header = "<%%-- Compiled from '%s'. Do not edit --%%>";
    protected $stripWhitespace = true;

    public function __construct($inputDirectory, $outputDirectory, Environment $compiler = null, $extension = false, $header = false, $stripWhitespace = true)
    {

        $msg = 'Directory does not exist or is not writable: %s';

        if (file_exists($inputDirectory) && is_writable($inputDirectory)) {
            $this->inputDirectory = $inputDirectory;
        } else {
            throw new \InvalidArgumentException(sprintf($msg, $inputDirectory));
        }

        if (file_exists($outputDirectory) && is_writable($outputDirectory)) {
            $this->outputDirectory = $outputDirectory;
        } else {
            throw new \InvalidArgumentException(sprintf($msg, $outputDirectory));
        }

        if (!is_null($compiler)) {
            $this->compiler = $compiler;
        } else {
            throw new \InvalidArgumentException('Invalid compiler');
        }

        if ($extension) {
            $this->extension = $extension;
        }

        if ($header) {
            $this->header = $header;
        }

        $this->stripWhitespace = $stripWhitespace;

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

                    if ($this->stripWhitespace) {

                        $compiledString = preg_replace(
                            array(
                                '/\>[^\S ]+/s', //strip whitespaces after tags, except space
                                '/[^\S ]+\</s', //strip whitespaces before tags, except space
                                '/(\s)+/s'  // shorten multiple whitespace sequences
                            ),
                            array(
                                '>',
                                '<',
                                '\\1'
                            ),
                            $compiledString
                        );

                    }

                    if (!file_exists($templateName) || $compiledString != file_get_contents($templateName)) {

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

    protected function glob($pattern)
    {

        $files = glob($pattern);
        $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        foreach ($dirs as $dir) {
            $files = array_merge($files, $this->glob($dir . '/' . basename($pattern)));
        }

        return $files;

    }

}
