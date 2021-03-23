<?php

namespace Simba77\EmbedMedia;

interface EmbedProvider
{
    /**
     * The method should implement search for semantic output and replace it with html preview code.
     *
     * @param string $content
     * @return string
     */
    public function parse(string $content): string;
}
