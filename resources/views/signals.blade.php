

@extends('layout')

@section('title', 'Сигналы')

@section('head')
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

    @include('notificationsList')

<div class="container d-flex">
<div class="realTimeHistorySearchBlock d-flex flex-column">
    <div class="d-flex flex-column">
        <label for="historySearchRT">Выберите к-во записей:</label>
        <div onclick="clearRealTimeSearchResults('realTimeHistorySearchResults')">
            <svg id="closeSearchResult" xmlns="http://www.w3.org/2000/svg" style="width: 30px; cursor: pointer;"
                 viewBox="0 0 32 32">
                <defs>
                    <style>.cls-1 {
                            fill: none;
                            stroke: #000;
                            stroke-linecap: round;
                            stroke-linejoin: round;
                            stroke-width: 2px;
                        }
                    </style>
                </defs>
                <title/>
                <g id="cross">
                    <line class="cls-1" x1="7" x2="25" y1="7" y2="25"/>
                    <line class="cls-1" x1="7" x2="25" y1="25" y2="7"/>
                </g>
            </svg>
        </div>
        <div class="d-flex">
            <select name="recordsQtySelect" id="recordsQtySelect" onclick="ajaxRealTimeSearchReqRes()">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
            <select name="pairForSort" id="pairForSort" onclick="ajaxRealTimeSearchReqRes()">
                @foreach($followedPairs as $followPair)
                {
                    <option value="{{$followPair['crypto_pair']}}">{{$followPair['crypto_pair']}}</option>
                }
                @endforeach
            </select>
            <input placeholder="Введите пару для поиска" type="text" name="pairForSortInput" id="pairForSortInput" style="display: none;" onclick="ajaxRealTimeSearchReqRes()">
        </div>
        <select name="profiles" id="profiles" onclick="ajaxRealTimeSearchReqRes()">
            <option value="{{$chosenProfile}}">Текущий профиль</option>

            @foreach($profiles as $profile)
            {
                @if($profile['id'] != $chosenProfile)
                    <option value="{{$profile['id']}}">{{$profile['name']}}</option>
                @endif

            }
            @endforeach

        </select>
    <select name="mode" id="mode" onclick="ajaxRealTimeSearchReqRes()">
        <option value="0" <?php if($chosenMode === 0) echo ' selected'?>>Обьемы</option>

        <option value="1" <?php if($chosenMode === 1) echo ' selected'?>>Проценты</option>

        <option value="2" <?php if($chosenMode === 2) echo ' selected'?>>Все Пары Проценты</option>
    </select>

    </div>
    <div id="realTimeHistorySearchResults"></div>
</div>

<div class="realTimeHistorySearchBlockAllSignals realTimeHistorySearchBlock d-flex flex-column" style="margin-left: 20px;">
        <div class="d-flex flex-column">
        <label for="historySearchRT">Выберите к-во записей:</label>
    <div onclick="clearRealTimeSearchResults('realTimeHistorySearchResults')">
        <svg id="closeSearchResult" xmlns="http://www.w3.org/2000/svg" style="width: 30px; cursor: pointer;"
    viewBox="0 0 32 32">
        <defs>
        <style>.cls-1 {
        fill: none;
        stroke: #000;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 2px;
    }
    </style>
    </defs>
    <title/>
    <g id="cross">
        <line class="cls-1" x1="7" x2="25" y1="7" y2="25"/>
        <line class="cls-1" x1="7" x2="25" y1="25" y2="7"/>
        </g>
        </svg>
        </div>
        <div class="d-flex">
        <select name="recordsQtySelectAllSignals" id="recordsQtySelectAllSignals" onclick="ajaxRealTimeSearchReqRes()">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
        </select>
    </div>
    <select name="profilesAllSignals" id="profilesAllSignals" onclick="ajaxRealTimeSearchReqRes()">
        <option value="{{$chosenProfile}}">Текущий профиль</option>

        @foreach($profiles as $profile)
    {
            @if($profile['id'] != $chosenProfile)
        <option value="{{$profile['id']}}">{{$profile['name']}}</option>
        @endif

    }
    @endforeach

    </select>
    <select name="modeAllSignals" id="modeAllSignals" onclick="ajaxRealTimeSearchReqRes()">
            <option value="0" <?php if($chosenMode === 0) echo ' selected'?>>Обьемы</option>

            <option value="1" <?php if($chosenMode === 1) echo ' selected'?>>Проценты</option>

            <option value="2" <?php if($chosenMode === 2) echo ' selected'?>>Все Пары Проценты</option>
    </select>

    </div>
    <div id="realTimeHistorySearchResultsAllPairs"></div>
</div>
<script src="/public/js/realTimeHistory.js"></script>

{{--<script src="/public/js/candleAbsorption.js"></script>--}}
@endsection
