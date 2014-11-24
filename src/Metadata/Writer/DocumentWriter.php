<?php

namespace Asoc\Dadatata\Metadata\Writer;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\WriterInterface;
use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\ThingInterface;

class DocumentWriter implements WriterInterface
{
    public function canHandle($object)
    {
        return $object instanceof DocumentInterface;
    }

    /**
     * @param ThingInterface|DocumentInterface $object
     * @param array                            $knowledge
     */
    public function apply($object, array $knowledge)
    {
        if (isset($knowledge[ReaderInterface::DOCUMENT_PAGE_COUNT])) {
            $object->setPages(intval($knowledge[ReaderInterface::DOCUMENT_PAGE_COUNT]));
        }
    }
}