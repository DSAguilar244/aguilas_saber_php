@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1 class="text-center mb-4">Dashboard - Águilas del Saber</h1>

    <div class="container">
        <div class="dashboard-row">
            <!-- Tarjeta 1 -->
            <div class="dashboard-card">
                <div class="card-header bg-primary text-white text-center">
                    Gestión de Productos
                </div>
                <div class="card-body">
                    <canvas id="gestionProductosChart"></canvas>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="dashboard-card">
                <div class="card-header bg-success text-white text-center">
                    Productos por Mes
                </div>
                <div class="card-body">
                    <canvas id="productosPorMesChart"></canvas>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="dashboard-card">
                <div class="card-header bg-info text-white text-center">
                    Estado de Recursos
                </div>
                <div class="card-body">
                    <canvas id="estadoRecursosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- ✅ Cargar la librería antes de usarla -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Datos reales enviados desde el controlador
        const productosData = {!! json_encode([$totalProductos, $totalDevoluciones, $totalIngresos]) !!};
        const productosMesLabels = {!! json_encode($mesLabels) !!};
        const productosMesData = {!! json_encode($mesData) !!};
        const recursoLabels = {!! json_encode(array_keys($estadoRecursos)) !!};
        const recursoData = {!! json_encode(array_values($estadoRecursos)) !!};

        // 📦 Gráfico de gestión de productos
        new Chart(document.getElementById('gestionProductosChart'), {
            type: 'bar',
            data: {
                labels: ['Productos', 'Devoluciones', 'Ingresos'],
                datasets: [{
                    label: 'Cantidad',
                    data: productosData,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });

        // 📅 Gráfico de productos por mes
        new Chart(document.getElementById('productosPorMesChart'), {
            type: 'bar',
            data: {
                labels: productosMesLabels,
                datasets: [{
                    label: 'Cantidad',
                    data: productosMesData,
                    backgroundColor: '#4bc0c0'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });

        // 🔍 Gráfico de estado de recursos
        new Chart(document.getElementById('estadoRecursosChart'), {
            type: 'pie',
            data: {
                labels: recursoLabels,
                datasets: [{
                    data: recursoData,
                    backgroundColor: ['#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });
    </script>
@endsection