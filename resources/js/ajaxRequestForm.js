function ajaxRequestForConnectingOnStream($streamUrl) {
    $.ajax({
        type : 'get',
        url : '{{URL::to('/stream')}}',
        data:{'url':$streamUrl},
        success:function(data){

            console.log(data);

            var conn = new WebSocket('wss://binance-check/websocket:9000');
            console.log(conn);

            conn.onopen = function() {
                alert("Соединение установлено.");
            };

            conn.onclose = function(event) {
                if (event.wasClean) {
                    alert('Соединение закрыто чисто');
                } else {
                    alert('Обрыв соединения'); // например, "убит" процесс сервера
                }
                alert('Код: ' + event.code + ' причина: ' + event.reason);
            };

            conn.onmessage = function(event) {
                let data = JSON.parse(event.data);
                console.log('Seller volume:' + data.k.V);
                console.log('Buyer volume:' + data.k.v);
                console.log('Quote volume:' + data.k.q);
                let rightDate = new Date(data.E);
                console.log(rightDate.toGMTString());
                // alert("Получены данные " + event.data);
            };

            conn.onerror = function(error) {
                alert("Ошибка " + error.message);
            };

            console.log(document.getElementsByClassName('selectedField'));
            let selectedFields = document.getElementsByClassName('selectedField');
            let webSocketParams = '';
            for (var i = 0; i < selectedFields.length; i++) {
                console.log(selectedFields[i]);
                if(i === 0) webSocketParams = selectedFields[i] + '@kline';
            }
            $('tbody#dropdown').html(data);
        },
        error: function($data){
            console.log('error');
        }
    });
}
