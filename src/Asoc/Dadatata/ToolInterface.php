<?php

namespace Asoc\Dadatata;

use Symfony\Component\Process\ProcessBuilder;

interface ToolInterface
{
    /**
     * @param array $directories Additional directories to search for the executable
     *
     * @return ToolInterface|null
     */
    public static function create($directories = []);

    /**
     * @return ProcessBuilder
     */
    public function getProcessBuilder();

    /**
     * @return null|false|string null = not available, false = failed to retrieve, string = version
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getBin();
}