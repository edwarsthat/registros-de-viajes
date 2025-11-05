# 🚗 Sistema de Registro de Viajes

## 📋 Descripción del Proyecto

Aplicación web completa desarrollada en PHP con MySQL y Bootstrap 5 para la gestión integral de usuarios, vehículos y registros de viajes. Incluye sistema de autenticación seguro con encriptación de contraseñas, validaciones robustas y una interfaz moderna y responsiva.

## ✨ Características Principales

- 🔐 **Sistema de autenticación seguro**
  - Login con validación de credenciales
  - Contraseñas encriptadas con bcrypt (PASSWORD_DEFAULT)
  - Gestión de sesiones PHP
  
- 👥 **Gestión de Usuarios** (CRUD completo)
  - Creación con validación de email único
  - Edición de información (con opción de cambio de contraseña)
  - Listado con tabla interactiva
  - Activación/desactivación de usuarios
  
- 🚗 **Gestión de Carros** (CRUD completo)
  - Registro de vehículos (placa, color, fecha de ingreso)
  - Edición de información del vehículo
  - Eliminación con validación de integridad (verifica viajes asociados)
  - Visualización con badges de colores
  
- 🗺️ **Gestión de Viajes**
  - Registro de viajes con origen y destino
  - Asociación de vehículos y ciudades
  - Edición de información de viajes
  - Listado con DataTables (búsqueda, ordenamiento, paginación)
  - Vista de relaciones entre carros y ciudades
  
- 🎨 **Interfaz Moderna**
  - Bootstrap 5.3 con diseño responsivo
  - Bootstrap Icons para iconografía
  - DataTables con localización en español
  - Navegación intuitiva con navbar persistente

## 🗂️ Estructura del Proyecto

```
registros-de-viajes/
│
├── index.php                 # Página de login con autenticación
├── logout.php                # Cierre de sesión
├── db.php                    # Conexión PDO a MySQL con variables de entorno
├── .env                      # Configuración de base de datos (no versionado)
├── composer.json             # Dependencias del proyecto
│
├── includes/                 # Componentes compartidos
│   ├── header.php           # Header HTML con Bootstrap 5
│   ├── footer.php           # Footer y scripts
│   └── navbar.php           # Barra de navegación (desuso)
│
├── usuarios/                 # Módulo de Usuarios
│   ├── listar.php           # Dashboard principal con listado
│   ├── crear.php            # Formulario de creación
│   ├── editar.php           # Formulario de edición
│   └── eliminar.php         # Eliminación de usuarios
│
├── carros/                   # Módulo de Carros
│   ├── listar.php           # Listado con badges de colores
│   ├── crear.php            # Registro de vehículos
│   ├── editar.php           # Edición de vehículos
│   └── eliminar.php         # Eliminación con validación
│
├── viajes/                   # Módulo de Viajes
│   ├── listar.php           # Tabla interactiva con DataTables
│   ├── crear.php            # Registro de viajes
│   └── editar.php           # Edición de viajes
│
├── api/                      # API REST (Slim Framework)
│   ├── index.php
│   ├── rutas.php
│   └── composer.json
│
└── assets/                   # Recursos estáticos
    ├── css/
    │   └── styles.css       # Estilos personalizados
    ├── js/
    │   └── main.js          # Scripts personalizados
    └── img/                 # Imágenes
```

## 💻 Requisitos del Sistema

