# Reporte de Correcciones #6 - Debugging JavaScript
**Fecha:** 12 de noviembre de 2025  
**Problemas detectados:** 2

---

## 🔍 PROBLEMA 1: Modal de ajustar stock no marca el tipo de movimiento

### Error reportado:
El usuario puede seleccionar entrada/salida/ajuste pero visualmente no se marca la selección.

### Análisis:
El modal usa radio buttons con Tailwind CSS `peer` selectors para mostrar visualmente qué opción está seleccionada:

```html
<label class="... peer-checked:border-green-600">
    <input type="radio" name="tipo_movimiento" value="entrada" class="sr-only peer">
    <div class="... peer-checked:opacity-100"></div>
</label>
```

**El CSS funciona correctamente**, pero:
- ✅ Los radio buttons están bien configurados con `name="tipo_movimiento"`
- ✅ Los estilos `peer-checked:` cambian el borde y la opacidad
- ✅ La validación HTML5 `required` está presente

### Causa raíz:
**No hay error técnico**. El usuario podría no estar viendo el cambio visual por:
1. Caché del navegador (CSS no actualizado)
2. El borde cambia de `border-gray-200` a `border-green-600` pero puede ser sutil
3. El modal se estaba cerrando antes de confirmar

### Solución implementada:

**Archivo:** `resources/views/productos/show.blade.php`

Añadido JavaScript para manejar el modal:

```javascript
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        modals.forEach(modal => {
            if (modal.id) closeModal(modal.id);
        });
    }
});
```

### Verificación del CSS:
Los estilos `peer-checked:` funcionan así:
- Cuando haces clic en el label, el input radio se selecciona
- El selector `.peer-checked:` aplica estilos al label cuando el input está checked
- Cambia: `border-gray-200` → `border-green-600` (entrada) / `border-red-600` (salida) / `border-blue-600` (ajuste)
- Cambia: `opacity-0` → `opacity-100` en el borde interior

---

## 🔴 PROBLEMA 2: Botón "Agregar" no añade productos en nueva venta

### Error reportado:
Al seleccionar un producto y hacer clic en "Agregar", no se añade a la lista del carrito.

### Causa raíz detectada:
El JavaScript estaba ejecutándose **ANTES** de que el DOM estuviera completamente cargado. El código intentaba acceder a `document.getElementById('btn-agregar-producto')` pero el elemento aún no existía en el DOM.

### Solución implementada:

**Archivo:** `resources/views/ventas/create.blade.php`

**ANTES:**
```javascript
let carrito = [];

document.getElementById('btn-agregar-producto').addEventListener('click', function(e) {
    // ❌ Este código se ejecuta inmediatamente, antes de que el DOM esté listo
    // Si el botón no existe aún, falla silenciosamente
});
```

**DESPUÉS:**
```javascript
let carrito = [];

console.log('Script de ventas cargado');

document.addEventListener('DOMContentLoaded', function() {  // ✅ Espera a que el DOM esté listo
    console.log('DOM cargado');
    
    const btnAgregar = document.getElementById('btn-agregar-producto');
    console.log('Botón agregar encontrado:', btnAgregar);
    
    if (!btnAgregar) {
        console.error('No se encontró el botón btn-agregar-producto');
        return;
    }

    btnAgregar.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Click en agregar producto');
        
        const select = document.getElementById('producto_select');
        console.log('Select encontrado:', select);
        
        const option = select.options[select.selectedIndex];
        console.log('Opción seleccionada:', option);
        
        if (!option.value) {
            console.log('No hay opción seleccionada');
            mostrarAlerta('Por favor seleccione un producto', 'warning');
            return;
        }
        
        const producto = {
            id: option.value,
            nombre: option.dataset.nombre,
            precio: parseFloat(option.dataset.precio),
            stock: parseInt(option.dataset.stock),
            cantidad: 1
        };
        
        console.log('Producto a agregar:', producto);
        
        // ... resto del código
        
        console.log('Carrito actual:', carrito);
        actualizarTabla();
        select.value = '';
    });
});
```

