<?php

namespace Nassau\Napi\Formatter;

interface Formatter
{
    /**
     * @param string $source
     * @param FormatterOptions $options
     * @return string
     */
    public function reformat($source, FormatterOptions $options);
}