- **PHP** >= 8.0 (Desarrollado con PHP 8.4.14)
- **MySQL** >= 8.0 o MariaDB >= 10.6
- **Composer** >= 2.0 (para gestionar dependencias)
- **Extensiones PHP requeridas:**
  - PDO
  - pdo_mysql
  - openssl
  - mbstring

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/edwarsthat/registros-de-viajes.git
cd registros-de-viajes
```

### 2. Configurar la base de datos

**Crear la base de datos:**
```sql
CREATE DATABASE prueba_tecnica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Crear las tablas:**
```sql
USE prueba_tecnica;

-- Tabla de usuarios
CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de carros
CREATE TABLE carro (
    idcarro INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) NOT NULL UNIQUE,
    color VARCHAR(50) NOT NULL,
    fecha_ingreso DATE NOT NULL
);

-- Tabla de ciudades
CREATE TABLE ciudad (
    idciudad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de viajes
CREATE TABLE viaje (
    idviaje INT AUTO_INCREMENT PRIMARY KEY,
    idcarro INT NOT NULL,
    idciudad_origen INT NOT NULL,
    idciudad_destino INT NOT NULL,
    tiempo_horas DECIMAL(5,2) NOT NULL,
    fecha DATE NOT NULL,
    FOREIGN KEY (idcarro) REFERENCES carro(idcarro),
    FOREIGN KEY (idciudad_origen) REFERENCES ciudad(idciudad),
    FOREIGN KEY (idciudad_destino) REFERENCES ciudad(idciudad)
);
```

**Insertar datos de ejemplo:**
```sql
-- Usuario de prueba (contraseña: 123456)
INSERT INTO usuario (nombre, email, password) 
VALUES ('Administrador', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Ciudades
INSERT INTO ciudad (nombre) VALUES 
('Bogotá'), ('Medellín'), ('Cali'), ('Barranquilla'), ('Cartagena');

-- Carros de ejemplo
INSERT INTO carro (placa, color, fecha_ingreso) VALUES 
('ABC123', 'Rojo', '2024-01-15'),
('XYZ789', 'Azul', '2024-02-20'),
('DEF456', 'Negro', '2024-03-10');
```

### 3. Configurar variables de entorno

Crear un archivo `.env` en la raíz del proyecto:

```env
DB_HOST=localhost
DB_NAME=prueba_tecnica
DB_USER=root
DB_PASS=tu_contraseña_mysql
```

### 4. Instalar dependencias

```bash
composer install
```

### 5. Iniciar el servidor

**Opción 1: Servidor integrado de PHP**
```bash
php -S localhost:8000
```

**Opción 2: XAMPP/WAMP/MAMP**
- Copiar el proyecto a la carpeta `htdocs` o `www`
- Acceder mediante `http://localhost/registros-de-viajes`

### 6. Acceder a la aplicación

Abrir en el navegador: `http://localhost:8000`

**Credenciales de prueba:**
- **Email:** admin@test.com
- **Contraseña:** 123456

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP** 8.4.14
- **MySQL** 8.0
- **PDO** (PHP Data Objects) para interacción segura con base de datos
- **Slim Framework** 4.0 (API REST)
- **vlucas/phpdotenv** 5.0 (Gestión de variables de entorno)

### Frontend
- **HTML5** y **CSS3**
- **Bootstrap** 5.3.0
- **Bootstrap Icons** 1.11.0
- **DataTables** 1.13.6 (Tablas interactivas)
- **jQuery** 3.7.0
- **JavaScript** (ES6+)

### Seguridad
- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Prepared Statements PDO (prevención SQL injection)
- Validación de datos en cliente y servidor
- Gestión de sesiones PHP
- Sanitización con `htmlspecialchars()`

## 📚 Módulos del Sistema

### 1️⃣ Módulo de Usuarios

**Funcionalidades:**
- ✅ Listado completo de usuarios con estado (activo/inactivo)
- ✅ Creación de usuarios con validación de email único
- ✅ Edición de información (nombre, email, contraseña opcional)
- ✅ Eliminación lógica/física de usuarios
- ✅ Contraseñas encriptadas con bcrypt

**Validaciones:**
- Email único en el sistema
- Formato de email válido
- Longitud mínima de contraseña
- Campos obligatorios

### 2️⃣ Módulo de Carros

**Funcionalidades:**
- ✅ Listado de vehículos con badges de colores
- ✅ Registro de carros (placa, color, fecha de ingreso)
- ✅ Edición de información del vehículo
- ✅ Eliminación con validación de integridad referencial
- ✅ Conversión automática de placas a mayúsculas

**Validaciones:**
- Placa única en el sistema
- Formato de placa válido
- Color obligatorio
- Prevención de eliminación si tiene viajes asociados

