<?php

namespace Asoc\Dadatata\Tool;

use Asoc\Dadatata\ToolInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

class Unoconv implements ToolInterface
{
    /**
     * @var string
     */
    private $bin;
    /**
     * @var string
     */
    private $home;
    /**
     * Fail if no listener is found (default: launch one)
     *
     * @var bool
     */
    private $noLaunch;

    /**
     * @var
     */
    private $version;

    public function __construct($bin = '/usr/bin/unoconv', $home = null, $noLaunch = false)
    {
        $this->bin = $bin;
        $this->home = $home;
        $this->noLaunch = $noLaunch;
    }

    public static function create($directories = [])
    {
        $finder = new ExecutableFinder();
        $bin = $finder->find('unoconv', null, $directories);

        if (null === $bin) {
            return null;
        }

        $tool = new static($bin);

        return $tool;
    }

    /**
     * @return ProcessBuilder|UnoconvBuilder
     */
    public function getProcessBuilder()
    {
        $pb = new UnoconvBuilder([$this->bin]);

        if (null !== $this->home) {
            $pb->setEnv('HOME', $this->home);
        }

        if ($this->noLaunch) {
            $pb->noLaunch();
        }

        return $pb;
    }

    public function getVersion()
    {
        if (null === $this->version) {
            $pb = new UnoconvBuilder([$this->bin]);
            $process = $pb->version()->getProcess();
            $process->run();
            if (preg_match('/unoconv\s([\d\.]+)/', $process->getOutput(), $version)) {
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

    /**
     * @return boolean
     */
    public function isNoLaunch()
    {
        return $this->noLaunch;
    }

    /**
     * @param boolean $noLaunch
     */
    public function setNoLaunch($noLaunch)
    {
        $this->noLaunch = $noLaunch;
    }

}