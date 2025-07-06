@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1 class="text-center mb-4">Dashboard - Aguilas del Saber</h1>
    <div class="container">
        <div class="row g-4">
            <!-- Tarjeta 1: Gestión de Productos -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        Gestión de Productos
                    </div>
                    <div class="card-body">
                        <canvas id="gestionProductosChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 2: Productos por Mes -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        Productos por Mes
                    </div>
                    <div class="card-body">
                        <canvas id="productosPorMesChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 3: Estado de Recursos -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white text-center">
                        Estado de Recursos
                    </div>
                    <div class="card-body">
                        <canvas id="estadoRecursosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico 1: Gestión de productos, devoluciones e ingresos
        const gestionProductosCtx = document.getElementById('gestionProductosChart').getContext('2d');
        new Chart(gestionProductosCtx, {
            type: 'bar',
            data: {
                labels: ['Productos', 'Devoluciones', 'Ingresos'],
                datasets: [{
                    label: 'Cantidad',
                    data: [50, 20, 70],
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                },
            }
        });

        // Gráfico 2: Productos por mes
        const productosPorMesCtx = document.getElementById('productosPorMesChart').getContext('2d');
        new Chart(productosPorMesCtx, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
                datasets: [{
                    label: 'Cantidad de Productos',
                    data: [30, 40, 35, 50, 60],
                    backgroundColor: '#4bc0c0',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                },
            }
        });

        // Gráfico 3: Estado de recursos
        const estadoRecursosCtx = document.getElementById('estadoRecursosChart').getContext('2d');
        new Chart(estadoRecursosCtx, {
            type: 'pie',
            data: {
                labels: ['Bueno', 'Deteriorado', 'Malo'],
                datasets: [{
                    label: 'Estado de Recursos',
                    data: [70, 20, 10],
                    backgroundColor: ['#2ecc71', '#f1c40f', '#e74c3c'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                },
            }
        });
    </script>
@endsection
