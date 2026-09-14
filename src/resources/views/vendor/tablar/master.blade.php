<!doctype html>
<html lang="{{ Config::get('app.locale') }}" {!! config('tablar.layout') == 'rtl' ? 'dir="rtl"' : '' !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('tablar.title_prefix', ''))
        @yield('title', config('tablar.title', 'Tablar'))
        @yield('title_postfix', config('tablar.title_postfix', ''))
    </title>

    {{-- CSS/JS files --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @if (config('tablar.vite'))
        @vite('resources/js/app.js')
    @endif

    {{-- Livewire Styles --}}
    @if (config('tablar.livewire'))
        @livewireStyles
    @endif

    {{-- Custom Stylesheets (post Tablar) --}}
    @yield('tablar_css')

</head>

@yield('body')

@include('tablar::extra.modal')

{{-- Livewire Script --}}
@if (config('tablar.livewire'))
    @livewireScripts
@endif

@yield('tablar_js')

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('swal'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: @json(session('swal.icon')),
            title: @json(session('swal.title')),
            text: @json(session('swal.text')),
            timer: 4000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    </script>
@elseif (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonText: 'Aceptar',
            scrollbarPadding: false,
            heightAuto: false
        });
    </script>
@elseif ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: @json(implode('<br>', $errors->all())),
            confirmButtonText: 'Aceptar',
            scrollbarPadding: false,
            heightAuto: false
        });
    </script>
@endif

</html>