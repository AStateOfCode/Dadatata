<?php

namespace Asoc\Dadatata\Metadata;

use Asoc\Dadatata\Model\ThingInterface;

class Descriptor implements DescriptorInterface
{
    /**
     * @var WriterInterface[]
     */
    private $writer;

    public function __construct(array $writer)
    {
        $this->writer = $writer;
    }

    public function describe(ThingInterface $thing, array $knowledge)
    {
        if ($thing->getMime() === null) {
            $thing->setMime($knowledge[ReaderInterface::MIME]);
        }
        $thing->setSize($knowledge[ReaderInterface::SIZE]);

        foreach ($this->writer as $kp) {
            if (!$kp->canHandle($thing)) {
                continue;
            }

            $kp->apply($thing, $knowledge);
        }
    }
}