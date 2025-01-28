<?php

namespace App\Http\Controllers;

use App\Domain\Property\LivingSpaceType;
use App\Domain\Property\UpdatePropertyDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Agent;
use App\Models\City;
use App\Models\User;
use App\Persistence\Converters\DtoToModelConverter;
use Html2Text\Html2Text;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    public function index()
    {
        return view('tool.index');
    }

    public function CalcDensity(Request $request)
    {
        if ($request->isMethod('GET')) {
            if (isset($request->keywordInput)) {
                $html = new Html2Text($request->keywordInput);
                $text = $html->getText();

                $totalWordCount = str_word_count($text);
                $wordsAndOccurences = array_count_values(str_word_count($text, 1));

                var_dump($totalWordCount);
                var_dump($wordsAndOccurences);
            }
        }
    }

    public function testFunction(Request $request)
    {
        $data = DtoToModelConverter::toArray(new UpdatePropertyDto(
            id: 12,
            address: '',
        ));
        return response()->json([
            'data' => $data,
            'type' => gettype($data),
        ]);
    }
}

readonly class RealCoolClass {
    public function __construct(
        public ?int $bebra = null,
    )
    {
    }
};
