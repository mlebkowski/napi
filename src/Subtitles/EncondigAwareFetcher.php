<?php

namespace Nassau\Napi\Subtitles;

class EncondigAwareFetcher implements Fetcher
{

    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @param Fetcher $fetcher
     */
    public function __construct(Fetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }


    public function getSubtitles(\SplFileInfo $file, $language)
    {
        $result = $this->fetcher->getSubtitles($file, $language);

        if (mb_detect_encoding($result, 'UTF-8', true) !== "UTF-8") {
            $result = iconv("windows-1250", "UTF-8//IGNORE", $result);
        }

        return $result;
    }
}