<?php

namespace Nassau\Napi\Fps;

interface FpsReader
{
    /**
     * @param \SplFileInfo $file
     * @return float|null
     */
    public function getFps(\SplFileInfo $file);
}