<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

declare(strict_types=1);

namespace Simba77\EmbedMedia\Providers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Simba77\EmbedMedia\EmbedProvider;

class Youtube implements EmbedProvider
{
    /** @var string */
    protected $classes = '';

    /** @var array */
    protected $styles = [];

    public function __construct(array $options = [])
    {
        if (isset($options['classes'])) {
            $this->classes = (string) $options['classes'];
        }
        if (isset($options['styles'])) {
            $this->styles = (array) $options['styles'];
        }
    }

    /**
     * @inheritDoc
     * @psalm-suppress MixedOperand, PossiblyUndefinedMethod
     */
    public function parse(string $content): string
    {
        $document = new Document($content);
        try {
            /** @var \DiDom\Element[] $embeds */
            $embeds = $document->find('figure > oembed');
            foreach ($embeds as $embed) {
                $url = $embed->getAttribute('url', '');
                if (! empty($url)) {
                    $params = $this->parseUrl($url);
                    if (isset($params['video_code'])) {
                        $html_player = '<div' . $this->getStyles() . '>';
                        $html_player .= '<div class="' . $this->classes . '">';
                        $html_player .= '<iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/' . $params['video_code'] . (! empty($params['time']) ? '?start=' . $params['time'] : '') . '"></iframe>';
                        $html_player .= '</div></div>';
                        $parent = $embed->parent();
                        if ($parent !== null) {
                            $parent->setInnerHtml($html_player);
                        }
                    }
                }
            }
        } catch (InvalidSelectorException $e) {
        }

        // We replace it because the standard LIBXML_HTML_NOIMPLIED flag leads to unexpected behavior when working with nodes.
        return str_replace(["<html><body>\n", "\n</body></html>"], '', $document->html());
    }

    protected function getStyles(): string
    {
        $styles = '';
        foreach ($this->styles as $name => $value) {
            $styles .= $name . ':' . $value . ';';
        }
        if (empty($styles)) {
            return '';
        }
        return ' style="' . $styles . '"';
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
