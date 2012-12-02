<?php

class HamlSilverStripeProcessor
{

    protected $inputDirectory;
    protected $outputDirectory;
    protected $environment;
    protected $watchExtension = '.ss.haml';
    protected $compileExtension = '.ss';
    protected $header = "<%%-- Compiled from '%s'. Do not edit --%%>";
    protected $stripWhitespace = true;

    public function __construct($inputDirectory, $outputDirectory, MtHaml\Environment $environment = null, $watchExtension = false, $compileExtension = false, $header = false, $stripWhitespace = true)
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

        if (!is_null($environment) && $environment instanceof MtHaml\Environment) {
            $this->environment = $environment;
        } else {
            throw new \InvalidArgumentException('Invalid environment');
        }

        if ($watchExtension) {
            $this->watchExtension = $watchExtension;
        }

        if ($compileExtension) {
            $this->compileExtension = $compileExtension;
        }

        if ($header) {
            $this->header = $header;
        }

        $this->stripWhitespace = $stripWhitespace;

    }

    public function process($files = false)
    {

        $files = is_array($files) ? $files : $this->glob($this->inputDirectory . '/*' . $this->watchExtension);
        $compiled = array();

        if (is_array($files)) {

            foreach ($files as $file) {

                if (file_exists($file)) {

                    $basename = basename($file, $this->watchExtension);

                    $dirname = str_replace(
                        $this->inputDirectory,
                        $this->outputDirectory,
                        dirname($file)
                    );

                    if (!file_exists($dirname)) {

                        mkdir($dirname, 0755, true);

                    }

                    $templateName = $dirname . '/' . $basename . $this->compileExtension;
                    $prettyHamlName = str_replace($this->inputDirectory, basename($this->inputDirectory), $file);
                    $prettyTemplateName = str_replace($this->outputDirectory, basename($this->outputDirectory), $templateName);

                    $compiledString =
                        sprintf($this->header, $prettyHamlName) .
                        $this->environment->compileString(
                            file_get_contents($file),
                            $file
                        );

                    if ($this->stripWhitespace) {

                        $compiledString = trim(preg_replace('/>\s+</', '><', $compiledString));

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
