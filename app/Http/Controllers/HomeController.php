<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TextRequest;
use App\Services\ParserService;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * @param TextRequest $request
     * @param ParserService $parser
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function parse(TextRequest $request, ParserService $parser)
    {
        $text = $request->get('text');
        $texts = [];
        foreach ($parser->parse($text) as $text) {
            $texts[] = compact('text');
        }

        return response()->json($texts);
    }
}
