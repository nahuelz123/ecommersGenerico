@extends('layouts.app')

@section('title', 'Sobre Nosotros')

@section('content')
<!-- Hero Section con imagen de fondo -->
<section class="relative bg-cover bg-center h-screen" style="background-image: url('/images/empresa.jpg');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="flex items-center justify-center h-full">
        <div class="text-center text-white px-6 md:px-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">¿Quiénes somos?</h1>
            <p class="text-lg md:text-2xl">Brindamos los mejores productos para acompañarte en tu día a día. Calidad, innovación y confianza.</p>
        </div>
    </div>
</section>

<!-- Sección de Productos en Oferta -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-center mb-12" data-aos="fade-up">Productos en Oferta</h2>

        <!-- Carrusel -->
        <div class="relative overflow-hidden">
            <div id="carousel" class="flex transition-transform duration-700 ease-in-out">
                @foreach ($products as $product)
                    <div class="min-w-[300px] bg-white rounded-lg shadow-lg p-6 mx-4 flex-shrink-0" data-aos="zoom-in">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-40 w-full object-cover rounded-md mb-4">
                        <h3 class="text-xl font-semibold mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-700 mb-2">${{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Ver Producto
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botones de navegación -->
        <div class="flex justify-center mt-6">
            <button onclick="prevSlide()" class="mx-2 bg-blue-500 text-white p-3 rounded-full hover:bg-blue-600">&#8592;</button>
            <button onclick="nextSlide()" class="mx-2 bg-blue-500 text-white p-3 rounded-full hover:bg-blue-600">&#8594;</button>
        </div>
    </div>
</section>

<!-- AOS (Animate On Scroll) -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script>
    AOS.init();

    let carousel = document.getElementById('carousel');
    let offset = 0;
    const slideWidth = 320; // 300px + margen
    const totalSlides = carousel.children.length;
    let interval;

    function nextSlide() {
        if (Math.abs(offset) >= slideWidth * (totalSlides - 1)) {
            offset = 0; // Reiniciar al principio
        } else {
            offset -= slideWidth;
        }
        updateCarousel();
    }

    function prevSlide() {
        if (offset >= 0) {
            offset = -(slideWidth * (totalSlides - 1));
        } else {
            offset += slideWidth;
        }
        updateCarousel();
    }

    function updateCarousel() {
        carousel.style.transform = `translateX(${offset}px)`;
    }

    // Mover automáticamente cada 3 segundos
    function startAutoSlide() {
        interval = setInterval(nextSlide, 3000);
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }

    // Iniciar el slide automático
    startAutoSlide();

    // Si el usuario pasa el mouse encima, se pausa
    carousel.addEventListener('mouseenter', stopAutoSlide);
    carousel.addEventListener('mouseleave', startAutoSlide);
</script>
@endsection