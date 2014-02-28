<?php


namespace Asoc\Dadatata\Filter\PDFBox;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\DocumentImageOptions;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class PdfToImage implements FilterInterface {

    /**
     * @var string
     */
    private $bin;
    /**
     * @var DocumentImageOptions
     */
    private $defaults;

    public function __construct($bin = '/usr/bin/pdfbox') {
        $this->bin = $bin;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param OptionsInterface $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        /** @var DocumentImageOptions $options */
        $options = $this->defaults->merge($options);

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('PDFToImage');
        $pb->add('-imageType')->add($options->getFormat());

        if($options->has($options::OPTION_PAGES)) {
            $pages = $options->getPages();

            if(strpos($pages, '-') !== false) {
                list($startPage, $endPage) = explode('-', $pages);
            }
            else {
                $startPage = $pages;
            }

            $startPage = intval($startPage);
            if(isset($endPage)) {
                $endPage = intval($endPage);
            }

            if($startPage === 0) {
                // one based
                $startPage = 1;

                if(isset($endPage)) {
                    $endPage++;
                }
            }

            $pb->add('-startPage')->add($startPage);

            if(isset($endPage)) {
                $pb->add('-endPage')->add($endPage);
            }
        }

        $pb->add('-outputPrefix')->add($tmpPath);
        $pb->add($sourcePath);

        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert PDF to image', $code, $process->getOutput(), $process->getErrorOutput());
        }

        if(isset($endPage)) {
            $tmpPaths = [];
            for($i = $startPage, $n = $endPage; $i < $n; $i++) {
                $tmpPaths[] = sprintf('%s%d.%s', $tmpPath, $i, $options->getFormat());
            }

            return $tmpPaths;
        }
        else {
            return [sprintf('%s1.%s', $tmpPath, $options->getFormat())];
        }
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing->getMime() === 'application/pdf';
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof DocumentImageOptions)) {
            $options = new DocumentImageOptions($options->all());
        }
        $this->defaults = $options;
    }
}