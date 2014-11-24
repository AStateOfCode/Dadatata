<?php

namespace Asoc\Tests\Dadatata\Filesystem;

use Asoc\Dadatata\Filesystem\Store;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Tests\Dadatata\BaseTestCase;

class StoreTest extends BaseTestCase
{
    public function testSaveWithFilePath()
    {
        $store = $this->createStore();
        $thing = $this->createThing();

        $data = $this->createTempFile();

        $store->save($thing, $data);

        $path = $store->getPath($thing);

        $this->assertFileExists($path);
        $this->assertFileEquals($data, $path);
    }

    public function testSaveWithSplFileInfo()
    {
        $store      = $this->createStore();
        $thing      = $this->createThing();
        $sourcePath = $this->createLocator()->getFilePath($this->createImageThingMock());

        $store->save($thing, new \SplFileInfo($sourcePath));

        $path = $store->getPath($thing);

        $this->assertFileExists($path);
        $this->assertFileEquals($sourcePath, $path);
    }

    public function testSaveWithFilePathFragments()
    {
        $store = $this->createStore();
        $thing = $this->createThing();

        $tempFiles = [
            $this->createTempFile(),
            $this->createTempFile(),
            $this->createTempFile()
        ];
        $store->save($thing, new FilePathFragments($tempFiles));

        $this->assertFileExists($store->getPath($thing, 1));
        $this->assertFileExists($store->getPath($thing, 2));
        $this->assertFileExists($store->getPath($thing, 3));
    }

    /**
     * @expectedException \Asoc\Dadatata\Exception\FileNotFoundException
     */
    public function testSaveWithNonExistentFile()
    {
        $store = $this->createStore();
        $thing = $this->createThing();
        $store->save($thing, sys_get_temp_dir().'/this_should_not_exist'.uniqid());
    }

    public function testLoad()
    {
        $store = $this->createStore();
        $thing = $this->createThing();

        $data = $this->createTempFile();

        $store->save($thing, $data);

        $path = $store->getPath($thing);
        $this->assertFileExists($path);

        $content = $store->load($thing);
        $this->assertStringEqualsFile($data, $content);
    }

    /**
     * @expectedException \Asoc\Dadatata\Exception\FileNotFoundException
     */
    public function testLoadWithNonExistentFile()
    {
        $store = $this->createStore();
        $thing = $this->createThing();
        $store->load($thing);
    }

    public function testRemove()
    {
        $store = $this->createStore();
        $thing = $this->createThing();
        $thing->expects($this->any())->method('getFragments')->will($this->returnValue(1));
        $data = $this->createTempFile();

        $store->save($thing, $data);
        $path = $store->getPath($thing);
        $store->remove($thing);

        $this->assertFileNotExists($path);
    }

    public function testRemoveWithMultipleFragments()
    {
        $store = $this->createStore();
        $thing = $this->createThing();

        $tempFiles = [
            $this->createTempFile(),
            $this->createTempFile(),
            $this->createTempFile()
        ];
        $store->save($thing, new FilePathFragments($tempFiles));

        $thing->expects($this->any())->method('getFragments')->will($this->returnValue(count($tempFiles)));

        $store->remove($thing);

        $this->assertFileNotExists($store->getPath($thing, 1));
        $this->assertFileNotExists($store->getPath($thing, 2));
        $this->assertFileNotExists($store->getPath($thing, 3));
    }

    protected function createThing()
    {
        $thing = $this->createEmptyThingMock();
        $thing->expects($this->any())->method('getKey')->will($this->returnValue(md5(uniqid())));

        return $thing;
    }

    protected function createStore()
    {
        $locator = $this->createTempLocator();

        return new Store($locator);
    }
}
 