<!doctype html>

<html lang="es">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Lero lero!</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>

.card{

    border-radius:18px;

    transition:.25s;

}

.card:hover{

    transform:translateY(-4px);

    box-shadow:0 18px 35px rgba(0,0,0,.08)!important;

}

.progress{

    background:#eef2f7;

}

</style>

</head>

<body style="background:#f5f7fb;">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">

<i class="bi bi-people-fill"></i>

Lero lero!

</a>

<button class="navbar-toggler"

data-bs-toggle="collapse"

data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">

<a class="nav-link"

href="{{ route('dashboard') }}">

<i class="bi bi-speedometer2"></i>

Dashboard

</a>

</li>

<li class="nav-item">

<a class="nav-link"

href="{{ route('importaciones.index') }}">

<i class="bi bi-upload"></i>

Importar

</a>

</li>

{{-- <li class="nav-item">

<a class="nav-link"

href="{{ route('fechas.index') }}">

<i class="bi bi-calendar-event"></i>

Fechas

</a>

</li> --}}

<li class="nav-item">

<a class="nav-link"

href="{{ route('asignaciones.index') }}">

<i class="bi bi-table"></i>

Asignaciones

</a>

</li>

</ul>

</div>

</div>

</nav>

<div class="container py-4">

@yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/asignaciones.js') }}"></script>

</body>

</html>