var allBinancePairs = [];
var massCompareTimeFrame = '1m';
var compareAllBinancePairs = true;
var percentsToCompare = getCookie('bombingVolume') ? parseInt(getCookie('bombingVolume')) : 100;
var connections = [];
var connectionsTimer = 0;
var binancePairsInLinkQTY = 0;
var audio = new Audio(); // Создаём новый элемент Audio
    audio.src = '/public/sounds/bomb.mp3'; // Указываем путь к звуку

function socketAllPairs(url) {
    var conn = new WebSocket(url);

    var breakConnectionTimeout = setTimeout(() => location.reload(), twentyMinsInMiliSeconds);


    const bullBearAbsorption = 2;

    conn.onopen = function () {
        console.log("Соединение установлено.");

        connections[connectionsTimer] = conn;
        //
        // console.log(connections[connectionsTimer]);

        connectionsTimer++;
    };

    conn.onclose = function (event) {
        clearInterval(timer);
        if (event.wasClean) {
            console.log('Соединение закрыто чисто');
        } else {
            console.log('Обрыв соединения'); // например, "убит" процесс сервера
        }
        console.log('Код: ' + event.code + ' причина: ' + event.reason);
    };

    conn.onmessage = function (event) {

        clearTimeout(breakConnectionTimeout);

        breakConnectionTimeout = setTimeout(() => location.reload(), twentyMinsInMiliSeconds);

        let streamData = JSON.parse(event.data);


        if(streamData.data.e == 'kline')
        {
            // let testObjStepOne = {
            //     eventType: streamData.data.e,
            //     eventTime: streamData.data.E,
            //     symbol: 'TESTTEST',
            //     klineStartTime: streamData.data.k.t,
            //     klineCloseTime: streamData.data.k.T,
            //     timeFrame: streamData.data.k.i,
            //     openPrice: 1,
            //     closePrice: 0.5,
            //     highPrice: 1.5,
            //     lowPrice: 0.2,
            //     baseAssetVolume: streamData.data.k.v,
            //     isClosed: streamData.data.k.x,
            //     quoteAssetVolume: streamData.data.k.q
            // };
            //
            // let testObjStepTwo = {
            //     eventType: streamData.data.e,
            //     eventTime: streamData.data.E,
            //     symbol: 'TESTTEST',
            //     klineStartTime: streamData.data.k.t,
            //     klineCloseTime: streamData.data.k.T,
            //     timeFrame: streamData.data.k.i,
            //     openPrice: 0.1,
            //     closePrice: 1.8,
            //     highPrice: 2.0,
            //     lowPrice: 0.01,
            //     baseAssetVolume: streamData.data.k.v,
            //     isClosed: streamData.data.k.x,
            //     quoteAssetVolume: streamData.data.k.q
            // };
            //
            // if(absorptionTimer == 0 && testObjStepOne.isClosed)
            // {
            //     checkCandleAbsorptionEnterPoint(testObjStepOne);
            //
            //     absorptionTimer++;
            // }
            // else
            // {
            //     if(absorptionTimer == 1 && testObjStepTwo.isClosed)
            //     {
            //         checkCandleAbsorptionEnterPoint(testObjStepTwo);
            //
            //         absorptionTimer++;
            //     }
            // }

            let candleStreamObj =
                {
                    eventType: streamData.data.e,
                    eventTime: streamData.data.E,
                    symbol: streamData.data.s,
                    klineStartTime: streamData.data.k.t,
                    klineCloseTime: streamData.data.k.T,
                    timeFrame: streamData.data.k.i,
                    openPrice: streamData.data.k.o,
                    closePrice: streamData.data.k.c,
                    highPrice: streamData.data.k.h,
                    lowPrice: streamData.data.k.l,
                    baseAssetVolume: streamData.data.k.v,
                    isClosed: streamData.data.k.x,
                    quoteAssetVolume: streamData.data.k.q
                }


                checkCandleVolumesEnterPoint(candleStreamObj);


        }

    };

    conn.onerror = function (error) {
        console.log("Ошибка " + error.message);
    };

    return conn;
}

function comparePercentVolume(candleStatObjFromMemory, quoteAssetVolumeFromCurObj)
{
    let onePercentVolume = candleStatObjFromMemory.quoteAssetVolume / 100;

    let percentsVolume = (quoteAssetVolumeFromCurObj - candleStatObjFromMemory.quoteAssetVolume) / onePercentVolume;

    if(percentsVolume > percentsToCompare && percentsVolume !== Infinity)
    {
        return percentsVolume;
    }

    return false;
}

