@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">
                    <i class="fas fa-cash-register mr-2 text-green-500"></i>PUNTO DE VENTA
                </h1>
                <p class="text-sm text-slate-500">Nueva venta</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-list mr-2"></i>Ver Ventas
            </a>
        </div>

        <form action="{{ route('ventas.store') }}" method="POST" id="form-venta">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Izquierda: Productos -->
                <div class="lg:col-span-2">
                    <!-- Selección Cliente y Método Pago -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6">
                            <h6 class="mb-4 text-lg font-semibold text-slate-700">
                                <i class="fas fa-user mr-2 text-purple-500"></i>Datos de Venta
                            </h6>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Cliente *</label>
                                    <select name="cliente_id" id="cliente_id" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="">Seleccione cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" data-edad="{{ $cliente->edad }}">
                                                {{ $cliente->nombre_completo }} - {{ $cliente->identificacion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Método de Pago *</label>
                                    <select name="metodo_pago" id="metodo_pago" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">Seleccione método</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="transferencia">Transferencia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6">
                            <h6 class="mb-4 text-lg font-semibold text-slate-700">
                                <i class="fas fa-box mr-2 text-blue-500"></i>Productos
                            </h6>
                            
                            <!-- Agregar Producto -->
                            <div class="mb-4 p-4 bg-slate-50 rounded-lg">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Buscar Producto</label>
                                <div class="flex gap-2">
                                    <select id="producto_select" class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                data-nombre="{{ $producto->nombre }}"
                                                data-codigo="{{ $producto->codigo }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-stock="{{ $producto->stock_actual }}">
                                                {{ $producto->codigo }} - {{ $producto->nombre }} (Stock: {{ $producto->stock_actual }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="btn-agregar-producto" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                        <i class="fas fa-plus mr-2"></i>Agregar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla Productos -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-slate-700" id="tabla-productos">
                                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3">Producto</th>
                                            <th class="px-4 py-3 text-center">Cantidad</th>
                                            <th class="px-4 py-3 text-right">Precio</th>
                                            <th class="px-4 py-3 text-right">Subtotal</th>
                                            <th class="px-4 py-3 text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-productos">
                                        <tr id="empty-row">
                                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                                                <p>No hay productos agregados</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Resumen -->
                <div class="lg:col-span-1">
                    <!-- Resumen Venta -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-gradient-to-br from-green-500 to-green-600 shadow-soft-xl rounded-2xl bg-clip-border sticky top-6">
                        <div class="p-6 text-white">
                            <h6 class="mb-4 text-lg font-semibold">
                                <i class="fas fa-calculator mr-2"></i>Resumen
                            </h6>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-2 border-b border-green-400">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold" id="subtotal-display">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-green-400">
                                    <span>IVA (15%):</span>
                                    <span class="font-semibold" id="iva-display">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-xl">
                                    <span class="font-bold">TOTAL:</span>
                                    <span class="font-bold" id="total-display">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
                            <textarea name="observaciones" rows="3" 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Notas adicionales..."></textarea>
                        </div>
                    </div>

                    <!-- Botón Procesar -->
                    <button type="submit" id="btn-procesar" disabled
                        class="w-full px-6 py-4 text-lg font-bold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i>PROCESAR VENTA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let carrito = [];

document.addEventListener('DOMContentLoaded', function() {
    const btnAgregar = document.getElementById('btn-agregar-producto');
    
    if (!btnAgregar) {
        console.error('No se encontró el botón btn-agregar-producto');
        return;
    }

    if (!btnAgregar) {
        console.error('ERROR: No se encontró el botón btn-agregar-producto');
        return;
    }

    btnAgregar.addEventListener('click', function(e) {
        e.preventDefault();
        
        const select = document.getElementById('producto_select');
        const option = select.options[select.selectedIndex];
        
        if (!option.value) {
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
        
        // Verificar stock disponible
        if (producto.stock <= 0) {
            mostrarAlerta('No hay stock disponible para este producto', 'error');
            return;
        }
        
        // Verificar si ya está en el carrito
        const existe = carrito.find(p => p.id === producto.id);
        if (existe) {
            if (existe.cantidad < existe.stock) {
                existe.cantidad++;
                mostrarAlerta('Cantidad actualizada en el carrito', 'success');
            } else {
                mostrarAlerta('No hay suficiente stock disponible', 'error');
                return;
            }
        } else {
            carrito.push(producto);
            mostrarAlerta('Producto agregado al carrito', 'success');
        }
        
        actualizarTabla();
        select.value = '';
    });
});function actualizarTabla() {
    const tbody = document.getElementById('tbody-productos');
    const btnProcesar = document.getElementById('btn-procesar');
    
    if (carrito.length === 0) {
        tbody.innerHTML = `
            <tr id="empty-row">
                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                    <p>No hay productos agregados</p>
                </td>
            </tr>
        `;
        btnProcesar.disabled = true;
        return;
    }
    
    btnProcesar.disabled = false;
    
    let html = '';
    carrito.forEach((producto, index) => {
        const subtotal = producto.precio * producto.cantidad;
        html += `
            <tr class="border-b hover:bg-slate-50">
                <td class="px-4 py-3">
                    <div class="flex flex-col">
                        <span class="font-medium">${producto.nombre}</span>
                        <span class="text-xs text-slate-500">Stock: ${producto.stock}</span>
                    </div>
                    <input type="hidden" name="productos[${index}][producto_id]" value="${producto.id}">
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center space-x-2">
                        <button type="button" onclick="cambiarCantidad(${index}, -1)" class="px-2 py-1 bg-slate-200 rounded hover:bg-slate-300">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" name="productos[${index}][cantidad]" value="${producto.cantidad}" 
                            min="1" max="${producto.stock}" 
                            onchange="actualizarCantidad(${index}, this.value)"
                            class="w-16 px-2 py-1 text-center border border-slate-300 rounded">
                        <button type="button" onclick="cambiarCantidad(${index}, 1)" class="px-2 py-1 bg-slate-200 rounded hover:bg-slate-300">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="px-4 py-3 text-right">
                    $${producto.precio.toFixed(2)}
                    <input type="hidden" name="productos[${index}][precio_unitario]" value="${producto.precio}">
                </td>
                <td class="px-4 py-3 text-right font-semibold">$${subtotal.toFixed(2)}</td>
                <td class="px-4 py-3 text-center">
                    <button type="button" onclick="eliminarProducto(${index})" 
                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    actualizarTotales();
}

function cambiarCantidad(index, cambio) {
    const producto = carrito[index];
    const nuevaCantidad = producto.cantidad + cambio;
    
    if (nuevaCantidad > 0 && nuevaCantidad <= producto.stock) {
        producto.cantidad = nuevaCantidad;
        actualizarTabla();
    }
}

function actualizarCantidad(index, valor) {
    const cantidad = parseInt(valor);
    const producto = carrito[index];
    
    if (cantidad > 0 && cantidad <= producto.stock) {
        producto.cantidad = cantidad;
        actualizarTabla();
    }
}

function eliminarProducto(index) {
    carrito.splice(index, 1);
    actualizarTabla();
}

function actualizarTotales() {
    const subtotal = carrito.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);
    const iva = subtotal * 0.15;
    const total = subtotal + iva;
    
    document.getElementById('subtotal-display').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('iva-display').textContent = '$' + iva.toFixed(2);
    document.getElementById('total-display').textContent = '$' + total.toFixed(2);
}

// Validar edad del cliente
document.getElementById('cliente_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const edad = parseInt(option.dataset.edad);
    
    if (edad < 18) {
        mostrarAlerta('⚠️ Advertencia: El cliente es menor de 18 años. No puede comprar licores.', 'warning');
        this.value = '';
    }
});

// Validar formulario antes de enviar
document.getElementById('form-venta').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        mostrarAlerta('⚠️ Debe agregar al menos un producto para procesar la venta', 'error');
        return false;
    }
    
    const clienteId = document.getElementById('cliente_id').value;
    const metodoPago = document.getElementById('metodo_pago').value;
    
    if (!clienteId) {
        e.preventDefault();
        mostrarAlerta('⚠️ Debe seleccionar un cliente', 'error');
        return false;
    }
    
    if (!metodoPago) {
        e.preventDefault();
        mostrarAlerta('⚠️ Debe seleccionar un método de pago', 'error');
        return false;
    }
    
    return true;
});

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const colores = {
        'success': 'bg-green-100 border-green-400 text-green-700',
        'error': 'bg-red-100 border-red-400 text-red-700',
        'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info': 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const iconos = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    const alerta = document.createElement('div');
    alerta.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded border ${colores[tipo] || colores.info} shadow-lg max-w-md animate-fade-in-down`;
    alerta.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconos[tipo] || iconos.info} mr-2"></i>
            <span>${mensaje}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alerta);
    
    setTimeout(() => {
        alerta.remove();
    }, 5000);
}
</script>
@endsection
