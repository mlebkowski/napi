<?php

namespace Nassau\Napi;

use Nassau\Napi\Formatter\Formatter;
use Nassau\Napi\Formatter\FormatterOptions;
use Nassau\Napi\Fps\FpsReader;
use Nassau\Napi\Subtitles\Fetcher;

class NapiService
{
    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var FpsReader
     */
    private $fpsReader;

    /**
     * NapiService constructor.
     * @param Fetcher $fetcher
     * @param Formatter $formatter
     * @param FpsReader $fpsReader
     */
    public function __construct(Fetcher $fetcher, Formatter $formatter, FpsReader $fpsReader)
    {
        $this->fetcher = $fetcher;
        $this->formatter = $formatter;
        $this->fpsReader = $fpsReader;
    }


    public function getSubtitles(\SplFileInfo $file, $language, FormatterOptions $options)
    {
        $subtitles = $this->fetcher->getSubtitles($file, $language);

        if (!$subtitles) {
            return false;
        }

        $options = $options->withFps($this->fpsReader->getFps($file) ?: $options->getFps());

        $subtitles = $this->formatter->reformat($subtitles, $options);

        $target = preg_replace('/\.[^.]+$/', sprintf('.%s.srt', strtolower($language)), $file->getRealPath());

        file_put_contents($target, $subtitles);

        return true;
    }
}