**Características especiales:**
- Badges visuales con colores dinámicos
- Verificación de relaciones antes de eliminar

### 3️⃣ Módulo de Viajes

**Funcionalidades:**
- ✅ Registro de viajes con origen y destino
- ✅ Asociación con vehículos y ciudades
- ✅ Edición de información de viajes
- ✅ Listado interactivo con DataTables
- ✅ Búsqueda y ordenamiento en tiempo real
- ✅ Paginación automática

**Características de DataTables:**
- 🔍 Búsqueda en tiempo real
- ⬆️⬇️ Ordenamiento por columnas
- 📄 Paginación (10, 25, 50, 100 registros)
- 🌐 Interfaz en español
- 📊 Vista de relaciones entre carros y ciudades

**Validaciones:**
- Ciudad origen diferente a ciudad destino
- Tiempo de viaje mayor a 0
- Fecha válida
- Vehículo y ciudades existentes

## 🗄️ Esquema de Base de Datos

### Tablas Principales

**usuario**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| idusuario | INT (PK) | ID único del usuario |
| nombre | VARCHAR(100) | Nombre completo |
| email | VARCHAR(100) | Email único |
| password | VARCHAR(255) | Contraseña hasheada |
| activo | BOOLEAN | Estado del usuario |
| fecha_creacion | DATETIME | Fecha de registro |
| fecha_actualizacion | DATETIME | Última modificación |

**carro**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| idcarro | INT (PK) | ID único del vehículo |
| placa | VARCHAR(20) | Placa única |
| color | VARCHAR(50) | Color del vehículo |
| fecha_ingreso | DATE | Fecha de registro |

**ciudad**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| idciudad | INT (PK) | ID único de la ciudad |
| nombre | VARCHAR(100) | Nombre de la ciudad |
| activo | BOOLEAN | Estado de la ciudad |

**viaje**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| idviaje | INT (PK) | ID único del viaje |
| idcarro | INT (FK) | Referencia al vehículo |
| idciudad_origen | INT (FK) | Ciudad de origen |
| idciudad_destino | INT (FK) | Ciudad de destino |
| tiempo_horas | DECIMAL(5,2) | Duración del viaje |
| fecha | DATE | Fecha del viaje |

### Relaciones
- `viaje.idcarro` → `carro.idcarro`
- `viaje.idciudad_origen` → `ciudad.idciudad`
- `viaje.idciudad_destino` → `ciudad.idciudad`

## 🎨 Capturas de Pantalla

### Login
- Página de autenticación con validación de credenciales
- Mensajes de error informativos

### Dashboard de Usuarios
- Tabla con listado completo
- Botones de acción (Crear, Editar, Eliminar)
- Indicadores de estado (Activo/Inactivo)

### Gestión de Carros
- Visualización con badges de colores
- Formularios de creación y edición
- Validaciones en tiempo real

### Registro de Viajes
- DataTable interactiva con búsqueda
- Selección de vehículos y ciudades
- Vista de relaciones y tiempos

## 🔒 Seguridad Implementada

### Autenticación
- ✅ Contraseñas hasheadas con bcrypt (PASSWORD_DEFAULT)
- ✅ Verificación segura con `password_verify()`
- ✅ Gestión de sesiones PHP
- ✅ Redirección automática para usuarios no autenticados

### Protección contra Vulnerabilidades
- ✅ **SQL Injection**: PDO Prepared Statements
- ✅ **XSS**: `htmlspecialchars()` en todas las salidas
- ✅ **CSRF**: Validación de sesiones
- ✅ **Inyección de código**: Sanitización de entradas

### Validaciones
- ✅ Validación en cliente (HTML5 + JavaScript)
- ✅ Validación en servidor (PHP)
- ✅ Verificación de integridad referencial
- ✅ Mensajes de error informativos

## 📝 Estado del Proyecto

✅ **Proyecto completamente funcional**

