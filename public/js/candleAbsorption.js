$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var audioBear = new Audio(); // Создаём новый элемент Audio
audioBear.src = '/public/sounds/bull.mp3'; // Указываем путь к звуку

var audioBull = new Audio(); // Создаём новый элемент Audio
audioBull.src = '/public/sounds/bear.mp3'; // Указываем путь к звуку

function checkCandleAbsorptionEnterPoint(candleStreamObj)
{
    //Обьект свечи, который был получен по API
    let CSO = candleStreamObj;

    let candleId = generateCandleId(CSO);

    let candleStatObj = getCandleStatObj(candleId, CSO);

    let candleObjectIsWritten = checkCandleStatObjInSessionStorage(candleId);

    if(!candleObjectIsWritten)
    {
        writeCandleStatObjInSessionStorage(candleStatObj)
    }
    else
    {
        let candleStatObjFromMemory = getCandleStatObjFromSessionStorage(candleId);


        let bullAbsorption = compareCandleStatBull(candleStatObjFromMemory, CSO.openPrice, CSO.closePrice, CSO.lowPrice, CSO.highPrice);

        let eventTime = CSO.eventTime.toString();

        if(bullAbsorption)
        {
            let patternObj = {
                name: 'Бычье поглощение',
                pair: CSO.symbol,
                timeframe: timeFrameArray.indexOf(CSO.timeFrame),
                created_at: parseInt(eventTime.substring(0, eventTime.length - 3)) + 10800,
                profile_id: getCurrentProfileId(),
                chosen: candleStatObj.chosen,
            }

            writeAbsorptionByAjax(patternObj);

            soundBull();

        }
        else
        {
            let bearAbsorption = compareCandleStatBear(candleStatObjFromMemory, CSO.openPrice, CSO.closePrice, CSO.lowPrice, CSO.highPrice);

            if(bearAbsorption)
            {
                let patternObj = {
                    name: 'Медвежье поглощение',
                    pair: CSO.symbol,
                    timeframe: timeFrameArray.indexOf(CSO.timeFrame),
                    created_at: parseInt(eventTime.substring(0, eventTime.length - 3)) + 10800,
                    profile_id: getCurrentProfileId(),
                    chosen: candleStatObj.chosen,
                }

                writeAbsorptionByAjax(patternObj);

                soundBear();
            }
        }

        sessionStorage.removeItem(candleId);

        writeCandleStatObjInSessionStorage(candleStatObj);

    }
}
// testWriteAbsorption();

function testWriteAbsorption()
{
    let currentTimestamp = 1622689254;
    let testObj = {
        name: 'test',
        pair: 'test',
        timeframe: 0,
        created_at: currentTimestamp
    }

    writeAbsorptionByAjax(testObj);
}

function writeAbsorptionByAjax(candleAbsorptionObj)
{
    $.ajax({
        url: '/writePattern',
        type: 'POST',
        data: candleAbsorptionObj,
        success: function (res) {
            if(res.status !== 'error')
            {
                res.error = '';
            }
            notificationHandler(res.status, res.msg, res.error)
        },
        fail: function () {

        }
    })
}

// //Сравниваем нынешнюю свечу со свечей сохраненной для получения бычьего поглощения
// function compareCandleStatBull(candleObj, openPrice, closePrice)
// {
//     let bullAbsorption = false;
//     if(openPrice < candleObj.lowPrice && closePrice > candleObj.highPrice)
//     {
//         bullAbsorption = true;
//     }
//
//     return bullAbsorption;
// }
// //Сравниваем нынешнюю свечу со свечей сохраненной для получения медвежьего поглощения
// function compareCandleStatBear(candleObj, openPrice, closePrice)
// {
//     let bearAbsorption = false;
//     if(openPrice > candleObj.highPrice && closePrice < candleObj.lowPrice)
//     {
//         bearAbsorption = true;
//     }
//
//     return bearAbsorption;
// }

//Сравниваем нынешнюю свечу со свечей сохраненной для получения бычьего поглощения
function compareCandleStatBull(candleObj, openPrice, closePrice, lowPrice, highPrice)
{
    let bullAbsorption = false;
    //
    // Если цена открытия нынешней свечи ниже минимальной цены предыдущей свечи и
    // цена закрытия нынешней свечи больше максимальной цены предыдущей свечи и
    // предыдущая свеча - медвежья
    // то фиксируем бычье поглощение
    if(lowPrice < candleObj.lowPrice && highPrice > candleObj.highPrice && openPrice < closePrice &&
        closePrice > candleObj.closePrice &&
        closePrice > candleObj.openPrice && closePrice > candleObj.highPrice)
    {
        bullAbsorption = true;
    }

    return bullAbsorption;
}
//Сравниваем нынешнюю свечу со свечей сохраненной для получения медвежьего поглощения
function compareCandleStatBear(candleObj, openPrice, closePrice, lowPrice, highPrice)
{
    let bearAbsorption = false;
    // Если цена открытия нынешней свечи выше максимальной цены предыдущей свечи и
    // цена закрытия нынешней свечи ниже минимальной цены предыдущей свечи и
    // предыдущая свеча - бычья
    // то фиксируем медвежье поглощение
    if(lowPrice < candleObj.lowPrice && highPrice > candleObj.highPrice && openPrice > closePrice &&
        closePrice < candleObj.closePrice && openPrice > candleObj.closePrice &&
        closePrice < candleObj.openPrice && closePrice < candleObj.openPrice)
    {
        bearAbsorption = true;
    }

    return bearAbsorption;
}

/**
 * Получение обьекта, который хранит в себе состояние свечи, нужен для сохранения и сравнения с уже имеющимся
 * @param candleId
 * @returns {{candleId: string, lowPrice: float, highPrice: float, openPrice: float, closePrice: float}}
 */
function getCandleStatObj(candleId, CandleStatObj)
{
    let CSO = CandleStatObj;
    let pairNodeId = `selectedFieldOf_${CSO.symbol.toLowerCase()}@kline_${CSO.timeFrame}`;
    let pairNode = document.getElementById(pairNodeId);
    return {
        id: candleId,
        lowPrice: CSO.lowPrice,
        highPrice: CSO.highPrice,
        openPrice: CSO.openPrice,
        closePrice: CSO.closePrice,
        chosen: pairNode.querySelector('.favourite').classList.contains('choosen') ? 1 : 0
    };
}

function soundBull()
{
    audioBear.pause();
    audioBear.play();
}

function soundBear()
{
    audioBull.pause();
    audioBull.play();
}

function deletePatterns(deleteAllRecords) {
    $.ajax({
        type: "POST",
        url: '/delete-patterns',
        data: {
            data : readDeleteQuery(deleteAllRecords),
        },
        success: (res) => {
            console.log(res);
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['status'], res['msg'], res['error']);
        },
        error: (res) => {
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['error'], res['Произошла ошибка при передаче данных на сервер, вывод в консоли'], res);
        },
    });
}
function readDeleteQuery(deleteAllRecords = false) {
    if(deleteAllRecords) {
        return {
            deleteAll: true,
        }
    }
    else {
        return {
            startDateTime: document.getElementById('patternsDeleteStartDatetime').value,
            finishDateTime: document.getElementById('patternsDeleteFinishDatetime').value,
        };
    }

}
