<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filesystem\StoreInterface;
use Asoc\Dadatata\Metadata\DescriptorInterface;
use Asoc\Dadatata\Metadata\ExaminerInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ModelManagerInterface;
use Asoc\Dadatata\Model\ModelProviderInterface;
use Asoc\Dadatata\Model\ThingInterface;

class Library implements LibraryInterface
{

    /**
     * @var ModelProviderInterface|ModelManagerInterface
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

    public function __construct(ModelProviderInterface $manager, StoreInterface $sourceStore, StoreInterface $variantStore, ExaminerInterface $examiner, DescriptorInterface $descriptor) {
        $this->manager = $manager;
        $this->sourceStore = $sourceStore;
        $this->variantStore = $variantStore;
        $this->examiner = $examiner;
        $this->descriptor = $descriptor;
    }



    public function store(ThingInterface $thing, $data) {
        if($this->manager instanceof ModelManagerInterface) {
            $this->manager->save($thing);
        }

        $this->sourceStore->save($thing, $data);
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

        if($this->manager instanceof ModelManagerInterface) {
            $this->manager->remove($thing);
        }
    }

    public function storeVariant(ThingInterface $thing, $variant, $data) {
        $thingVariant = $this->identify($data);
        $thing->addVariant($variant, $thingVariant);

        if($this->manager instanceof ModelManagerInterface) {
            $this->manager->save($thingVariant);
        }

        $this->variantStore->save($thing, $data);
        return $thing;
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

    public function getVariantPath(ThingInterface $thing, $variant, $fragment = 1) {
        if(!$thing->hasVariant($variant)) {
            throw new \Exception(sprintf('Variant does not exist: %s, %s', $thing->getKey(), $variant));
        }

        $fileVariant = $thing->getVariant($variant);
        return $this->variantStore->getPath($fileVariant);
    }

}