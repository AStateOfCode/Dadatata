<?php

namespace Asoc\Dadatata\Tests;

use Asoc\Dadatata\Filesystem\FlatLocator;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\Manager;
use Symfony\Component\Process\ExecutableFinder;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected $tempFiles;

    protected $tempStores;

    protected $tmpFs;

    protected function getTmpFs()
    {
        if (null === $this->tmpFs) {
            $this->tmpFs = Manager::create();
        }

        return $this->tmpFs;
    }

    protected function createTempFile()
    {
        $tempfile = tempnam(sys_get_temp_dir(), 'dadatatatest');
        file_put_contents($tempfile, uniqid());

        if (null === $this->tempFiles) {
            $this->tempFiles = [];
        }

        $this->tempFiles[] = $tempfile;

        return $tempfile;
    }

    protected function rrmdir($dir)
    {
        foreach (glob($dir.'/*') as $file) {
            if (is_dir($file)) {
                $this->rrmdir($file);
            } else {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    protected function tearDown()
    {
        if (null !== $this->tempFiles) {
            foreach ($this->tempFiles as $tempfile) {
                unlink($tempfile);
            }
            $this->tempFiles = [];
        }

        if (null !== $this->tempStores) {
            foreach ($this->tempStores as $path) {
                $this->rrmdir($path);
            }
        }
    }

    protected function markTestSkippedIfToolIsNotAvailable($name, ToolInterface $tool = null)
    {
        if (null === $tool) {
            $this->markTestSkipped(sprintf('Tool is not available: %s', $name));
        }
    }

    protected function skipIfToolIsNotAvailable($name, $message)
    {
        $executableFinder = new ExecutableFinder();
        $path             = $executableFinder->find($name);

        if ($path === null) {
            $this->markTestSkipped(sprintf('Tool is not available: %s (%s)', $name, $message));
        }

        return $path;
    }

    /**
     * @return ThingInterface|ImageInterface
     */
    protected function createImageThingMock()
    {
        $thing = $this->getMockForAbstractClass('Asoc\Dadatata\Tests\Model\ImageMock');
        $thing->expects($this->any())->method('getKey')->will($this->returnValue('php_logo.png'));
        $thing->expects($this->any())->method('getWidth')->will($this->returnValue(1000));
        $thing->expects($this->any())->method('getHeight')->will($this->returnValue(530));

        return $thing;
    }

    protected function createTempDirectory($create = true)
    {
        $dir = sys_get_temp_dir().'/dadatatatest'.uniqid();

        if ($create && !is_dir($dir)) {
            mkdir($dir, 0777);
        }

        if (null === $this->tempStores) {
            $this->tempStores = [];
        }
        $this->tempStores[] = $dir;

        return $dir;
    }

    protected function createDocumentMock($key = null)
    {
        $thing = $this->getMockForAbstractClass('Asoc\Dadatata\Tests\Model\DocumentMock');

        if (null !== $key) {
            $thing->expects($this->any())->method('getKey')->will($this->returnValue($key));
        }

        return $thing;
    }

    protected function createImageMock($key = null)
    {
        $thing = $this->getMockForAbstractClass('Asoc\Dadatata\Tests\Model\ImageMock');

        if (null !== $key) {
            $thing->expects($this->any())->method('getKey')->will($this->returnValue($key));
        }

        return $thing;
    }

    protected function createEmptyThingMock()
    {
        return $this->getMockForAbstractClass('Asoc\Dadatata\Tests\Model\ImageMock');
    }

    protected function createLocator()
    {
        return new FlatLocator(__DIR__.'/Resources/');
    }

    protected function createTempLocator()
    {
        // don't create the directory, the locator should do it
        return new FlatLocator($this->createTempDirectory(false));
    }

    protected function assertMime($filePath, array $mimes)
    {
        try {
            $finfo      = new \finfo(FILEINFO_MIME_TYPE);
            $actualMime = $finfo->file($filePath);
        } catch (\Exception $e) {
            $actualMime = 'couldNotReadMime';
        }

        $this->assertTrue(in_array($actualMime, $mimes));
    }
}