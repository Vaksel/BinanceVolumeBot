<?php


namespace App\Http\Controllers;


use App\Jobs\BinanceSocketHandlingJob;
use App\Models\PatternsList;
use App\Models\Profile;
use App\Models\ReloadSignal;
use App\Models\SavedPairs;
use App\Models\Settings;
use App\Models\Ticker;

use Exception;
//use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoreController extends Controller
{
    public $reloadSignal;

    public function __construct()
    {
        exec('php /artisan view:clear');
    }

    public function serverProcessingToggler()
    {
        $processing_record = Settings::find(1);

        if($processing_record->value === 'client')
        {
            $processing_record->value = 'server';

            try {
                $processing_record->save();
            }
            catch(\Exception $e)
            {
                return Response($e);
            }



            $job = new BinanceSocketHandlingJob();
            $this->dispatch($job);

            return $this->responseHelper('success', 'Обработка на сервере успешно активирована, ожидайте прихода сигналов', ['processing_method' => 'client']);
        }
        else
        {
            $processing_record->value = 'client';

            $processing_record->save();

            return $this->responseHelper('success', 'Обработка на клиенте успешно активирована, ожидайте прихода сигналов', ['processing_method' => 'server']);
        }
    }

    ////////VIEW FUNCTIONS////////
    ///
    public function Index(Request $request)
    {
        $this->syncTickersFromDBwithTickersFromApi();

        $timeFrameTransformativeArr = [
            '1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d',
            '3d','1w','1M'
        ];

        $chosenProfile = $request->cookie('chosenProfile');
        $mode = !empty($_COOKIE['mode']) ? (int)$_COOKIE['mode'] : 0;
        $bombingTimeframe = !empty((int)$request->cookie('bombingTimeframe')) ? (int)$request->cookie('bombingTimeframe') : 0;



        $profiles = Profile::all()->toArray();
        $patternsList = PatternsList::get('*');
        $patternsListJSON = cookie('patternsListJSON', json_encode($patternsList->toArray(), JSON_UNESCAPED_UNICODE, 10), 0,null,null,false, false, false);


        $currentProfile = $this->checkProfileAvailabilityAndGetDefaultIfEmpty($chosenProfile);

        $chosenProfile = $currentProfile->id;

        $cookie = cookie('chosenProfile', $currentProfile->id, 0,null,null,false, false, false);

        $savedFollows = $this->readSavedFollows($currentProfile->id, $mode);



        $json = json_encode($patternsList->toArray(), JSON_UNESCAPED_UNICODE, 10);

//        Cache::flush();

        return response(view('main', compact('timeFrameTransformativeArr', 'profiles', 'savedFollows', 'chosenProfile', 'patternsList', 'patternsListJSON', 'json', 'mode', 'bombingTimeframe')))
            ->withCookie($cookie)
            ->withCookie($patternsListJSON)
            ->header('Cache-Control', 'no-cache');
//            ->header('Cache-Control', 'no-store')
//            ->header('Cache-Control', 'must-revalidate');
    }

    ////////OFFICIAL FUNCTIONS//////////
    ///
    ///////1.CHECKING FOR UPDATES ON BINANCE////////
    ///Нужно перебрать методы и удалить неиспользующиеся 15.06.2021
    public function takeAllBinanceTickersFromApi()
    {
        $url = 'https://api.binance.com/api/v3/ticker/price';

        $options = array([
            'symbol' => 'BTCUSDT'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        //print_r($url.'?'.http_build_query($options));

        $response = curl_exec($ch);
        curl_close($ch);

//        foreach(json_decode($response) as $obj)
//        {
//            if($obj['symbol'] === 'BCHSVUSDT')
//            {
//                ddd($obj);
//            }
//        }
//
//        ddd(json_decode($response));

        return json_decode($response);
    }
    private function checkProfileAvailabilityAndGetDefaultIfEmpty($profile_id)
    {
        try
        {
            $profile = Profile::where(['id' => $profile_id])->first();
        }
        catch(Exception $e)
        {
            $profile = Profile::find(1)->first();
        }

        if(empty($profile))
        {
            $profile = Profile::find(1)->first();
        }

        return $profile;
    }
    private function takeAllBinanceTickersFromDB()
    {
        $tickers = Ticker::all()->toArray();

        $tickerArr = [];
        foreach ($tickers as $item)
        {
            $tickerArr[] = $item['symbol'];
        }

        return $tickerArr;
    }

    private function syncTickersFromDBwithTickersFromApi()
    {
        $apiTickers = $this->takeAllBinanceTickersFromApi(); //indexed array with objects {'symbol' : 'USDBTC', 'price' : 35000}

        DB::table('tickers')->truncate();

        foreach($apiTickers as $item)
        {
            $dbTickerRow['symbol'] = $item->symbol;
            $dbTickerRow['show'] = false;

            $dbLoadArray[] = $dbTickerRow; //indexed array with symbols
        }

        DB::table('tickers')->insert($dbLoadArray);

        return true;
    }

    public function getTickersInJSON()
    {
        $apiTickers = $this->takeAllBinanceTickersFromApi(); //indexed array with objects {'symbol' : 'USDBTC', 'price' : 35000}

        $dbLoadArray = [];
        foreach($apiTickers as $item)
        {
            $dbTickerRow['symbol'] = $item->symbol;

            $dbLoadArray[] = $dbTickerRow; //indexed array with symbols
        }

        return json_encode($dbLoadArray);
    }

    private function compareTickersFromDBAndFromApi()
    {
        $apiTickers = $this->takeAllBinanceTickersFromApi(); //indexed array with objects {'symbol' : 'USDBTC', 'price' : 35000}
//        $dbTickers = $this->takeAllBinanceTickersFromDB(); //indexed array with symbols

        foreach($apiTickers as $item)
        {
            $dbSubArray['symbol'] = $item;
            $dbSubArray['show'] = false;

            $apiSymbols[] = $dbSubArray; //indexed array with symbols
        }

        $resultOfComparing = array_diff($dbTickers, $apiSymbols); //indexed array with symbols  which shows characters that are not in Response from Api

        return $resultOfComparing;
    }

    private function makeCorrectQueryForDB()
    {
        $tickersToBeAdded = $this->compareTickersFromApiAndFromDB(); //indexed array with symbols which shows characters that are not in DB

        $dbLoadArray = [];
        $dbSubArray = [];
        foreach ($tickersToBeAdded as $item)
        {
            $dbSubArray['symbol'] = $item;
            $dbSubArray['show'] = false;

            $dbLoadArray[] = $dbSubArray;
        }

        return $dbLoadArray; // indexed 2-dimension array [['symbol' => USDBTC, 'show' => false],['symbol' => USDETH, 'show' => false]]
    }

    private function writeFieldsInDB()
    {
        if($correctFields = $this->makeCorrectQueryForDB())  // indexed 2-dimension array [['symbol' => USDBTC, 'show' => false],['symbol' => USDETH, 'show' => false]]
        {

        }
    }

    private function deleteFieldsInDB()
    {
        $tickersToBeRemoved = $this->compareTickersFromDBAndFromApi(); //indexed array with symbols  which shows characters that are not in Response from Api

        foreach ($tickersToBeRemoved as $item)
        {
            if($ticker = Ticker::where('symbol', '=', $item)->first()) $ticker->delete();
        }
    }

    ////////2.TICKERS_SEARCH FUNCTIONS/////////
    ///
    ///
    ///
    ///
    ///
    private function takeTickersListFromDB($searchRequest) {
        return Ticker::where('symbol','LIKE','%'.$searchRequest->search."%")->orderBy('symbol', 'desc')->get();
    }

    private function formingHTMLResponse($tickersListFromDB) {
        $data = $tickersListFromDB;
        $htmlOutput = '';

        if(count($tickersListFromDB) > 0) {
            foreach ($data as $tickerRecord) {
                $htmlOutput.='<tr>'.
                    '<td class="search-ticker">'.$tickerRecord->symbol.'</td>'.
                    '<td class="search-timeframe">'.'<select id="timeFrameFor_'.$tickerRecord->symbol.'">'.
                    '<option class="search-checkVolume" value="1m" selected>Минута</option>
                                    <option value="3m">3 мин.</option>
                                    <option value="5m">5 мин.</option>
                                    <option value="15m">15 мин.</option>
                                    <option value="30m">30 мин.</option>
                                    <option value="1h">Час</option>
                                    <option value="2h">2 часа</option>
                                    <option value="4h">4 часа</option>
                                    <option value="6h">6 час.</option>
                                    <option value="8h">8 час.</option>
                                    <option value="12h">12 час.</option>
                                    <option value="1d">День</option>
                                    <option value="3d">3 дня</option>
                                    <option value="1w">Неделя</option>
                                    <option value="1M">Месяц</option>
                                </select>'.
                    '</td>'.
                    '<td>'."<input type='text' class='search-checkVolume' id='volumeOf_$tickerRecord->symbol'>".'</td>'.
                    '<td>'."<input type='button' class='followButton' id='follow_$tickerRecord->symbol' value='Следить'>".'</td>'.
                    '</tr>';
            }
        }
        else {
            $htmlOutput = '<tr><td>Результатов нету</td></tr>';
        }

        return $htmlOutput;
    }

    public function takeTickersListInHTML(Request $request)
    {
        if($request->ajax())
        {
            $tickers = $this->takeTickersListFromDB($request);

            $htmlOutput = $this->formingHTMLResponse($tickers);

            return Response($htmlOutput);
        }
    }


    /////////3.TICKERS_FOLLOW FUNCTIONS//////////
    ///
    ///
    ///
    ///
    ///
    public function saveFollowInDB(Request $request)
    {
        $validateArr = [
            'crypto_pair'   => $request->data['crypto_pair'],
            'time_frame'    => $request->data['time_frame'],
            'compare_volume'=> $request->data['compare_volume'],
            'choosen'       => $request->data['choosen'],
            'profile_id'    => $request->data['profile_id'],
            'mode'          => $request->data['mode']
        ];

        try {
            $savePairObj = new SavedPairs();
            $savePairObj->crypto_pair = $validateArr['crypto_pair'];
            $savePairObj->time_frame = $validateArr['time_frame'];
            $savePairObj->compare_volume = $validateArr['compare_volume'] ?? 0;
            $savePairObj->choosen = $validateArr['choosen'];
            $savePairObj->profile_id = $validateArr['profile_id'];
            $savePairObj->mode = $validateArr['mode'];
            $savePairObj->save();

            $request->session()->put('field_count', $request->data['field_count']);


            return Response($this->responseHelper('success', 'Настройки сохранены, подписка на пару работает'));
        }
        catch (Exception $e) {
            return Response($this->responseHelper('error', 'Ошибка сервера, настройки не сохранены, вывод в консоли',['error' => $e]));
        }

    }
//
//    public function saveReloadFollowInDB(Request $request)
//    {
//        try {
//            $savedReloadFollow = ReloadFollow::follow();
//            if($savedReloadFollow) {
//                $savedReloadFollow->fields_count = $request->data['fields_count'];
//                $savedReloadFollow->save();
//            }
//            else {
//                $saveNewReloadFollow = new ReloadFollow();
//                $saveNewReloadFollow->fields_count = $request->data['fields_count'];
//                $saveNewReloadFollow->save();
//            }
//
//            return Response('Обновление прошло успешно');
//        }
//        catch (Exception $e) {
//            return Response('Возникла ошибка:'.$e);
//        }
//    }

    public function checkReloadFollow(Request $request)
    {
        if($request->session()->get('field_count', 0) !== $request->data['field_count']) {
            return Response($request->data['field_count']);
        }
        else {
            return Response(false);
        }
    }

    public function saveReloadFollow(Request $request)
    {
        $this->reloadSignal = $request->data['field_count'];

        return Response($this->responseHelper('success', 'Сохранение прошло успешно'));
    }

    public function deleteFollowInDB(Request $request)
    {
        $mode = $request->data['mode'] ?? 0;

        try {
            $pairForDelete = SavedPairs::where('crypto_pair', $request->data['crypto_pair'])->
            where('time_frame', $request->data['time_frame'])->
            where('profile_id', $request->data['profile_id'])->
            where('mode', $mode)->first();

            if(empty($pairForDelete))
            {
                return Response($this->responseHelper('warning', 'Пара не найдена, попробуйте снова'));
            }

            $pairForDelete->delete();

            return Response($this->responseHelper('success', 'Удаление прошло успешно'));
        }
        catch(Exception $e)
        {
            return Response($this->responseHelper('error', 'Произошла ошибка сервера', ['error' => $e]));
        }

    }

    public function updateFollowInDB(Request $request)
    {
        $mode = $request->data['mode'] ?? 0;

        $validateArr = [
            'crypto_pair'   =>  $request->data['crypto_pair'],
            'time_frame'    =>  $request->data['time_frame'],
            'chosen_pattern'=>  $request->data['chosen_pattern'],
            'compare_volume'=>  (double)$request->data['compare_volume'],
            'chosen'        =>  $request->data['choosen'] == 'true' ? 1 : 0,
            'profile_id'    =>  $request->data['profile_id'],
            'mode'          =>  $mode,
        ];


        try
        {
            $savedPairObj =
                SavedPairs::where(['crypto_pair' => $validateArr['crypto_pair'], 'time_frame' => $validateArr['time_frame'],
                        'profile_id' => $validateArr['profile_id'], 'mode' => $validateArr['mode']
                    ])->first();

            if($savedPairObj) {
                $savedPairObj->chosen_pattern = $validateArr['chosen_pattern'];
                $savedPairObj->compare_volume = $validateArr['compare_volume'];
                $savedPairObj->choosen = $validateArr['chosen'];
                $savedPairObj->save();
                return Response($this->responseHelper('success', 'Обновление прошло успешно'));
            }
            else {
                return Response($this->responseHelper('warning','Не было найдено подписку'));
            }
        }
        catch (Exception $e)
        {
            return Response($this->responseHelper('error','Произошла ошибка сервера', ['error' => $e]));
        }

    }

    private function readSavedFollows($profile_id, $mode)
    {
        $savedFollows = SavedPairs::where(['profile_id' => $profile_id, 'mode' => $mode])->get();
        return $savedFollows;
    }


}
