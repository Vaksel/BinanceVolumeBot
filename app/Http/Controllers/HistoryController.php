<?php

namespace App\Http\Controllers;

use App\Models\Botman;
use App\Models\Profile;
use App\Models\SavedPairs;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Itstructure\GridView\DataProviders\EloquentDataProvider;

use App\Models\History;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function testBot()
    {

    }

    public function History()
    {

        $dataProvider = new EloquentDataProvider(History::query()->orderBy('created_at', 'desc'));

        $historyRecords = [];
        return view('history',
            compact('dataProvider', 'historyRecords'));
    }

    public function testSearch()
    {
        return view('searchtest');
    }

    public function signals(Request $request)
    {

        $followedPairs = $this->getSavedPairs(!empty($request->cookie('chosenProfile')) ? $request->cookie('chosenProfile') : 1);

        $profiles = Profile::select('id', 'name')->get();
        $chosenProfile = !empty($request->cookie('chosenProfile')) ? $request->cookie('chosenProfile') : 1;
        $chosenMode = !empty($_COOKIE['mode']) ? $_COOKIE['mode'] : 0;

        return view('signals', compact('followedPairs', 'profiles', 'chosenProfile', 'chosenMode'));
    }

    public function getSavedPairs($chosenProfile)
    {
        $profiles = SavedPairs::where(['profile_id' => $chosenProfile])->select('crypto_pair')->get()->toArray();

        return $profiles;
    }

    //Отформатировать вывод HTML в удобочитабельный
    public function historySearch(Request $request){
        $output = '';
        $sortedOutput = '';

        $profile_id = $request->data['requested_profile'] ?? 1;

        $profile_id_all_pairs = $request->data['requested_profile_all_pairs'] ?? 1;

        $mode = $request->data['mode'] ?? 0;

        $modeAllSignals = $request->data['modeAllSignals'] ?? 0;


        $timeFrameTransformativeArr = [
            'Минута','3 мин','5 мин','15 мин','30 мин','Час','2 часа','4 часа','6 час',
            '8 час','12 час','День','3 дня','Неделя','Месяц',
        ];
        try {
            $realTimeHistoryQty = $request->data['realTimeHistoryQty'];
        } catch (Exception $e) {
            $realTimeHistoryQty = null;
        }

        try {
            $realTimeHistoryQtyAllSignals = $request->data['realTimeHistoryQtyAllSignals'];
        } catch (Exception $e) {
            $realTimeHistoryQtyAllSignals = null;
        }

        if(!$realTimeHistoryQty) {
            $data = History::where('ticker', 'LIKE', '%'.$request->data['historySearchPair'].'%')->
            where('timeframe', 'LIKE', '%'.$request->data['historySearchTimeframe'].'%')->
            where('volume_status', 'LIKE', '%'.$request->data['historySearchCandleStatus'].'%')->
            where('volume', 'LIKE', '%'.$request->data['historySearchVolume'].'%')->
            where('compare_volume', 'LIKE', '%'.$request->data['historySearchCompareVolume'].'%')->
            where('created_at', 'LIKE', '%'.$request->data['historySearchDatetime'].'%')->
            where('profile_id', 'LIKE', '%'.$profile_id.'%')->
            latest()->get();

            if($data){
                foreach($data as $item){
                    $choosenClass = $item->choosen ? 'choosen' : '';
                    $output.="<tr class='{$choosenClass}'>".
                        '<td>'.$item->ticker.'</td>'.
                        '<td>'.$timeFrameTransformativeArr[$item->timeframe].'</td>'.
                        '<td>'.$item->volume_status.'</td>'.
                        '<td>'.$item->volume.'</td>'.
                        '<td>'.$item->compare_volume.'</td>'.
                        '<td>'.$item->created_at.'</td>'.
                        '</tr>';
                }
                return Response($output);
            }
        }
        else {
            $pairForSort = $request->data['pairForSort'];
            $pairForSortManualInput = $request->data['pairForSortInput'];

            if(!empty($pairForSortManualInput))
            {
                $pairForSort = $pairForSortManualInput;
            }

            $data = History::latest()->where(['profile_id' => $profile_id_all_pairs, 'record_type' => $modeAllSignals])->take($realTimeHistoryQtyAllSignals)->get();
            $savedPairs = SavedPairs::where(['profile_id' => $profile_id])->select('crypto_pair')->get()->toArray();
            $sortedData = History::latest()->where(['profile_id' => $profile_id, 'ticker' => $pairForSort, 'record_type' => $mode])->take($realTimeHistoryQtyAllSignals)->get();

            if($data) {
                foreach ($data as $item)
                {
                    $choosenClass = $item->choosen ? 'choosen' : '';

                    $differencePercent = $item->volume_difference / ($item->compare_volume / 100);

                    $compareResultHTML = '<strong>Разница</strong>' . ': ' . '<strong>' . $item->volume_difference .
                        ' (' . sprintf("%01.2f", $differencePercent) . '%' . ')' . '</strong>';

                    if($item->record_type === 1 || $item->record_type === 2)
                    {
                        $compareResultHTML = '<strong>Разница</strong>' . ': ' . '<strong>' . $item->volume_difference .
                            '%'  . '</strong>';
                    }

                    $output .= "<div class='{$item->volume_status} {$choosenClass}'>" .
                        '<strong>' . $item->ticker . '</strong>' . '<br> ' .
                        '<strong>Обьем</strong>' . ': ' . '<strong>' . $item->volume . '</strong>' . '<br>' . 'Тайм-фрейм: '
                        . $timeFrameTransformativeArr[$item->timeframe] . '<br>' .
                        $compareResultHTML . '<br>' . $item->created_at .
                        '<br>' . "<a href='https://www.binance.com/ru/trade/{$item->ticker}?layout=basic'>Просмотреть</a>" .
                        '</div>';
                }
            }



            if($sortedData)
            {
                foreach($sortedData as $item)
                {
                    $choosenClass = $item->choosen ? 'choosen' : '';

                    $differencePercent = $item->volume_difference / ($item->compare_volume / 100);


                    $compareResultHTML = '<strong>Разница</strong>' . ': ' . '<strong>' . $item->volume_difference .
                        ' (' . sprintf("%01.2f", $differencePercent) . '%' . ')' . '</strong>';

                    if($item->record_type === 1 || $item->record_type === 2)
                    {
                        $compareResultHTML = '<strong>Разница</strong>' . ': ' . '<strong>' . $item->volume_difference .
                            '%'  . '</strong>';
                    }

                    $sortedOutput.="<div class='{$item->volume_status} {$choosenClass}'>".
                        '<strong>'.$item->ticker.'</strong>'.'<br> ' .
                        '<strong>Обьем</strong>' . ': ' . '<strong>'.$item->volume.'</strong>'.'<br>' .'Тайм-фрейм: '
                        .$timeFrameTransformativeArr[$item->timeframe]. '<br>' .
                        $compareResultHTML .'<br>' . $item->created_at.
                        '<br>' . "<a href='https://www.binance.com/ru/trade/{$item->ticker}?layout=basic'>Просмотреть</a>".
                        '</div>';
                }
            }

            return Response(['response' => $output,'sortedOutput' => $sortedOutput, 'savedPairs' => $savedPairs]);
        }

    }

    protected function sendHistoryToBot($history)
    {
        $botmanResponse = Botman::sendSingleMessage($history['volume'], ['recipient_id' => 382142310]);

        return $botmanResponse;
    }

    public function writeDataHistory(Request $request)
    {
        $history = (array)$request->data;
//
//        if(isset((array)$request->data['finish']))

        if(!empty($history['volume']) && !empty($history['compare_volume']) && empty($history['volume_difference']))
        {
            $history['volume_difference'] = $history['volume'] - $history['compare_volume'];
        }

        $response = json_encode(['warning' => true,'msg' => 'Был передан пустой массив']);
        if(!empty($history))
        {
            try
            {
                DB::table("histories")->insert($history);
//
//                $response = $this->sendHistoryToBot($history);

//                if($response['status'] === 'error')
//                {
//                    return $this->responseHelper($response['status'], $response['msg'], $response['error']);
//                }

                $history = json_encode($history);
                $response = "Передача прошла успешно, переданные данные: {$history}";
            }
            catch (Exception $e)
            {
                $response = json_encode(['error' => true, 'msg' => "При попытке записать данные в бд произошла ошибка: {$e}"]);
            }
        };
//
        return Response($response);
    }

    public function deleteDataHistory(Request $request)
    {
        if(empty($request->data['deleteAll']))
        {
            $startDateTime = $request->data['startDateTime'];
            $finishDateTime = $request->data['finishDateTime'];

            try {
                History::select('*')->whereBetween('created_at', [$startDateTime,$finishDateTime])->delete();
            }
            catch(\Exception $e)
            {
                return Response($this->responseHelper('error', 'Произошла ошибка при удалении, вывод в консоли', $e));
            }
        }
        else
        {
            try {
                History::select('*')->delete();
            }
            catch (\Exception $e)
            {
                return Response($this->responseHelper('error', 'Произошла ошибка при удалении, вывод в консоли', $e));
            }
        }

        return Response($this->responseHelper('success', 'Удаление прошло успешно, обновите страницу'));

    }
}
