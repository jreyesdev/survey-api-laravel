# API RESTfull de Encuestas

API RESTfull simple de encuestas, donde podras crear, editar y publicar encuestas con opcion de tiempo de expiracion, ademas asignar el tipo de respuesta que puede ser de texto, parrafo o de seleccion simple o multiple. Incluye seccion de administracion de las encuestas incluyendo los indicadores de las mismas.

## Tecnologias

-   [Laravel](https://laravel.com).

## Instalacion

1 - Clona este repositorio

```bash
git clone https://github.com/jreyesdev/survey-api-laravel.git
```

2 - Ve al directorio del repositorio clonado e ingresa los siguientes comandos

```bash
# Copia del archivo .env.example y agrega los valores correspondientes
# para la base de datos en el archivo .env
cp .env.example .env

# Instala dependencias de Laravel
composer install

# Genera la key
php artisan key:generate

# Ejecuta las migraciones
php artisan migrate

# OPCIONAL para un mejor performance
php artisan config:cache
php artisan route:cache

# Inicia el servidor en http://localhost:8000
php artisan serve
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
