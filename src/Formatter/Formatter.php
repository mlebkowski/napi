<?php

namespace Nassau\Napi\Formatter;

interface Formatter
{
    /**
     * @param string $source
     * @param float $fps
     * @return string
     */
    public function reformat($source, $fps);
}