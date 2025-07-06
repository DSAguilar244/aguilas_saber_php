@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1 class="main-header mb-3">Bienvenido a Aguilas del Saber</h1>
    <p class="text-secondary mb-4">Una plataforma integral para gestionar usuarios, préstamos, productos y más
        de forma eficiente.</p>
    <div class="row">
        @php
            $cards = [
                [
                    'icon' => 'fas fa-users',
                    'color' => 'primary',
                    'title' => 'Gestión de Usuarios',
                    'text' => 'Administra información de usuarios registrados en el sistema.',
                    'href' => route('usuarios.index'),
                    'btn' => 'primary',
                    'label' => 'Ir a Usuarios',
                ],
                [
                    'icon' => 'fas fa-archive',
                    'color' => 'success',
                    'title' => 'Gestión de Préstamos',
                    'text' => 'Supervisa y gestiona los préstamos de artículos o libros.',
                    'href' => route('prestamos.index'),
                    'btn' => 'success',
                    'label' => 'Ir a Préstamos',
                ],
                [
                    'icon' => 'fas fa-shopping-cart',
                    'color' => 'danger',
                    'title' => 'Gestión de Productos',
                    'text' => 'Monitorea y organiza el inventario de productos disponibles.',
                    'href' => route('productos.index'),
                    'btn' => 'danger',
                    'label' => 'Ir a Productos',
                ],
                [
                    'icon' => 'fas fa-database',
                    'color' => 'info',
                    'title' => 'Recursos',
                    'text' => 'Controla y organiza los recursos de manera eficiente.',
                    'href' => route('recursos.index'),
                    'btn' => 'info',
                    'label' => 'Ir a Recursos',
                ],
                [
                    'icon' => 'fas fa-user-shield',
                    'color' => 'dark',
                    'title' => 'Gestión de Roles',
                    'text' => 'Administra y asigna roles y permisos a los usuarios del sistema.',
                    'href' => route('roles.index'),
                    'btn' => 'dark',
                    'label' => 'Ir a Roles',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="{{ $card['icon'] }} fa-3x text-{{ $card['color'] }} mb-3"></i>
                        <h5 class="card-title">{{ $card['title'] }}</h5>
                        <p class="card-text">{{ $card['text'] }}</p>
                        <a href="{{ $card['href'] }}" class="btn btn-{{ $card['btn'] }}">{{ $card['label'] }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, 200 * (index + 1));
            });
        });
    </script>
@endsection
