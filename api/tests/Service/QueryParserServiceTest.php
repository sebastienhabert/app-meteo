<?php

namespace App\Tests\Service;

use App\Service\QueryParserService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QueryParserServiceTest extends TestCase
{
    private QueryParserService $parser;

    protected function setUp(): void
    {
        $this->parser = new QueryParserService();
    }

    public function testIsCoordinates(): void
    {
        $this->assertTrue($this->parser->isCoordinates('47.31,5.01'));
        $this->assertTrue($this->parser->isCoordinates('-33.86,151.20'));
        $this->assertTrue($this->parser->isCoordinates(' 47.31 , 5.01 '));
        $this->assertFalse($this->parser->isCoordinates('Dijon'));
        $this->assertFalse($this->parser->isCoordinates('abc,123'));
    }

    public function testExtractCoordinatesValid(): void
    {
        [$lat, $lon] = $this->parser->extractCoordinates('47.31,5.01');
        $this->assertSame(47.31, $lat);
        $this->assertSame(5.01, $lon);

        [$lat, $lon] = $this->parser->extractCoordinates('  -33.86 , 151.20 ');
        $this->assertSame(-33.86, $lat);
        $this->assertSame(151.20, $lon);
    }

    public function testExtractCoordinatesInvalidThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->parser->extractCoordinates('Dijon');
    }
}
