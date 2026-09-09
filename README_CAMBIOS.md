# Sistema Oficina de Agua - Modulo de Lecturas

Documento de los cambios realizados al modulo de **Lecturas** (y archivos
relacionados de Contadores y Tarifas) para la revisión y mantenimiento futuro.

---

## Resumen de los cambios

Se completó el módulo de **Contadores** (100%) y se trabajó el módulo de
**Lecturas** siguiendo el diagrama de casos de uso, en fases con confirmación
del usuario. Además se colgaron **tarifas de prueba** y **datos de demostración**
para poder revisar la funcionalidad en la app.

### Decisiones de negocio implementadas
- **Una lectura por contador por mes de calendario**: al guardar, si ya existe
  una lectura de ese contador en el mes, se rechaza; recién el próximo mes se
  puede registrar otra.
- **Fecha automática**: al registrar una lectura se usa la fecha/hora actual del
  sistema (el usuario ya no la digita).
- **Edición de la lectura del mes**: se permite corregir la lectura vigente
  (la más reciente) y se recalcula consumo y monto. Se bloquea si la lectura es
  de un mes anterior o si ya fue pagada.
- **Buscar por número de contador** en el listado de lecturas pendientes.
- **Mostrar información del usuario** (cliente) y tarifa vigente en el
  formulario de lectura.
- **Barrio = Sector** (el filtro por zona/sector existente cubre "barrio").

---

## Archivos modificados / agregados

| Archivo | Cambio | ¿Por qué? / ¿Qué hace? |
| --- | --- | --- |
| `app/Models/LecturaModel.php` | Se agregó `monto_base` y `monto_exceso` a `$allowedFields`. | Bug: CodeIgniter descartaba esos campos al guardar, por lo que el monto nunca se almacenaba (quedaba en 0.00). Con esto se guardan los montos calculados. |
| `app/Models/LecturaModel.php` | Nuevos métodos: `existeEnMes()`, `lecturaDelMesActual()`, `esUltimaDeContador()`, `tienePago()`. | Soportan la regla de una lectura por mes y la edición de la lectura vigente (con bloqueo si es de mes anterior o si ya tiene pago). |
| `app/Models/ContadorModel.php` | `pendientesLectura()` ahora acepta un parámetro `$q` (búsqueda por número de contador o nombre de cliente). | Da soporte al caso de uso "Buscar por número de contador" del listado de lecturas. |
| `app/Controllers/Lecturas/LecturasController.php` | `index()`: se añadió búsqueda `q` y se envía `lecturasMes` a la vista. | Muestra el estado de la lectura del mes de cada contador (pendiente o registrada). |
| `app/Controllers/Lecturas/LecturasController.php` | `nueva()`: la fecha ahora es automática (`date('Y-m-d H:i:s')`). | Se quitó la captura manual de la fecha; muestra la tarifa vigente y la información del usuario (ya se hacía) y la fecha automática. |
| `app/Controllers/Lecturas/LecturasController.php` | `guardar()`: regla de una lectura por mes + fecha automática. | Rechaza la segunda lectura del mismo contador en el mismo mes con mensaje claro. |
| `app/Controllers/Lecturas/LecturasController.php` | Nuevos métodos `editar()` y `actualizar()`. | Permiten corregir la lectura vigente del mes y recalculan consumo y montos; bloquean si es de mes anterior o si ya fue pagada. |
| `app/Views/Lecturas/index.php` | Se agregó la caja de búsqueda (`q`), la columna "Estado mes actual" y botón contextual (Registrar / Editar). | El listado ahora indica si cada contador ya tiene lectura este mes. |
| `app/Views/Lecturas/nueva.php` | Se quitó el campo FECHA y se muestra la fecha automática como texto. | La fecha se asigna sola; el usuario solo ingresa la lectura actual. |
| `app/Views/Lecturas/editar.php` | **Archivo nuevo**: formulario de edición de la lectura vigente. | Muestra datos originales (fecha, lectura anterior, recibo) y permite corregir la lectura actual. |
| `app/Config/Routes.php` | Se agregaron las rutas `lecturas/editar/(:num)` y `lecturas/actualizar`. | Conexión de las URLs del nuevo flujo de edición. |

---

## Archivos relacionados del trabajo previo (Contadores)

| Archivo | Cambio |
| --- | --- |
| `app/Database/Migrations/2026-09-03-000012_AddDpiAClientes.php` | Columna `dpi` (varchar 13, única) en `Tb_Clientes` para diferenciar homónimos. |
| `app/Database/Migrations/2026-09-03-000013_AddFechasAContadores.php` | Columnas `fecha_asignacion` y `fecha_desactivacion` en `Tb_Contadores`. |
| `app/Models/ClienteModel.php` | Se agregó `dpi` a `allowedFields` y su validación. |
| `app/Controllers/Contadores/ContadoresController.php` | CRUD completo con regla de duplicado y toggle activo/inactivo. |
| `app/Views/Contadores/index.php`, `form.php`, `ver.php` | Listado con filtros y DPI; formulario; detalle con historial y pagos. |

---

## Datos de prueba cargados

Para revisar cómo funciona la app se cargaron en `agua_db`:

- **Tarifas** (precios por litro): CUARTO_PAJA Q0.05, MEDIA_PAJA Q0.04, EXCESO Q0.08 (vigentes desde 2026-01-01).
- **Contadores**: 5 activos repartidos en los 4 sectores. El cliente 1 tiene 2 contadores en direcciones distintas (prueba de la regla multi-contador).
- **Lecturas**: 26 históricas (ene–ago 2026) con consumo y montos ya calculados.
- **Pagos**: 8 repartidos entre contadores; algunos al día y otros pendientes para ver los estados "Al día" / "Pendiente".

> Nota: con la regla de "una lectura por mes", las lecturas de **septiembre**
> aún no existen, por lo que el listado de lectura muestra todos en estado
> "Pendiente de lectura" y permite registrarlos.

---

## Para revisar en la app
1. Login: `admin@oficinadelagua.local` / `admin123`.
2. `/contadores` — listado con badges y filtros; `/contadores/ver/{id}` — detalle.
3. `/lecturas` — contadores pendientes, búsqueda por número, estado del mes.
4. `/lecturas/nueva/{id}` — registrar lectura (fecha automática, tarifa, info del usuario).
5. `/lecturas/editar/{id}` — corregir la lectura del mes (si aún no tiene pago).
