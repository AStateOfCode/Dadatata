<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Exception\FileNotFoundException;
use Asoc\Dadatata\Metadata\ExaminerInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ModelProviderInterface;
use Asoc\Dadatata\Model\ThingInterface;

abstract class AbstractLibrary implements LibraryInterface
{
    protected function retrievePathToData($data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Data cannot be empty');
        }

        // if there are multiple fragments, use the first for metadata. all others are assumed to be of the same type.
        if ($data instanceof FilePathFragments) {
            // first fragment is responsible for determining all the infos
            $file = $data->getFileInfos()[0];
            $path = $file->getPathname();
        } elseif ($data instanceof \SplFileInfo) {
            $path = $data->getPathname();
        } elseif (is_string($data) && ctype_print($data) === true) {
            $path = $data;
        } else {
            throw new \InvalidArgumentException(sprintf('Unsupported type: %s', gettype($data)));
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Given path does not exist: %s', $path));
        }

        return $path;
    }

    public function identify($data, ThingInterface $thing = null)
    {
        $examiner = $this->getIdentifier();

        // by default, there's one file fragment
        $fragments = 1;
        if ($data instanceof FilePathFragments) {
            $fragments = $data->getNum();
        }

        $path = $this->retrievePathToData($data);
        list($category, $mime) = $examiner->categorize($path);

        $modelProvider = $this->getModelProvider();

        if ($thing === null) {
            $thing = $modelProvider->create($category);
        }

        $thing->setMime($mime);
        $thing->setFragments($fragments);

        // retrieve basic file metadata (eg. hashes or filesize)
        $this->describe($thing, $path);

        return $thing;
    }

    /**
     * @return ExaminerInterface
     */
    abstract protected function getIdentifier();

    abstract protected function describe(ThingInterface $thing, $path);

    /**
     * @return ModelProviderInterface
     */
    abstract protected function getModelProvider();
}