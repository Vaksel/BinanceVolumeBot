var socket = null;
var followStreamStr = "wss://stream.binance.com:9443/stream?streams=";
var followStreamStrBuffer;
var timeCompareArr = [
    '1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d',
    '3d','1w','1M'
];

document.getElementById('dropdown').onclick = function followTicker(event) {
    if(event.target.className === 'followButton') {

        let selectedField = document.createElement('tr');
        selectedField.id = 'selectedFieldOf_' + (event.target.id.split('_'))[1];
        selectedField.className = 'selectedField';

        let symbolTD = document.createElement('td');
        symbolTD.innerHTML = (event.target.id.split('_'))[1];

        let timeTD = document.createElement('td');

        let timeSelect = document.createElement('select');
        timeSelect.innerHTML =
            '<option value="1m">Минута</option>' +
            '<option value="3m">3 мин.</option>' +
            '<option value="5m">5 мин.</option>' +
            '<option value="15m">15 мин.</option>' +
            '<option value="30m">30 мин.</option>' +
            '<option value="1h">Час</option>' +
            '<option value="2h">2 часа</option>' +
            '<option value="4h">4 часа</option>' +
            '<option value="6h">6 час.</option>' +
            '<option value="8h">8 час.</option>' +
            '<option value="12h">12 час.</option>' +
            '<option value="1d">День</option>' +
            '<option value="3d">3 дня</option>' +
            '<option value="1w">Неделя</option>' +
            '<option value="1M">Месяц</option>';
        timeSelect.selectedIndex = document.getElementById('timeFrameFor_' + (event.target.id.split('_'))[1]).options.selectedIndex;

        timeTD.insertBefore(timeSelect,null);

        let sellVolume = document.createElement('td');
        sellVolume.innerHTML = 'Ожидайте';

        let buyVolume = document.createElement('td');
        buyVolume.innerHTML = 'Ожидайте';

        let compareTD = document.createElement('td');

        let compareVolume = document.createElement('input');
        compareVolume.type = 'text';
        compareVolume.id = 'volumeOf_' + (event.target.id.split('_'))[1];
        compareVolume.value = document.getElementById('volumeOf_' + (event.target.id.split('_'))[1]).value;

        compareTD.insertBefore(compareVolume,null);


        let cancelTD = document.createElement('td');

        let cancelFollow = document.createElement('input');
        cancelFollow.type = 'button';
        cancelFollow.id = 'cancelFollow_' + (event.target.id.split('_'))[1];
        cancelFollow.value = 'Отменить слежку';
        cancelFollow.className = 'cancelFollow';

        cancelTD.insertBefore(cancelFollow,null);


        selectedField.insertBefore(symbolTD,null);
        selectedField.insertBefore(timeTD,null);
        selectedField.insertBefore(sellVolume,null);
        selectedField.insertBefore(buyVolume,null);
        selectedField.insertBefore(compareTD,null);
        selectedField.insertBefore(cancelTD,null);
        document.getElementById('selectedTickers').insertBefore(selectedField, null);

        saveFollowInDB(selectedField);


        var connArr = [];

        let selectedFieldsCollection = document.getElementsByClassName('selectedField');

        for (let i = 0; i<selectedFieldsCollection.length; i++) {
            followStreamStr += selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
                + timeCompareArr[selectedFieldsCollection[i].childNodes[1].firstChild.options.selectedIndex] + '/';
            selectedFieldsCollection[i].id = 'selectedFieldOf_' + selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
                + timeCompareArr[selectedFieldsCollection[i].childNodes[1].firstChild.options.selectedIndex];
        }

        followStreamStrBuffer = followStreamStr;

        socket ? socket.close() : null;

        socket = socketCreate(followStreamStr.substring(0, followStreamStr.length - 1), selectedFieldsCollection);
        followStreamStr = "wss://stream.binance.com:9443/stream?streams=";

    }
}

window.onload = function () {
    let selectedFieldsCollection = document.getElementsByClassName('selectedField');

    for (let i = 0; i<selectedFieldsCollection.length; i++) {
        followStreamStr += selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
            + timeCompareArr[selectedFieldsCollection[i].childNodes[1].firstChild.options.selectedIndex] + '/';
        selectedFieldsCollection[i].id = 'selectedFieldOf_' + selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
            + timeCompareArr[selectedFieldsCollection[i].childNodes[1].firstChild.options.selectedIndex];
    }

    let finalString = followStreamStr;

    finalString[finalString.length - 1] === '/' ? finalString = finalString.substring(0, finalString.length - 1) : null;

    socket = socketCreate(finalString);

    followStreamStrBuffer = followStreamStr;
}

document.getElementById('selectedTickers').onclick = function actionsWithSelectedFields(event) {
    if(event.target.className === 'cancelFollow') {
        cancelFollow(event.target.closest('tr'));
    }
}

function cancelFollow(element) {

    element.remove();

    deleteFollowInDB(element);

    $searchStr = element.id.split('_')[1] + '_' + element.id.split('_')[2];

    let finalString = followStreamStrBuffer.split(followStreamStrBuffer.substring(followStreamStrBuffer.search($searchStr),
        followStreamStrBuffer.search($searchStr) + $searchStr.length + 1))[0] +
        followStreamStrBuffer.split(followStreamStrBuffer.substring(followStreamStrBuffer.search($searchStr),
            followStreamStrBuffer.search($searchStr) + $searchStr.length + 1))[1];

    console.log(finalString);

    followStreamStrBuffer = finalString;

    finalString[finalString.length - 1] === '/' ? finalString = finalString.substring(0, finalString.length - 1) : null;

    socket ? socket.close() : null;

    socket = socketCreate(finalString);
}

function saveFollowInDB(element) {
    let followObject = {};
        followObject.crypto_pair = element.childNodes[0].textContent;
        followObject.time_frame = element.childNodes[1].firstChild.selectedIndex;
        followObject.compare_volume = element.childNodes[4].firstChild.value;

    $.ajax({
        type: "POST",
        url: '/saveFollow',
        data: {
            data: followObject,
        },
        success: function (msg) {
             console.log(msg);
        }
    });
}

function deleteFollowInDB(element) {
    let followObject = {};
    followObject.crypto_pair = element.childNodes[0].textContent;
    followObject.time_frame = element.childNodes[1].firstChild.selectedIndex;
    followObject.compare_volume = element.childNodes[4].firstChild.value;

    $.ajax({
        type: "POST",
        url: '/deleteFollow',
        data: {
            data: followObject,
        },
        success: function (msg) {
            console.log(msg);
        }
    });
}
