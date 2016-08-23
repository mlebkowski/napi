<?php

namespace Nassau\Napi\Subtitles;

interface Fetcher
{
    /**
     * @param \SplFileInfo $file
     * @param string $language
     * @return string|null
     */
    public function getSubtitles(\SplFileInfo $file, $language);
}