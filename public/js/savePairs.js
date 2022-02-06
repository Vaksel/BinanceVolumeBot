let wasWarning = false;

function savePair(event)
{
    switch(event.target.className)
    {
        case 'btn btn-warning click-save': { //сохранение избранных пар
            let pairs = getSavedPairsFields('chosen');

            let objsArrReadyToSave = getResultArr(pairs);

            saveCurrentStateOfPairs(objsArrReadyToSave);

            break;
        }
        case 'btn btn-success click-save': { //сохранение всех пар
            let pairs = getSavedPairsFields('all');

            let objsArrReadyToSave = getResultArr(pairs);

            saveCurrentStateOfPairs(objsArrReadyToSave);

            break;
        }
        default: {

            break;
        }
    }
}

function saveCurrentStateOfPairs(data)
{
    let timer = setTimeout(function(){
        console.groupEnd();
    }, 10000);

    console.groupCollapsed('Данные пришедшие с сервера');

    let errors = [];
    for (let i = 0; i < data.length; i++)
    {
        $.ajax({
            type: "POST",
            url: '/writeDataHistory',
            data: {data: data[i]},
            success: function (msg) {

                clearTimeout(timer);

                timer = setTimeout(function(){
                    console.groupEnd();
                    console.log('Время прихода: ' + new Date(Date.now()));
                }, 3000);

                console.log('Прибыли данные:' + msg);

                if(msg.error)
                {
                    errors.push(msg.error);

                    notificationHandler('error', 'Возникла ошибка сервера, вывод в консоли.', errors);

                    return false;
                }

            }
        });

    }
    //
    // ajaxRealTimeSearchReqRes();

    if(!wasWarning)
    {
        notificationHandler('success', 'Состояние пар было успешно записано', '');
    }
    else
    {
        wasWarning = false;
    }

    return true;
}

function flash(msg, type)
{
    if(document.getElementById('flash'))
    {
        document.getElementById('flash').remove();
    }

    let flashField = document.createElement('h2');
        flashField.className = 'flash ' + type;
        flashField.innerText = msg;
        flashField.id = 'flash';

    document.querySelector('.main_content').insertAdjacentElement('afterbegin', flashField);

    setTimeout(function () {
        if(document.getElementById('flash'))
        {
            document.getElementById('flash').remove();
        }
    }, 30000);
}

function getResultArr(pairs)
{
    let pairObjsReadyToDbWrite = [];

    let pairObjsWithProblems = [];

    for (let i=0; i < pairs.length; i++)
    {
        let pairObjectResponse = getPairObject(pairs[i]);

        if(pairObjectResponse.status == 'success')
        {
            pairObjsReadyToDbWrite.push(pairObjectResponse.data)
        }
        else
        {
            pairObjsWithProblems.push(pairObjectResponse)
            wasWarning = true;
        }
    }

    if(wasWarning)
    {
        notificationHandler('warning', 'Состояние части пар сохранилось, но при сохранении возникли ошибки, вывод смотрите в консоли.', '')
        console.groupCollapsed('Ошибки');
        console.warn(pairObjsWithProblems);
        console.groupEnd();
    }

    return pairObjsReadyToDbWrite;
}

function getSavedPairsFields(saveAttribute)
{
    let returnableFields = [];

    let savedPairsNodes = document.getElementsByClassName('selectedField');

    for(let i=0; i < savedPairsNodes.length; i++)
    {
        let node = savedPairsNodes[i];

        if(saveAttribute == 'chosen')
        {
            if(node.children[0].classList.contains('choosen'))
            {
                returnableFields.push(node);
            }
        }
        else
        {
            returnableFields.push(node);
        }
    }

    return returnableFields;
}

/*
    Получение полей с парами.

    В случае если данные ещё не пришли
    с binance, тогда
    возвращается обьект skipObj.

    Возвращает обьект fieldObj.

*/
function getPairObject(element)
{
    let tickerStatusBuy = false;


    if(element.querySelector('.sellVolume').innerHTML == '---')
    {
        tickerStatusBuy = true;
    }

    if(element.querySelector('.sellVolume').innerHTML == 'Ожидайте')
    {
        let skipObj = {
            status: 'skip',
            msg: 'Данные не были получены',
            pairNode: element
        }

        return skipObj;
    }

        var currentDateTime = new Date(Date.now());
        if (tickerStatusBuy) {
            fieldObj = {
                status: 'success',
                data: {}
            };

            fieldObj.data.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
            fieldObj.data.ticker = element.querySelector('.ticker').textContent;
            fieldObj.data.timeframe = element.querySelector('.timeframe').selectedIndex;
            fieldObj.data.volume_status = "buy";
            fieldObj.data.volume = element.querySelector('.buyVolume').innerHTML;
            fieldObj.data.compare_volume = element.querySelector('.checkVolume').value;
            fieldObj.data.created_at =
                formatDate(currentDateTime) + ' ' + currentDateTime.getHours() + ':' +
                currentDateTime.getMinutes() + ':' + currentDateTime.getSeconds();
            fieldObj.data.unique_field = fieldObj.data.ticker + fieldObj.data.timeframe + fieldObj.data.volume +
                fieldObj.data.created_at;

        } else {
            fieldObj = {
                status: 'success',
                data: {}
            };

            fieldObj.data.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;
            fieldObj.data.ticker = element.querySelector('.ticker').textContent;
            fieldObj.data.timeframe = element.querySelector('.timeframe').selectedIndex;
            fieldObj.data.volume_status = "sell";
            fieldObj.data.volume = element.querySelector('.sellVolume').innerHTML;
            fieldObj.data.compare_volume = element.querySelector('.checkVolume').value;
            fieldObj.data.created_at =
                formatDate(currentDateTime) + ' ' + currentDateTime.getHours() + ':' +
                currentDateTime.getMinutes() + ':' + currentDateTime.getSeconds();
            fieldObj.data.unique_field = fieldObj.data.ticker + fieldObj.data.timeframe + fieldObj.data.volume +
                fieldObj.data.created_at;

        }

        return fieldObj;
}

function savePairInitBtns()
{
    let savedPairsdocument = document.getElementsByClassName('click-save');

    for(let i=0; i<savedPairsdocument.length; i++)
    {
        savedPairsdocument[i].addEventListener('click', savePair);
    }
}

savePairInitBtns();
