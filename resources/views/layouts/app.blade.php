<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi E-Commerce')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a los estilos de Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Enlace a los scripts de Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    {{-- Links comunes --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Productos</a>
                    </li>
                @auth
                @if(auth()->user()->role === 'admin' or auth()->user()->role === 'client')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('addresses.index') }}">Direcciones</a>
                    </li>

                        @if(auth()->user()->role === 'admin')
                            {{-- Links para Admin --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.orders.index') }}">Admin Órdenes</a>
                            </li>
                        @endif

                        {{-- Links para Cliente --}}
                        @if(auth()->user()->role === 'client')
                            <li class="nav-item">
                            @verbatim
                                <a class="nav-link" href="{{ route('orders.index') }}">Mis Órdenes</a>
                            @endverbatim
                            </li>
                        @endif
                    @endauth
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto me-5">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                  <li>
                             <a class="dropdown-item" href="{{ route('user.index')}}" id="userDropdown" role="button">Perfil </a>
                            </li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Contenido --}}
    <main class="container my-4">
        @yield('content')
    </main>

    {{-- Footer opcional --}}
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Mi E-Commerce. Todos los derechos reservados.</p>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
