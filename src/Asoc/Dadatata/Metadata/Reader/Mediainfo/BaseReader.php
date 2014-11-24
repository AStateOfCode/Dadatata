<?php

namespace Asoc\Dadatata\Metadata\Reader\Mediainfo;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Symfony\Component\Process\ProcessBuilder;

abstract class BaseReader implements ReaderInterface
{
    private $bin;

    public function __construct($mediainfoBin = '/usr/bin/mediainfo')
    {
        $this->bin = $mediainfoBin;
    }

    protected function getMediaInfo($path)
    {
        $mediainfo = new ProcessBuilder([$this->bin]);
        $mediainfo->add('--Output=XML');
        $mediainfo->add($path);
        $process = $mediainfo->getProcess();
        $process->run();

        if ($process->isSuccessful()) {
            $result = $process->getOutput();
            try {
                return simplexml_load_string($result);
            } catch (\Exception $e) {
                // ignored on purpose
            }
        }

        return null;
    }

    protected function extractDuration($duration)
    {
        $length = 0;
        if (preg_match('/(\d+)h/', $duration, $matches) === 1) {
            $length += intval($matches[1]) * 60 * 60; // hours to seconds
        }
        if (preg_match('/(\d+)mn/', $duration, $matches) === 1) {
            $length += intval($matches[1]) * 60; // minutes to seconds
        }
        if (preg_match('/(\d+)s/', $duration, $matches) === 1) {
            $length += intval($matches[1]);
        }

        return $length;
    }
}