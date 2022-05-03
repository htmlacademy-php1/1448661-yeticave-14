<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class GetDtRangeTest extends TestCase
{
    public function testGetDtRange()
    {
        $result = getDtRange('2022-03-02', '2022-03-01 12:00');
        $this->assertEquals([12, 0], $result);

        $result = getDtRange('2022-02-02', '2022-03-01 12:00');
        $this->assertEquals([0, 0], $result);

        $result = getDtRange('2022-03-02', '2022-03-01 20:30');
        $this->assertEquals([3, 30], $result);

        $result = getDtRange('2022-03-08', '2022-03-01 00:00');
        $this->assertEquals([168, 0], $result);
    }
}