### Completado
- [x] Sistema de autenticación con sesiones
- [x] Módulo de Usuarios (CRUD completo)
- [x] Módulo de Carros (CRUD completo)
- [x] Módulo de Viajes (Crear, Editar, Listar)
- [x] Base de datos con relaciones
- [x] Validaciones de seguridad
- [x] Interfaz responsiva con Bootstrap 5
- [x] DataTables con localización español
- [x] Gestión de integridad referencial

### En Desarrollo
- [ ] API REST endpoints
- [ ] Módulo de reportes
- [ ] Exportación a PDF/Excel
- [ ] Panel de estadísticas
- [ ] Historial de cambios (audit log)

## 🚦 Uso del Sistema

### 1. Iniciar Sesión
```
URL: http://localhost:8000
Email: admin@test.com
Contraseña: 123456
```

### 2. Gestionar Usuarios
- Ir a "Usuarios" en la barra de navegación
- Crear, editar o eliminar usuarios
- Activar/desactivar cuentas

### 3. Registrar Vehículos
- Ir a "Carros"
- Agregar placas, colores y fechas
- Editar o eliminar (si no tiene viajes)

### 4. Registrar Viajes
- Ir a "Viajes"
- Seleccionar vehículo, origen y destino
- Ingresar tiempo y fecha
- Ver tabla interactiva con todos los registros

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
```
Verificar que MySQL esté ejecutándose
Revisar credenciales en el archivo .env
Confirmar que la base de datos 'prueba_tecnica' existe
```

### Error 404 al acceder a módulos
```
Verificar que el servidor esté iniciado
Revisar la ruta del proyecto
Usar rutas relativas desde la raíz
```

### DataTables no funciona
```
Verificar que jQuery esté cargado antes de DataTables
Revisar la consola del navegador para errores
Confirmar conexión a CDN de DataTables
```

### Problema con contraseñas
```
Las contraseñas se hashean con bcrypt
No se pueden recuperar, solo resetear
Usar password_verify() para validación
```

## 🤝 Contribución

Si deseas contribuir al proyecto:

1. **Fork** el repositorio
2. Crear una rama para tu feature
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```
3. **Commit** de los cambios
   ```bash
   git commit -m "Añadir: descripción de la funcionalidad"
   ```
4. **Push** a la rama
   ```bash
   git push origin feature/nueva-funcionalidad
   ```
5. Abrir un **Pull Request**

### Estándares de Código
- Seguir PSR-12 para código PHP
- Comentar funciones complejas
- Usar nombres descriptivos para variables
- Mantener funciones pequeñas y enfocadas

## 📄 Licencia

Este proyecto es de uso educativo y demostrativo.

## 👨‍💻 Autor

**edwarsthat**
- GitHub: [@edwarsthat](https://github.com/edwarsthat)
- Repositorio: [registros-de-viajes](https://github.com/edwarsthat/registros-de-viajes)

## 📞 Contacto

Para consultas, sugerencias o reportar problemas:
- Abrir un **Issue** en GitHub
- Enviar un **Pull Request** con mejoras

---

## 📌 Notas Adicionales

### Requisitos de Desarrollo en Windows
Si estás desarrollando en Windows, asegúrate de:
- Instalar PHP desde [windows.php.net](https://windows.php.net/download/)
- Habilitar extensiones en `php.ini`: `pdo_mysql`, `openssl`, `mbstring`
- Instalar MySQL/MariaDB o usar XAMPP
- Instalar Composer globalmente

### Configuración de Composer en Windows
```bash
# Descargar certificados SSL
curl https://curl.se/ca/cacert.pem -o cacert.pem

# Configurar en php.ini
curl.cainfo = "C:/php/cacert.pem"
openssl.cafile = "C:/php/cacert.pem"
```

### Variables de Entorno Recomendadas (.env)
```env
# Base de datos
DB_HOST=localhost
DB_NAME=prueba_tecnica
DB_USER=root
DB_PASS=tu_contraseña

# Aplicación
APP_ENV=development
APP_DEBUG=true
APP_TIMEZONE=America/Bogota
```

---

**Última actualización:** Noviembre 2025

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub
