var updatedTime = 0;
var timer = null;
var absorptionTimer = 0;
var dayInMiliSeconds = 23 * 60 * 60 * 1000 + 50 * 60 * 1000 + 30 * 1000;
var twentyMinsInMiliSeconds = 20 * 60 * 1000;
var sixHoursInMiliSeconds = 1000 * 60 * 60 * 6;

var audioBuy = new Audio(); // Создаём новый элемент Audio
audioBuy.src = '/public/sounds/buy.mp3'; // Указываем путь к звуку "клика"

var audioSell = new Audio(); // Создаём новый элемент Audio
audioSell.src = '/public/sounds/sell.mp3'; // Указываем путь к звуку "клика"

setInterval(() => {
    location.reload();
}, dayInMiliSeconds);

setInterval(() => {
    var _lsTotal = 0,
        _xLen, _x;
    for (_x in localStorage) {
        if (!localStorage.hasOwnProperty(_x)) {
            continue;
        }
        _xLen = ((localStorage[_x].length + _x.length) * 2);
        _lsTotal += _xLen;
    };

    if(_lsTotal > 2000)
    {
        clearNotificationsInLS();
    }
}, sixHoursInMiliSeconds)

function jobStart()
{
    $.ajax({
        type: 'POST',
        url: '/processingToggler',
        success: function (res){
            console.log(res);
        }

    })
}

jobStart();
function socketCreate(url) {

    var conn = new WebSocket(url);
    console.log(conn);

    var breakConnectionTimeout = setTimeout(() => location.reload(), twentyMinsInMiliSeconds);


    const bullBearAbsorption = 2;

    conn.onopen = function () {
            timer = setInterval(() => {
                $.ajax({
                    type: "GET",
                    url: '/checkReloadFollow',
                    data: {
                        data : {
                            field_count : document.getElementById('selectedTickers').children.length
                        },
                    },
                    success: function (msg) {
                        if(msg.response) {
                                conn.close();
                                alert('Обновите страницу, были внесены изменения в отслеживаемые ячейки.');
                        }
                        // console.log(msg);
                    }
                });
            }, 5000);
        console.log("Соединение установлено.");
        console.log(document.getElementById('selectedTickers').children.length);
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

        var field = document.getElementById('selectedFieldOf_' +
            streamData.stream
        );

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

                if(candleStreamObj.isClosed && field.querySelector('.compare_pattern').selectedIndex + 1 === bullBearAbsorption )
                {
                    checkCandleAbsorptionEnterPoint(candleStreamObj);
                }
        }
        //
        // console.log(streamData);

        //
        // console.log(field.childNodes);

        if (streamData.data.k.c > streamData.data.k.o)
        {

            let mode = parseInt(getCookie('mode')) ?? 0;

            switch(mode)
            {
                case 0 :
                {
                    compareVolume(field.querySelector('.checkVolume').value, streamData.data.k.q, true, field, streamData.data.k.x, streamData.stream, streamData.data.E);
                    field.querySelector('.buyVolume').textContent = streamData.data.k.q;
                    field.querySelector('.sellVolume').textContent = '---';
                    break;
                }
                case 1 :
                {
                    if(streamData.data.k.x)
                    {
                        compareVolume(field.querySelector('.checkVolume').value, streamData.data.k.q, true, field, streamData.data.k.x, streamData.stream, streamData.data.E);
                        field.querySelector('.buyVolume').textContent = streamData.data.k.q;
                        field.querySelector('.sellVolume').textContent = '---';
                        break;
                    }
                }
            }

        } else {

            let mode = parseInt(getCookie('mode')) ?? 0;

            switch(mode)
            {
                case 0 :
                {
                    compareVolume(field.querySelector('.checkVolume').value, streamData.data.k.q, false, field, streamData.data.k.x, streamData.stream, streamData.data.E);
                    field.querySelector('.buyVolume').textContent = streamData.data.k.q;
                    field.querySelector('.sellVolume').textContent = '---';
                    break;
                }
                case 1 :
                {
                    if(streamData.data.k.x)
                    {
                        compareVolume(field.querySelector('.checkVolume').value, streamData.data.k.q, false, field, streamData.data.k.x, streamData.stream, streamData.data.E);
                        field.querySelector('.buyVolume').textContent = streamData.data.k.q;
                        field.querySelector('.sellVolume').textContent = '---';
                        break;
                    }
                }
            }
        }
    };

    conn.onerror = function (error) {
        console.log("Ошибка " + error.message);
    };

    return conn;
}

