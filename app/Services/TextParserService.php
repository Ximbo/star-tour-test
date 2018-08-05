<?php

namespace App\Services;

/**
 * Разбирает переданный текст
 *
 * Class TextParserService
 * @package App\Services
 */
class TextParserService
{
    /**
     * Парсит текст и вытаскивает из него данные в виде такого массива
     *
     * [
     *  ...
     *  [
     *      'url' => 'http...',
     *      'xpath' => [
     *          'id="some_id"',
     *          'class="some_class other_class"',
     *          'id="other_id"',
     *          'class="another_class"',
     *          ...
     *      ]
     *  ],
     *  ...
     * ]
     *
     * @param string $text
     * @return array
     * @throws \Exception
     */
    public function parse(string $text): array
    {
        if (! preg_match_all($this->getUrlPattern(), $text, $urls)) {
            throw new \InvalidArgumentException('В тексте отсутствуют url');
        }
        $parts = preg_split($this->getUrlPattern(), $text);
        $parse = [];
        foreach ($urls[0] as $k => $url) {
            $xpath = $this->makeXpath($parts[$k + 1]);
            $parse[] = [
                'url' => $url,
                'xpath' => $xpath,
            ];
        }
        return $parse;
    }

    /**
     * В $text что-то вроде такого
     *  'after link id="first-id" else class="first-class" after string'
     *
     * @param string $text
     * @return array
     */
    private function makeXpath(string $text): array
    {
        preg_match_all($this->getXpathPattern(), $text, $matches);
        return $matches[0];
    }

    /**
     * @return string
     * @see http://urlregex.com/
     */
    private function getUrlPattern()
    {
        return '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu';
    }

    /**
     * @return string
     */
    private function getXpathPattern()
    {
        return '/(id|class)=("|\')(\S+)("|\')+/iu';
    }
}
