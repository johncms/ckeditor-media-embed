<?php

declare(strict_types=1);

namespace Simba77\EmbedMediaTest;

use PHPUnit\Framework\TestCase;
use Simba77\EmbedMedia\Embed;
use Simba77\EmbedMedia\Exception\RuntimeException;

class EmbedTest extends TestCase
{
    public function testEmbedMedia(): void
    {
        $embed = new Embed([]);
        $html = $embed->embedMedia('test string');
        $this->assertEquals('test string', $html);
    }

    /**
     * @noinspection PhpParamsInspection
     * @psalm-suppress InvalidArgument
     */
    public function testEmbedMediaException(): void
    {
        $this->expectException(RuntimeException::class);
        $providers = [1];
        $embed = new Embed($providers);
        $embed->embedMedia('');
    }
}
