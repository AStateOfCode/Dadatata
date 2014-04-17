<?php

namespace Asoc\Dadatata\Tool;

use Symfony\Component\Process\ProcessBuilder;

class PdfBoxBuilder extends ProcessBuilder {

    private $bin;

    public function __construct(array $arguments = array())
    {
        $this->bin = $arguments[0];
        parent::__construct($arguments);
    }

    /**
     * @return PdfBoxExtractTextBuilder
     */
    public function extractText() {
        return new PdfBoxExtractTextBuilder([$this->bin, 'ExtractText']);
    }

}