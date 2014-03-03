<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

interface LocatorInterface
{
    /**
     * Absolute file path.
     *
     * @param ThingInterface $thing
     * @param int $fragment
     * @return string
     */
    public function getFilePath(ThingInterface $thing, $fragment = 1);

    /**
     * Relative file path.
     *
     * @param ThingInterface $thing
     * @param int $fragment
     * @return string
     */
    public function getRelativeFilePath(ThingInterface $thing, $fragment = 1);

    /**
     * Absolute directory path.
     *
     * @param ThingInterface $thing
     * @param int $fragment
     * @return string
     */
    public function getDirectory(ThingInterface $thing, $fragment = 1);

    /**
     * Relative directory path.
     *
     * @param ThingInterface $thing
     * @param int $fragment
     * @return string
     */
    public function getRelativeDirectory(ThingInterface $thing, $fragment = 1);
}