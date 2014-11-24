<?php

namespace Asoc\Tests\Dadatata\Filter;

use Asoc\Dadatata\Filter\AggregateFilter;
use Asoc\Tests\Dadatata\BaseTestCase;

class AggregateFilterTest extends BaseTestCase
{
    public function testProcessWithoutAnyChildFilter()
    {
        $chain = new AggregateFilter([]);
        $this->assertNull($chain->process($this->createEmptyThingMock(), 'dontcare'));
    }

    public function testProcessWithOneChildFilterThatCannotHandleIt()
    {
        $thing = $this->createEmptyThingMock();

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(false));
        $filter1->expects($this->never())->method('process');

        $chain  = new AggregateFilter([$filter1]);
        $result = $chain->process($thing, 'dontcare');

        $this->assertNull($result);
    }

    public function testProcessWithOneChildFilterThatCanHandleIt()
    {
        $path           = uniqid();
        $thing          = $this->createEmptyThingMock();
        $expectedResult = ['foo'];

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(true));
        $filter1->expects($this->once())->method('process')->with($thing, $path, null)->will(
            $this->returnValue($expectedResult)
        );

        $chain  = new AggregateFilter([$filter1]);
        $result = $chain->process($thing, $path);

        $this->assertNotNull($result);
        $this->assertEquals($expectedResult, $result);
    }

    public function testProcessWithMultipleChildFilter()
    {
        $path            = uniqid();
        $options         = $this->getMock('Asoc\Dadatata\Filter\OptionsInterface');
        $thing           = $this->createEmptyThingMock();
        $expectedResult2 = ['step2'];

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(false));
        $filter1->expects($this->never())->method('process');
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(true));
        $filter2->expects($this->once())->method('process')->with($thing, $path, $options)->will(
            $this->returnValue($expectedResult2)
        );
        $filter3 = $this->createEmptyFilterMock();
        $filter3->expects($this->never())->method('canHandle');
        $filter3->expects($this->never())->method('process');

        $chain  = new AggregateFilter([$filter1, $filter2, $filter3]);
        $result = $chain->process($thing, $path, $options);

        $this->assertNotNull($result);
        $this->assertEquals($expectedResult2, $result);
    }

    public function testCanHandle()
    {
        $thing = $this->createEmptyThingMock();

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(false));
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->once())->method('canHandle')->with($thing)->will($this->returnValue(true));
        $filter3 = $this->createEmptyFilterMock();
        $filter3->expects($this->never())->method('canHandle');

        $chain  = new AggregateFilter([$filter1, $filter2]);
        $result = $chain->canHandle($thing);

        $this->assertTrue($result);
    }

    public function testSetOptions()
    {
        $options = $this->getMock('Asoc\Dadatata\Filter\OptionsInterface');

        $filter1 = $this->createEmptyFilterMock();
        $filter1->expects($this->once())->method('setOptions')->with($options);
        $filter2 = $this->createEmptyFilterMock();
        $filter2->expects($this->once())->method('setOptions')->with($options);
        $filter3 = $this->createEmptyFilterMock();
        $filter3->expects($this->once())->method('setOptions')->with($options);

        $chain = new AggregateFilter([$filter1, $filter2, $filter3]);
        $chain->setOptions($options);
    }

    protected function createEmptyFilterMock()
    {
        return $this->getMock('Asoc\Dadatata\Filter\FilterInterface');
    }
}