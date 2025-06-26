// 1. Importar librerías de terceros
import './bootstrap';
import Alpine from 'alpinejs';
import PerfectScrollbar from 'perfect-scrollbar'; // Importar la librería
import Chart from 'chart.js/auto'; // Importar Chart.js

// 2. Hacer las librerías globales para que los scripts de la plantilla las encuentren
//    (La plantilla Soft UI espera que estas existan en el objeto `window`)
window.Alpine = Alpine;
window.PerfectScrollbar = PerfectScrollbar;
window.Chart = Chart;

// 3. Importar los scripts de la plantilla en un orden lógico
//    (Plugins y utilidades primero, luego los que inicializan cosas)
import './soft-ui/nav-pills.js';
import './soft-ui/dropdown.js';
import './soft-ui/navbar-collapse.js';
import './soft-ui/sidenav-burger.js';
import './soft-ui/fixed-plugin.js';
import './soft-ui/navbar-sticky.js';
import './soft-ui/tooltips.js';

// Scripts que dibujan los gráficos (pueden ir aquí o al final)
import './soft-ui/chart-1.js';
import './soft-ui/chart-2.js';

// 4. Iniciar Alpine.js
Alpine.start();

// Mensaje de confirmación en la consola
console.log('✅ App.js inicializado correctamente con dependencias y scripts de Soft UI.');

