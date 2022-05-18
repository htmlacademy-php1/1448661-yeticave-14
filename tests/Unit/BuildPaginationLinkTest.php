<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BuildPaginationLinkTest extends TestCase
{
    public function testBuildPaginationLink()
    {
        $requestData = ['search' => 'test'];
        $result = buildPaginationLink('search.php', 3, $requestData);
        $this->assertEquals('/search.php?search=test&page=3', $result);

        $requestData = ['search' => 'test', 'page' => '2'];
        $result = buildPaginationLink('search.php', 4, $requestData);
        $this->assertEquals('/search.php?search=test&page=4', $result);

        $requestData = [];
        $result = buildPaginationLink('all-lots.php', 5, $requestData);
        $this->assertEquals('/all-lots.php?page=5', $result);

        $requestData = ['search' => 'test'];
        $result = buildPaginationLink('search.php', 1, $requestData);
        $this->assertEquals('/search.php?search=test', $result);
    }
}
