<?php

declare(strict_types=1);

namespace Simba77\EmbedMediaTest\Providers;

use PHPUnit\Framework\TestCase;
use Simba77\EmbedMedia\Providers\Youtube;

class YoutubeTest extends TestCase
{
    /**
     * @var string
     */
    protected $html_string = '';

    protected function setUp(): void
    {
        $this->html_string = '<figure class="media"><oembed url="https://youtu.be/8ZLSKEmbt0Y?t=75"></oembed></figure><figure class="media"><oembed url="https://youtu.be/8ZLSKEmbt0Y"></oembed></figure>';
    }

    public function testParse(): void
    {
        $youtube = new Youtube(['classes' => 'player_classes']);
        $content = $youtube->parse($this->html_string);
        $this->assertEquals(
            '<figure class="media"><div style="max-width: 600px"><div class="player_classes">' .
            '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/8ZLSKEmbt0Y?start=75"></iframe></div></div></figure><figure class="media"><div style="max-width: 600px"><div class="player_classes">' .
            '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/8ZLSKEmbt0Y"></iframe></div></div></figure>',
            $content
        );

        $youtube = new Youtube(['max_width' => '900px']);
        $content = $youtube->parse($this->html_string);
        $this->assertEquals(
            '<figure class="media"><div style="max-width: 900px"><div class="">' .
            '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/8ZLSKEmbt0Y?start=75"></iframe></div></div></figure><figure class="media"><div style="max-width: 900px"><div class="">' .
            '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/8ZLSKEmbt0Y"></iframe></div></div></figure>',
            $content
        );
    }
}
