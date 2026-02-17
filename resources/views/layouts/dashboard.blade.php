<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Panel</title>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    @include('layouts.navigation')

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:block overflow-y-auto">
             @include('layouts.sidebar') 
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            @yield('content')
        </main>
        
    </div>

    </body>
</html>
