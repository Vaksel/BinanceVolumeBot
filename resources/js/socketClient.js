function socketCreate(url) {
    var conn = new WebSocket(url);
    console.log(conn);

    conn.onopen = function () {
        console.log("Соединение установлено.");
    };

    conn.onclose = function (event) {
        if (event.wasClean) {
            console.log('Соединение закрыто чисто');
        } else {
            console.log('Обрыв соединения'); // например, "убит" процесс сервера
        }
        console.log('Код: ' + event.code + ' причина: ' + event.reason);
    };

    conn.onmessage = function (event) {
        let streamData = JSON.parse(event.data);

        console.log(streamData);

        var field = document.getElementById('selectedFieldOf_' +
            streamData.stream
        );
        //
        // console.log(field.childNodes);

        if (streamData.data.k.c > streamData.data.k.o) {
            compareVolume(field.childNodes[4].firstChild.value, streamData.data.k.q, true, field, streamData.data.k.x, streamData.stream);
            field.childNodes[3].textContent = streamData.data.k.q;
            field.childNodes[2].textContent = '---';
            decay(field.childNodes[3], true);
        } else {
            compareVolume(field.childNodes[4].firstChild.value, streamData.data.k.q, false, field, streamData.data.k.x, streamData.stream);
            field.childNodes[2].textContent = streamData.data.k.q;
            field.childNodes[3].textContent = '---';
            decay(field.childNodes[2], false);
        }


        // console.log('Seller volume:' + data.k.V);
        // console.log('Buyer volume:' + data.k.v);
        // console.log('Quote volume:' + data.k.q);
        // let rightDate = new Date(data.E);
        // console.log(rightDate.toGMTString());
        // alert("Получены данные " + event.data);
    };

    conn.onerror = function (error) {
        console.log("Ошибка " + error.message);
    };

    return conn;
}

function formatDate(date) {
    let DATE = new Date();



    console.log(formatDate(DATE));
}

function formatDate(date) {
    let dateOfyear = date.getFullYear() + ""; // год;
    // console.log(dateOfyear);
    let newDateOfyear = dateOfyear.slice(2); // год последние две цифры;

    let day = date.getDate(); // текущий день
    day = day < 10 ? "0" + day : day;
    let month = date.getMonth() + 1; //текущий месяцж
    month = month < 10 ? "0" + month : month;

    return +newDateOfyear + "." + month + "." + day;
}

var fieldObj = {};
var compareData = {};
function compareVolume(volumeForCheck, volumeFromStream, buy, element, candleIsClose, streamTicker) {
    if(candleIsClose && compareData[streamTicker]) {
        console.log(compareData[streamTicker]);
        ajaxSendRequest("/writeDataHistory", compareData[streamTicker]);
        buy ? soundClickBuy() : soundClickSell();
        fieldObj = {};
    }
    else {
        if (Math.fround(volumeFromStream) >= Math.fround(volumeForCheck)) {
            var currentDateTime = new Date();
            if (buy) {
                fieldObj = {};
                fieldObj.ticker = element.childNodes[0].firstChild.textContent;
                fieldObj.timeframe = element.childNodes[1].firstChild.selectedIndex;
                fieldObj.volume_status = "buy";
                fieldObj.volume = element.childNodes[3].firstChild.textContent;
                fieldObj.compare_volume = element.childNodes[4].firstChild.value;
                fieldObj.created_at =
                    formatDate(currentDateTime) + ' ' + currentDateTime.getHours() + ':' +
                    currentDateTime.getMinutes() + ':' + currentDateTime.getSeconds();

                compareData[streamTicker] = fieldObj;
            } else {
                fieldObj = {};
                fieldObj.ticker = element.childNodes[0].firstChild.textContent;
                fieldObj.timeframe = element.childNodes[1].firstChild.selectedIndex;
                fieldObj.volume_status = "sell";
                fieldObj.volume = element.childNodes[2].firstChild.textContent;
                fieldObj.compare_volume = element.childNodes[4].firstChild.value;
                fieldObj.created_at =
                    formatDate(currentDateTime) + ' ' + currentDateTime.getHours() + ':' +
                    currentDateTime.getMinutes() + ':' + currentDateTime.getSeconds();

                compareData[streamTicker] = fieldObj;
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
            console.log("Прибыли данные: " + msg);
        }
    });
}


function soundClickBuy() {
    var audio = new Audio(); // Создаём новый элемент Audio
    audio.src = '/sounds/buy.mp3'; // Указываем путь к звуку "клика"
    audio.autoplay = true; // Автоматически запускаем
}

function soundClickSell() {
    var audio = new Audio(); // Создаём новый элемент Audio
    audio.src = '/sounds/sell.mp3'; // Указываем путь к звуку "клика"
    audio.autoplay = true; // Автоматически запускаем
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
    }, 10);
}
