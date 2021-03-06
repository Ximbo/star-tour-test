<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\HtmlParserService;

class HtmlParserServiceTest extends TestCase
{
    /**
     * Test parse
     *
     * @return void
     */
    public function testParse()
    {
        $service = $this->getService();
        $xpaths = [
            'id="main_content"',
            'class="main_block_of_content"',
            'class="mboc_text"'
        ];
        $results = $service->parse($this->getHtml(), $xpaths);
        $expected = [
            '<p>mboc_text_1</p>',
            '<p><b>H1</b><br>mboc_text_21<br><br><b>H2</b><br>mboc_text_22</p>',
            'mboc_text31<p>mboc_text32</p>'
        ];
        $this->assertEquals($expected, $results);
    }

    /**
     * Test strip
     *
     * @return void
     */
    public function testStrip()
    {
        $service = $this->getService();
        $html = implode('', [
            '<small>SmallText</small>',
            '<h1>H1</h1>',
            '<table style="color: red;"><tbody><tr><td>TD</td></tr></tbody></table>',
            '<p><b>H1</b><br>mboc_text_21<br><br><b>H2</b><br>mboc_text_22</p>'
        ]);
        $result = $service->strip($html);
        $expected = implode('', [
            '<small>SmallText</small>',
            '<h1>H1</h1>',
            '<table><tbody><tr><td>TD</td></tr></tbody></table>',
            '<p>H1mboc_text_21H2mboc_text_22</p>'
        ]);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return HtmlParserService
     */
    private function getService()
    {
        /** @var HtmlParserService $service */
        return $this->app->make(HtmlParserService::class);
    }

    /**
     * @return string
     */
    private function getHtml()
    {
        return
<<<HTML
<div id="main_content">
    <div class="wrapper">
        <div class="breadcrumbST"></div>
        <div class="content">
            <div class="wrapper_block_of_content">
                <div class="searchBlock">searchBlock</div>
                <div class="main_block_of_content">
                    <div class="blockOfGeneralInforamtionCountry">blockOfGeneralInforamtionCountry</div>
                    <h1>Pagetitle</h1>
                    <div class="mboc_hr">mboc_hr</div>
                    <div class="mboc_text"><p>mboc_text_1</p></div>
                    <div class="mboc_hr">mboc_hr</div>
                    <div class="mboc_text"><p><b>H1</b><br>mboc_text_21<br><br><b>H2</b><br>mboc_text_22</p></div>
                    <div class="mboc_hr">mboc_hr</div>
                    <div class="mboc_text">mboc_text31<p>mboc_text32</p></div>
                </div>
            </div>
            
            <div class="blockOfSubscribes">blockOfSubscribes</div>
            <div class="blockOfNews">blockOfNews</div>
            <div class="blockOfCountries">blockOfCountries</div>
        </div>
    </div>
</div>
	
HTML;
    }
}
