let interval = setInterval(ajaxRealTimeSearchReqRes, 1000);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    function ajaxRealTimeSearchReqRes() {
        $.ajax({
            type: "POST",
            url: '/historySearch',
            data: {
                data : readRealTimeSearchQuery(),
            },
            success: function (res) {
                writeRealTimeSearchResults(res['sortedOutput'],'realTimeHistorySearchResults');
                writeRealTimeSearchResults(res['response'], 'realTimeHistorySearchResultsAllPairs');
                changeTickersList(res['savedPairs'])
            },
        });
    }

    function writeRealTimeSearchResults(data,id) {
        document.getElementById(id).innerHTML = data;
    }

    function changeTickersList(tickers)
    {

        let pairForSortSelect = document.getElementById('pairForSort');

        let html = '';

        tickers.forEach(ticker => html += `<option value="${ticker['crypto_pair']}">${ticker['crypto_pair']}</option>`);

        if(pairForSortSelect.innerHTML != html)
        {
            pairForSortSelect.innerHTML = html;
        }
    }

    function readRealTimeSearchQuery() {
        let transformArr = [50, 100, 200];
        let recordsQty = document.getElementById('recordsQtySelect').options.selectedIndex;
        let pairForSort = document.getElementById('pairForSort').value;
        let pairForSortInput = document.getElementById('pairForSortInput').value;
        let requestedProfile = document.getElementById('profiles').value;
        let recordsQtyAllSignals = document.getElementById('recordsQtySelectAllSignals').options.selectedIndex;
        let requestedProfileAllSignals = document.getElementById('profilesAllSignals').value;
        let mode = document.getElementById('mode').selectedIndex;
        let modeAllSignals = document.getElementById('modeAllSignals').selectedIndex;

        // console.log(document.getElementById('profiles'));
        //
        // console.log(requestedProfile);
        recordsQty = transformArr[recordsQty];
        recordsQtyAllSignals = transformArr[recordsQtyAllSignals];
        return {
            realTimeHistoryQty: recordsQty,
            pairForSort: pairForSort,
            pairForSortInput: pairForSortInput,
            requested_profile: requestedProfile,
            realTimeHistoryQtyAllSignals: recordsQtyAllSignals,
            requested_profile_all_pairs: requestedProfileAllSignals,
            mode: mode,
            modeAllSignals: modeAllSignals
        };
    }

    function clearRealTimeSearchResults(id) {
        document.getElementById(id).innerHTML = '';
    }

    function stopSearching()
    {
        clearInterval(interval);
    }

    function returnSearching()
    {
        // console.log(interval);

        stopSearching();

        // console.log(interval);
    }

    function initModeTogglerForSearch()
    {
        let modeSelect = document.getElementById('mode');

        modeSelect.addEventListener('change', function ()
        {
            switch (this.selectedIndex)
            {
                case 0 : {
                    $('#pairForSortInput').hide();

                    $('#pairForSort').show();

                    $('#pairForSortInput').val('');

                    break;
                }
                case 1 : {
                    $('#pairForSortInput').hide();

                    $('#pairForSort').show();

                    $('#pairForSortInput').val('');

                    break;
                }
                case 2 : {
                    $('#pairForSortInput').show();

                    $('#pairForSort').hide();

                    break;
                }
            }

        })
    }

    initModeTogglerForSearch();

