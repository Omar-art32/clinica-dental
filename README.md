# Clínica Dental

Sistema web para la gestión de una clínica dental, desarrollado con Laravel y Docker.

## Requisitos

Antes de comenzar, es necesario tener instalado:

* Docker
* Docker Compose
* Git

Para comprobar que están instalados:

```bash
docker --version
docker compose version
git --version
```

## Instalación

### 1. Clonar el proyecto

Clonar el repositorio y entrar en la carpeta:

```bash
git clone https://github.com/Omar-art32/clinica-dental.git
cd clinica-dental
```

### 2. Configurar Laravel

Crear el archivo `.env` a partir del archivo de ejemplo:

```bash
cp src/.env.example src/.env
```

La configuración de la base de datos debe utilizar el servicio `db` de Docker:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=clinica_dental_2026
DB_USERNAME=clinica
DB_PASSWORD=clinica
```

No es necesario modificar `DB_HOST` a `localhost`, ya que Laravel se conecta a MariaDB desde el contenedor.

### 3. Levantar los contenedores

Construir y levantar los servicios:

```bash
docker compose up -d --build
```

Comprobar que los contenedores estén funcionando:

```bash
docker compose ps
```

El proyecto utiliza los siguientes servicios:

* Laravel / PHP
* MariaDB
* Nginx
* Node.js
* phpMyAdmin

### 4. Instalar las dependencias de PHP

Ejecutar:

```bash
docker compose exec app composer install
```

Generar la clave de Laravel:

```bash
docker compose exec app php artisan key:generate
```

### 5. Configurar la base de datos

Ejecutar las migraciones:

```bash
docker compose exec app php artisan migrate
```

Si el proyecto cuenta con seeders para generar datos iniciales, se pueden ejecutar con:

```bash
docker compose exec app php artisan db:seed
```

### 6. Instalar las dependencias de Node.js

```bash
docker compose run --rm node npm install
```

### 7. Compilar los archivos frontend

```bash
docker compose run --rm node npm run build
```

Después de completar estos pasos, el sistema estará listo para utilizarse.

## Acceso al sistema

Aplicación:

```text
http://localhost:8080
```

Inicio de sesión:

```text
http://localhost:8080/login
```

phpMyAdmin:

```text
http://localhost:8082
```

### Datos de conexión a la base de datos

Desde phpMyAdmin:

```text
Servidor: db
Usuario: clinica
Contraseña: clinica
Base de datos: clinica_dental_2026
```

## Desarrollo frontend

Durante el desarrollo se puede ejecutar Vite en modo desarrollo:

```bash
docker compose run --rm --service-ports node npm run dev -- --host 0.0.0.0
```

Vite estará disponible en:

```text
http://localhost:5173
```

Este proceso debe permanecer ejecutándose mientras se realizan cambios en CSS o JavaScript.

Si solamente se quiere ejecutar el sistema sin modificar el frontend, no es necesario mantener Vite ejecutándose.

## Detener el proyecto

Para detener los contenedores:

```bash
docker compose down
```

Para volver a iniciar el proyecto:

```bash
docker compose up -d
```

## Base de datos

La información de MariaDB se almacena en un volumen de Docker llamado:

```text
clinica_dental_db
```

Para detener los contenedores sin eliminar la información de la base de datos:

```bash
docker compose down
```

**No utilizar:**

```bash
docker compose down -v
```

a menos que se quiera eliminar también el volumen y todos los datos almacenados en la base de datos.

## Solución de problemas

Si algún servicio presenta problemas, se pueden consultar los contenedores:

```bash
docker compose ps
```

Ver los registros de Laravel/PHP:

```bash
docker compose logs app
```

Ver los registros de Nginx:

```bash
docker compose logs nginx
```

Ver los registros de MariaDB:

```bash
docker compose logs db
```

Limpiar las cachés de Laravel:

```bash
docker compose exec app php artisan optimize:clear
```

Volver a compilar los archivos frontend:

```bash
docker compose run --rm node npm run build
```

## Estructura del proyecto

```text
clinica-dental/
├── docker/
│   └── nginx/
├── src/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   ├── artisan
│   ├── composer.json
│   └── package.json
├── compose.yaml
├── Dockerfile
├── .gitignore
└── README.md
```

## Tecnologías

* Laravel
* PHP
* MariaDB
* Nginx
* Docker
* Docker Compose
* Node.js
* Vite
* Tabler
* Spatie Laravel Permission
* phpMyAdmin

