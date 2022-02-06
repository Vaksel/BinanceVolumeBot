var socket = null;
var followStreamStr = "wss://stream.binance.com:9443/stream?streams=";
var followStreamStrBuffer;
// var timeCompareArr = [
//     '1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d',
//     '3d','1w','1M'
// ];

document.getElementById('dropdown').onclick = function followTicker(event) {
    if(event.target.className === 'followButton') {

        let selectedField = document.createElement('tr');
        selectedField.id = 'selectedFieldOf_' + (event.target.id.split('_'))[1];
        selectedField.className = 'selectedField';

        let favouriteBtn = document.createElement('td');
        favouriteBtn.className = 'favourite';
        favouriteBtn.innerHTML = '<svg aria-hidden="true" style="color: orange;" width="50" height="50" focusable="false" data-prefix="far" data-icon="star" class="favouritePairToggler svg-inline--fa fa-star fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">\n' +
            '                                <path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4\n' +
            '                                    0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7\n' +
            '                                    68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3\n' +
            '                                    65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z">\n' +
            '                                </path>\n' +
            '                            </svg>';
        favouriteBtn.onclick = favouriteStatusToggler;

        let symbolTD = document.createElement('td');
        symbolTD.innerHTML = (event.target.id.split('_'))[1];
        symbolTD.className = 'ticker';

        let timeTD = document.createElement('td');

        let patternCompare = document.createElement('select');

        patternCompare.className = 'compare_pattern';

        patternsCollection.forEach((patternObj)=>{
            let patternCompareOption = document.createElement('option');
                patternCompareOption.value = patternObj.id;
                patternCompareOption.innerHTML = patternObj.name;
            patternCompare.insertAdjacentElement('afterbegin', patternCompareOption);
        })

        patternCompare.selectedIndex = 0;


        let timeSelect = document.createElement('select');
        timeSelect.className = 'timeFrame';
        timeSelect.disabled = true;
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
        sellVolume.className = 'sellVolume';

        let buyVolume = document.createElement('td');
        buyVolume.innerHTML = 'Ожидайте';
        buyVolume.className = 'buyVolume';

        let compareTD = document.createElement('td');

        let compareVolume = document.createElement('input');
        compareVolume.type = 'text';
        compareVolume.id = 'volumeOf_' + (event.target.id.split('_'))[1];
        compareVolume.value = document.getElementById('volumeOf_' + (event.target.id.split('_'))[1]).value;
        compareVolume.className = 'checkVolume';

        compareTD.insertBefore(compareVolume,null);


        let cancelTD = document.createElement('td');

        let cancelFollow = document.createElement('input');
        cancelFollow.type = 'button';
        cancelFollow.id = 'cancelFollow_' + (event.target.id.split('_'))[1];
        cancelFollow.value = 'Отменить слежку';
        cancelFollow.className = 'cancelFollow';

        cancelTD.insertBefore(cancelFollow,null);


        selectedField.insertBefore(favouriteBtn, null);
        selectedField.insertBefore(symbolTD,null);
        selectedField.insertBefore(patternCompare,null);
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
                + timeFrameArray[selectedFieldsCollection[i].querySelector('.timeframe').options.selectedIndex] + '/';
            selectedFieldsCollection[i].id = 'selectedFieldOf_' + selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
                + timeFrameArray[selectedFieldsCollection[i].querySelector('.timeframe').options.selectedIndex];
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
            + timeFrameArray[selectedFieldsCollection[i].querySelector('.timeframe').options.selectedIndex] + '/';
        selectedFieldsCollection[i].id = 'selectedFieldOf_' + selectedFieldsCollection[i].id.split('_')[1].toLowerCase().split('@')[0] + '@kline_'
            + timeFrameArray[selectedFieldsCollection[i].querySelector('.timeframe').options.selectedIndex];
    }

    let finalString = followStreamStr;

    finalString[finalString.length - 1] === '/' ? finalString = finalString.substring(0, finalString.length - 1) : null;

    console.log(finalString);

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

    followStreamStrBuffer = finalString;

    finalString[finalString.length - 1] === '/' ? finalString = finalString.substring(0, finalString.length - 1) : null;

    socket ? socket.close() : null;

    socket = socketCreate(finalString);
}

function saveFollowInDB(element) {
    let selectedTickersNodes = document.getElementById('selectedTickers').children;
    let followObject = {};
        followObject.crypto_pair = element.querySelector('.ticker').textContent;
        followObject.time_frame = element.querySelector('.timeframe').selectedIndex;
        followObject.compare_volume = element.querySelector('.checkVolume').value;
        followObject.field_count = selectedTickersNodes.length;
        followObject.choosen = 0;
        followObject.profile_id = getCookie('chosenProfile') ?? 1;
        followObject.mode = getCookie('mode') ?? 0;

    $.ajax({
        type: "POST",
        url: '/saveFollow',
        data: {
            data: followObject,
        },
        success: function (res) {
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['status'], res['msg'], res['error'])
        }
    });
}

function deleteFollowInDB(element) {
    let selectedTickersNodes = document.getElementById('selectedTickers').children;

    let followObject = {};
    followObject.crypto_pair = element.querySelector('.ticker').textContent;
    followObject.time_frame = element.querySelector('.timeframe').selectedIndex;
    followObject.compare_volume = element.querySelector('.checkVolume').value;
    followObject.field_count = selectedTickersNodes.length;
    followObject.profile_id = parseInt(getCookie('chosenProfile')) ?? 1;
    followObject.mode = parseInt(getCookie('mode')) ?? 0;

    console.log(followObject);

    $.ajax({
        type: "POST",
        url: '/deleteFollow',
        data: {
            data: followObject,
        },
        success: function (res) {
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['status'], res['msg'], res['error'])
        }
    });
}
