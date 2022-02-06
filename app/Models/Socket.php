<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use WebSocket\Client;

class Socket extends Model
{
    use HasFactory;

    public static function getSocketResponse($link)
    {
        $client = new Client($link);
        try
        {
            $response = $client->receive();
        }
        catch (\WebSocket\ConnectionException $e)
        {
            return ['status' => 'error', 'error' => $e];
        }

        return $response;
    }

    /**
     * @param $binancePairJSON
     * @return array[
     * 'eventType' => string, 'eventTime' => timestamp, 'symbol' => string,
     * 'klineStartTime' => timestamp, 'klineCloseTime' => timestamp, 'timeFrame' => string,
     * 'openPrice' => float, 'closePrice' => float, 'highPrice' => float, 'lowPrice' => float,
     * 'baseAssetVolume' => float, 'isClosed' => boolean, 'quoteAssetVolume' => float
     * ]
     */
    public static function getBinancePairArray($binancePairJSON)
    {
        $binancePair = json_decode($binancePairJSON, true, 10);
        $binancePairResult = array();

        $binancePairResult['eventType'] = $binancePair['data']['e'];
        $binancePairResult['eventTime'] = (string)$binancePair['data']['E'];
        $binancePairResult['eventTime'] = (int)substr($binancePairResult['eventTime'], 0, strlen($binancePairResult['eventTime']) - 3);
        $binancePairResult['eventTime'] += 10800;
        $binancePairResult['symbol'] = $binancePair['data']['s'];
        $binancePairResult['klineStartTime'] = $binancePair['data']['k']['t'];
        $binancePairResult['klineCloseTime'] = $binancePair['data']['k']['T'];
        $binancePairResult['timeFrame'] = $binancePair['data']['k']['i'];
        $binancePairResult['openPrice'] = $binancePair['data']['k']['o'];
        $binancePairResult['closePrice'] = $binancePair['data']['k']['c'];
        $binancePairResult['highPrice'] = $binancePair['data']['k']['h'];
        $binancePairResult['lowPrice'] = $binancePair['data']['k']['l'];
        $binancePairResult['baseAssetVolume'] = $binancePair['data']['k']['v'];
        $binancePairResult['isClosed'] = $binancePair['data']['k']['x'];
        $binancePairResult['quoteAssetVolume'] = $binancePair['data']['k']['q'];

        return $binancePairResult;
    }
}
