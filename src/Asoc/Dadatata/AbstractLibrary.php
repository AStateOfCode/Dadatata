<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Exception\FileNotFoundException;
use Asoc\Dadatata\Metadata\ExaminerInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ModelProviderInterface;
use Asoc\Dadatata\Model\ThingInterface;

abstract class AbstractLibrary implements LibraryInterface {

    public function identify($data, ThingInterface $thing = null) {
        if($data === null || !isset($data)) {
            throw new \InvalidArgumentException('Cannot identify empty data');
        }

        $examiner = $this->getIdentifier();

        // by default, there's one file fragment
        $fragments = 1;

        // if there are multiple fragments, use the first for metadata. all others are assumed to be of the same type.
        if($data instanceof FilePathFragments) {
            // first fragment is responsible for determining all the infos
            $file = $data->getFileInfos()[0];
            $path = $file->getPathname();
            list($category, $mime) = $examiner->categorize($path);
            $fragments = $data->getNum();
        }
        else if($data instanceof \SplFileInfo) {
            $path = $data->getPathname();
            list($category, $mime) = $examiner->categorize($path);
        }
        else if(is_string($data) && ctype_print($data) === true) {
            $path = $data;
            list($category, $mime) = $examiner->categorize($data);
        }
        else {
            throw new FileNotFoundException(sprintf('Cannot itentify data, unsupported type: %s', gettype($data)));
        }

        $modelProvider = $this->getModelProvider();

        if($thing === null) {
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