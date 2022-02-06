//Формируем уникальный номер свечи для сохранения в sessionStorage
function generateCandleId(candleStreamObj, usage = 'bull/bear absorption')
{
    let candleSymbol = candleStreamObj.symbol;
    let candleTimeframe = candleStreamObj.timeFrame;
    let profileId = getCurrentProfileId();

    switch(usage)
    {
        case "bull/bear absorption": {
            return candleSymbol + '_' + candleTimeframe + '_' + profileId;
        }
        case "volume-percent compare": {
            let vp_compare = 'vp_compare';
            return vp_compare + '_' + candleSymbol + '_' + candleTimeframe + '_' + profileId;
        }
    }
}

function checkCandleStatObjInSessionStorage(candleId)
{
    if(sessionStorage.getItem(candleId))
    {
        return true;
    }

    return false;
}

function writeCandleStatObjInSessionStorage(candleObj)
{
    try
    {
        sessionStorage.setItem(candleObj.id, JSON.stringify(candleObj));

        return true;
    }
    catch(e)
    {
        notificationHandler('error', 'Произошла ошибка, невозможно записать обьект в хранилище', e);

        return false;
    }
}


function getCandleStatObjFromSessionStorage(candleId)
{
    try
    {
        return JSON.parse(sessionStorage.getItem(candleId));
    }
    catch(e)
    {
        notificationHandler('error', 'Произошла ошибка, невозможно получить обьект из хранилища', e);

        return false;
    }
}