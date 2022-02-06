<?php

namespace App\Models;

use App\Jobs\VolumeCompareJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class VolumeCompare extends Model
{
    use HasFactory;

    public const TIMEFRAME_TRANSFORMATIVE_ARR = [
                '1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d',
                '3d','1w','1M'
            ];

    public static function volumeCompareStart()
    {
        $savedPairs = self::getAllSavedPairsWithoutDuplicates();

        $connectionLink = self::generateBinanceConnectionLink($savedPairs);

        self::volumeCompareHandler($connectionLink);
    }

    public static function volumeCompareHandler($connectionLink)
    {
        while(true)
        {
            $responseObjFromBinance = Socket::getSocketResponse($connectionLink);

            $binancePairFromSocket = Socket::getBinancePairArray($responseObjFromBinance);

            $cacheId = $binancePairFromSocket['symbol'] . '_' . array_search($binancePairFromSocket['timeFrame'], self::TIMEFRAME_TRANSFORMATIVE_ARR);

            self::putPairArrToCache($cacheId, $binancePairFromSocket);

            $savedPairs = self::getAllSavedPairsWithDuplicates();

            $job = new VolumeCompareJob($binancePairFromSocket, $savedPairs);
            dispatch($job);
        }
    }

    public static function putPairArrToCache($key, $arr, $seconds = null)
    {
        empty($seconds) ? Cache::put($key, $arr) : Cache::put($key, $arr, $seconds);
    }

    public static function getPairArrFromCache($key)
    {
        return Cache::get($key);
    }

    public static function volumeCompareAndGetWriteResults($binancePairFromSocket, $savedPairs)
    {
        $pfs = $binancePairFromSocket;

        $results = [];

            $historyRecord = [];

            $savedPairsToCompareVolumeWithThat = $savedPairs[$pfs['symbol'].'_'. array_search($pfs['timeFrame'], self::TIMEFRAME_TRANSFORMATIVE_ARR)];

            foreach ($savedPairsToCompareVolumeWithThat as $savedPair)
            {
                if($pfs['quoteAssetVolume'] > $savedPair['compare_volume'])
                {
                    $historyRecord['choosen'] = $savedPair['choosen'];
                    $historyRecord['ticker'] = $savedPair['crypto_pair'];
                    $historyRecord['timeframe'] = $savedPair['time_frame'];
                    $historyRecord['volume_status'] = $savedPair['openPrice'] > $savedPair['closePrice'] ? 'sell' : 'buy';
                    $historyRecord['volume'] = $pfs['quoteAssetVolume'];
                    $historyRecord['compare_volume'] = $savedPair['compare_volume'];
                    $historyRecord['volume_difference'] = $historyRecord['volume'] - $historyRecord['compare_volume'];
                    $historyRecord['created_at'] = date('Y-m-d H:i:s', $pfs['eventTime']);
                    $historyRecord['profile_id'] = $savedPair['profile_id'];
                    $historyRecord['record_type'] = $savedPair['mode'];
                    $historyRecord['unique_field'] = $historyRecord['ticker'] . $historyRecord['timeframe'] .
                        $historyRecord['profile_id'] . $historyRecord['record_type'];

                    $results[] = History::writeDataHistory($historyRecord);
                }
            }

        return $results;
    }

    /**
     *
     */
    public static function getAllSavedPairsWithDuplicates()
    {
        $savedPairs = SavedPairs::where(['mode' => 0])->get();

        $savedPairsWithDuplicates = array();

        foreach ($savedPairs as $val)
        {
            $savedPairsWithDuplicates[$val->crypto_pair.'_'.$val->time_frame][] = $val;
        }

        return $savedPairsWithDuplicates;
    }

    /**
     * @return array
     */
    public static function getAllSavedPairsWithoutDuplicates()
    {
        $savedPairs = SavedPairs::where(['mode' => 0])->get();

        $savedPairsWithoutDuplicates = array();

        foreach ($savedPairs as $el)
        {
            if(!isset($savedPairsWithoutDuplicates[$el->crypto_pair.'_'.$el->time_frame]))
            {
                $savedPairsWithoutDuplicates[$el->crypto_pair.'_'.$el->time_frame] = $el;
            }
        }

        return $savedPairsWithoutDuplicates;
    }

    public static function generateBinanceConnectionLink($pairs)
    {
        $baseLink = 'wss://stream.binance.com:9443/stream?streams=';
        $link = $baseLink;

        $timeFrameTransformativeArr =
        [
            '1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d',
            '3d','1w','1M'
        ];

        foreach ($pairs as $val)
        {
            $link .= strtolower($val->crypto_pair) . '@kline_' . $timeFrameTransformativeArr[$val->time_frame] . '/';
        }

        $link = substr($link, 0, strlen($link) - 1);

        return $link;
    }
}
