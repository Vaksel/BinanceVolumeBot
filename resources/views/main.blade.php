<?php

?>

@extends('layout')

@section('title', 'Мониторинг')

@section('head')
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
            crossorigin="anonymous"></script></script>

    <script src="/public/js/service/candle.js"></script>

    <script src="/public/js/service/profiles.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @endsection

    @section('content')

    @include('notificationsList')

    @include('profile.profiles', compact('profiles'))

    <script>
    	window.patternsCollection = ({!! $json !!});
    	window.patternsCollection = window.patternsCollection.reverse();
    </script>

	<script>
		if(!getCookie('reloadTimer'))
		{
            setCookie('reloadTimer', 1, {'max-age' : 5});
            window.location.reload(true);
        }
	</script>

    <div class="d-flex">
        <div class="d-flex flex-column" style="width: 100%; margin: 20px;">
            @if($mode === 2) {{--  перенести по отдельным blade компонентам и отталкиваясь от mode применять нужный  --}}

            <div class="d-flex flex-row justify-content-between">

                        <div class="mode-settings-outer d-flex flex-column">

                            <div>
                                Обьем для бомбежки
                            </div>

                            <input type="text" name="bombing-volume" id="bombing-volume" value="100">
                        </div>

                        <div class="mode-settings-outer d-flex flex-column">
                            <div>
                                Таймфреймы для бомбежки
                            </div>

                            <div name="bombing-timeframe" id="bombing-timeframe">
								<label for="">
									1m
                                	<input type="checkbox" value="1m">
								</label>
								<label for="">
									3m
                                	<input type="checkbox" value="3m">
								</label>
																<label for="">
																	5m
								<input type="checkbox" value="5m">
																</label>
																<label for="">
																	15m
								<input type="checkbox" value="15m">
                                								</label>
																<label for="">
																	30m
								<input type="checkbox" value="30m">
																</label>
																<label for="">
																	1h
								<input type="checkbox" value="1h">
																</label>
																<label for="">
																	2h
								<input type="checkbox" value="2h">
                                								</label>
																<label for="">
																	4h
								<input type="checkbox" value="4h">
																</label>
																<label for="">
																	6h
								<input type="checkbox" value="6h">
																</label>
																<label for="">
																	8h
								<input type="checkbox" value="8h">
																</label>
																<label for="">
																	12h
								<input type="checkbox" value="12h">
                                								</label>
																<label for="">
																	1d
								<input type="checkbox" value="1d">
																</label>
																<label for="">
																	3d
								<input type="checkbox" value="3d">
                                								</label>
																<label for="">
																	1w
								<input type="checkbox" value="1w">
                            								</label>
							</div>
                        </div>

                    <div class="mode-outer d-flex flex-column">
                        <div>
                            Режим работы
                        </div>

                        <select name="mode" id="mode">
                            <option value="0" >
                                Обьемы в значениях
                            </option>
                            <option value="1">
                                Обьемы в процентах
                            </option>
                            <option value="2">
                                Бомбежка
                            </option>
                        </select>
                    </div>
            </div>
            @else
                <div class="d-flex flex-row justify-content-between">

                <div class="d-flex flex-column">
                    <label for="cryptoPairSearch">Введите криптопару:</label>
                    <div>
                        <input type="text" name="cryptoPairSearch" id="search">
                        <svg id="closeSearchResult" xmlns="http://www.w3.org/2000/svg"
                             style="width: 30px; cursor: pointer;"
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
                </div>



                <div class="mode-outer d-flex flex-column">
                    <div>
                        Режим работы
                    </div>

                    <select name="mode" id="mode">
                        <option value="0">
                            Обьемы в значениях
                        </option>
                        <option value="1">
                            Обьемы в процентах
                        </option>
                        <option value="2">
                            Бомбежка
                        </option>
                    </select>
                </div>

