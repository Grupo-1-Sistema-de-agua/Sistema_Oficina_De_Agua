# Sistema de Gestion — Oficina del Agua

Proyecto grupal del curso de Desarrollo Web. Construido con CodeIgniter 4,
MariaDB y Material Design for Bootstrap (MDB), corriendo todo en Docker.

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y corriendo
- Git

## Inicio Rapido

```bash
# Clonar el repositorio
git clone <url-del-repo>
cd Sistema_de_Agua

# Cambiarse a la rama developer (ver seccion "Flujo de trabajo con Git")
git checkout developer

# Copiar el archivo de variables de entorno
cp .env.example .env

# Levantar los contenedores (la primera vez tarda unos minutos:
# construye la imagen de PHP y corre `composer install` dentro)
docker-compose up -d --build
```

Esto levanta:

| Servicio    | Puerto | URL                    |
|-------------|--------|------------------------|
| App (CI4)   | 8000   | http://localhost:8000  |
| MariaDB     | 3306   | localhost:3306         |
| phpMyAdmin  | 8080   | http://localhost:8080  |

Con los contenedores arriba, hay que crear las tablas y los datos base
(roles, tipos de servicio, metodos de pago, usuario administrador):

```bash
docker-compose exec app php spark migrate
docker-compose exec app php spark db:seed DatabaseSeeder
```

Con eso ya pueden entrar a http://localhost:8000/login con:

- Correo: `admin@oficinadelagua.local`
- Contrasena: `admin123`

**Cambien esa contrasena en cuanto tengan el modulo de usuarios listo.**

## Credenciales de la Base de Datos

| Campo            | Valor       |
|------------------|-------------|
| Host (desde host)| localhost   |
| Host (desde app) | db          |
| Puerto           | 3306        |
| Usuario          | agua_user   |
| Contrasena       | agua2026    |
| Base de datos    | agua_db     |
| Usuario root     | root        |
| Contrasena root  | agua2026    |

## Estructura del proyecto (lo que ya esta listo)

```
app/
  Config/Routes.php        -> rutas agrupadas por modulo
  Config/Filters.php       -> filtros 'auth' y 'role' ya registrados
  Constants/Roles.php      -> nombres de rol (administrador, secretaria, lector)
  Filters/                 -> AuthFilter (exige sesion), RoleFilter (exige rol)
  Controllers/Auth/        -> login / logout, ya funcional
  Controllers/<Modulo>/    -> un controlador placeholder por modulo
  Models/                  -> un Model por tabla, ya conectado al esquema
  Database/Migrations/     -> el esquema completo (10 tablas), versionado
  Database/Seeds/          -> catalogos base + usuario administrador
  Views/layouts/main.php   -> layout compartido (navbar + sidebar), con
                               paleta de color propia y boton de tema
                               claro/oscuro (data-mdb-theme)
  Views/<Modulo>/index.php -> vista placeholder por modulo
public/assets/             -> css/js de MDB + custom.css con la paleta del equipo
docker/app/                -> Dockerfile + config de Apache del contenedor app
sql/schema.sql             -> referencia del esquema (ya NO es la fuente de verdad)
```

### Que deben hacer en su modulo

Cada modulo (Clientes, Contadores, Tarifas, Lecturas, Pagos) ya tiene:

1. Un controlador en `app/Controllers/<Modulo>/<Modulo>Controller.php` con un
   `index()` de ejemplo.
2. Una ruta protegida en `app/Config/Routes.php` (dentro del grupo `auth`).
3. Un Model en `app/Models/` con los campos y validaciones ya definidas.
4. Una vista placeholder en `app/Views/<Modulo>/index.php`.

Lo que agregan ustedes: los metodos `create`, `store`, `edit`, `update`,
`delete` en su controlador, sus vistas, y las rutas correspondientes
**dentro del bloque de su modulo** en `Routes.php` (asi evitamos que dos
personas editen la misma linea y se generen conflictos de merge).

Si necesitan restringir una ruta a un rol especifico, usen el filtro `role`
despues de `auth`, por ejemplo:

```php
$routes->get('tarifas/nueva', 'Tarifas\TarifasController::create', [
    'filter' => 'auth,role:' . \App\Constants\Roles::ADMINISTRADOR,
]);
```

## Comandos Utiles

```bash
# Levantar los contenedores
docker-compose up -d

# Reconstruir la imagen de la app (si cambian el Dockerfile)
docker-compose up -d --build

# Detener los contenedores
docker-compose down

# Detener y borrar todos los datos (reiniciar la BD desde cero)
docker-compose down -v

# Ver logs de la app o de MariaDB
docker-compose logs -f app
docker-compose logs -f db

# Entrar a la consola de MariaDB desde terminal
docker-compose exec db mysql -u agua_user -p agua_db

# Correr un comando de spark (CLI de CodeIgniter) dentro del contenedor
docker-compose exec app php spark <comando>.

# Crear una migracion nueva
docker-compose exec app php spark make:migration NombreDeLaMigracion

# Aplicar migraciones pendientes / deshacer la ultima
docker-compose exec app php spark migrate
docker-compose exec app php spark migrate:rollback

# Reiniciar todo desde cero
docker-compose down -v && docker-compose up -d --build \
  && docker-compose exec app php spark migrate \
  && docker-compose exec app php spark db:seed DatabaseSeeder
```

## Flujo de trabajo con Git

Usamos dos ramas permanentes:

- **`main`** — solo codigo integrado y funcionando. Nadie hace push directo aqui.
- **`developer`** — donde se integra el trabajo de todos antes de pasar a `main`.

Y ramas temporales por persona/tarea, siempre creadas a partir de `developer`:

```bash
git checkout developer
git pull
git checkout -b feature/nombre-del-modulo
```

Al terminar su parte:

```bash
git add .
git commit -m "Describe que hiciste"
git push origin feature/nombre-del-modulo
```

Y abren un Pull Request de `feature/nombre-del-modulo` hacia `developer`
(nunca directo a `main`). Cuando `developer` este estable (antes de un
checkpoint o del Demo Day), se hace merge de `developer` a `main`.

**Nunca** subir el archivo `.env` a git (ya esta en `.gitignore`). Si
alguien agrega una migracion nueva, los demas corren:

```bash
git pull
docker-compose exec app php spark migrate
```

## Notas

- La BD se persiste en un volumen de Docker. Solo se destruye con `docker-compose down -v`.
- Si `writable/` da errores de permisos en Linux, corran `chmod -R 777 writable`.
- `vendor/` vive en un volumen aparte (no se sube a git); si Composer se
  queda "atascado" por algun cambio raro, `docker-compose down -v` lo reinstala limpio.