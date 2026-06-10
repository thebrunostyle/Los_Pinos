# Eventara — Instalación en XAMPP

## Estructura final en htdocs

```
C:\xampp\htdocs\eventara\
├── index.html
├── calendario.html
├── registro.html
├── assets/
│   └── js/
│       ├── api.js
│       └── registro.js
├── api/
│   ├── config.php        ← configuración + JWT + helpers
│   ├── auth.php          ← POST login
│   ├── eventos.php       ← GET eventos + calendario
│   ├── participantes.php ← POST registro / GET lista
│   ├── reservas.php      ← POST reserva / GET lista
│   └── servicios.php     ← GET servicios, cotizaciones, dashboard
└── sql/
    └── eventara_mysql.sql
```

---

## Paso 1 — Copiar archivos

Copia toda esta carpeta dentro de:
```
C:\xampp\htdocs\eventara\
```

---

## Paso 2 — Crear la base de datos

1. Abre XAMPP y arranca **Apache** y **MySQL**
2. Abre el navegador en: **http://localhost/phpmyadmin**
3. Haz clic en **"Nueva"** (panel izquierdo)
4. Escribe `eventara` como nombre → clic en **Crear**
5. Con la base `eventara` seleccionada → pestaña **SQL**
6. Pega el contenido de `sql/eventara_mysql.sql` → clic en **Continuar**

---

## Paso 3 — Configurar la conexión

Abre `api/config.php` y ajusta si es necesario:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // XAMPP no tiene password por defecto
define('DB_NAME', 'eventara');
```

Si tu XAMPP tiene password en MySQL, ponla en `DB_PASS`.

---

## Paso 4 — Abrir el sitio

Con Apache y MySQL corriendo, abre:

| Página            | URL                                         |
|-------------------|---------------------------------------------|
| Inicio            | http://localhost/eventara/                  |
| Calendario        | http://localhost/eventara/calendario.html   |
| Registro          | http://localhost/eventara/registro.html     |
| API eventos       | http://localhost/eventara/api/eventos.php   |
| API participantes | http://localhost/eventara/api/participantes.php |

---

## Credenciales de admin por defecto

| Campo    | Valor             |
|----------|-------------------|
| Correo   | admin@eventara.co |
| Password | Admin2026!        |

Para cambiar el password, genera un nuevo hash en PHP:
```php
echo password_hash('TuNuevoPassword', PASSWORD_DEFAULT);
```
Y actualiza el campo `password_hash` en la tabla `usuarios`.

---

## Probar la API desde el navegador

**Login:**
```
POST http://localhost/eventara/api/auth.php
Body: {"correo":"admin@eventara.co","password":"Admin2026!"}
```

**Eventos públicos:**
```
GET http://localhost/eventara/api/eventos.php
```

**Calendario de junio 2026:**
```
GET http://localhost/eventara/api/eventos.php?calendario=1&year=2026&month=6
```

---

## ¿Qué hace cada archivo PHP?

| Archivo             | Método | Función                              |
|---------------------|--------|--------------------------------------|
| auth.php            | POST   | Login → retorna token JWT            |
| eventos.php         | GET    | Lista eventos + calendario por mes   |
| eventos.php         | POST   | Crear evento (admin)                 |
| participantes.php   | POST   | Registro público de participante     |
| participantes.php   | GET    | Lista participantes (admin)          |
| reservas.php        | POST   | Solicitar reserva con anti-solapamiento |
| reservas.php        | GET    | Lista reservas (admin)               |
| servicios.php?endpoint=servicios    | GET  | Catálogo de servicios |
| servicios.php?endpoint=cotizaciones | POST | Crear cotización     |
| servicios.php?endpoint=dashboard    | GET  | Métricas del panel   |