{{--                <div class="btn btn-warning click-save" id="saveChosenPairs">Сохранить избранные</div>--}}

                <div class="btn btn-success click-save" id="saveAllPairs">Сохранить состояние всех пар</div>

            </div>

                <div id="searchResults">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Крипто-пара</th>
                        <th>Тайм-фрейм</th>
                        <th>Обьем для проверки</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="dropdown">
                    </tbody>
                </table>
            </div>

                <table class="table table-bordered table-hover" style="margin: 20px;">
                <thead>
                <tr>
                    <th>Статус избранная</th>
                    <th>Крипто-пара</th>
                    <th>Паттерн для сравнения</th>
                    <th>Тайм-фрейм</th>
                    <th>Обьем на sell</th>
                    <th>Обьем на buy</th>
                    <th>Обьем для проверки</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="selectedTickers">
                @foreach($savedFollows as $item)
                    <tr id="selectedFieldOf_{{strtolower($item->crypto_pair).'@kline_'.$timeFrameTransformativeArr[$item->time_frame]}}"
                        class="selectedField">
                        <?php
                        $statusClass = $item->choosen ? 'favourite choosen' : 'favourite';
                        ?>
                        <td class="{{ $statusClass }}">
                            @if(!$item->choosen)
                                <svg aria-hidden="true" style="color: orange;" width="50" height="50" focusable="false"
                                     data-prefix="far" data-icon="star"
                                     class="favouritePairToggler svg-inline--fa fa-star fa-w-18"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4
                                        0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7
                                        68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3
                                        65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z">
                                    </path>
                                </svg>
                            @else
                                <svg aria-hidden="true" style="color: orange;" width="50" height="50" focusable="false"
                                     data-prefix="fas" data-icon="star"
                                     class="favouritePairToggler svg-inline--fa fa-star fa-w-18"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7
                                        103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7
                                        68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
                                    </path>
                                </svg>
                            @endif
                        </td>
                        <td class="ticker">{{$item->crypto_pair}}</td>
                        <td>
                            <select class="compare_pattern">
                                @foreach($patternsList as $pattern_row)
                                    {
                                    <option value="{{$pattern_row->id}}" {{$item->chosen_pattern == $pattern_row->id ? 'selected' : null}}>
                                        {{$pattern_row->name}}
                                    </option>
                                    }
                                @endforeach

                            </select>
                        </td>
                        <td>
                            <select class="timeFrame" disabled>
                                <option value="1m" {{$item->time_frame == 0 ? 'selected' : null}}>
                                    Минута
                                </option>
                                <option value="3m" {{$item->time_frame == 1 ? 'selected' : null}}>
                                    3 мин.
                                </option>
                                <option value="5m" {{$item->time_frame == 2 ? 'selected' : null}}>
                                    5 мин.
                                </option>
                                <option value="15m" {{$item->time_frame == 3 ? 'selected' : null}}>
                                    15 мин.
                                </option>
                                <option value="30m" {{$item->time_frame == 4 ? 'selected' : null}}>
                                    30 мин.
                                </option>
                                <option value="1h" {{$item->time_frame == 5 ? 'selected' : null}}>
                                    Час
                                </option>
                                <option value="2h" {{$item->time_frame == 6 ? 'selected' : null}}>
                                    2 часа
                                </option>
                                <option value="4h" {{$item->time_frame == 7 ? 'selected' : null}}>
                                    4 часа
                                </option>
                                <option value="6h" {{$item->time_frame == 8 ? 'selected' : null}}>
                                    6 час.
                                </option>
                                <option value="8h" {{$item->time_frame == 9 ? 'selected' : null}}>
                                    8 час.
                                </option>
                                <option value="12h" {{$item->time_frame == 10 ? 'selected' : null}}>
                                    12 час.
                                </option>
                                <option value="1d" {{$item->time_frame == 11 ? 'selected' : null}}>
                                    День
                                </option>
                                <option value="3d" {{$item->time_frame == 12 ? 'selected' : null}}>
                                    3 дня
                                </option>
                                <option value="1w" {{$item->time_frame == 13 ? 'selected' : null}}>
                                    Неделя
                                </option>
                                <option value="1M" {{$item->time_frame == 14 ? 'selected' : null}}>
                                    Месяц
                                </option>
                            </select>
                        </td>
                        <td class="sellVolume">Ожидайте</td>
                        <td class="buyVolume">Ожидайте</td>
                        <td>
                            <input class="checkVolume" type="text" value="{{$item->compare_volume}}"
                                   id="volumeOf_{{$item->crypto_pair}}">
                        </td>
                        <td>
                            <input type="button" class="cancelFollow" id="cancelFollow_{{$item->crypto_pair}}"
                                   value="Отменить слежку">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>

    <script src="/public/js/socketClient.js"></script>

    @if($mode !== 2)
        <script src="/public/js/candleAbsorption.js"></script>

        <script src="/public/js/liveSearch.js"></script>

        <script src="/public/js/favouritePair.js"></script>

        <script src="/public/js/updateFollow.js"></script>

        <script src="/public/js/savePairs.js"></script>
    @endif


    <script src="/public/js/profiles.js"></script>

    <script src="/public/js/volumePercentCompare.js"></script>


@endsection
