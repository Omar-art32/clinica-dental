# Clínica Dental

Sistema web desarrollado con Laravel y Docker.

## Requisitos

Antes de comenzar, es necesario tener instalado:

* Docker
* Docker Compose
* Git

En Windows y macOS se puede utilizar Docker Desktop.

En Linux se puede instalar Docker y Docker Compose desde los repositorios oficiales de la distribución.

Para comprobar la instalación:

```bash
docker --version
docker compose version
git --version
```

## Instalación

### 1. Clonar el proyecto

Clonar el repositorio y entrar a la carpeta:

```bash
git clone https://github.com/Omar-art32/clinica-dental.git
cd clinica-dental
```

### 2. Configurar Laravel

Crear el archivo `.env` a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

En Windows también se puede copiar `.env.example` manualmente y renombrarlo como `.env`.

La configuración de la base de datos debe utilizar el servicio `db` de Docker:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=clinica_dental
DB_USERNAME=clinica
DB_PASSWORD=clinica
```

### 3. Levantar los contenedores

Construir y levantar los servicios:

```bash
docker compose up -d --build
```

Comprobar que estén funcionando:

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

Si el proyecto incluye un respaldo de la base de datos con información inicial, importarlo en la base de datos antes de utilizar el sistema.

### 6. Instalar las dependencias frontend

```bash
docker compose run --rm node npm install
```

### 7. Compilar los archivos frontend

```bash
docker compose run --rm node npm run build
```

Después de completar estos pasos, el proyecto estará listo para utilizarse.

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

Para conectarse a MariaDB desde phpMyAdmin:

```text
Servidor: db
Usuario: clinica
Contraseña: clinica
```

## Desarrollo frontend

Cuando se estén realizando cambios en CSS o JavaScript, se puede ejecutar Vite en modo desarrollo:

```bash
docker compose run --rm --service-ports node npm run dev -- --host 0.0.0.0
```

Vite quedará disponible en:

```text
http://localhost:5173
```

Este proceso debe permanecer ejecutándose mientras se trabaja con Vite.

Si solamente se quiere ejecutar el sistema, no es necesario mantener Node ejecutándose.

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

La información de MariaDB se almacena en un volumen de Docker.

No utilizar:

```bash
docker compose down -v
```

a menos que se quiera eliminar también el volumen de la base de datos.

## Solución de problemas

Si el proyecto presenta errores después de realizar cambios, primero comprobar que los contenedores estén activos:

```bash
docker compose ps
```

También se pueden consultar los registros:

```bash
docker compose logs app
docker compose logs nginx
docker compose logs db
```

Si se realizaron cambios en la configuración de Laravel, se puede limpiar la caché:

```bash
docker compose exec app php artisan optimize:clear
```

Si los estilos o JavaScript no aparecen correctamente, volver a generar los archivos frontend:

```bash
docker compose run --rm node npm run build
```

## Estructura principal

```text
clinica-dental/
├── docker/
├── src/
├── compose.yaml
├── Dockerfile
└── README.md
```

El código de Laravel se encuentra dentro de `src`.

La configuración de los servicios de Docker se encuentra en `compose.yaml`.

La configuración de Nginx se encuentra dentro de `docker/`.

## Tecnologías

* Laravel
* PHP
* MariaDB
* Nginx
* Docker
* Docker Compose
* Node.js
* Vite
* Tablar
