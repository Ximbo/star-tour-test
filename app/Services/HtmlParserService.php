<?php

namespace App\Services;

/**
 * Разбирает html по xpath
 *
 * Class HtmlParserService
 * @package App\Services
 */
class HtmlParserService
{
    /**
     * Нужно получить информацию из $html из блока указанного в $xpaths
     * <div id="main_content">
     *      <div class="main_block_of_content" >
     *          <div class="mboc_text">
     *              Блок для парсинга
     *          </div>
     *      </div>
     * </div>
     *
     * Формат $xpaths
     * [
     *  'id="main_content"',
     *  'class="main_block_of_content"',
     *  'class="mboc_text"'
     *   ...
     * ]
     *
     * @param string $html
     * @param array $xpaths
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function parse(string $html, array $xpaths): array
    {
        if (empty($xpaths)) {
            throw new \InvalidArgumentException('Не указаны селекторы поиска');
        }
        $doc = new \DOMDocument;
        \libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        \libxml_use_internal_errors(false);
        $xpath = new \DOMXPath($doc);
        // Like $query = "//*[@id='main_content']//*[@class='main_block_of_content']//*[@class='mboc_text']";
        $query = '';
        foreach ($xpaths as $p) {
            $query .= sprintf("//*[@%s]", $p);
        }
        $strings = [];
        foreach ($xpath->query($query) as $node) {
            /** @var \DOMElement $node */
            $s = [];
            foreach ($node->childNodes as $c) {
                /** @var \DOMElement $c */
                $s[] = $c->ownerDocument->saveHTML($c);
            }
            $strings[] = implode('', $s);
        }
        return $strings;
    }

    /**
     * Вырезает из указанного html-текста html теги кроме строчных, заголовков, таблиц и параграфов,
     *  а также инлайновые стили
     *
     * @param $html
     * @return string
     */
    public function strip(string $html): string
    {
        $html = strip_tags($html, implode('', $this->getAllowedTags()));
        $html = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
        $html = preg_replace('/(<[^>]+) style=\'.*?\'/i', '$1', $html);

        return $html;
    }

    /**
     * @return array
     */
    private function getAllowedTags(): array
    {
        return [
            '<small>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>', '<p>',
            '<table>', '<thead>', '<tbody>', '<tr>', '<td>', '<th>'
        ];
    }
}
