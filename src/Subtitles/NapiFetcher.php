<?php

namespace Nassau\Napi\Subtitles;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;

class NapiFetcher implements Fetcher
{
    const ENDPOINT = 'http://www.napiprojekt.pl/api/api-napiprojekt3.php';

    /**
     * @var Client
     */
    private $client;

    /**
     * NapiFetcher constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getSubtitles(\SplFileInfo $file, $language)
    {
        $hash = md5(fread(fopen($file->getRealPath(), 'r'), 10 * 1024 * 1024));

        $params = [
            'client' => 'Napiprojekt',
            'downloaded_subtitles_id' => $hash,
            'downloaded_subtitles_lang' => strtolower($language),
            'downloaded_subtitles_txt' => '1',
            'mode' => '1',
        ];

        $url = (new Uri(self::ENDPOINT))->withQuery(http_build_query($params));

        $result = (string)$this->client->post($url, ['form_params' => $params])->getBody();

        if (strlen($result) < 1024) {
            return null;
        }

        return base64_decode((string)(new \SimpleXMLElement($result))->xpath('//content')[0]);

    }

}