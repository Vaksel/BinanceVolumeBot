if(getCookie('chosenProfile') != '1')
{
    document.querySelector('.active').classList.remove('active');
    document.querySelector('.chosen-profile').closest('.carousel-item').classList.add('active');
}


function addProfile(event)
{
    event.preventDefault();

    let url = event.target.href; //должен быть /add-profile

    let profileName = document.querySelector('#profileName').value;
    let profilePairs = getSavedPairsFields();

    let pairsReadyToSave = [];

    for(let i = 0; i < profilePairs.length; i++)
    {
        let pairObj = transformToPairObj(profilePairs[i]);

        pairsReadyToSave.push(pairObj);
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            profile_name : profileName,
            profile_pairs: pairsReadyToSave
        },
        success: function(data) {
            if(data['status'] !== 'error')
            {
                data['error'] = '';

                setCookie('chosenProfile', data['profile_info']['profile_id']);
                renderAddedProfile(data['profile_info']);
            }
            notificationHandler(data['status'], data['msg'], data['error']);
        },
        fail: function(data) {
            notificationHandler('error', 'Произошла ошибка при передаче данных, вывод в консоли.', data);
        }
    })
}

/**
 *
 * @returns {{
 *     crypto_pair : string,
 *     time_frame : int,
 *     compare_volume : int,
 *     choosen : boolean
 *     profile_id : bigint
 * }}
 */
function transformToPairObj(element)
{
    let followObject = {};

    followObject.crypto_pair = element.querySelector('.ticker').textContent;
    followObject.time_frame = element.querySelector('.timeFrame').selectedIndex;
    followObject.compare_volume = element.querySelector('.checkVolume').value;
    followObject.chosen_pattern = element.querySelector('.compare_pattern').selectedIndex + 1;
    followObject.choosen = element.querySelector('.favourite').classList.contains('choosen') ? 1 : 0;

    return followObject;
}

function renderAddedProfile(profileInfo)
{
    let profileNode = document.createElement('div');
        profileNode.className = 'card';
        profileNode.id = `profileId#${profileInfo['profile_id']}`;
        profileNode.style.width = '18rem';
        profileNode.style.margin = '20px';

    let profileHeader = document.createElement('div');
        profileHeader.className = 'card-header';

    let headerInnerContent = document.createElement('div');
        headerInnerContent.className = 'd-flex';

    let headerInput = document.createElement('input');
        headerInput.className = 'form-control';
        headerInput.type = 'text';
        headerInput.value = profileInfo['profile_name'];

    let headerH2Id = document.createElement('h2');
        headerH2Id.style.color = 'black';
        headerH2Id.innerHTML = `ID:${profileInfo['profile_id']}`;

    headerInnerContent.insertAdjacentElement('beforeend', headerH2Id);

    headerInnerContent.insertAdjacentElement('afterbegin', headerInput);

    profileHeader.insertAdjacentElement('afterbegin', headerInnerContent);

    let profileUl = document.createElement('ul');
        profileUl.className = 'list-group list-group-flush';
        profileUl.innerHTML = `<li class='list-group-item'><a class='btn btn-success profile-btn profile-save' data-id='${profileInfo['profile_id']}' href='http://orendaherbal.com/edit-profile'>Сохранить профиль</a></li>\n` +
            `    <li class='list-group-item'><a class='btn btn-warning profile-btn profile-load' data-id='${profileInfo['profile_id']}' href='http://orendaherbal.com/load-profile'>Загрузить профиль</a></li>\n` +
            `    <li class='list-group-item'><a class='btn btn-danger profile-btn profile-delete' data-id='${profileInfo['profile_id']}' href='http://orendaherbal.com/delete-profile'>Удалить профиль</a></li>`

    let carouselItem = document.createElement('div');
        carouselItem.className = 'carousel-item active';

    let profileCarousel = document.querySelector('.profiles div.carousel-inner');

    profileCarousel.querySelector('.active').classList.remove('active');

    profileNode.insertAdjacentElement('afterbegin', profileHeader);

    profileNode.insertAdjacentElement('beforeend', profileUl);

    carouselItem.insertAdjacentElement('afterbegin', profileNode);

    profileCarousel.insertAdjacentElement('afterbegin', carouselItem);

    initProfileBtns();
}

function getCurrentProfileId()
{
    let profileId = getCookie('chosenProfile')

    if(profileId !== undefined && profileId !== null && profileId !== '')
    {
        return profileId;
    }

    return 0;
}

function changeProfile(event)
{
    event.preventDefault();

    let url = event.target.href;

    let profileId = event.target.getAttribute('data-id');

    let profileName = document.getElementById(`profileId#${profileId}`).querySelector('input').value;

    console.log(profileName);

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            profile_name : profileName,
            profile_id : profileId
        },
        success: function(data) {
            if(data['status'] !== 'error')
            {
                data['error'] = '';
            }
            notificationHandler(data['status'], data['msg'], data['error']);
        },
        fail: function(data) {
            notificationHandler('error', 'Произошла ошибка при передаче данных, вывод в консоли.', data);
        }
    })

    console.log('save');
    return false;
}

function loadProfile(event)
{
    event.preventDefault();

    let profileId = event.target.getAttribute('data-id');

    setCookie('chosenProfile', profileId);

    location.reload();

    console.log('load')
}

function deleteProfile(event)
{
    event.preventDefault();

    let url = event.target.href; //должен быть /add-profile

    let profileId = event.target.getAttribute('data-id');

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            profile_id : profileId,
        },
        success: function(data) {

            if(data['status'] !== 'error')
            {
                data['error'] = '';
            }
            notificationHandler(data['status'], data['msg'], data['error']);

            deleteCookie('chosenProfile');
            deleteProfileNode(data['profile_id']);
            document.getElementById('profileId#1').querySelector('a').click();
        },
        fail: function(data) {
            notificationHandler('error', 'Произошла ошибка при передаче данных, вывод в консоли.', data);
        }
    })

    console.log('delete')
    return false;
}

function deleteProfileNode(profileId)
{
    document.getElementById(`profileId#${profileId}`).remove();
}

function initProfileBtns()
{
    let profileSaveNodes = document.getElementsByClassName('profile-save');

    for(let i = 0; i < profileSaveNodes.length; i++)
    {
        profileSaveNodes[i].addEventListener('click', changeProfile);
    }

    let profileLoadNodes = document.getElementsByClassName('profile-load');

    for(let i = 0; i < profileLoadNodes.length; i++)
    {
        profileLoadNodes[i].addEventListener('click', loadProfile);
    }

    let profileDeleteNodes = document.getElementsByClassName('profile-delete');

    for(let i = 0; i < profileDeleteNodes.length; i++)
    {
        profileDeleteNodes[i].addEventListener('click', deleteProfile);
    }

    let profileAddNode = document.querySelector('.profile-add');

    profileAddNode.addEventListener('click', addProfile);
}

initProfileBtns();
