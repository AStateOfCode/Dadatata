<?php

namespace Asoc\Dadatata\Tool;

use Asoc\Dadatata\Tool\PdfBox\Builder;
use Asoc\Dadatata\ToolInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

class PdfBox implements ToolInterface
{
    /**
     * @var
     */
    private $bin;
    /**
     * @var
     */
    private $version;

    public function __construct($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @param array $directories Additional directories to search for the executable
     *
     * @return mixed
     */
    public static function create($directories = [])
    {
        $finder = new ExecutableFinder();
        $bin    = $finder->find('pdfbox', null, $directories);

        if (null === $bin) {
            return null;
        }

        $tool = new static($bin);

        return $tool;
    }

    /**
     * @return ProcessBuilder|Builder
     */
    public function getProcessBuilder()
    {
        $pb = new Builder([$this->bin]);

        return $pb;
    }

    /**
     * @return null|false|string null = not available, false = failed to retrieve, string = version
     */
    public function getVersion()
    {
        if (null === $this->version) {
            $pb      = new Builder([$this->bin]);
            $process = $pb->getProcess();
            $process->run();
            if (preg_match('/PDFDBox\sversion:\s"([\d\.]+)"/', $process->getErrorOutput(), $version)) {
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