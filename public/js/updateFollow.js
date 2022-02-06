document.getElementById('selectedTickers').onkeyup = function updateFollowInDB(event) {
    console.log(event);
    if (event.target.nodeName === 'INPUT') {
        updateFollowAjax(event);
    }
};

document.getElementById('selectedTickers').onchange = function updateFollowInDB(event) {
    if (event.target.className === 'compare_pattern') {
        updateFollowAjax(event);
    }
}

function updateFollowAjax(event)
{
    let element = event.target.closest('tr');

    let followObject = shapePairObjectFromElement(element);

    console.log(followObject);

    $.ajax({
        type: "POST",
        url: '/updateFollow',
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

/**
 * Формируем обьект пары для записи в бд с поля криптопары
 * @returns {{
 *     crypto_pair : string,
 *     time_frame : int,
 *     compare_volume : int,
 *     choosen : boolean
 *     profile_id : bigint
 * }}
 */
function shapePairObjectFromElement(element)
{
    let followObject = {};
    let profileId = parseInt(getCookie('chosenProfile')) ?? 1;
    let mode = parseInt(getCookie('mode')) ?? 0;

    Object.assign(followObject, transformToPairObj(element));

    followObject.profile_id = profileId;
    followObject.mode = mode;

    return followObject;
}
