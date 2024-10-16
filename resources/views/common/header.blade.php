<header>
    <div class="inner p-5 text-center border-b-2 flex justify-between items-center gap-2">
        <a href="" class="max-w-[200px] flex justify-center items-center">Logo</a>
        @auth()
            <nav class="flex flex-1 justify-center">
                <ul class="menu flex justify-between items-center gap-4">
                    <li>
                        <a class="hover:opacity-50 transition-all" href="{{ route('main') }}">Home</a>
                    </li>
                    <li>
                        <a class="hover:opacity-50 transition-all" href="{{ route('chatIndex') }}">Chats</a>
                    </li>
                    <li>
                        <a class="hover:opacity-50 transition-all" href="{{ route('webhookData') }}">WebhookData</a>
                    </li>
                </ul>
            </nav>
            <div class="mr-1.5 border-2 rounded p-3">Hello, {{  auth()->user()->name }}</div>
            <a class="hover:opacity-50 transition-all" href="{{ route('logout') }}">Выйти</a>
        @endauth

        @guest()
            <a class="hover:opacity-50 transition-all" href="{{ route('login') }}">Войти</a>
        @endguest
    </div>
</header>
