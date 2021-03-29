<?php

declare(strict_types=1);

namespace Simba77\EmbedMedia\Providers;

use DOMDocument;
use Simba77\EmbedMedia\EmbedProvider;

class Youtube implements EmbedProvider
{
    /** @var string */
    protected $player_max_width = '600px';

    /** @var string */
    protected $player_classes = '';

    public function __construct(array $options = [])
    {
        if (isset($options['player_max_width'])) {
            $this->player_max_width = (string) $options['player_max_width'];
        }
        if (isset($options['player_classes'])) {
            $this->player_classes = (string) $options['player_classes'];
        }
    }

    /**
     * @inheritDoc
     * @psalm-suppress MixedOperand
     */
    public function parse(string $content): string
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $embeds = $doc->getElementsByTagName('oembed');
        foreach ($embeds as $embed) {
            $url_attr = $embed->attributes->getNamedItem('url');
            if ($url_attr !== null) {
                $params = $this->parseUrl($url_attr->value);
                if (isset($params['video_code'])) {
                    $html_player = '<div style="max-width: ' . $this->player_max_width . '"><div class="' . $this->player_classes . '">' .
                        '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/' . $params['video_code'] . (! empty($params['time']) ? '?start=' . $params['time'] : '') . '"></iframe>' .
                        '</div></div>';
                    $content = str_replace('<oembed url="' . $url_attr->value . '"></oembed>', $html_player, $content);
                }
            }
        }

        return $content;
    }

    /**
     * @param string $url
     * @return array {video_code: string, time: string}
     */
    protected function parseUrl(string $url): array
    {
        $url_params = [];
        $allowed_hosts = [
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'youtu.be',
            'www.youtu.be',
        ];

        $parsed_url = parse_url($url);
        if (isset($parsed_url['host']) && in_array($parsed_url['host'], $allowed_hosts)) {
            if (! empty($parsed_url['query'])) {
                parse_str($parsed_url['query'], $params);
                if (! empty($params['t'])) {
                    $url_params['time'] = (string) $params['t'];
                }
                if (! empty($params['v'])) {
                    $url_params['video_code'] = (string) $params['v'];
                }
            }

            if (! isset($url_params['video_code']) && ! empty($parsed_url['path'])) {
                // For youtu.be
                $url_params['video_code'] = ltrim($parsed_url['path'], '/');
            }
        }

        return $url_params;
    }
}
