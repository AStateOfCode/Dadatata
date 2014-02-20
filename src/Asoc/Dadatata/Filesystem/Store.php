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
        if(is_object($data)) {
            if($data instanceof \SplFileInfo) {
                $path = $this->getPath($thing, true);
                /** @var \SplFileInfo $data */
                copy($data->getPathname(), $path);
            }
            else if($data instanceof FilePathFragments) {
                $i = 1;
                foreach($data->getFileInfos() as $file) {
                    $path = $this->getPath($thing, true, $i);
                    copy($file->getPathname(), $path);
                    $i++;
                }
            }
        }
        else {
            $path = $this->getPath($thing, true);
            file_put_contents($path, $data);
        }
    }

    public function load(ThingInterface $thing, $fragment = 1, $asStream = false)
    {
        if($asStream === true) {
            throw new \Exception('Not implemented');
        }
        $path = $this->locator->getFilePath($thing);
        return file_get_contents($path);
    }

    public function remove(ThingInterface $thing)
    {
        $path = $this->locator->getFilePath($thing);
        if(file_exists($path)) {
            unlink($path);
        }
    }

    public function getPath(ThingInterface $thing, $createDirectories = false, $fragment = 1) {
        $path = $this->locator->getFilePath($thing, $fragment);
        if($createDirectories) {
            $directory = $this->locator->getDirectory($thing);
            if(!is_dir($directory)) {
                if(!mkdir($directory, 0777, true)) {
                    throw new \Exception('Could not create directory');
                }
            }
        }
        return $path;
    }
}