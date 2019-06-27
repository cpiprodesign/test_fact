@php $path = explode('/', request()->path()); $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:''; $path[2] = (array_key_exists(2,
$path)> 0)?$path[2]:''; $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp

<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            Menu
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    @if(in_array('dashboard', $vc_modules))
                        <li class="
                            {{ ($path[0] === 'dashboard')?'nav-active':'' }}
                            ">
                            <a class="nav-link" href="{{route('tenant.dashboard')}}">
                                <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('documents', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'documents')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'credit-notes')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'payments')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'pos')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'sale-notes')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'quotations')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'summaries')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'voided')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-receipt" aria-hidden="true"></i>
                            <span>Ingresos</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'documents' && $path[1] === 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.documents.create')}}">
                                    Venta
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'documents' && $path[1] != 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.documents.index')}}">
                                    Listados
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'credit-notes')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.credit_notes.index')}}">
                                    Notas de Crédito
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'payments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.payments.index')}}">
                                    Pagos Recibidos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'pos')?'nav-active':'' }}">
                                <a class="nav-link" target="_blank" href="{{route('tenant.pos.register')}}">
                                    Punto de Venta
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'sale-notes')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.sale_notes.index')}}">
                                    Nota de Venta
                                </a>
                            </li>
                            <li class="
                                {{ ($path[0] === 'summaries')?'nav-active':'' }}
                                ">
                                <a class="nav-link" href="{{route('tenant.summaries.index')}}">
                                    <span>Resúmenes</span>
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'quotations' && $path[1] != 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.quotations.index')}}">
                                        Cotizaciones
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    @endif
                    @if(in_array('purchases', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'purchases')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'expenses')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                            <span>Gastos</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'purchases' && $path[1] === 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.purchases.create')}}">
                                    Nueva compra
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'purchases' && $path[1] != 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.purchases.index')}}">
                                    Listado de compras
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'expenses')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.expenses.index')}}">
                                    Gastos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="nav-parent {{ (in_array($path[0], ['inventory', 'warehouses', 'items', 'price-list']) ||
                                                ($path[0] === 'reports' && in_array($path[1], ['kardex', 'inventory'])))?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-boxes" aria-hidden="true"></i>
                            <span>Inventario</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'items')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.items.index')}}">
                                    Productos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'price-list')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.price_list.index')}}">
                                    Lista de Precios
                                </a>
                            </li>
                            <li>
                                <a class="nav-link" href="#">
                                    Atributos/Variantes <span class="label label-info"> Pronto</span>
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'warehouses')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('warehouses.index')}}">Almacenes</a>
                            </li>
                            <li class="{{ ($path[0] === 'inventory')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('inventory.index')}}">Movimientos</a>
                            </li>
                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'kardex')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('reports.kardex.index')}}">
                                    Reporte Kardex
                                </a>
                            </li>
                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'inventory')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('reports.inventory.index')}}">
                                    Valor de Inventario
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="
                        {{ ($path[0] === 'box')?'nav-active':'' }}
                        ">
                        <a class="nav-link" href="{{route('tenant.box.index')}}">
                            <i class="fas fa-cash-register" aria-hidden="true"></i>
                            <span>Control de Caja</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="fas fa-comment-dollar" aria-hidden="true"></i>
                            <span>Contable </span><span class="label label-info"> Pronto</span>
                        </a>
                    </li>
                    <li class="
                        {{ ($path[0] === 'accounts')?'nav-active':'' }}
                        ">
                        <a class="nav-link" href="{{route('tenant.accounts.index')}}">
                            <i class="fas fa-university" aria-hidden="true"></i>
                            <span>Bancos</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="fas fa-store" aria-hidden="true"></i>
                            <span>Tienda Online </span><span class="label label-info"> Pronto</span>
                        </a>
                    </li>
                    @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['users', 'persons'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <span>Contactos</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'users')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.users.index')}}">
                                    Usuarios
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}">
                                    Proveedores
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'customers'])}}">
                                    Clientes
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif @if(in_array('advanced', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'retentions')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'dispatches')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-file-alt" aria-hidden="true"></i>
                            <span>Otros comprobantes</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'dispatches')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.dispatches.index')}}">
                                    Guías de remisión
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'retentions')?'nav-active':'' }}">
                                <a class="nav-link" href="#">
                                    Retenciones <span class="label label-info"> Pronto</span>
                                </a>
                            </li>
                            <li class="#">
                                <a class="nav-link" href="#">
                                    Percepciones <span class="label label-info"> Pronto</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if(in_array('reports', $vc_modules))
                        <li class="nav-parent {{  (($path[0] === 'reports') && in_array($path[1], ['', 'purchases', 'expenses'])) ? 'nav-active nav-expanded' : ''}}">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-area" aria-hidden="true"></i>
                                <span>Reportes</span>
                            </a>
                            <ul class="nav nav-children" style="">
                                <li class="{{(($path[0] === 'reports') && ($path[1] === '')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.index')}}">
                                        Ventas
                                    </a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] === 'purchases')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.purchases.index')}}">
                                        Compras
                                    </a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] === 'expenses')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.expenses.index')}}">
                                        Gastos
                                    </a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] === 'expenses')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.customer.index')}}">
                                        Ventas por Cliente
                                    </a>
                                </li>
                                {{-- <li class="{{(($path[0] === 'reports') && ($path[1] === 'kardex')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.kardex.index')}}">
                                        Kardex
                                    </a>
                                </li> --}}
                                {{-- <li class="{{(($path[0] === 'reports') && ($path[1] == 'inventories')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.reports.inventories.index')}}">
                                        Inventarios
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                    @endif @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['companies', 'catalogs', 'advanced', 'establishments', 'inventories'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cogs" aria-hidden="true"></i>
                            <span>Configuracion</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'companies')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.companies.create')}}">
                                    Empresa
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'catalogs')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Unidades
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'catalogs')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Categorías
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'catalogs')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Marca
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'catalogs')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Monedas
                                </a>
                            </li>
                            {{-- <li class="{{ ($path[0] === 'establishments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.establishments.index')}}">
                                    Series
                                </a>
                            </li> --}}
                            <li class="{{ ($path[0] === 'establishments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.establishments.index')}}">
                                    Establecimientos
                                </a>
                            </li>
                            <li class="{{($path[0] === 'inventories' && $path[1] === 'configuration') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.inventories.configuration.index')}}">Inventarios</a>
                            </li>
                            <li class="{{($path[0] === 'configuration' && $path[1] === 'documents') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.documents.configuarion')}}">Documentos</a>
                            </li>
                            <li class="{{ ($path[0] === 'advanced')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.advanced.index')}}">
                                    Avanzado
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>