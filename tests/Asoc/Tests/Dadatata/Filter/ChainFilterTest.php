<?php

namespace Asoc\Tests\Dadatata\Filter;

use Asoc\Dadatata\Filter\ChainFilter;
use Asoc\Tests\Dadatata\BaseTestCase;

class ChainFilterTest extends BaseTestCase {

    public function testProcessWithoutAnyChildFilter() {
        $chain = new ChainFilter([]);
        $this->assertNull($chain->process($this->createEmptyThingMock(), 'dontcare'));
    }

    public function testProcessWithOneChildFilter() {
        $path = uniqid();
        $options = $this->getMock('Asoc\Dadatata\Filter\OptionsInterface');
        $thing = $this->createEmptyThingMock();
        $expectedResult = ['ok'];

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('process')->with($thing, $path, $options)->will($this->returnValue($expectedResult));

        $chain = new ChainFilter([$filter1]);
        $result = $chain->process($thing, $path, $options);

        $this->assertNotNull($result);
        $this->assertEquals($expectedResult, $result);
    }

    public function testProcessWithMultipleChildFilter() {
        $path = uniqid();
        $options = $this->getMock('Asoc\Dadatata\Filter\OptionsInterface');
        $thing = $this->createEmptyThingMock();
        $expectedResult1 = ['step1'];
        $expectedResult2 = ['step2'];
        $expectedResult3 = ['step3'];

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('process')->with($thing, $path, $options)->will($this->returnValue($expectedResult1));
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->once())->method('process')->with($thing, $expectedResult1[0], $options)->will($this->returnValue($expectedResult2));
        $filter3 = $this->createEmptyFilterMock();
        $filter3->expects($this->once())->method('process')->with($thing, $expectedResult2[0], $options)->will($this->returnValue($expectedResult3));

        $chain = new ChainFilter([$filter1, $filter2, $filter3]);
        $result = $chain->process($thing, $path, $options);

        $this->assertNotNull($result);
        $this->assertEquals($expectedResult3, $result);
    }

    public function testCanHandle() {
        $thing = $this->createEmptyThingMock();

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(true));
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->never())->method('canHandle');

        $chain = new ChainFilter([$filter1, $filter2]);
        $result = $chain->canHandle($thing);

        $this->assertTrue($result);
    }

    public function testSetOptions() {
        $options = $this->getMock('Asoc\Dadatata\Filter\OptionsInterface');

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('setOptions')->with($options);
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->once())->method('setOptions')->with($options);
        $filter3 = $this->createEmptyFilterMock();
        $filter3->expects($this->once())->method('setOptions')->with($options);

        $chain = new ChainFilter([$filter1, $filter2, $filter3]);
        $chain->setOptions($options);
    }

    protected function createEmptyFilterMock() {
        return $this->getMock('Asoc\Dadatata\Filter\FilterInterface');
    }

} 