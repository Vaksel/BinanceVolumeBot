<?php

?>

@extends('layout')

@section('title', 'Паттерны')

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

            @php
                $gridData = [
                    'dataProvider' => $dataProvider,
                    'title' => 'Паттерны',
                    'useFilters'    => true,
                    'tableHover'    => false,
                    'rowsPerPage'   => 100,
                    'columnFields'  => [
                        [
                            'label'     => 'ID записи',
                            'attribute' => 'id',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\TextFilter::class,
                            ]
                        ],
                        [
                            'label'     => 'Имя паттерна',
                            'attribute' => 'name',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\TextFilter::class,
                            ]
                        ],
                        [
                            'label'     => 'Пара',
                            'attribute' => 'pair',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\TextFilter::class,
                            ]
                        ],
                        [
                            'label' => 'Таймфрейм',
                            'value' => function ($row) {
                                    $timeFrameTransformativeArr = [
                                        'Минута','3 мин','5 мин','15 мин','30 мин','Час','2 часа','4 часа','6 час',
                                        '8 час','12 час','День','3 дня','Неделя','Месяц',
                                    ];
                                return $timeFrameTransformativeArr[$row->timeframe];
                            },
                            'attribute' => 'timeframe',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                                'data'  => [
                                        0 => 'Минута',1 => '3 мин',2 => '5 мин',3 => '15 мин',4 => '30 мин',5 => 'Час',6 => '2 часа',7 => '4 часа',8 => '6 час',
                                        9 => '8 час',10 => '12 час',11 => 'День',12 =>'3 дня',13 => 'Неделя',14 => 'Месяц',
                                    ]
                            ]
                        ],
                        [
                            'label' => 'Профиль',
                            'value' => function ($row) {
                                return $row->profile_id;
                            },
                            'attribute' => 'profile_id',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\TextFilter::class,
                            ]
                        ],
                        [
                            'label'     => 'Дата добавления',
                            'attribute' => 'created_at',
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\TextFilter::class,
                            ]
                        ],
                        [
                            'label'     => 'Выбранная пара',
                            'attribute' => 'chosen',
                            'value'     => function($row) {
                                    $statusColor = $row->chosen ? 'orange' : 'white';
                                    $chosenText = $row->chosen ? 'Избранная' : 'Не избранная';
                                return "<span style='background-color: ". $statusColor ."'>{$chosenText}</span>";
                            },
                            'filter'    => [
                                'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                                'data'  => [
                                        1 => 'Избранная',0 => 'Не избранная'
                                    ]
                            ],
                            'format' => 'html'
                        ],
                        [
                            'label'     => 'Активная ссылка',
                            'value'     => function($row) {
                                return "<a href='https://www.binance.com/en/trade/" . $row->pair . "?layout=basic&type=spot'>Перейти</a>";
                            },
                            'format' => 'html'
                        ],
                    ]
                ];
            @endphp

@section('content')

    @include('notificationsList')

        <div class="container">
            <div id="searchFields" class="d-flex flex-column">
                <h2 class="card-title">Удаление записей</h2>
                <div class="d-flex">
                    <div class="d-flex flex-column">
                        <div>Промежуток для удаления</div>
                        <div class="d-flex">
                            <input type="text" id="patternsDeleteStartDatetime" width="350" placeholder="Начало промежутка">
                            <input type="text" id="patternsDeleteFinishDatetime" width="350" placeholder="Конец промежутка">
                        </div>
                        <input type="submit" id="searchSubmitDelete" onclick="deletePatterns()" value="Удалить">
                        <div id="flashMessage"></div>
                    </div>
                    <input type="submit" id="patternsDeleteAll" class="btn-danger" value="Удалить все записи" onclick="deletePatterns(true)">
                </div>
            </div>
        <br>
        @gridView($gridData)
        </div>

{{--<script>--}}
{{--    let chosenProfile = getCookie('chosenProfile');--}}

{{--    document.querySelector('table tr:nth-child(2) td:nth-child(7) > input').value = chosenProfile;--}}

{{--    document.getElementById('grid_view_search_button').click();--}}
{{--</script>--}}


<script src="/public/js/candleAbsorption.js"></script>

@endsection
