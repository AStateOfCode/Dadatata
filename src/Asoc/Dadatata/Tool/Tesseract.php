<?php

namespace Asoc\Dadatata\Tool;

use Asoc\Dadatata\ToolInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

class Tesseract implements ToolInterface {

    /**
     * @var
     */
    private $bin;
    /**
     * @var
     */
    private $version;

    public function __construct($bin) {
        $this->bin = $bin;
    }

    /**
     * @param array $directories Additional directories to search for the executable
     * @return mixed
     */
    public static function create($directories = [])
    {
        $finder = new ExecutableFinder();
        $bin = $finder->find('tesseract', null, $directories);

        if (null === $bin) {
            return null;
        }

        $tool = new static($bin);

        return $tool;
    }

    /**
     * @return ProcessBuilder|TesseractBuilder
     */
    public function getProcessBuilder()
    {
        $pb = new TesseractBuilder([$this->bin]);
        return $pb;
    }

    /**
     * @return null|false|string null = not available, false = failed to retrieve, string = version
     */
    public function getVersion()
    {
        if (null === $this->version) {
            $pb = new TesseractBuilder([$this->bin]);
            $process = $pb->version()->getProcess();
            $process->run();
            if (preg_match('/tesseract\s([\d\.]+)/', $process->getErrorOutput(), $version)) {
                $this->version = $version[1];
            } else {
                $this->version = false;
            }
        }

        return $this->version;
    }

    /**
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }
}