# 📋 Códigos HTTP - Referencia de la API

## Resumen de Códigos HTTP Implementados

| Código | Nombre | Uso |
|--------|--------|-----|
| **200** | OK | Solicitud exitosa (GET, PUT) |
| **201** | Created | Recurso creado exitosamente (POST) |
| **204** | No Content | Éxito sin contenido (DELETE) |
| **400** | Bad Request | Solicitud inválida o datos faltantes |
| **404** | Not Found | Recurso no encontrado |
| **409** | Conflict | Conflicto de validación |
| **500** | Internal Server Error | Error del servidor |

---

## 🔍 Endpoint por Endpoint

### 1. GET `/api/viajes/por-placa/{placa}`

**Descripción:** Obtiene todos los viajes de un vehículo por su placa

#### Respuestas Posibles:

**200 OK - Viajes encontrados:**
```json
{
  "status": "success",
  "code": 200,
  "message": "OK: Viajes encontrados",
  "placa": "ABC123",
  "count": 2,
  "data": [
    {
      "idviaje": 1,
      "placa": "ABC123",
      "color": "Rojo",
      "ciudad_origen": "Bogotá",
      "ciudad_destino": "Medellín",
      "tiempo_horas": "5.50",
      "fecha": "2024-11-04"
    }
  ]
}
```

**400 Bad Request - Placa inválida:**
```json
{
  "status": "error",
  "code": 400,
  "message": "Bad Request: Formato de placa inválido",
  "error": "La placa debe contener solo letras y números"
}
```

**404 Not Found - Sin viajes:**
```json
{
  "status": "info",
  "code": 404,
  "message": "Not Found: No hay viajes registrados",
  "placa": "XYZ999",
  "count": 0,
  "data": []
}
```

**500 Internal Server Error - Error BD:**
```json
{
  "status": "error",
  "code": 500,
  "message": "Internal Server Error",
  "error": "Error de conexión a BD: ..."
}
```

---

### 2. POST `/api/viajes`

**Descripción:** Crea un nuevo viaje

#### Body (JSON):
```json
{
  "idcarro": 1,
  "idciudad_origen": 1,
  "idciudad_destino": 2,
  "tiempo_horas": 5.5,
  "fecha": "2024-11-04"
}
```

#### Respuestas Posibles:

