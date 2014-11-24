<?php

namespace Asoc\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Imagine\Image\ImagineInterface;

class Imagine implements ReaderInterface
{
    /**
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;

    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    public function extract($path)
    {
        $image = $this->imagine->open($path);
        $size  = $image->getSize();

        $result = [
            ReaderInterface::IMAGE_WIDTH  => $size->getWidth(),
            ReaderInterface::IMAGE_HEIGHT => $size->getHeight()
        ];

        return $result;
    }

    public function canHandle($mime)
    {
        return strpos($mime, 'image/') !== false;
    }
}