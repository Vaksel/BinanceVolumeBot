<?php
?>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/public/css/app.css">
    @yield('head')
    <script src="/public/js/service/globalVars.js"></script>

    <script src="/public/js/service/cookie.js"></script>

    <script src="/public/js/service/time.js"></script>
</head>
<body>
<script type="text/javascript">
    $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    console.log('{{ csrf_token() }}');
</script>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="d-flex justify-content-between" style="width:100%;">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('monitoring')}}">Мониторинг</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('showHistory')}}">История</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('showSignals')}}">Сигналы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('showPatterns')}}">Паттерны</a>
                    </li>
                </ul>
                <div class="notifications">
                    <svg aria-hidden="true" id="notifications" focusable="false" data-prefix="far" data-icon="bell" class="svg-inline--fa fa-bell fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"/></svg>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="main_content">

    @yield('content')
</div>

<script type="text/javascript">
    $('#search').on('keyup', function () {
        document.getElementById('searchResults').style.display = 'block';
        $value = $(this).val();
        $.ajax({
            type: 'POST',
            url: '{{URL::to('/search')}}',
            data: {'search': $value},
            success: function (data) {
                $('tbody#dropdown').html(data);
            }
        });
    })
    $('#closeSearchResult').on('click', function () {
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('closeSearchResult').value = '';
    })
</script>

<script src="/public/js/service/notifications.js"></script>

</body>
</html>
