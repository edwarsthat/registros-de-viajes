# Sistema de Gestión - Prueba Técnica

## Descripción del Proyecto

Aplicación web desarrollada en PHP con MySQL y Bootstrap para la gestión de usuarios, carros y viajes. El proyecto incluye una interfaz web con sistema de autenticación y una API REST desarrollada con Slim Framework.

## Características Principales

- 🔐 **Sistema de autenticación** (Login)
- 👥 **Gestión de Usuarios** (CRUD completo)
- 🚗 **Gestión de Carros** (CRUD completo)
- 🗺️ **Gestión de Viajes** (Crear, listar y editar)
- 🔌 **API REST** con Slim Framework para operaciones programáticas

## Estructura del Proyecto

```
/prueba_tecnica/
│
├── index.php                 # Página de login
├── db.php                    # Conexión a MySQL
├── /includes/                # Archivos compartidos
│   ├── header.php
│   ├── footer.php
│   └── navbar.php
│
├── /usuarios/                # Módulo CRUD de usuarios
│   ├── listar.php
│   ├── crear.php
│   ├── editar.php
│   └── eliminar.php
│
├── /carros/                  # Módulo CRUD de carros
│   ├── listar.php
│   ├── crear.php
│   ├── editar.php
│   └── eliminar.php
│
├── /viajes/                  # Módulo CRUD de viajes
│   ├── listar.php
│   ├── crear.php
│   └── editar.php
│
├── /api/                     # APIs REST en Slim
│   ├── index.php
│   ├── rutas.php
│   └── composer.json
│
├── /assets/                  # Recursos estáticos
│   ├── /css/
│   ├── /js/
│   └── /img/
│
└── README.md
```

## Requisitos del Sistema

- PHP >= 7.4
- MySQL >= 5.7 o MariaDB >= 10.2
- Composer (para gestionar dependencias del API)
- Servidor web (Apache/Nginx)

## Instalación

### 1. Clonar o descargar el proyecto

```bash
git clone <url-del-repositorio>
cd prueba_tecnica
```

### 2. Configurar la base de datos

1. Crear una base de datos en MySQL:
```sql
CREATE DATABASE prueba_tecnica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importar el script SQL de la base de datos (cuando esté disponible)

3. Configurar las credenciales en `db.php`

### 3. Instalar dependencias del API

```bash
cd api
composer install
```

### 4. Configurar el servidor web

Apuntar el DocumentRoot a la carpeta del proyecto o usar el servidor integrado de PHP:

```bash
php -S localhost:8000
```

### 5. Acceder a la aplicación

Abrir en el navegador: `http://localhost:8000`

## Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3
- **API Framework**: Slim Framework 4
- **Gestor de dependencias**: Composer

## Módulos del Sistema

### 1. Usuarios
- Listar todos los usuarios
- Crear nuevo usuario
- Editar usuario existente
- Eliminar usuario

### 2. Carros
- Listar todos los carros
- Registrar nuevo carro
- Editar información del carro
- Eliminar carro

### 3. Viajes
- Listar todos los viajes
- Registrar nuevo viaje
- Editar información del viaje

### 4. API REST
Endpoints disponibles para integración con otras aplicaciones:
- `/api/usuarios` - Operaciones con usuarios
- `/api/carros` - Operaciones con carros
- `/api/viajes` - Operaciones con viajes

## Estado del Proyecto

⚠️ **Proyecto en fase de estructura inicial**

Este repositorio contiene la estructura base del proyecto. La implementación de la lógica de negocio, formularios y conexiones a base de datos está pendiente.

## Próximos Pasos

1. Implementar la conexión a base de datos en `db.php`
2. Crear el esquema de base de datos (tablas usuarios, carros, viajes)
3. Desarrollar la lógica de autenticación en `index.php`
4. Implementar los CRUD completos para cada módulo
5. Desarrollar los endpoints de la API REST
6. Añadir validaciones y seguridad
7. Implementar manejo de sesiones
8. Diseñar las vistas con Bootstrap

## Contribución

Este es un proyecto de prueba técnica. Para contribuir:

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit de los cambios (`git commit -m 'Añadir nueva característica'`)
4. Push a la rama (`git push origin feature/NuevaCaracteristica`)
5. Abrir un Pull Request

## Licencia

Este proyecto es de uso educativo/técnico.

## Contacto

Para consultas sobre el proyecto, contactar a: [tu-email@ejemplo.com]

---
**Nota**: Este README será actualizado conforme se implemente la funcionalidad del proyecto.
