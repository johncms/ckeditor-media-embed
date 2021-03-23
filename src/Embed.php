<?php

declare(strict_types=1);

namespace Simba77\EmbedMedia;

use Simba77\EmbedMedia\Exception\RuntimeException;

class Embed
{
    /** @var array */
    protected $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param string $html
     * @return string
     */
    public function embedMedia(string $html): string
    {
        foreach ($this->providers as $key => $provider) {
            if ($provider instanceof EmbedProvider) {
                $html = $provider->parse($html);
            } else {
                throw new RuntimeException(sprintf('Provider #%s is not an implementation of %s.', $key, EmbedProvider::class));
            }
        }

        return $html;
    }
}
