<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Exception\FailedToStoreDataException;
use Asoc\Dadatata\Exception\FileNotFoundException;
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
            if(true !== @mkdir($directory, 0777, true)) {
                throw new FailedToStoreDataException(sprintf('Could not create directory: %s', $directory));
            }
        }

        if(!is_writable($directory)) {
            throw new FailedToStoreDataException(sprintf('Cannot store file, directory not writable: %s', $directory));
        }

        if($data instanceof \SplFileInfo) {
            if(!file_exists($data->getPathname())) {
                throw new FileNotFoundException(sprintf('Does not exist: %s', $data->getPathname()));
            }

            $path = $this->getPath($thing);

            /** @var \SplFileInfo $data */
            if(!copy($data->getPathname(), $path)) {
                throw new FailedToStoreDataException('Could not copy data');
            }
        }
        else if($data instanceof FilePathFragments) {
            $i = 1;
            foreach($data->getFileInfos() as $file) {
                if(!file_exists($file->getPathname())) {
                    throw new FileNotFoundException(sprintf('Fragment does not exist: %s (%d)', $file->getPathname(), $i));
                }

                $path = $this->getPath($thing, $i);

                if(!copy($file->getPathname(), $path)) {
                    throw new FailedToStoreDataException('Could not copy data');
                }
                $i++;
            }
        }
        else if(is_string($data)) {
            if(!file_exists($data)) {
                throw new FileNotFoundException(sprintf('Does not exist: %s', $data));
            }

            $path = $this->getPath($thing);

            if(!copy($data, $path)) {
                throw new FailedToStoreDataException('Could not copy data');
            }
        }
        else {
            throw new FailedToStoreDataException(sprintf('Unrecognized input data: %s', gettype($data)));
        }
    }

    public function load(ThingInterface $thing, $fragment = 1, $asStream = false)
    {
        if($asStream === true) {
            throw new \Exception('Not implemented');
        }

        $path = $this->locator->getFilePath($thing, $fragment);
        if(!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Does not exist: %s', $path));
        }

        return file_get_contents($path);
    }

    public function remove(ThingInterface $thing)
    {
        for($i = 1, $n = $thing->getFragments(); $i <= $n; $i++) {
            $path = $this->locator->getFilePath($thing, $i);
            if(file_exists($path)) {
                if(!is_writable($path)) {
                    throw new FailedToStoreDataException('Cannot delete file (permission denied)');
                }
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