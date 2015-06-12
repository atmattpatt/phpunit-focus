<?php

namespace Mattpatt\PHPUnitFocus;

class CLI
{
    const EXIT_OK = 0;

    private $argv = [];
    private $program;
    private $phpunit;
    private $file;
    private $line;

    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->parseArgs();
    }

    public function run()
    {
        if (!$this->isValid()) {
            $this->printUsage();
            return static::EXIT_OK;
        }

        $sourceFile = new SourceFile($this->file);
        $filter = sprintf('/%s/', $sourceFile->getTestForLine($this->line));

        $command = $this->buildCommand($filter);

        return $this->exec($command);
    }

    protected function exec($command)
    {
        echo "$command\n";

        $return = null;
        passthru($command, $return);

        return $return;
    }

    private function buildCommand($filter)
    {
        return trim(sprintf(
            '%s --filter %s %s%s',
            $this->phpunit,
            escapeshellarg($filter),
            $this->getPHPUnitArgString(),
            $this->file
        ));
    }

    private function getPHPUnitArgString()
    {
        if (count($this->phpunitArgs) == 0) {
            return '';
        } else {
            return implode(' ', $this->phpunitArgs) . ' ';
        }
    }

    private function isValid()
    {
        return $this->file !== null && $this->line !== null;
    }

    private function parseArgs()
    {
        $args = $this->argv;
        $this->program = array_shift($args);
        $this->phpunit = array_shift($args);
        $this->file = array_shift($args);
        $this->line = array_shift($args);
        $this->phpunitArgs = $args;
    }

    private function printUsage()
    {
        echo "Usage: {$this->program} PHPUNIT_CMD FILE LINE\n";
    }
}
