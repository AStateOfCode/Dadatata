<?php

namespace Asoc\Dadatata\Filter;


use Asoc\Dadatata\Model\AudioInterface;
use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\TextInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Model\VideoInterface;

class PassFilter implements FilterInterface {

    /**
     * @var PassOptions
     */
    private $defaults;

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof PassOptions)) {
            $options = new PassOptions($options);
        }

        $this->defaults = $options;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        return [$sourcePath];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        $ok = false;

        if($this->defaults->passIfCategory()) {
            $category = $this->defaults->getCategory();
            if($category === 'image' && $thing instanceof ImageInterface
                || $category === 'document' && $thing instanceof DocumentInterface
                || $category === 'audio' && $thing instanceof AudioInterface
                || $category === 'video' && $thing instanceof VideoInterface
                || $category === 'text' && $thing instanceof TextInterface) {
                $ok = true;
            }
        }
        if($this->defaults->passIfMime()) {
            $mime = $this->defaults->getMime();
            $ok = $mime === $thing->getMime();
        }

        return $ok;
    }
}