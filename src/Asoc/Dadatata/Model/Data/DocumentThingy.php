<?php

namespace Asoc\Dadatata\Model\Data;

trait DocumentThingy
{
    protected $pages;

    /**
     * @param mixed $pages
     */
    public function setPages($num)
    {
        $this->pages = $num;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }
}