# 🚀 API REST - Documentación

## 📌 Descripción

API REST desarrollada con **Slim Framework 4** para gestionar viajes, carros y usuarios de la aplicación.

## 🔧 Configuración

### Instalación de dependencias

```bash
cd api
composer install
```

### Iniciar servidor

```bash
# Desde la carpeta api
php -S localhost:8001

# O desde la raíz
php -S localhost:8001 -t api
```

La API estará disponible en: `http://localhost:8001`

## 📚 Endpoints Disponibles

### 🗺️ VIAJES

#### 1. Obtener todos los viajes
```
GET /api/viajes
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Viajes obtenidos correctamente",
  "count": 3,
  "data": [
    {
      "idviaje": 1,
      "idcarro": 1,
      "placa": "ABC123",
      "color": "Rojo",
      "idciudad_origen": 1,
      "ciudad_origen": "Bogotá",
      "idciudad_destino": 2,
      "ciudad_destino": "Medellín",
      "tiempo_horas": "5.50",
      "fecha": "2024-11-04"
    }
  ]
}
```

---

#### 2. Obtener viajes por placa del vehículo ⭐ **PRINCIPAL**
```
GET /api/viajes/por-placa/{placa}
```

**Parámetros:**
- `placa` (string, required): Placa del vehículo (ej: ABC123)

**Ejemplos:**
```
GET /api/viajes/por-placa/ABC123
GET /api/viajes/por-placa/XYZ789
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Viajes encontrados para la placa: ABC123",
  "placa": "ABC123",
  "count": 2,
  "data": [
    {
      "idviaje": 1,
      "idcarro": 1,
      "placa": "ABC123",
      "color": "Rojo",
      "ciudad_origen": "Bogotá",
      "ciudad_destino": "Medellín",
      "tiempo_horas": "5.50",
      "fecha": "2024-11-04"
    },
    {
      "idviaje": 3,
      "idcarro": 1,
      "placa": "ABC123",
      "color": "Rojo",
      "ciudad_origen": "Medellín",
      "ciudad_destino": "Cali",
      "tiempo_horas": "8.00",
      "fecha": "2024-11-03"
    }
  ]
}
```

**Respuesta sin viajes (200):**
```json
{
  "status": "info",
  "message": "No hay viajes registrados para el vehículo con placa: XYZ999",
  "placa": "XYZ999",
  "count": 0,
  "data": []
}
```

---

#### 3. Obtener viaje por ID
```
GET /api/viajes/{id}
```

**Parámetros:**
- `id` (integer, required): ID del viaje

**Ejemplo:**
```
GET /api/viajes/1
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Viaje obtenido correctamente",
  "data": {
    "idviaje": 1,
    "idcarro": 1,
    "placa": "ABC123",
    "color": "Rojo",
    "ciudad_origen": "Bogotá",
    "ciudad_destino": "Medellín",
    "tiempo_horas": "5.50",
    "fecha": "2024-11-04"
  }
}
```

---

#### 4. Crear viaje
```
POST /api/viajes
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "idcarro": 1,
  "idciudad_origen": 1,
  "idciudad_destino": 2,
  "tiempo_horas": 5.5,
  "fecha": "2024-11-04"
}
```

**Respuesta exitosa (201):**
```json
{
  "status": "success",
  "message": "Viaje creado exitosamente",
  "id": 8,
  "data": {
    "idviaje": 8,
    "idcarro": 1,
    "idciudad_origen": 1,
    "idciudad_destino": 2,
    "tiempo_horas": 5.5,
    "fecha": "2024-11-04"
  }
}
```

---

#### 5. Actualizar viaje
```
PUT /api/viajes/{id}
Content-Type: application/json
```

**Parámetros:**
- `id` (integer, required): ID del viaje

**Body (JSON) - Solo enviar campos a actualizar:**
```json
{
  "tiempo_horas": 6.5,
  "fecha": "2024-11-05"
}
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Viaje actualizado exitosamente"
}
```

