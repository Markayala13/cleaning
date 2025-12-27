# Admin Panel - File Manager

Sistema de administración con login para el cliente.

## 📋 Credenciales de Acceso

**URL:** `tudominio.com/admin/login.php` o `tudominio.com/admin/`

**Usuario:** `admin`
**Contraseña:** `Cleaning2025!`

⚠️ **IMPORTANTE:** Cambia la contraseña en el archivo `config.php` antes de subir a producción.

---

## 🚀 Cómo Funciona

1. El cliente entra a `tudominio.com/admin/`
2. Ingresa usuario y contraseña
3. Ve su dashboard con estadísticas de archivos
4. Puede descargar los archivos que le subes

---

## 📁 Cómo Subir Archivos para el Cliente

1. Accede a tu panel de Hostinger
2. Ve al File Manager
3. Navega a `/html/admin/files/`
4. Sube los archivos ahí (PDFs, imágenes, documentos, etc.)
5. El cliente los verá automáticamente en su panel

---

## 🔒 Seguridad

- Los archivos NO son accesibles directamente desde el navegador
- Solo se pueden descargar a través del sistema de login
- La carpeta está protegida con `.htaccess`
- Las sesiones PHP manejan la autenticación

---

## 🛠️ Archivos del Sistema

- `login.php` - Página de login
- `index.php` - Dashboard principal
- `config.php` - Configuración de credenciales
- `logout.php` - Cerrar sesión
- `download.php` - Maneja las descargas
- `files/` - Carpeta donde subes archivos para el cliente
- `.htaccess` - Protección de seguridad

---

## ⚙️ Cambiar Contraseña

Edita el archivo `config.php` y cambia esta línea:

```php
define('ADMIN_PASS', 'TuNuevaContraseña');
```

---

## 📞 Soporte

Si tienes problemas, verifica:
1. Que tu hosting tenga PHP habilitado
2. Que las sesiones PHP funcionen
3. Que los permisos de carpetas sean correctos (755)

---

**Desarrollado por Antoshka Dev**
https://antoshkadev.com
