<?php

namespace App\Services;

use App\Url as UrlModel;
use App\Xpath;
use App\Content;

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
            $t = $this->loadTexts($url, $xpath);
            $texts = array_merge($texts, $t === false ? $this->requestTexts($url, $xpath) : $t);
        }
        return $texts;
    }

    /**
     * Загрузка из бд если есть
     *
     * @param string $url
     * @param array $xpath
     * @return bool|string[]
     */
    private function loadTexts(string $url, array $xpath)
    {
        try {
            $texts = [];
            $u = UrlModel::where('url', $url)->firstOrFail();
            /** @var Xpath $x */
            $x = $u->xpath($xpath)->firstOrFail();
            foreach ($x->contents as $content) {
                $texts[] = $content->content;
            }
            return $texts;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Запрос из внешнего url
     *
     * @param string $url
     * @param array $xpath
     * @return string[]
     */
    private function requestTexts(string $url, array $xpath): array
    {
        $html = $this->send($url);
        $texts = [];
        foreach ($this->htmlParser->parse($html, $xpath) as $h) {
            $t = $this->htmlParser->strip($h);
            $texts[] = $t;
        }
        $this->saveTexts($url, $xpath, $texts);
        return $texts;
    }

    /**
     * Сохранение обработтанных текстов
     *
     * @param string $url
     * @param array $xpath
     * @param array $texts
     */
    private function saveTexts(string $url, array $xpath, array $texts)
    {
        try {
            $u = UrlModel::where('url', $url)->firstOrFail();
        } catch (\Exception $e) {
            $u = new UrlModel(['url' => $url]);
            $u->save();
        }
        try {
            /** @var Xpath $x */
            $x = $u->xpath($xpath)->firstOrFail();
        } catch (\Exception $e) {
            $x = (new Xpath(['url_id' => $u->id]))->setXpaths($xpath);
            $x->save();
        }
        foreach ($texts as $text) {
            $x->contents()->save(new Content(['content' => $text]));
        }
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
