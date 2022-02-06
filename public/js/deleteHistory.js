function deleteHistory(deleteAllRecords) {
    $.ajax({
        type: "POST",
        url: '/historyDelete',
        data: {
            data : readDeleteQuery(deleteAllRecords),
        },
        success: (res) => {
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['status'], res['msg'], res['error']);
        },
        error: (res) => {
            if(res['status'] !== 'error')
            {
                res['error'] = '';
            }

            notificationHandler(res['error'], res['Произошла ошибка при передаче данных на сервер, вывод в консоли'], res);
        },
    });
}
function readDeleteQuery(deleteAllRecords = false) {
    if(deleteAllRecords) {
        return {
            deleteAll: true,
        }
    }
    else {
        return {
            startDateTime: document.getElementById('historyDeleteStartDatetime').value,
            finishDateTime: document.getElementById('historyDeleteFinishDatetime').value,
        };
    }

}
function flashMessage(status) {
    let message = '';

    message = status ? 'Удаление прошло успешно, обновите страницу' : 'Что-то пошло не так, повторите...';
    document.getElementById('flashMessage').innerHTML = message;
    setTimeout(()=>{
        document.getElementById('flashMessage').innerHTML = '';
    },5000);
}
