# Sistema de Gestión — Oficina del Agua

Grupo No. 1

## Integrantes del equipo

| Nombre completo | Carnet | Correo electrónico | Rol |
|---|---|---|---|
| **Mario Fernando Cerna Nájera** (Líder del equipo) | 0905-23-5025 | mcernan@miumg.edu.gt | Contadores + Lecturas |
| Mijeli Azucena Lucero Burgos | 0905-23-5501 | mlucerob@miumg.edu.gt | Tarifas + Dashboard + Seguridad |
| Imanol José Miguel Gutierrez Cardona | 0905-19-4862 | igutierrezc2@miumg.edu.gt | Clientes + Recibo |
| Alan Steven Marroquín Villaseñor | 0905-23-15264 | amarroquinv7@miumg.edu.gt | Auth + Roles + Pagos |

### Detalle de responsabilidades por módulo

- **Mario Fernando Cerna Nájera (Líder)** — Gestión de contadores. En
  Lecturas, consulta del listado de contadores pendientes y de la
  última lectura registrada.
- **Mijeli Azucena Lucero Burgos** — Tarifas con vigencia, indicadores
  generales del sistema (Dashboard), y revisión de seguridad e
  integración del sistema completo.
- **Imanol José Miguel Gutierrez Cardona** — Gestión de clientes y
  recibo imprimible de lectura.
- **Alan Steven Marroquín Villaseñor** — Usuarios/roles del sistema,
  registro y control de pagos. 

## Enlaces del proyecto

- **Tablero Jira:**
  https://desarrollogrupo1.atlassian.net/jira/software/projects/SDGA/boards/34/backlog?atlOrigin=eyJpIjoiYzM0MTlkYWY2NzNjNGY1Y2ExMzMzMjQ3NGFmYWI1MWEiLCJwIjoiaiJ9

## Stack tecnológico

- **Backend:** PHP 8.2 + CodeIgniter 4
- **Base de datos:** MariaDB 10.11
- **Frontend:** Bootstrap 5 / MDB, Chart.js
- **Infraestructura:** Docker / Docker Compose

## Cómo levantar el proyecto localmente

```bash
git clone https://github.com/Grupo-1-Sistema-de-agua/Sistema_Oficina_De_Agua.git
cd Sistema_Oficina_De_Agua
cp .env.example .env
docker-compose up -d
docker-compose exec app php spark migrate
docker-compose exec app php spark db:seed DatabaseSeeder
```

El sistema queda disponible en `http://localhost:8000`.

## Credenciales de prueba

Generadas automáticamente al correr el seeder (`DatabaseSeeder`):

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@oficinadelagua.local | admin123. |
| Secretaria | secretaria@oficinadelagua.local | secretaria123. |
| Lector | lector@oficinadelagua.local | lector123. |