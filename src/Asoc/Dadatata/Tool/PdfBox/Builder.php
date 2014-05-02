<?php

namespace Asoc\Dadatata\Tool\PdfBox;

use Symfony\Component\Process\ProcessBuilder;

class Builder extends ProcessBuilder {

    private $bin;

    public function __construct(array $arguments = array())
    {
        $this->bin = $arguments[0];
        parent::__construct($arguments);
    }

    /**
     * @return ExtractTextBuilder
     */
    public function extractText() {
        return new ExtractTextBuilder([$this->bin, 'ExtractText']);
    }

    /**
     * @return MergerBuilder
     */
    public function merger() {
        return new MergerBuilder([$this->bin, 'PDFMerger']);
    }

}