---

#### 6. Eliminar viaje
```
DELETE /api/viajes/{id}
```

**Parámetros:**
- `id` (integer, required): ID del viaje

**Ejemplo:**
```
DELETE /api/viajes/8
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Viaje eliminado exitosamente"
}
```

---

## 🧪 Pruebas con cURL

### Obtener viajes por placa

```bash
# Windows PowerShell
curl -X GET "http://localhost:8001/api/viajes/por-placa/ABC123"

# Linux/Mac
curl -X GET http://localhost:8001/api/viajes/por-placa/ABC123
```

### Obtener todos los viajes

```bash
curl -X GET http://localhost:8001/api/viajes
```

### Crear viaje

```bash
curl -X POST http://localhost:8001/api/viajes \
  -H "Content-Type: application/json" \
  -d "{\"idcarro\":1,\"idciudad_origen\":1,\"idciudad_destino\":2,\"tiempo_horas\":5.5,\"fecha\":\"2024-11-04\"}"
```

### Actualizar viaje

```bash
curl -X PUT http://localhost:8001/api/viajes/1 \
  -H "Content-Type: application/json" \
  -d "{\"tiempo_horas\":6.5}"
```

### Eliminar viaje

```bash
curl -X DELETE http://localhost:8001/api/viajes/1
```

---

## 🧪 Pruebas con Postman

1. **Importar colección** (opcional)
2. **Crear request GET:**
   - URL: `http://localhost:8001/api/viajes/por-placa/ABC123`
   - Method: GET
   - Click en "Send"

3. **Crear request POST:**
   - URL: `http://localhost:8001/api/viajes`
   - Method: POST
   - Headers: `Content-Type: application/json`
   - Body (raw JSON):
   ```json
   {
     "idcarro": 1,
     "idciudad_origen": 1,
     "idciudad_destino": 2,
     "tiempo_horas": 5.5,
     "fecha": "2024-11-04"
   }
   ```

---

## 📋 Códigos de Respuesta HTTP

| Código | Descripción |
|--------|-------------|
| **200** | OK - Solicitud exitosa |
| **201** | Created - Recurso creado exitosamente |
| **400** | Bad Request - Error en la solicitud |
| **404** | Not Found - Recurso no encontrado |
| **500** | Internal Server Error - Error del servidor |

---

## 🔒 CORS

La API tiene CORS habilitado. Se aceptan requests desde cualquier origen:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

---

## 📝 Estructura de Carpetas

```
api/
├── index.php              # Punto de entrada
├── vendor/                # Dependencias de Composer
├── .htaccess              # Reescritura de URLs (Apache)
├── composer.json          # Configuración de dependencias
├── composer.lock          # Lock file de dependencias
├── src/
│   ├── routes.php         # Definición de rutas
│   ├── config/
│   │   └── Database.php   # Clase de conexión PDO
│   └── controllers/
│       └── ViajesController.php  # Controlador de viajes
└── README_API.md          # Este archivo
```

---

## 🚨 Solución de Problemas

### Error: "Class not found"
- Asegúrate de haber ejecutado `composer install`
- Verifica que la carpeta `vendor/` exista

### Error: "Base de datos no encontrada"
- Revisa que las variables en `.env` sean correctas
- Verifica que el servidor MySQL esté corriendo
- Ejecuta los scripts de creación de tablas

### Error CORS
- La API tiene CORS habilitado globalmente
- Verifica que el header `Content-Type: application/json` esté incluido

### Puerto 8001 ya en uso
```bash
# Cambiar puerto
php -S localhost:8002 -t api
```

---

## 🎯 Próximas Características

- [ ] Autenticación JWT
- [ ] Endpoints para Usuarios
- [ ] Endpoints para Carros
- [ ] Validaciones más robustas
- [ ] Documentación Swagger/OpenAPI
- [ ] Rate limiting
- [ ] Caché de resultados

---

**Última actualización:** Noviembre 2025

