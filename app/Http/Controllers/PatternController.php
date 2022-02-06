<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

use App\Models\Patterns;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Mockery\Exception;


class PatternController extends Controller
{
    public function patterns(Request $request)
    {
        $dataProvider = new EloquentDataProvider(Patterns::query()->where(['profile_id' => $request->cookie('chosenProfile')])->orderBy('created_at', 'desc'));

//        ddd($dataProvider);

        $historyRecords = [];

        return view('patterns',compact('dataProvider', 'historyRecords'));
    }

    public function writePatternSignal(Request $request)
    {
        if(empty($request->all()))
        {
            return $this->responseHelper('warning', 'Записывать нечего');
        }

        try
        {
            $timeFrameTransformativeArr = [
                'Минута','3 мин','5 мин','15 мин','30 мин','Час','2 часа','4 часа','6 час',
                '8 час','12 час','День','3 дня','Неделя','Месяц',
            ];

            $model = new Patterns();

            $model->name = $request->name;
            $model->pair = $request->pair;
            $model->timeframe = (int)$request->timeframe;
            $model->created_at = date('Y-m-d H:i:s', (int)$request->created_at);
            $model->profile_id = $request->profile_id;
            $model->chosen = $request->chosen;

            $model->save();

            if($request->name === 'Медвежье поглощение')
            {
                return $this->responseHelper('error', "Паттерн по паре '{$model->pair}' и таймфрейму '{$timeFrameTransformativeArr[$model->timeframe]}' под именем '{$model->name}' был успешно зафиксирован");
            }
            else
            {
                return $this->responseHelper('success', "Паттерн по паре '{$model->pair}' и таймфрейму '{$timeFrameTransformativeArr[$model->timeframe]}' под именем '{$model->name}' был успешно зафиксирован");
            }

        }
        catch (\Exception $e)
        {
            return $this->responseHelper('error', 'Произошла ошибка при записи паттерна, вывод в консоли', ['error' => $e]);
        }
    }

    public function deletePatterns(Request $request)
    {
        if(empty($request->data['deleteAll']))
        {
            $startDateTime = $request->data['startDateTime'];
            $finishDateTime = $request->data['finishDateTime'];

            try {
                Patterns::select('*')->whereBetween('created_at', [$startDateTime,$finishDateTime])->delete();
            }
            catch(\Exception $e)
            {
                return Response($this->responseHelper('error', 'Произошла ошибка при удалении, вывод в консоли', $e));
            }
        }
        else
        {
            try {
                Patterns::select('*')->delete();
            }
            catch (\Exception $e)
            {
                return Response($this->responseHelper('error', 'Произошла ошибка при удалении, вывод в консоли', $e));
            }
        }

        return Response($this->responseHelper('success', 'Удаление прошло успешно, обновите страницу'));

    }
}
