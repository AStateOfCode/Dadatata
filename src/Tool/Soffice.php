<?php

namespace Asoc\Dadatata\Tool;

use Asoc\Dadatata\ToolInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class Soffice
 *
 * @package Asoc\Dadatata\Tool
 */
class Soffice implements ToolInterface
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $home;

    /**
     * @param string $bin  Path to binary.
     * @param string $home Home directory (set HOME env).
     */
    public function __construct($bin, $home = null)
    {
        $this->bin  = $bin;
        $this->home = $home;
    }

    /**
     * @param array $directories Additional directories to search for the executable
     *
     * @return ToolInterface|null
     */
    public static function create($directories = [])
    {
        $finder = new ExecutableFinder();
        $bin    = $finder->find('soffice', null, $directories);

        if (null === $bin) {
            return null;
        }

        $tool = new static($bin);

        return $tool;
    }

    /**
     * @return ProcessBuilder|SofficeBuilder
     */
    public function getProcessBuilder()
    {
        $pb = new SofficeBuilder([$this->bin, '--headless']);

        if (null !== $this->home) {
            $pb->setEnv('HOME', $this->home);
        }

        return $pb;
    }

    /**
     * @return null|false|string null = not available, false = failed to retrieve, string = version
     */
    public function getVersion()
    {
        if (null === $this->version) {
            $pb = new ProcessBuilder([$this->bin]);
            $pb->add('--headless')->add('--version');
            $process = $pb->getProcess();
            $process->run();

            // LibreOffice 3.5
            // LibreOffice 4.3.5.2.0 430m0(Build:2)
            if (preg_match('/LibreOffice ([^\s]+)/', $process->getOutput(), $version)) {
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

    /**
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param string $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }
}