function checkCandleVolumesEnterPoint(candleStreamObj)
{
    let CSO = candleStreamObj;

    let timer = 0;

    if(CSO.isClosed)
    {

        let candleId = generateCandleId(CSO, 'volume-percent compare');

        let isRecordTypeVolumePercent = 2;

        let candleStatObj = getCandleStreamObjForVolumeCompare(candleId, CSO);

        let candleObjectIsWritten = checkCandleStatObjInSessionStorage(candleId);

        if (!candleObjectIsWritten) {
            // console.log(candleStatObj);
            writeCandleStatObjInSessionStorage(candleStatObj)
        } else {
            let candleStatObjFromMemory = getCandleStatObjFromSessionStorage(candleId);

            let volumePercentCompare = comparePercentVolume(candleStatObjFromMemory, CSO.quoteAssetVolume);

            if (volumePercentCompare) {

                fieldObj = {};
                fieldObj.choosen = 0;
                fieldObj.ticker = CSO.symbol;
                fieldObj.timeframe = timeFrameArray.indexOf(CSO.timeFrame);
                fieldObj.volume_status = CSO.openPrice < CSO.closePrice ? 'buy' : 'sell';
                fieldObj.volume = CSO.quoteAssetVolume;
                fieldObj.compare_volume = percentsToCompare;
                fieldObj.created_at = getDateTime(CSO.eventTime);
                fieldObj.profile_id = getCurrentProfileId();
                fieldObj.record_type = isRecordTypeVolumePercent;
                fieldObj.volume_difference = volumePercentCompare;
                fieldObj.unique_field = fieldObj.ticker + fieldObj.timeframe + fieldObj.volume +
                    fieldObj.created_at + fieldObj.profile_id;
                //
                // console.log(fieldObj);

                ajaxSendRequest("/writeDataHistory", fieldObj);

                playSoundBomb();

            }

            sessionStorage.removeItem(candleId);

            writeCandleStatObjInSessionStorage(candleStatObj);

        }

    }
}

function getCandleStreamObjForVolumeCompare(candleId, candleStreamObj)
{
    let CSO = candleStreamObj;

    return {
        id: candleId,
        quoteAssetVolume: CSO.quoteAssetVolume,
        baseAssetVolume: CSO.baseAssetVolume,
    }
}

function getBinanceSocketConnectionLink(timeFrame)
{
    let connectionLink = 'wss://stream.binance.com:9443/stream?streams=';

    let massCompareTimeFrame = timeFrame;

    let socketConnectionPairs = '';

    let i = binancePairsInLinkQTY;

    for(i; i < binancePairsInLinkQTY + 500; i++)
    {

        if(allBinancePairs[i] === undefined || allBinancePairs[i] === null)
        {
            break;
        }
        socketConnectionPairs += allBinancePairs[i]['symbol'].toLowerCase() + '@kline_' + massCompareTimeFrame + '/';

    }

    if(allBinancePairs[i] !== undefined)
    {
        binancePairsInLinkQTY = i;
    }
    else
    {
        binancePairsInLinkQTY = 0;
    }



    socketConnectionPairs = socketConnectionPairs.substring(0, socketConnectionPairs.length - 1);

    return connectionLink + socketConnectionPairs;
}

function setTimeFrameForMassCompare()
{
    massCompareTimeFrame = timeFrameArray[this.selectedIndex];
}

function allTickersCompare(timeFrame = '1m')
{
        $.ajax({
            type: "POST",
            url: '/get-all-tickers',
            data: '',
            success: function (msg) {
                allBinancePairs = JSON.parse(msg);



                let link = getBinanceSocketConnectionLink(timeFrame);

                socketAllPairs(link);

                link = getBinanceSocketConnectionLink(timeFrame);

                socketAllPairs(link);

                link = getBinanceSocketConnectionLink(timeFrame);

                socketAllPairs(link);

                link = getBinanceSocketConnectionLink(timeFrame);

                socketAllPairs(link);


            }
        });
}

function timeFrameBomberHandler()
{
        let bombTimeframe = document.getElementById('bombing-timeframe');

        let bombVolume = document.getElementById('bombing-volume');

        bombVolume.addEventListener('keyup', function() {
            percentsToCompare = this.value;

            setCookie('bombingVolume', percentsToCompare);
        })

        let timeFrameChecks = bombTimeframe.children;

        var timeFrameSthCheckedIndicator = 0;

        if(connections[connectionsTimer - 1] !== undefined)
        {

            for(let i = 0; i<connectionsTimer; i++)
            {
                connections[i].close();
            }

            connections = [];
            connectionsTimer = 0;
        }

        for(let i = 0; i<timeFrameChecks.length; i++)
        {
            if(timeFrameChecks[i].children[0].checked == true)
            {
                if(timeFrameSthCheckedIndicator == 0)
                {
                    connections = [];

                    connectionsTimer = 0;
                }

                binancePairsInLinkQTY = 0;

                timeFrameSthCheckedIndicator++;

                allTickersCompare(timeFrameChecks[i].children[0].value);
            }

            // allTickersCompare();
        }

        if(timeFrameSthCheckedIndicator === 0)
        {
            allTickersCompare('1m');
            timeFrameSthCheckedIndicator = 0;
        }

        bombTimeframe.addEventListener('click', timeFrameBomberHandler);
}

function playSoundBomb()
{
    audio.pause();
    audio.play();
}

function connectionInit()
{
    if(getCookie('mode') == 2)
    {
        timeFrameBomberHandler();
    }
}

connectionInit();



