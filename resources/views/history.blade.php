<?php
?>
@extends('layout')

@section('title', 'История')

@section('head')
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@php
            $gridData = [
                'dataProvider' => $dataProvider,
                'title' => 'История',
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
                        'label'     => 'Тикер записи',
                        'attribute' => 'ticker',
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
                        'label' => 'Режим роботы',
                        'value' => function ($row) {
                                $modeTransformativeArr = [
                                    'Обьемы','Проценты', 'Все Пары Проценты'
                                ];
                            return $modeTransformativeArr[$row->record_type];
                        },
                        'attribute' => 'record_type',
                        'filter'    => [
                            'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                            'data'  => [
                                    0 => 'Обьемы',1 => 'Проценты', 2 => 'Все Пары Проценты',
                                ]
                        ]
                    ],
                    [
                        'label'     => 'Статус свечи',
                        'attribute' => 'volume_status',
                        'filter'    => [
                            'class' => Itstructure\GridView\Filters\TextFilter::class,
                        ]
                    ],
                    [
                        'label'     => 'Обьем свечи',
                        'attribute' => 'volume',
                        'filter'    => [
                            'class' => Itstructure\GridView\Filters\TextFilter::class,
                        ]
                    ],
                    [
                        'label'     => 'Разница обьема',
                        'attribute' => 'volume_difference',
                        'filter'    => [
                            'class' => Itstructure\GridView\Filters\TextFilter::class,
                        ]
                    ],
                    [
                        'label'     => 'Обьем для сравнения',
                        'attribute' => 'compare_volume',
                        'filter'    => [
                            'class' => Itstructure\GridView\Filters\TextFilter::class,
                        ]
                    ],
                    [
                        'label' => 'Профиль',
                        'value' => function ($row) {
                            return $row->profile_id;
                        },
                        'attribute' => 'timeframe',
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
                        <input type="text" id="historyDeleteStartDatetime" width="350" placeholder="Начало промежутка">
                        <input type="text" id="historyDeleteFinishDatetime" width="350" placeholder="Конец промежутка">
                    </div>
                    <input type="submit" id="searchSubmitDelete" onclick="deleteHistory()" value="Удалить">
                    <div id="flashMessage"></div>
                </div>
                <input type="submit" id="historyDeleteAll" class="btn-danger" value="Удалить все записи" onclick="deleteHistory(true)">
            </div>
        </div>
        <br>
        @gridView($gridData)
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script src="/public/js/historySearch.js"></script>

    <script src="/public/js/deleteHistory.js"></script>

@endsection
