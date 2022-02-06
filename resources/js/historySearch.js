$('#historySearchDatetime').datetimepicker({
    uiLibrary: 'bootstrap4',
    defaultDate: new Date(),
    // modal: true,
    footer: true,
    format: 'yyyy-mm-dd HH:MM',
    // sideBySide: true,
    // id: 'historySearchDateTimeCalendar',
});

let searchFields = document.getElementById('searchFields').childNodes;

var sendingSearchObject = {};

document.getElementById('historySearchSubmit').
addEventListener('click', () => {
    console.log(searchFields);
    for (let counter = 0;counter < searchFields.length; counter++) {
        let searchField = searchFields[counter];
        if(searchField.id === 'historySearchTimeframe') {
            sendingSearchObject[searchField.id] = searchField.selectedIndex;
        }
        else
        {
            if(searchField.id !== 'historySearchSubmit') {
                if(!searchField.firstChild) {
                    if(searchField.id) {
                        sendingSearchObject[searchField.id] = searchField.value;
                    }
                }
                else{
                    if(searchField.firstChild.id) {
                        sendingSearchObject[searchField.firstChild.id] = searchField.firstChild.value;
                    }
                }
            }
        }
    }

    console.log(sendingSearchObject);

    $.ajax({
        type: "POST",
        url: '/historySearch',
        data: {
            data: sendingSearchObject,
        },
        success: function (msg) {
            document.querySelector('tbody').innerHTML = msg;
            // console.log(msg);
        }
    });
});

document.getElementById('historySearchPair')

