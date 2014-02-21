<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Metadata\ExaminerInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ModelProviderInterface;
use Asoc\Dadatata\Model\ThingInterface;

abstract class AbstractLibrary implements LibraryInterface {

    public function identify($data) {
        $examiner = $this->getIdentifier();

        // by default, there's one file fragment
        $fragments = 1;

        // if there are multiple fragments, use the first for metadata. all others are assumed to be of the same type.
        if($data instanceof FilePathFragments) {
            // first fragment is responsible for determining all the infos
            $file = $data->getFileInfos()[0];
            list($category, $mime) = $examiner->categorize($file);
            $fragments = $data->getNum();
            $path = $file->getPathname();
        }
        else if($data instanceof \SplFileInfo) {
            list($category, $mime) = $examiner->categorize($data);
            $path = $data->getPathname();
        }
        // check if a blob or something different has been passed here
        else {
            // not a file, assuming arbitrary data. we need to store the data in a temporary file so all the tools
            // can operate on it
            if(is_string($data) && ctype_print($data) === true && !is_file($data)) {
                $path = tempnam(sys_get_temp_dir(), 'dadatata');
                file_put_contents($path, $data);
            }
            else if(is_file($data)) {
                $path = realpath($data);
            }
            else {
                throw new \Exception(sprintf('Given data is not supported: %s', gettype($data)));
            }

            if($path !== null && file_exists($path)) {
                list($category, $mime) = $examiner->categorize($data);
            }
            else {
                throw new \Exception(sprintf('Given path does not exist: %s', gettype($data)));
            }
        }

        $modelProvider = $this->getModelProvider();

        $thing = $modelProvider->create($category);
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