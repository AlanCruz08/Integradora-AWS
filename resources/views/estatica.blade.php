<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Explicación de Nuestro Dispositivo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1>Nuestro Dispositivo</h1>
            <p>Una solución innovadora para...</p>
        </div>
    </header>

    <section class="main-content py-5">
        <div class="container">
            <h2>Funciones Clave</h2>
            <ul>
                <li>Función 1: Permitirá mostrar la distancia que se encuentra un usuario hacia el Dispositivo.</li>
                <li>Función 2: Permitirá detectar el movimiento que se encuentre dentro de la sala.</li>
                <li>Función 3: Medirá la temperatura dentro de la habitación.</li>
                <li>Función 4: Permitirá mostrar la humedad que se encuentra dentro de la habitación.</li>
                <li>Función 5: Mostrará cualquier alteración en caso de terremoto.</li>
                <li>Función 6: Mostrará cualquier alteración en caso de incendio.</li>
                <!-- Agrega más funciones según sea necesario -->
            </ul>
        </div>
    </section>

    <section class="advantages py-5 bg-light">
        <div class="container">
            <h2>Ventajas</h2>
            <p>Esta tecnología ofrece ventajas como...</p>
            <ul>
                <li>Ventaja 1: Monitoreo preciso y en tiempo real, permitiendo a los usuarios obtener información actualizada y tomar decisiones rápidas.</li>
                <li>Ventaja 2: Seguridad mejorada al detectar movimientos o cambios inusuales en el entorno, alertando sobre posibles riesgos.</li>
                <li>Ventaja 3: Ambiente controlado al permitir el control de temperatura y humedad para mantener un entorno óptimo.</li>
                <li>Ventaja 4: Alertas tempranas ante eventos como terremotos o incendios, permitiendo tomar medidas preventivas.</li>
                <li>Ventaja 5: Automatización inteligente que optimiza sistemas y procesos en base a la información recopilada.</li>
                <li>Ventaja 6: Facilidad de uso y accesibilidad, proporcionando una interfaz amigable y acceso remoto.</li>
                <!-- Agrega más ventajas según sea necesario -->
            </ul>
        </div>
    </section>

    <section class="advantages py-5">
        <h2 class="text-center">Frameworks</h2>
        <div class="container" style="display:flex; flex-direction: row; justify-content:space-around;" >
            
            <img src="{{ asset('img/aws.png') }}" alt="Laravel" width="200" height="200">
            <img src="{{ asset('img/github.jpg') }}" alt="Laravel" width="200" height="200">
            <img src="{{ asset('img/python.png') }}" alt="Laravel" width="200" height="200">
            <img src="{{ asset('img/rastberry.png') }}" alt="Laravel" width="200" height="200">
            <img src="{{ asset('img/arduino.png') }}" alt="Laravel" width="200" height="200">
        </div>

    </section>

    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p>&copy; 2023 Nuestro Dispositivo. Todos los derechos reservados.</p>
        </div>
    </footer>

    <a href="#" class="btn btn-primary position-fixed" style="top: 20px; left: 20px;">
        <img src="{{ asset('img/flecha-hacia-atras.png') }}" alt="Flecha hacia atrás" width="25" height="25">
    </a>


    <!-- llamar la img de flecha-hacia-atras como boton de regreso-->


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>