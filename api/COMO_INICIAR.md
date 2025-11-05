# 🚀 Cómo Iniciar la API REST

## Método 1: Usando el archivo app.php (RECOMENDADO)

Este es el archivo simplificado que contiene toda la API funcional.

### En Windows PowerShell:

```powershell
# Opción A: Desde cualquier lugar (especificando el documento raíz)
php -S localhost:8001 -t d:\trabajo\prueba_spy\registros-de-viajes\api app.php

# Opción B: Desde la carpeta api
cd d:\trabajo\prueba_spy\registros-de-viajes\api
php -S localhost:8001 app.php

# Opción C: Desde la carpeta api sin especificar app.php
cd d:\trabajo\prueba_spy\registros-de-viajes\api
php -S localhost:8001
```

### Resultado esperado:
```
[Mon Nov  4 21:15:00 2025] PHP 8.4.14 Development Server (http://127.0.0.1:8001) started
```

---

## Método 2: Usando index.php (SI FUNCIONA)

Si los errores de namespace se solucionan:

```powershell
cd d:\trabajo\prueba_spy\registros-de-viajes\api
php -S localhost:8001 index.php
```

---

## 🧪 Probar la API

Una vez que el servidor esté corriendo en http://localhost:8001

### 1. Ruta raíz (prueba que funciona)
```powershell
(Invoke-WebRequest -Uri 'http://localhost:8001/' -Method Get).Content | ConvertFrom-Json
```

Respuesta esperada:
```json
{
  "status": "success",
  "message": "🚀 API REST funcionando",
  "endpoints": {
    "GET /api/viajes/por-placa/{placa}": "Obtener viajes por placa"
  }
}
```

### 2. Obtener viajes por placa
```powershell
(Invoke-WebRequest -Uri 'http://localhost:8001/api/viajes/por-placa/ABC123' -Method Get).Content | ConvertFrom-Json
```

Respuesta exitosa (200):
```json
{
  "status": "success",
  "message": "Viajes encontrados para: ABC123",
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

---

## 🔧 Comandos rápidos

### Iniciar API en background (Windows)
```powershell
Start-Process -NoNewWindow -FilePath "C:\php\php.exe" -ArgumentList "-S localhost:8001 app.php" -WorkingDirectory "D:\trabajo\prueba_spy\registros-de-viajes\api"
```

### Detener API
```powershell
Get-Process -Name php | Stop-Process -Force
```

### Ver si la API responde
```powershell
curl http://localhost:8001/
```

---

## ✅ Archivos de la API

- **`app.php`** ← Usa este (versión simplificada y funcional)
- **`index.php`** ← Versión con estructura más compleja (en desarrollo)
- **`src/routes.php`** ← Rutas de Slim
- **`src/controllers/ViajesController.php`** ← Lógica de negocio
- **`src/config/Database.php`** ← Conexión PDO

---

## 🐛 Si hay errores

### Error: "Class not found App\Config\Database"
✅ Usa `app.php` que no tiene ese problema

### Error: "Unable to connect to database"
Verifica que:
1. MySQL esté corriendo
2. Las credenciales en `.env` sean correctas
3. La base de datos `prueba_tecnica` exista

### Error: "Method not allowed"
Asegúrate de usar GET para `/api/viajes/por-placa/{placa}`

---

**Resumen rápido:**
```powershell
cd D:\trabajo\prueba_spy\registros-de-viajes\api
php -S localhost:8001 app.php
```

Luego abre: http://localhost:8001/
