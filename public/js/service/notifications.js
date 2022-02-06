let mainContentNode = document.querySelector('.main_content');
let notificationsList = document.querySelector('#notificationsList');
var notificationAutoIncrement = getNotificationsArrayFromSession().length;
var notificationLoadedObjects = 0;
function notificationHandler(status, msg, params)
{
    switch(status)
    {
        case 'success' :
        {
            notificationLoader('alert alert-success', msg, params)
            break;
        }
        case 'warning' :
        {
            notificationLoader('alert alert-warning', msg, params);
            break;
        }
        case 'error' :
        {
            notificationLoader('alert alert-danger', msg, params);
            break;
        }
        case 'check' :
        {
            notificationLoader('alert alert-dark', msg, params);
            break;
        }
    }
}

function notificationInitBtn()
{
    let notificationBtn = document.getElementById('notifications');

        notificationBtn.addEventListener('click', ()=>{

            let notificationsToWrite = getNotificationsArrayFromSession();

            notificationAutoIncrement = notificationsToWrite.length;

            let isButton = true;

            renderNotificationsToNotificationPanel(notificationsToWrite, isButton);

            if(document.getElementById('clearNotificationStorage') === undefined || document.getElementById('clearNotificationStorage') === null)
            {
                let delNotifBtn = getDeleteNotificationsBtnNode();

                document.getElementById('notificationsList').insertAdjacentElement('afterbegin', delNotifBtn);
            }
            else
            {
                document.getElementById('clearNotificationStorage').remove();

                let delNotifBtn = getDeleteNotificationsBtnNode();

                document.getElementById('notificationsList').insertAdjacentElement('afterbegin', delNotifBtn);
            }

            $('#notificationsList').toggle('slow');
        });
}

function getDeleteNotificationsBtnNode()
{
    let clearNotificationStorageBtn = document.createElement('div');

    clearNotificationStorageBtn.className = 'btn btn-danger';
    clearNotificationStorageBtn.id = 'clearNotificationStorage';
    clearNotificationStorageBtn.style.opacity = '0.7';
    clearNotificationStorageBtn.style.width = '100%';
    clearNotificationStorageBtn.innerHTML = 'Очистить все уведомления';

    clearNotificationStorageBtn.addEventListener('click', () => {
        localStorage.clear();
        notificationAutoIncrement = 0;
        notificationLoadedObjects = 0;
        let notificationsList = document.getElementById('notificationsList');

        notificationsList.innerHTML = '';
        notificationsList.insertAdjacentElement('afterbegin', clearNotificationStorageBtn);
    })

    return clearNotificationStorageBtn;
}

function notificationLoader(notificationClass, msg, data)
{
    if(data !== '')
    {
        if(notificationClass === 'alert alert-danger')
        {
            console.error(data);
        }
        else {
            if(notificationClass === 'alert alert-warning')
            {
                console.warn(data);
            }
            else
            {
                console.log(data);
            }
        }
    }



    let notificationObject = getNotificationObject(notificationClass, msg);

    saveNotificationToSession(notificationObject);



    let notificationsToWrite = getNotificationsArrayFromSession();

    renderNotificationsToNotificationPanel(notificationsToWrite);

}

function notificationNode(notificationObjectJSON)
{
    let notificationAlert = document.createElement('div');
    notificationAlert.className = `${notificationObjectJSON.class} alert_in-list`;
    notificationAlert.setAttribute('role', 'alert');

    let notificationInner = document.createElement('div');
    notificationInner.className = 'd-flex flex-column';

    let notificationText = document.createElement('span');
    notificationText.textContent = notificationObjectJSON.msg;

    let notificationDate = document.createElement('span');
    notificationDate.className = 'notificatioDate';
    notificationDate.textContent = notificationObjectJSON.notificationDate;

    notificationInner.insertAdjacentElement('afterbegin', notificationText);

    notificationInner.insertAdjacentElement('beforeend', notificationDate);

    notificationAlert.insertAdjacentElement('afterbegin', notificationInner);

    return notificationAlert;
}

function getNotificationObject(notificationClass, msg)
{
    let notificationSessionId = `Notification №${notificationAutoIncrement}`;

    return {
        id: notificationSessionId,
        class: notificationClass,
        msg: msg,
        notificationDate: getDateTime(Date.now())
    }
}

function saveNotificationToSession(notificationObject)
{
    try
    {
        localStorage.setItem(`Notification №${notificationAutoIncrement}`, JSON.stringify(notificationObject));

        notificationAutoIncrement++;
        return true;
    }
    catch(e)
    {
        notificationHandler('error', 'Произошла ошибка, невозможно записать обьект в хранилище', e);

        return false;
    }
}

function renderNotificationsToNotificationPanel(notificationsArray, isButton = false)
{
    notificationsList.innerHTML = '';
    notificationsArray.forEach((notificationObject)=>{

        let notificationObjectJSON = JSON.parse(notificationObject);

        if(typeof timeout !== 'undefined')
        {
            clearTimeout(timeout);
        }

        let notificationAlert = notificationNode(notificationObjectJSON);

        notificationsList.insertAdjacentElement('afterbegin', notificationAlert);

        notificationLoadedObjects++;

        if(!isButton)
        {
            if(document.getElementById('notificationsList').style.display === 'none')
            {
                $('#notificationsList').toggle('fast');
            }

            timeout = setTimeout(()=>{

                if(document.getElementById('notificationsList').style.display !== 'none')
                {
                    $('#notificationsList').toggle('fast');
                }

            }, 60000)
        }
    });
}

function getNotificationsArrayFromSession()
{
    let notificationsList = [];

    for(let i = 0; i > -1; i++)
    {
        let notificationItem = localStorage.getItem(`Notification №${i}`);

        if(notificationItem === null || notificationItem === undefined )
        {
            return notificationsList;
        }

        notificationsList.push(localStorage.getItem(`Notification №${i}`));

    }

    return notificationsList;
}

notificationInitBtn();
