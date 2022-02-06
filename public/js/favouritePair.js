function initTogglers()
{
    let favPairBtns = document.getElementsByClassName('favouritePairToggler');

    console.log(favPairBtns);

    for(let i=0; i<favPairBtns.length; i++)
    {
        favPairBtns[i].addEventListener('click', favouriteStatusToggler);
    }
}

function favouriteStatusToggler(event)
{
    let favouriteStatTogglerNode = event.target.closest('td');

    let fs = favouriteStatTogglerNode;

    let unChoosenSvg = '<svg aria-hidden="true" style="color: orange;" width="50" height="50" focusable="false" data-prefix="far" data-icon="star" class="favouritePairToggler svg-inline--fa fa-star fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">\n' +
        '               <path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4\n' +
        '                   0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7\n' +
        '                   68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3\n' +
        '                   65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z">\n' +
        '               </path>\n' +
        '           </svg><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>';

    let choosenSvg = '<svg aria-hidden="true" style="color: orange;" width="50" height="50" focusable="false" data-prefix="fas" data-icon="star" class="favouritePairToggler svg-inline--fa fa-star fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>';

    if(fs.className == 'favourite choosen')
    {
        fs.className = 'favourite';
        fs.innerHTML = unChoosenSvg;

        let element = fs.closest('tr');
        let followObject = {};

        let profile_id = getCookie('chosenProfile') ? parseInt(getCookie('chosenProfile')) : 1;
        let mode = getCookie('mode') ? parseInt(getCookie('mode')) : 1;

        followObject.crypto_pair = element.querySelector('.ticker').textContent;
        followObject.time_frame = element.querySelector('.timeFrame').selectedIndex;
        followObject.compare_volume = element.querySelector('.checkVolume').value;
        followObject.chosen_pattern = element.querySelector('.compare_pattern').selectedIndex + 1;
        followObject.choosen = false;
        followObject.profile_id = profile_id;
        followObject.mode = mode;

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

                notificationHandler(res['status'], res['msg'], res['error']);
            }
        });
    }
    else
    {
        fs.className = 'favourite choosen';
        fs.innerHTML = choosenSvg;

        let element = fs.closest('tr');
        let followObject = {};

        let profile_id = getCookie('chosenProfile') ? parseInt(getCookie('chosenProfile')) : 1;
        let mode = getCookie('mode') ? parseInt(getCookie('mode')) : 0;


        followObject.crypto_pair = element.querySelector('.ticker').textContent;
        followObject.time_frame = element.querySelector('.timeFrame').selectedIndex;
        followObject.compare_volume = element.querySelector('.checkVolume').value;
        followObject.chosen_pattern = element.querySelector('.compare_pattern').selectedIndex + 1;
        followObject.choosen = true;
        followObject.profile_id = profile_id;
        followObject.mode = mode;

        console.log('updateFollow1');

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

                notificationHandler(res['status'], res['msg'], res['error']);
            }
        });
    }

    fs.querySelector('.favouritePairToggler').addEventListener('click', favouriteStatusToggler);
}

initTogglers();