**201 Created - Viaje creado:**
```json
{
  "status": "success",
  "code": 201,
  "message": "Created: Viaje creado exitosamente",
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

**400 Bad Request - Campo faltante:**
```json
{
  "status": "error",
  "code": 400,
  "message": "Bad Request: Campo requerido faltante",
  "error": "El campo 'idcarro' es requerido",
  "campos_requeridos": ["idcarro", "idciudad_origen", "idciudad_destino", "tiempo_horas", "fecha"]
}
```

**409 Conflict - Validación fallida:**
```json
{
  "status": "error",
  "code": 409,
  "message": "Conflict: Validación fallida",
  "error": "La ciudad de origen debe ser diferente a la de destino"
}
```

**500 Internal Server Error:**
```json
{
  "status": "error",
  "code": 500,
  "message": "Internal Server Error",
  "error": "..."
}
```

---

### 3. PUT `/api/viajes/{id}`

**Descripción:** Actualiza un viaje existente

#### Body (JSON):
```json
{
  "tiempo_horas": 6.5,
  "fecha": "2024-11-05"
}
```

#### Respuestas Posibles:

**200 OK - Actualizado:**
```json
{
  "status": "success",
  "code": 200,
  "message": "OK: Viaje actualizado exitosamente",
  "id": 1
}
```

**400 Bad Request - Sin datos:**
```json
{
  "status": "error",
  "code": 400,
  "message": "Bad Request: Sin datos para actualizar"
}
```

**404 Not Found - Viaje no existe:**
```json
{
  "status": "error",
  "code": 404,
  "message": "Not Found: El viaje no existe"
}
```

**500 Internal Server Error:**
```json
{
  "status": "error",
  "code": 500,
  "message": "Internal Server Error",
  "error": "..."
}
```

---

### 4. DELETE `/api/viajes/{id}`

**Descripción:** Elimina un viaje

#### Respuestas Posibles:

**204 No Content - Eliminado:**
(Sin body, solo header)
```
HTTP/1.1 204 No Content
Content-Type: application/json
```

**404 Not Found - Viaje no existe:**
```json
{
  "status": "error",
  "code": 404,
  "message": "Not Found: El viaje no existe"
}
```

**500 Internal Server Error:**
```json
{
  "status": "error",
  "code": 500,
  "message": "Internal Server Error",
  "error": "..."
}
```

---

## 🧪 Ejemplos de Prueba con PowerShell

### GET - 200 OK
```powershell
$response = Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes/por-placa/ABC123' -Method Get
Write-Host "Status: $($response.StatusCode)"
$response.Content | ConvertFrom-Json
```

### POST - 201 Created
```powershell
$body = @{
    idcarro = 1
    idciudad_origen = 1
    idciudad_destino = 2
    tiempo_horas = 5.5
    fecha = "2024-11-04"
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes' `
    -Method Post `
    -Headers @{'Content-Type' = 'application/json'} `
    -Body $body

Write-Host "Status: $($response.StatusCode)"
$response.Content | ConvertFrom-Json
```

### PUT - 200 OK
```powershell
$body = @{
    tiempo_horas = 6.5
    fecha = "2024-11-05"
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes/1' `
    -Method Put `
    -Headers @{'Content-Type' = 'application/json'} `
    -Body $body

Write-Host "Status: $($response.StatusCode)"
$response.Content | ConvertFrom-Json
```

### DELETE - 204 No Content
```powershell
$response = Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes/1' `
    -Method Delete

Write-Host "Status: $($response.StatusCode)"
Write-Host "Body: $($response.Content)"  # Vacío para 204
```

### GET - 404 Not Found
```powershell
try {
    $response = Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes/por-placa/NOEXISTE' -Method Get
} catch {
    Write-Host "Status: $($_.Exception.Response.StatusCode.Value__)"
}
```

---

## 📊 Tabla de Respuestas

| Endpoint | Método | Código | Escenario |
|----------|--------|--------|-----------|
| `/api/viajes/por-placa/{placa}` | GET | 200 | Viajes encontrados |
| `/api/viajes/por-placa/{placa}` | GET | 400 | Placa inválida |
| `/api/viajes/por-placa/{placa}` | GET | 404 | Sin viajes |
| `/api/viajes/por-placa/{placa}` | GET | 500 | Error BD |
| `/api/viajes` | POST | 201 | Viaje creado |
| `/api/viajes` | POST | 400 | Campo faltante |
| `/api/viajes` | POST | 409 | Conflicto validación |
| `/api/viajes` | POST | 500 | Error BD |
| `/api/viajes/{id}` | PUT | 200 | Actualizado |
| `/api/viajes/{id}` | PUT | 400 | Sin datos |
| `/api/viajes/{id}` | PUT | 404 | Viaje no existe |
| `/api/viajes/{id}` | PUT | 500 | Error BD |
| `/api/viajes/{id}` | DELETE | 204 | Eliminado |
| `/api/viajes/{id}` | DELETE | 404 | Viaje no existe |
| `/api/viajes/{id}` | DELETE | 500 | Error BD |

---

## 📝 Significado de Códigos

### 2xx - Exitoso
- **200** - La solicitud fue exitosa
- **201** - Recurso creado exitosamente
- **204** - Operación exitosa sin contenido de respuesta

### 4xx - Error del Cliente
- **400** - Solicitud inválida (datos faltantes o formato incorrecto)
- **404** - Recurso no encontrado
- **409** - Conflicto (validación fallida, restricción de integridad)

### 5xx - Error del Servidor
- **500** - Error interno del servidor (error no controlado)

---

## ✅ Validaciones Implementadas

### GET `/api/viajes/por-placa/{placa}`
- ✅ Placa no vacía
- ✅ Formato alfanumérico
- ✅ Case insensitive (ABC123 = abc123)

### POST `/api/viajes`
- ✅ Campos requeridos presentes
- ✅ Origen ≠ Destino
- ✅ Tiempo > 0
- ✅ Fecha válida

### PUT `/api/viajes/{id}`
- ✅ Viaje existe (404 si no)
- ✅ Al menos un campo para actualizar

### DELETE `/api/viajes/{id}`
- ✅ Viaje existe (404 si no)