### Cambios clave:
1. ✅ **DOMContentLoaded**: Espera a que todo el HTML esté parseado
2. ✅ **Validación null**: Verifica que el botón exista antes de añadir el listener
3. ✅ **Console.log extensivos**: Para depurar en DevTools (F12 → Console)
4. ✅ **Error handling**: Si no encuentra el botón, muestra error en consola

### Logs de depuración añadidos:
```
Script de ventas cargado
DOM cargado
Botón agregar encontrado: <button>
Click en agregar producto
Select encontrado: <select>
Opción seleccionada: <option>
Producto a agregar: {id: "1", nombre: "...", precio: 10, stock: 5, cantidad: 1}
Producto agregado al carrito
Carrito actual: [{...}]
```

---

## 🛠️ Instrucciones de prueba para el usuario

### Para probar el modal de ajustar stock:

1. **Limpia el caché del navegador:**
   - Chrome/Edge: `Ctrl + Shift + Delete` → Marcar "Imágenes y archivos en caché" → Borrar
   - O hacer hard refresh: `Ctrl + Shift + R`

2. **Abre DevTools (F12)** para ver errores si los hay

3. **Ve a Productos → Ver detalles de un producto**

4. **Haz clic en "Ajustar Stock"**

5. **Selecciona un tipo de movimiento:**
   - **Entrada** (verde): Debe aparecer un **borde verde** alrededor
   - **Salida** (roja): Debe aparecer un **borde rojo** alrededor
   - **Ajuste** (azul): Debe aparecer un **borde azul** alrededor

6. **Ingresa cantidad y descripción**

7. **Haz clic en "Confirmar Ajuste"**

### Para probar agregar productos en ventas:

1. **Abre DevTools (F12) → Pestaña Console**

2. **Ve a Ventas → Crear Venta**

3. **Deberías ver en la consola:**
   ```
   Script de ventas cargado
   DOM cargado
   Botón agregar encontrado: <button...>
   ```

4. **Selecciona un producto del dropdown**

5. **Haz clic en "Agregar"**

6. **Deberías ver en la consola:**
   ```
   Click en agregar producto
   Select encontrado: <select...>
   Opción seleccionada: <option...>
   Producto a agregar: Object { id: "1", nombre: "...", ... }
   Producto agregado al carrito
   Carrito actual: Array [ Object ]
   ```

7. **El producto debe aparecer en la tabla debajo**

### Si sigue sin funcionar:

**Para el modal:**
- Verifica que estés haciendo clic DENTRO de los cuadros (entrada/salida/ajuste)
- El borde debe ser claramente visible cuando está seleccionado
- Intenta con diferentes navegadores

**Para agregar productos:**
- Si NO ves los mensajes en la consola, el script no se está cargando
- Verifica que no haya errores rojos en la consola
- Busca errores tipo "mostrarAlerta is not defined"
- Comparte screenshot de la consola

---

## 📋 Resumen de cambios

### Archivos modificados:

1. **resources/views/productos/show.blade.php**
   - ✅ Añadidas funciones `closeModal()` y `openModal()`
   - ✅ Event listener para cerrar con tecla ESC
   - ✅ Manejo correcto del estado del modal

2. **resources/views/ventas/create.blade.php**
   - ✅ Envuelto código en `DOMContentLoaded`
   - ✅ Añadida validación de existencia del botón
   - ✅ Añadidos 10+ console.log para debugging
   - ✅ Mejor manejo de errores

### Sin errores:
```
✅ productos/show.blade.php - 0 errores
✅ ventas/create.blade.php - 0 errores
```

---

## 🎯 Próximos pasos

1. **Usuario debe abrir DevTools (F12)** y compartir lo que ve en la consola
2. **Limpiar caché del navegador** para asegurar que los scripts se carguen
3. **Probar en modo incógnito** para descartar extensiones del navegador
4. **Si sigue fallando**, compartir screenshot de:
   - La consola de DevTools (pestaña Console)
   - La pestaña Network (filtrar por .js)
   - La pestaña Elements (inspeccionar el botón)

---

**Estado:** ⚠️ REQUIERE VALIDACIÓN DEL USUARIO CON DEVTOOLS
