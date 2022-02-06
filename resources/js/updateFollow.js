document.getElementById('selectedTickers').onkeyup = function updateFollowInDB(event) {
    if (event.target.nodeName === 'INPUT') {
        let element = event.target.closest('tr');
        let followObject = {};
        followObject.crypto_pair = element.childNodes[0].textContent;
        followObject.time_frame = element.childNodes[1].firstChild.selectedIndex;
        followObject.compare_volume = element.childNodes[4].firstChild.value;

        console.log(followObject);

        $.ajax({
            type: "POST",
            url: '/updateFollow',
            data: {
                data: followObject,
            },
            success: function (msg) {
                console.log(msg);
            }
        });
    }
};
