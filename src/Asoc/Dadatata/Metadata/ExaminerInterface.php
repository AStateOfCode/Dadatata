<?php

namespace Asoc\Dadatata\Metadata;

interface ExaminerInterface {

    /**
     * @param string $path
     * @return array Index 0 = array of meta info, Index 1 = mime type
     */
    public function examine($path);

    /**
     * @param mixed $data
     * @return array Index 0 = category, Index 1 = mime type
     */
    public function categorize($data);

}