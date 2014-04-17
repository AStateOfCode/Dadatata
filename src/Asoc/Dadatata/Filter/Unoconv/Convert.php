<?php

namespace Asoc\Dadatata\Filter\Unoconv;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\DocumentOptions;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\Unoconv;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

class Convert implements FilterInterface {

	/**
	 * @var \Asoc\Dadatata\ToolInterface|Unoconv
	 */
	private $unoconv;
    /**
     * @var \Neutron\TemporaryFilesystem\TemporaryFilesystemInterface
     */
    private $tmpFs;
    /**
     * @var DocumentOptions
     */
    private $defaults;

    public function __construct(ToolInterface $unoconv, TemporaryFilesystemInterface $tmpFs) {
		$this->unoconv = $unoconv;
        $this->tmpFs = $tmpFs;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof DocumentOptions)) {
            $options = new DocumentOptions($options->all());
        }
        $this->defaults = $options;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null|DocumentOptions $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpDir = $this->tmpFs->createTemporaryDirectory();
        $tmpFile = $tmpDir.DIRECTORY_SEPARATOR.$thing->getKey();

        /** @var DocumentOptions $options */
        $options = $this->defaults->merge($options);

        $pb = $this->unoconv->getProcessBuilder()
            ->format($options->getFormat())
            ->output($tmpFile)
            ->input($sourcePath);
        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert document to PDF', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpFile];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof DocumentInterface || $thing instanceof ImageInterface;
    }

}