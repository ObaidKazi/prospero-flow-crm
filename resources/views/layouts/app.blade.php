<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ url('/asset/img/funnel.png') }}">
    <link rel="manifest" href="{{ url('/manifest') }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <!-- Scripts -->
    
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
        integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="/asset/css/prospect-flow.css">
    <link rel="stylesheet" href="/asset/theme/space/css/space.css">
    @auth
        @if (Auth::user()->company &&
                !empty(Auth::user()->company->name) &&
                is_file(public_path(
                        '/asset/upload/company/' .
                            \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') .
                            '/' .
                            \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') .
                            '.css')))
            <link rel="stylesheet"
                href="{{ '/asset/upload/company/' . \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') . '/' . \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') . '.css' }}">
        @endif
    @endauth
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @stack('styles')
</head>
<body>
    @auth
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark d-print-none" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        @if (empty(Auth::user()->company->logo))
                            {{ env('APP_NAME') }}
                        @else
                            <img src="/asset/upload/company/{{ \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') }}/{{ Auth::user()->company->logo }}"
                                alt="{{ env('APP_NAME') }}" class="logo">
                        @endif
                        @if (!App::environment('production'))
                            <span class="float-right">
                                <small class="">{{ env('APP_ENV') }}</small>
                            </span>
                        @endif
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        @auth
                            @include('menu.top')
                        @endauth

                        <ul class="navbar-nav navbar-right">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @endguest

                            @auth
                                <li class="nav-item dropdown dropstart">
                                    <a class="nav-link" href="#" id="notification-list" data-bs-toggle="dropdown"
                                        aria-expanded="false" onclick="ProspectFlow.Notification.getLatest()">
                                        <i class="fa-regular fa-bell"></i>
                                        <span id="notification-badge">
                                            <span class="visually-hidden">{{ __('New notifications') }}</span>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu" style="width: 50vw">
                                        <ul class="list-group" id="notification-message-list"
                                            aria-labelledby="notification-list">
                                            <li class="p-2">
                                                <div class="m-0 alert alert-warning fade show" role="alert">
                                                    {{ __('You have no unread notifications') }}
                                                </div>
                                            </li>
                                        </ul>
                                        <hr class="dropdown-divider">
                                        <a href="{{ url('/notification') }}"
                                            class="dropdown-item">{{ __('View notifications') }}</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdown10" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        @if (empty(Auth::user()->photo))
                                            <img src="/asset/img/user.jpg" alt="{{ Auth::user()->first_name }}" width="32"
                                                height="32" class="rounded-circle">
                                        @else
                                            <img src="/asset/upload/company/{{ \Illuminate\Support\Str::slug(Auth::user()->company->name, '_') }}/{{ Auth::user()->photo }}"
                                                alt="{{ Auth::user()->first_name }}" width="32" height="32"
                                                class="rounded-circle">
                                        @endif
                                        {{ Auth::user()->first_name }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdown10">
                                        <li>
                                            <a href="{{ url('/profile') }}" class="dropdown-item">
                                                <i class="las la-user-tie"></i> {{ __('Profile') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/setting') }}" class="dropdown-item">
                                                <i class="las la-cogs"></i> {{ __('Setting') }}
                                            </a>
                                        </li>
                                        <li role="separator" class="dropdown-divider"></li>
                                        <li>
                                            <a href="#" onclick="Hammer.exit('{{ __('Do you want to exit?') }}')"
                                                class="dropdown-item">
                                                <i class="las la-door-open"></i> {{ __('Exit') }}</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    @endauth
    <main>
        @auth
            @include('menu.sidebar')
        @endauth
        <div class="container-fluid @auth mt-5 pt-3 @endauth" style="overflow-y: scroll;">
            @include('layouts.partials._errors')
            @yield('content')
            <footer class="d-print-none">
                <div class="text-center">
                    <small class="">{{ env('APP_NAME') }} | Ver. {{ config('app.version') }}</small>
                </div>
            </footer>
        </div>
    </main>
    <!-- Floating Chat Button -->
    <button id="mcp-open-chat"
        class="btn btn-primary shadow-lg rounded-circle position-fixed d-flex align-items-center justify-content-center"
        style="bottom:30px;right:30px;width:56px;height:56px;z-index:9999;">
        <span style="font-size: 1.7rem;">💬</span>
    </button>

    <!-- Chat Modal -->
    <div id="mcp-chat-modal" class="card shadow-lg position-fixed"
        style="display:none;bottom:90px;right:30px;z-index:10000;width:370px;max-width:95vw;height:530px;border-radius:18px;overflow:hidden;">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center">
                <span class="me-2"><img src="https://ui-avatars.com/api/?name=AI&background=007bff&color=fff"
                        width="32" height="32" class="rounded-circle"></span>
                <span class="fw-bold">Assistant</span>
            </div>
            <button type="button" class="btn-close btn-close-white btn-sm" aria-label="Close"
                onclick="$('#mcp-chat-modal').hide();"></button>
        </div>
        <!-- Body -->
        <div id="mcp-chat-body" class="card-body px-2 py-3" style="height:350px;overflow-y:auto;background:#f6f8fa;">
        </div>
        <!-- Typing Indicator -->
        <div id="mcp-chat-typing" class="px-3 pb-2" style="display:none;">
            <span class="spinner-border spinner-border-sm text-primary me-2"></span>
            <span class="fst-italic text-secondary">Assistant is typing...</span>
        </div>
        <!-- Suggestions -->
        <div id="mcp-chat-suggestions" class="px-3 pb-2"></div>
        <!-- Form -->
        <form id="mcp-chat-form" autocomplete="off" class="d-flex border-top bg-white px-2 py-2">
            <input type="text" id="mcp-chat-input" class="form-control rounded-start-pill"
                placeholder="Type your message...">
            <button type="submit" class="btn btn-primary rounded-end-pill ms-2 shadow-sm px-4">Send</button>
        </form>
    </div>

    <div id="notifications-toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!--JavaScript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="/asset/js/ProspectFlow.js"></script>
    <script src="/asset/js/Notification.js"></script>
    @auth
        <script>
            let inactivityInterval;
            let lockTimeMin = 15;
            const inactivityTime = lockTimeMin * 60 * 1000; // 15 minutes

            $(document).on('mousemove keydown', function() {
                clearTimeout(inactivityInterval);
                inactivityInterval = setTimeout(function() {
                    // Show lock screen on inactivity
                    window.location.href = '/lock';
                }, inactivityTime);
            });
        </script>
        <script>
            $('#mcp-open-chat').on('click', function() {
                $('#mcp-chat-modal').fadeToggle(150);
                if ($('#mcp-chat-modal').is(':visible')) {
                    let path = window.location.pathname;
                    if (path.includes('user')) {
                        window.currentPage = 'user';
                    } else if (path.includes('lead')) {
                        window.currentPage = 'lead';
                    } else {
                        window.currentPage = 'unknown';
                    }
                    loadSuggestions();
                }
            });


            function appendMessage(role, content, avatar, time) {
                let bubble = '';
                let align = (role === "Assistant") ? "start" : "end";
                let bg = (role === "Assistant") ? "bg-white text-dark border" : "bg-primary text-white";
                let userAvatar = avatar || (role === "Assistant" ?
                    "https://ui-avatars.com/api/?name=AI&background=007bff&color=fff" :
                    "https://ui-avatars.com/api/?name=U&background=6c757d&color=fff");
                let timeHtml = time ? `<span class="ms-2 text-muted small">${time}</span>` : '';
                bubble = `
    <div class="d-flex flex-row justify-content-${align} mb-2">
        ${(role === "Assistant") ? `<img src="${userAvatar}" class="rounded-circle me-2" width="32" height="32">` : ""}
        <div class="p-2 ${bg} rounded-4 shadow-sm" style="max-width:75%;">
            ${content}
        </div>
        ${(role !== "Assistant") ? `<img src="${userAvatar}" class="rounded-circle ms-2" width="32" height="32">` : ""}
        ${timeHtml}
    </div>`;
                $('#mcp-chat-body').append(bubble);
                $('#mcp-chat-body').scrollTop($('#mcp-chat-body')[0].scrollHeight);
            }

            function showTyping(show = true) {
                if (show) {
                    $('#mcp-chat-typing').show();
                    $('#mcp-chat-body').scrollTop($('#mcp-chat-body')[0].scrollHeight);
                } else {
                    $('#mcp-chat-typing').hide();
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#mcp-chat-form').on('submit', function(e) {
                e.preventDefault();
                let msg = $('#mcp-chat-input').val();
                if (!msg) return;
                let now = new Date();
                let time = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                appendMessage('You', $('<div/>').text(msg).html(), '', time);
                $('#mcp-chat-input').val('');
                showTyping(true);

                $.post('/api/mcp/chat', {
                    message: msg,
                    page: window.currentPage
                }, function(res) {
                    showTyping(false);
                    // res.reply is expected to be safe HTML
                    appendMessage('Assistant', res.reply, '', timeNow());
                }).fail(function() {
                    showTyping(false);
                    appendMessage('Assistant', '<span class="text-danger">Failed to get response.</span>', '',
                        timeNow());
                });
            });

            function loadSuggestions() {
                let path = window.location.pathname;
                if (path.includes('user')) {
                    window.currentPage = 'user';
                } else if (path.includes('lead')) {
                    window.currentPage = 'lead';
                } else {
                    window.currentPage = 'unknown';
                }
                $.get('/api/mcp/suggestions', {
                    page: window.currentPage
                }, function(res) {
                    $('#mcp-chat-suggestions').empty();
                    res.suggestions.forEach(function(s) {
                        $('#mcp-chat-suggestions').append(
                            `<button class="btn btn-outline-primary btn-sm rounded-pill mcp-suggest-btn me-2 mb-2">${s}</button>`
                        );
                    });
                    $('.mcp-suggest-btn').on('click', function() {
                        let msg = $(this).text();
                        appendMessage('You', $('<div/>').text(msg).html(), '', timeNow());
                        showTyping(true);
                        $.post('/api/mcp/chat', {
                            message: msg,
                            page: window.currentPage
                        }, function(res) {
                            showTyping(false);
                            appendMessage('Assistant', res.reply, '', timeNow());
                        }).fail(function() {
                            showTyping(false);
                            appendMessage('Assistant',
                                '<span class="text-danger">Failed to get response.</span>', '',
                                timeNow());
                        });
                    });
                });
            }



            // Helper to get current time in HH:mm
            function timeNow() {
                let now = new Date();
                return now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Optional: Enter key focus
            $('#mcp-chat-input').on('focus', function() {
                $('#mcp-chat-form').addClass('border-primary');
            }).on('blur', function() {
                $('#mcp-chat-form').removeClass('border-primary');
            });
        </script>
    @endauth
    @stack('scripts')
</body>
</html>
