<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

class Store implements StoreInterface {

    /**
     * @var LocatorInterface
     */
    protected $locator;

    public function __construct(LocatorInterface $locator) {
        $this->locator = $locator;
    }

    public function save(ThingInterface $thing, $data)
    {
        $directory = $this->locator->getDirectory($thing);
        if(!is_dir($directory)) {
            if(!mkdir($directory, 0777, true)) {
                throw new \Exception('Could not create directory');
            }
        }

        if($data instanceof \SplFileInfo) {
            $path = $this->getPath($thing);
            /** @var \SplFileInfo $data */
            copy($data->getPathname(), $path);
        }
        else if($data instanceof FilePathFragments) {
            $i = 1;
            foreach($data->getFileInfos() as $file) {
                $path = $this->getPath($thing, $i);
                copy($file->getPathname(), $path);
                $i++;
            }
        }
        else if(is_string($data) && is_file($data)) {
            $path = $this->getPath($thing);
            copy($data, $path);
        }
        else {
            throw new \Exception(sprintf('Given data is not a file: %s', gettype($data)));
        }
    }

    public function load(ThingInterface $thing, $fragment = 1, $asStream = false)
    {
        if($asStream === true) {
            throw new \Exception('Not implemented');
        }
        $path = $this->locator->getFilePath($thing, $fragment);
        return file_get_contents($path);
    }

    public function remove(ThingInterface $thing)
    {
        for($i = 1, $n = $thing->getFragments(); $i <= $n; $i++) {
            $path = $this->locator->getFilePath($thing, $i);
            if(file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function getPath(ThingInterface $thing, $fragment = 1, $relative = false) {
        if($relative) {
            return $this->locator->getRelativeFilePath($thing, $fragment);
        }
        return $this->locator->getFilePath($thing, $fragment);
    }
}