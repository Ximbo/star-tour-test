<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\TextParserService;

class TextParserServiceTest extends TestCase
{
    /**
     * Test parse service
     *
     * @return void
     */
    public function testParse()
    {
        $expected = [[
            'url' => 'https://www.sunrise-tour.ru/russia/chernoe-more/tury/',
            'xpath' => [
                'id="main_content"',
                'class="main_block_of_content"',
                'class="mboc_text"'
            ],
        ]];
        $this->assertParseResults($this->getText(), $expected);

        $expected = [
            [
                'url' => 'http://google.ru',
                'xpath' => [
                    'id="first-id"',
                    'class="first-class"',
                ],
            ],
            [
                'url' => 'https://yandex.ru/other',
                'xpath' => [
                    'id="second-id"',
                    'class="second-class"'
                ],
            ]
        ];
        $this->assertParseResults($this->getText2(), $expected);
    }

    /**
     * @param string $text
     * @param array $expected
     */
    private function assertParseResults($text, $expected)
    {
        $service = $this->app->make(TextParserService::class);
        $results = $service->parse($text);
        $this->assertInternalType('array', $results);
        $this->assertNotEmpty($results);

        foreach ($results as $result) {
            $this->assertInternalType('array', $result);
            $this->assertNotEmpty($result);
            $this->assertArrayHasKey('url', $result);
            $this->assertArrayHasKey('xpath', $result);
        }

        $this->assertEquals($results, $expected);
    }

    /**
     * @return string
     */
    private function getText()
    {
        return 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s https://www.sunrise-tour.ru/russia/chernoe-more/tury/ standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type id="main_content"  specimen book. It has survived class="main_block_of_content" not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was populari class="mboc_text" sed in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum';
    }

    /**
     * @return string
     */
    private function getText2()
    {
        return 'Some string http://google.ru after link id="first-id" else class="first-class" after string https://yandex.ru/other and id="second-id" alse class="second-class"';
    }
}
