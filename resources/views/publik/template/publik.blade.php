@include('publik.template.header')

<body>
    @yield('content')
    @include('publik.template.footer')
    @stack('js')
</body>

</html>