var fieldObj = {};
var compareData = {};
function compareVolume(volumeForCheck, volumeFromStream, buy, element, candleIsClose, streamTicker, streamTime, momentumSave = false) {
    let mode = parseInt(getCookie('mode')) ?? 0;

    console.log(streamTime);

    switch (mode)
    {
        case 0 : {
            if(candleIsClose)
            {
                fieldObj = {};
                if (Math.fround(volumeFromStream) > Math.fround(volumeForCheck)) {

                    var currentDateTime = new Date(streamTime);
                    if (buy) {
                        fieldObj = {};
                        fieldObj.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
                        fieldObj.ticker = element.querySelector('.ticker').textContent;
                        fieldObj.timeframe = element.querySelector('.timeframe').selectedIndex;
                        fieldObj.volume_status = "buy";
                        fieldObj.volume = volumeFromStream;
                        fieldObj.compare_volume = element.querySelector('.checkVolume').value;
                        fieldObj.created_at = getDateTime(streamTime);
                        fieldObj.profile_id = getCookie('chosenProfile') ?? 1;
                        fieldObj.record_type = 0;

                        fieldObj.unique_field = fieldObj.ticker + fieldObj.timeframe + fieldObj.volume +
                            fieldObj.created_at + fieldObj.profile_id + fieldObj.record_type;

                        compareData[streamTicker] = fieldObj;
                        decay(element.children[4], true);
                        soundClickBuy();
                    } else {
                        fieldObj = {};
                        fieldObj.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
                        fieldObj.ticker = element.querySelector('.ticker').textContent;
                        fieldObj.timeframe = element.querySelector('.timeframe').selectedIndex;
                        fieldObj.volume_status = "sell";
                        fieldObj.volume = volumeFromStream;
                        fieldObj.compare_volume = element.querySelector('.checkVolume').value;
                        fieldObj.created_at = getDateTime(streamTime);
                        fieldObj.profile_id = getCookie('chosenProfile') ?? 1;
                        fieldObj.record_type = 0;

                        fieldObj.unique_field = fieldObj.ticker + fieldObj.timeframe + fieldObj.volume +
                            fieldObj.created_at + fieldObj.profile_id + fieldObj.record_type;

                        compareData[streamTicker] = fieldObj;
                        decay(element.children[3], false);
                        soundClickSell();
                    }
                }
                // console.log(compareData);
                ajaxSendRequest("/writeDataHistory", compareData[streamTicker]);
                compareData={};
            }
            break;
        }

        case 1 : {

            let compareVolume = element.querySelector('.buyVolume').innerText;

            if(compareVolume !== '---')
            {
                compareVolume = compareVolume;
            }

            if(compareVolume === '---')
            {
                compareVolume = element.querySelector('.sellVolume').innerText;
            }

            if(candleIsClose)
            {
                console.log(volumeFromStream);
                fieldObj = {};

                let onePercentVolume = compareVolume / 100;

                let percentsVolume;

                console.log(compareVolume)

                if (buy) {
                    percentsVolume = (volumeFromStream - compareVolume) / onePercentVolume;

                    if (percentsVolume > volumeForCheck && percentsVolume !== Infinity) {
                        fieldObj.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
                        fieldObj.ticker = element.querySelector('.ticker').textContent;
                        fieldObj.timeframe = element.querySelector('.timeframe').selectedIndex;
                        fieldObj.volume_status = "buy";
                        fieldObj.volume = Math.fround(volumeFromStream);
                        fieldObj.compare_volume = element.querySelector('.checkVolume').value;
                        fieldObj.created_at = getDateTime(streamTime);
                        fieldObj.profile_id = getCookie('chosenProfile') ?? 1;
                        fieldObj.record_type = 1;
                        fieldObj.volume_difference = percentsVolume;

                        fieldObj.unique_field = fieldObj.ticker + fieldObj.timeframe + fieldObj.volume +
                            fieldObj.created_at + fieldObj.profile_id + fieldObj.record_type;

                        compareData[streamTicker] = fieldObj;
                        decay(element.children[5], true);
                        soundClickBuy();
                    }
                } else {
                    percentsVolume = (volumeFromStream - compareVolume) / onePercentVolume;

                    console.log(percentsVolume);
                    if (percentsVolume > volumeForCheck && percentsVolume !== Infinity) {
                        fieldObj = {};
                        fieldObj.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
                        fieldObj.ticker = element.querySelector('.ticker').textContent;
                        fieldObj.timeframe = element.querySelector('.timeframe').selectedIndex;
                        fieldObj.volume_status = "sell";
                        fieldObj.volume = Math.fround(volumeFromStream);
                        fieldObj.compare_volume = element.querySelector('.checkVolume').value;
                        fieldObj.created_at = getDateTime(streamTime);
                        fieldObj.profile_id = getCookie('chosenProfile') ?? 1;
                        fieldObj.record_type = 1;
                        fieldObj.volume_difference = percentsVolume;

                        fieldObj.unique_field = fieldObj.ticker + fieldObj.timeframe + fieldObj.volume +
                            fieldObj.created_at + fieldObj.profile_id + fieldObj.record_type;

                        compareData[streamTicker] = fieldObj;
                        decay(element.children[4], false);
                        soundClickSell();
                    }
                }
                console.log(compareData);

                ajaxSendRequest("/writeDataHistory", compareData[streamTicker]);
                compareData = {};

                break;
            }
        }
    }



}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ajaxSendRequest(url, data) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            data : data,
        },
        success: function (msg) {
            console.log('Прибыли данные:' + msg);
        }
    });
}


function soundClickBuy() {
    audioBuy.pause();
    audioBuy.play();
}

function soundClickSell() {
    audioSell.pause();
    audioSell.play();
}

function decay(element, buy) {
    var ofs = 0;
    let start = Date.now();

    let timer = setInterval(function () {
        let timePassed = Date.now() - start;

        if (timePassed > 4000) {
            clearInterval(timer);
            element.style.background = 'rgba(255,0,0,0)';
            return true;
        }
        element.style.background = buy ? 'rgba(0,255,0,' + Math.abs(Math.sin(ofs)) + ')' : 'rgba(255,0,0,' + Math.abs(Math.sin(ofs)) + ')';
        ofs += 0.02;
    }, 20);
}

document.getElementById('mode').addEventListener('change', changeMode);

document.getElementById('mode').selectedIndex = getCookie('mode');

function changeMode()
{
    switch (this.selectedIndex)
    {
        case 0: {
            setCookie('mode', 0);
            location.reload();
            break;
        }
        case 1: {
            setCookie('mode', 1);
            location.reload();
            break;
        }
        case 2: {
            setCookie('mode', 2);
            location.reload();
            break;
        }
    }
}
