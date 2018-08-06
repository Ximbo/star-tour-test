<?php

namespace App\Services;

/**
 * Class ParserService
 * @package App\Services
 */
class ParserService
{
    /** @var \GuzzleHttp\Client */
    private $client;

    /** @var TextParserService */
    private $textParser;

    /** @var HtmlParserService */
    private $htmlParser;

    /**
     * @param \GuzzleHttp\Client $client
     * @param TextParserService $textParser
     * @param HtmlParserService $htmlParser
     */
    public function __construct(
        \GuzzleHttp\Client $client,
        TextParserService $textParser,
        HtmlParserService $htmlParser
    ) {
        $this->client = $client;
        $this->textParser = $textParser;
        $this->htmlParser = $htmlParser;
    }

    /**
     * @param string $text
     * @return string[]
     * @throws \Exception
     */
    public function parse(string $text): array
    {
        $texts = [];
        $parses = $this->textParser->parse($text);
        foreach ($parses as $parse) {
            $url = $parse['url'];
            $xpath = $parse['xpath'];
            $html = $this->send($url);
            foreach ($this->htmlParser->parse($html, $xpath) as $h) {
                $texts[] = $this->htmlParser->strip($h);
            }
        }
        return $texts;
    }

    /**
     * Отправлет запрос на указанный адрес
     *
     * @param string $url
     * @return string
     */
    private function send($url): string
    {
        return $this->client->get($url)->getBody()->getContents();
    }
}
