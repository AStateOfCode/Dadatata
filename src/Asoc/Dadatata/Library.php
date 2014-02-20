<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filesystem\StoreInterface;
use Asoc\Dadatata\Metadata\DescriptorInterface;
use Asoc\Dadatata\Metadata\ExaminerInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\MetadataCreatorInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Model\MetadataManagerInterface;

class Library implements LibraryInterface
{

    /**
     * @var MetadataCreatorInterface|MetadataManagerInterface
     */
    protected $manager;
    /**
     * @var StoreInterface
     */
    protected $sourceStore;
    /**
     * @var StoreInterface
     */
    protected $variantStore;
    /**
     * @var \Asoc\Dadatata\Metadata\ExaminerInterface
     */
    protected $examiner;
    /**
     * @var \Asoc\Dadatata\Metadata\DescriptorInterface
     */
    protected $descriptor;

    public function __construct(MetadataCreatorInterface $manager, StoreInterface $sourceStore, StoreInterface $variantStore, ExaminerInterface $examiner, DescriptorInterface $descriptor) {
        $this->manager = $manager;
        $this->sourceStore = $sourceStore;
        $this->variantStore = $variantStore;
        $this->examiner = $examiner;
        $this->descriptor = $descriptor;
    }

    public function store($data) {
        if($data instanceof FilePathFragments) {
            list($category, $mime) = $this->examiner->categorize($data->getFileInfos()[0]);
            $fragments = $data->getNum();
        }
        else {
            list($category, $mime) = $this->examiner->categorize($data);
            $fragments = 1;
        }

        $thing = $this->manager->create($category);
        $thing->setMime($mime);
        $thing->setFragments($fragments);
        $this->sourceStore->save($thing, $data);

        list($knowledge) = $this->examiner->examine($this->getPath($thing));
        $this->descriptor->describe($thing, $knowledge);

        if($this->manager instanceof MetadataManagerInterface) {
            $this->manager->save($thing);
        }

        return $thing;
    }

    public function fetch(ThingInterface $thing, $fragment = 1) {
        return $this->sourceStore->load($thing, $fragment);
    }

    public function remove(ThingInterface $thing) {
        foreach($thing->getVariants() as $variant) {
            $this->variantStore->remove($variant);
        }
        $this->sourceStore->remove($thing);

        if($this->manager instanceof MetadataManagerInterface) {
            $this->manager->remove($thing);
        }
    }

    public function storeVariant(ThingInterface $thing, $variant, $data) {
        if($data instanceof FilePathFragments) {
            list($category, $mime) = $this->examiner->categorize($data->getFileInfos()[0]);
            $fragments = $data->getNum();
        }
        else {
            list($category, $mime) = $this->examiner->categorize($data);
            $fragments = 1;
        }

        if($thing->hasVariant($variant)) {
            $oldThingVariant = $thing->getVariant($variant);
            if($oldThingVariant !== false) {
                $this->variantStore->remove($oldThingVariant);
                $thing->removeVariant($variant);
            }
        }

        $thingVariant = $this->manager->create($category);
        $thingVariant->setMime($mime);
        $thingVariant->setFragments($fragments);
        $this->variantStore->save($thingVariant, $data);
        $this->removeTemporaryData($data);

        list($knowledge) = $this->examiner->examine($this->getVariantPath($thingVariant));
        $this->descriptor->describe($thingVariant, $knowledge);

        $thing->addVariant($variant, $thingVariant);

        if($this->manager instanceof MetadataManagerInterface) {
            $this->manager->save($thing);
        }

        return $thingVariant;
    }

    public function fetchVariant(ThingInterface $thing, $variant, $fragment = 1) {
        return $this->variantStore->load($thing, $fragment);
    }

    public function removeVariant(ThingInterface $thing, $variant) {
        $this->variantStore->remove($variant);
    }

    public function getPath(ThingInterface $thing, $fragment = 1) {
        return $this->sourceStore->getPath($thing);
    }

    public function getVariantPath(ThingInterface $thing, $fragment = 1) {
        return $this->variantStore->getPath($thing);
    }

    private function removeTemporaryData($data) {
        if($data instanceof \SplFileInfo) {
            unlink($data->getPathname());
        }
        else if($data instanceof FilePathFragments) {
            foreach($data->getFileInfos() as $fragment) {
                unlink($fragment->getPathname());
            }
        }
    }

}