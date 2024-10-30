  <nav class="sidebar-nav">
      <ul class="nav in side-menu">
          <?php
          $post = Request::path();
          $uri_parts = explode('/', $post);
          $uri_tail = head($uri_parts);
          ?>

          @if ($permisos->contains('IdPermiso', 1))
              <li
                  class="current-page menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'administracion' ? 'active' : '' ?>">
                  {{-- <a href="javascript:void(0);"><i class="list-icon material-icons">storage</i> <span
                          class="hide-menu">Administración</span></a> --}}
                  <a href="javascript:void(0);"><i class='bx bx-layer list-icon material-icons fs-22'></i><span
                          class="hide-menu">Administración</span></a>
              @else
              <li
                  class="current-page menu-item-has-children d-none <?= isset($uri_tail) == 'administracion' ? 'active' : '' ?> ">
                  <a href="javascript:void(0);"><i class="list-icon material-icons">storage</i> <span
                          class="hide-menu">Administración</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 1))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Almacén</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 1))
                              <li><a href="{!! url('/administracion/almacen/productos') !!}">Productos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 2))
                              <li><a href="{!! url('/administracion/almacen/servicios') !!}">Servicios</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 3))
                              <li><a href="{!! url('/administracion/almacen/categorias') !!}">Categorías</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 4))
                              <li><a href="{!! url('/administracion/almacen/marcas') !!}">Marcas</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 5))
                              <li><a href="{!! url('/administracion/almacen/baja-productos') !!}">Baja de Productos</a></li>
                          @endif
                          <li><a href="{!! url('/administracion/almacen/traspasos') !!}">Traspasos</a></li>
                          {{-- @if ($subniveles->contains('IdSubNivel', 59))
                              <li><a href="{!! url('/administracion/almacen/grupos') !!}">Paquetes Mantenimiento</a></li>
                          @endif --}}

                          @if ($subniveles->contains('IdSubNivel', 60))
                              <li><a href="{!! url('/administracion/almacen/stock-articulos') !!}">Regularización de Inventario</a></li>
                          @endif
                          {{-- <li><a href="{!! url('/administracion/almacen/emparejar-stock') !!}">Regularizar Stock</a></li> --}}
                          {{-- <li><a href="{!! url('/administracion/almacen/emparejar-stock') !!}">Administrar Permisos</a></li> --}}
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 2))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Staff</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 6))
                              <li><a href="{!! url('/administracion/staff/clientes') !!}">Clientes</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 7))
                              <li><a href="{!! url('/administracion/staff/proveedores') !!}">Proveedores</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 8))
                              <li><a href="{!! url('/administracion/staff/operadores') !!}">Operador</a></li>
                          @endif
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 25))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Bancos</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 45))
                              <li><a href="{!! url('/administracion/bancos/cuentas-bancarias') !!}">Cuentas Bancarias</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 46))
                              <li><a href="{!! url('/administracion/bancos/tipo-cambio') !!}">Tipo de Cambio</a></li>
                          @endif
                      </ul>
                  </li>
              @endif
              @if ($subpermisos->contains('IdSubPermisos', 3))
                  <li><a href="{!! url('/administracion/usuarios') !!}">Usuarios</a>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 4))
                  <li><a href="{!! url('/administracion/permisos') !!}">Permisos</a>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 5))
                  <li><a href="{!! url('/administracion/sucursales') !!}">Sucursales</a>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 31))
                  <li><a href="{!! url('/administracion/gastos') !!}">Gastos</a>
                  </li>
              @endif
              {{-- Nuevo Items al menu Tareas Programadas --}}
              @if ($permisos->contains('IdUsuario', 1))
                  <li><a href="{!! url('/administracion/tareas-programadas') !!}">Tareas Programadas</a>
                  </li>
              @endif
              {{-- Fin --}}
              {{-- <li><a href="{!! url('/administracion/permisos-del-sistema') !!}">Permisos del Sistema</a></li> --}}
              @if ($subpermisos->contains('IdSubPermisos', 39))
                  <li><a href="{!! url('/administracion/planes-suscripcion') !!}">Planes de Suscripción</a></li>
              @endif
          </ul>
          </li>

          {{-- ------------------------------------------------ NUEVO CODIGO ------------------------------------------------------- --}}
          @if ($permisos->contains('IdPermiso', 8))
              <li
                  class="current-page menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'vehicular' ? 'active' : '' ?>">
                  <a href="javascript:void(0);"><i class='bx bxs-car-crash list-icon material-icons fs-22'></i><span
                          class="hide-menu">Vehicular</span></a>
              @else
              <li
                  class="current-page menu-item-has-children d-none <?= isset($uri_tail) == 'vehicular' ? 'active' : '' ?> ">
                  <a href="javascript:void(0);"><i class="list-icon material-icons">storage</i> <span
                          class="hide-menu">Vehicular</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 35))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Administración</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 66) && $subpermisos->contains('IdSubPermisos', 35))
                              <li><a href="{!! url('/vehicular/administracion/tipo') !!}">Tipo Vehículo</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 67))
                              <li><a href="{!! url('/vehicular/administracion/marca') !!}">Marca Vehículo</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 68))
                              <li><a href="{!! url('/vehicular/administracion/modelo') !!}">Modelo Vehículo</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 69))
                              <li><a href="{!! url('/vehicular/administracion/lista-vehiculos') !!}">Vehículo</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 70))
                              <li><a href="{!! url('/vehicular/administracion/operario') !!}">Mecánicos</a></li>
                          @endif
                          @if ($modulosSelect->contains('IdModulo', 5))
                              @if ($subniveles->contains('IdSubNivel', 71))
                                  <li><a href="{!! url('/vehicular/administracion/seguros-vehiculares') !!}">Compañia de Seguros</a></li>
                              @endif
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 72))
                              <li><a href="{!! url('/vehicular/administracion/paquetes') !!}">Paquetes Mantenimiento</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 80))
                              <li><a href="{!! url('vehicular/administracion/paquetes-promocionales') !!}">Paquetes Promocionales</a></li>
                          @endif
                      </ul>
                  </li>
              @endif
              @if ($subpermisos->contains('IdSubPermisos', 36))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Gestión de Taller</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 73))
                              <li><a href="{!! url('/vehicular/check-in') !!}">Check-List</a></li>
                          @endif
                          @if ($modulosSelect->contains('IdModulo', 7))
                              @if ($subniveles->contains('IdSubNivel', 74))
                                  <li><a href="{!! url('vehicular/consultas/cronograma-mantenimiento') !!}">Cronograma mantenimiento</a></li>
                              @endif
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 81))
                              <li><a href="{!! url('/vehicular/control-calidad') !!}">Control de Calidad</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 87))
                              <li><a href="{!! url('/vehicular/gestion-taller/monitoreo-atencion') !!}">Monitoreo de Atención</a></li>
                          @endif
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 37))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Consultas</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 65))
                              <li><a href="{!! url('/vehicular/consultas/atenciones-vehiculares') !!}">Histórico de Atenciones</a></li>
                          @endif
                      </ul>
                  </li>
              @endif
              @if ($subpermisos->contains('IdSubPermisos', 38))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Reportes</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 75))
                              <li><a href="{!! url('/vehicular/reportes/placa') !!}">Ventas x Vehículo</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 76))
                              <li><a href="{!! url('/vehicular/reportes/mecanico') !!}">Productividad x Mecánico</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 77))
                              <li><a href="{!! url('/vehicular/reportes/vehiculo') !!}">Listado de Vehículos</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 78))
                              <li><a href="{!! url('vehicular/reportes/ganancias-por-placa') !!}">Ganancias x Vehículo</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 83))
                              <li><a href="{!! url('vehicular/reportes/vehiculos-atendidos') !!}">Vehículos Atendidos</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 84))
                              <li><a href="{!! url('vehicular/reportes/tipo-atencion') !!}">Tipo Atención</a></li>
                          @endif
                      </ul>
                  </li>
              @endif
          </ul>
          </li>
          {{-- ---------------------------------------------------- FIN ------------------------------------------------------------- --}}
          @if ($permisos->contains('IdPermiso', 2))
              <li class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'operaciones' ? 'active' : '' ?>">
                  <a href="javascript:void(0);"><i class="list-icon material-icons">shopping_cart</i> <span
                          class="hide-menu">Operaciones</span></a>
              @else
              <li class="menu-item-has-children d-none"><a href="javascript:void(0);"><i
                          class="list-icon material-icons">shopping_cart</i> <span
                          class="hide-menu">Operaciones</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 6))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Ventas</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($modulosSelect->contains('IdModulo', 4))
                              @if ($subniveles->contains('IdSubNivel', 9))
                                  <li><a href="{!! url('/operaciones/ventas/realizar-venta') !!}">Boleta / Factura</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 10))
                                  <li><a href="{!! url('/operaciones/ventas/nota-credito-debito') !!}">Nota de Crédito</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 11))
                                  <li><a href="{!! url('/operaciones/ventas/guia-remision') !!}">Guía de Remitente</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 82))
                                  <li><a href="{!! url('/operaciones/ventas/anticipos') !!}">Anticipos</a></li>
                              @endif
                          @elseif($modulosSelect->contains('IdModulo', 1))
                              @if ($subniveles->contains('IdSubNivel', 9))
                                  <li><a href="{!! url('/operaciones/ventas/realizar-venta') !!} ">Boleta / Factura</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 82))
                                  <li><a href="{!! url('/operaciones/ventas/anticipos') !!}">Anticipos</a></li>
                              @endif
                          @endif
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 22))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Cotizacion</a>
                      <ul class="list-unstyled sub-menu">
                          @if ($subniveles->contains('IdSubNivel', 32))
                              <li><a href="{!! url('/operaciones/cotizacion/realizar-cotizacion') !!}">Crear Cotización</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 33))
                              <li><a href="{!! url('/operaciones/cotizacion/consultar-cotizacion') !!}">Consultar Cotización</a></li>
                          @endif
                      </ul>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 7))
                  <li><a href="{!! url('operaciones/compras/lista-compras') !!}">Compras</a></li>
              @endif
              @if ($subpermisos->contains('IdSubPermisos', 40))
                  <li><a href="{!! url('operaciones/ordenes-compra') !!}">Ordenes de Compra</a></li>
              @endif
          </ul>
          </li>
          @if ($permisos->contains('IdPermiso', 3))
              <li class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'caja' ? 'active' : '' ?>"><a
                      href="javascript:void(0);"><i class="list-icon material-icons">dvr</i> <span
                          class="hide-menu">Caja</span></a>
              @else
              <li class="menu-item-has-children d-none"><a href="javascript:void(0);"><i
                          class="list-icon material-icons">dvr</i> <span class="hide-menu">Caja</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 8))
                  <li><a href="{!! url('/caja/ingresos-egresos') !!}">Ingresos / Egresos</a></li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 9))
                  <li><a href="{!! url('/caja/cierre-caja') !!}">Apertura / Cierre Caja</a></li>
              @endif
          </ul>
          </li>
          @if ($modulosSelect->contains('IdModulo', 3))
              @if ($permisos->contains('IdPermiso', 4))
                  <li
                      class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'cobranzas' ? 'active' : '' ?>">
                      {{-- <a href="javascript:void(0);"><i class="list-icon material-icons">monetization_on</i> <span
                              class="hide-menu">Pagos/Cobranzas</span></a> --}}
                      <a href="javascript:void(0);"><i
                              class='bx bx-money-withdraw list-icon material-icons fs-22'></i><span
                              class="hide-menu">Pagos/Cobranzas</span></a>
                  @else
                  <li class="menu-item-has-children d-none"><a href="javascript:void(0);"><i
                              class="list-icon material-icons">monetization_on</i> <span
                              class="hide-menu">Pagos/Cobranzas</span></a>
              @endif
              <ul class="list-unstyled sub-menu">
                  @if ($subpermisos->contains('IdSubPermisos', 26))
                      <li><a href="{!! url('/cobranzas') !!}">Cobranzas a Clientes</a></li>
                  @endif

                  @if ($subpermisos->contains('IdSubPermisos', 27))
                      <li><a href="{!! url('/pagos') !!}">Pagos a Proveedores</a></li>
                  @endif
              </ul>
              </li>
          @endif
          @if ($permisos->contains('IdPermiso', 5))
              <li class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'consultas' ? 'active' : '' ?>"><a
                      href="javascript:void(0);"><i class="list-icon material-icons">assignment</i> <span
                          class="hide-menu">Consultas</span></a>
              @else
              <li class="menu-item-has-children d-none"><a href="javascript:void(0);"><i
                          class="list-icon material-icons">assignment</i> <span class="hide-menu">Consultas</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 10))
                  <li><a href="{!! url('/consultas/precios') !!}">Precios / Stock</a></li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 11))
                  <li><a href="{!! url('/consultas/clientes') !!}">Clientes</a></li>
              @endif

              @if ($modulosSelect->contains('IdModulo', 4))
                  @if ($subpermisos->contains('IdSubPermisos', 12))
                      <li><a href="{!! url('/consultas/ventas-boletas-facturas') !!}">Ventas: Boletas / Facturas</a></li>
                  @endif

                  {{-- @if ($subpermisos->contains('IdSubPermisos', 13))
                    <li><a href="{!! url('/consultas/compras-boletas-facturas') !!}">Compras: Boletas / Facturas</a></li>
						@endif --}}
                  @if ($subpermisos->contains('IdSubPermisos', 13))
                      <li><a href="{!! url('/consultas/compras-boletas-facturas') !!}">Compras / Orden Compra</a></li>
                  @endif

                  @if ($subpermisos->contains('IdSubPermisos', 14))
                      <li><a href="{!! url('/consultas/notas-credito-debito') !!}">Notas Créditos</a></li>
                  @endif

                  @if ($subpermisos->contains('IdSubPermisos', 15))
                      <li><a href="{!! url('/consultas/guias-remision') !!}">Guías Remisión</a></li>
                  @endif
              @elseif($modulosSelect->contains('IdModulo', 1))
                  @if ($subpermisos->contains('IdSubPermisos', 12))
                      <li><a href="{!! url('/consultas/ventas-boletas-facturas') !!}">Ventas: Boletas / Facturas</a></li>
                  @endif

                  @if ($subpermisos->contains('IdSubPermisos', 13))
                      <li><a href="{!! url('/consultas/compras-boletas-facturas') !!}">Compras: Boletas / Facturas</a></li>
                  @endif
              @endif

              {{-- @if ($subpermisos->contains('IdSubPermisos', 23))
                  <li><a href="{!! url('/consultas/atenciones-vehiculares') !!}">Atenciones Vehiculares</a></li>
              @endif --}}
          </ul>
          </li>
          @if ($permisos->contains('IdPermiso', 6))
              <li class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'reportes' ? 'active' : '' ?>">
                  {{-- <a href="javascript:void(0);"><i class="list-icon material-icons">assessment</i> <span
                          class="hide-menu">Reportes</span></a> --}}
                  <a href="javascript:void(0);"><i
                          class='bx bxs-bar-chart-alt-2 list-icon material-icons fs-22'></i><span
                          class="hide-menu">Reportes</span></a>
              @else
              <li class="menu-item-has-children d-none"><a href="javascript:void(0);"><i
                          class="list-icon material-icons">assessment</i> <span class="hide-menu">Reportes</span></a>
          @endif
          <ul class="list-unstyled sub-menu">
              @if ($subpermisos->contains('IdSubPermisos', 16))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Ventas</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 12))
                              <li><a href="{!! url('/reportes/ventas/vendedores') !!}">Vendedor</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 13))
                              <li><a href="{!! url('/reportes/ventas/productos') !!}">Producto/Servicios</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 14))
                              <li><a href="{!! url('/reportes/ventas/clientes') !!}">Cliente</a></li>
                          @endif
                          <!--<li><a href="../reportes/ventas/clientes-top">Cliente TOP</a></li>-->
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 17))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Compras</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 15))
                              <li><a href="{!! url('/reportes/compras/productos') !!}">Productos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 16))
                              <li><a href="{!! url('/reportes/compras/proveedores') !!}">Proveedores</a></li>
                          @endif
                          <li><a href="{!! url('/reportes/compras/ordenes-compra') !!}">Ordenes de Compra</a></li>
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 18))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Gerenciales</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 17))
                              <li><a href="{!! url('/reportes/gerenciales/mas-vendidos') !!}">Los más vendidos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 18))
                              <li><a href="{!! url('/reportes/gerenciales/compras-ventas') !!}">Compras y ventas</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 19))
                              <li><a href="{!! url('/reportes/gerenciales/ganancias') !!}">Ganancias</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 20))
                              <li><a href="{!! url('/reportes/gerenciales/clientes-top') !!}">Cliente TOP</li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 21))
                              <li><a href="{!! url('/reportes/gerenciales/fines-de-dia') !!}">Fines de día</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 43))
                              <li><a href="{!! url('/reportes/gerenciales/ingresos-egresos') !!}">Ingresos / Egresos</a></li>
                          @endif
                      </ul>
                  </li>
              @endif

              @if ($modulosSelect->contains('IdModulo', 4))
                  {{-- @if ($subpermisos->contains('IdSubPermisos', 19)) --}}
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Facturación</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 22))
                              <li><a href="{!! url('/reportes/facturacion/resumen-diario') !!}">Resumen Diario</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 23))
                              <li><a href="{!! url('/reportes/registro-ventas-electronicas') !!}">Registro Ventas Sunat</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 24))
                              <li><a href="{!! url('/reportes/facturacion/baja-documentos') !!}">Baja de Fact y NC Pendientes</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 25))
                              <li><a href="{!! url('/reportes/facturacion/facturas-pendientes') !!}">Facturas y NC Pendientes</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 38))
                              <li><a href="{!! url('/reportes/facturacion/guias') !!}">Guías Remitente Electrónicas</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 39))
                              <li><a href="{!! url('/reportes/facturacion/guias-remision-pendientes') !!}">Guías Remisión Pendientes</a></li>
                          @endif


                      </ul>
                  </li>
                  {{-- @endif --}}
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 20))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Almacén</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 26))
                              <li><a href="{!! url('/reportes/almacen/stock') !!}">Stock</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 85))
                              <li><a href="{!! url('/reportes/almacen/stock-por-fecha') !!}">Stock Histórico</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 27))
                              <li><a href="{!! url('/reportes/almacen/kardex') !!}">Kardex</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 44))
                              <li><a href="{!! url('/reportes/almacen/baja-productos') !!}">Baja de Productos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 56))
                              <li><a href="{!! url('/reportes/almacen/traspasos') !!}">Traspasos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 61))
                              <li><a href="{!! url('/reportes/almacen/regularizacion-inventario') !!}">Regularización de Inventario</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 79))
                              <li><a href="{!! url('/reporte/almacen/productos-eliminados') !!}">Productos Eliminados</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 86))
                              <li><a href="{!! url('/reportes/almacen/movimiento-inventario') !!}">Consolidado Mov. Inventario</a></li>
                          @endif
                      </ul>
                  </li>
              @endif


              @if ($modulosSelect->contains('IdModulo', 3))
                  {{-- @if ($subpermisos->contains('IdSubPermisos', 21)) --}}
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Cobranzas</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 28))
                              <li><a href="{!! url('/reportes/cobranzas/ventas-por-cobrar') !!}">Ventas por cobrar</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 29))
                              <li><a href="{!! url('/reportes/cobranzas/creditos-vencidos') !!}">Créditos vencidos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 30))
                              <li><a href="{!! url('/reportes/cobranzas/pagos-parciales') !!}">Cobros Parciales</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 30))
                              <li><a href="{!! url('/reportes/cobranzas/pagos-totales') !!}">Cobros Totales</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 31))
                              <li><a href="{!! url('/reportes/cobranzas/clientes-morosos') !!}">Clientes con Morosidad</a></li>
                          @endif
                      </ul>
                  </li>
                  {{-- @endif --}}
              @endif
              @if ($modulosSelect->contains('IdModulo', 3))
                  @if ($subpermisos->contains('IdSubPermisos', 28))
                      <li class="menu-item-has-children"><a href="javascript:void(0);">Pagos</a>
                          <ul class="list-unstyled sub-menu menuReporte">
                              @if ($subniveles->contains('IdSubNivel', 40))
                                  <li><a href="{!! url('/reportes/pagos/compras-por-pagar') !!}">Compras por pagar</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 41))
                                  <li><a href="{!! url('/reportes/pagos/pagos-parciales') !!}">Pagos Parciales</a></li>
                              @endif

                              @if ($subniveles->contains('IdSubNivel', 42))
                                  <li><a href="{!! url('/reportes/pagos/pagos-totales') !!}">Pagos Totales</a></li>
                              @endif
                          </ul>
                      </li>
                  @endif
              @endif
              {{-- @if ($subpermisos->contains('IdSubPermisos', 30))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Vehiculares</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 50))
                              <li><a href="{!! url('/reportes/vehiculares/placa') !!}">Placa</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 51))
                              <li><a href="{!! url('/reportes/vehiculares/mecanico') !!}">Productividad x Mecánico</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 54))
                              <li><a href="{!! url('/reportes/vehiculares/vehiculo') !!}">Vehículo</a></li>
                          @endif
                          @if ($subniveles->contains('IdSubNivel', 63))
                              <li><a href="{!! url('/reportes/vehiculares/ganancias') !!}">Ganancias x Placa</a></li>
                          @endif
                      </ul>
                  </li>
              @endif --}}

              @if ($subpermisos->contains('IdSubPermisos', 32))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Financieros</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 52))
                              <li><a href="{!! url('/reportes/financieros/gastos') !!}">Gastos</a></li>
                          @endif

                          @if ($subniveles->contains('IdSubNivel', 55))
                              <li><a href="{!! url('/reportes/financieros/bancos') !!}">Bancos</a></li>
                          @endif
                      </ul>
                  </li>
              @endif

              @if ($subpermisos->contains('IdSubPermisos', 33))
                  <li class="menu-item-has-children"><a href="javascript:void(0);">Cotización</a>
                      <ul class="list-unstyled sub-menu menuReporte">
                          @if ($subniveles->contains('IdSubNivel', 58))
                              <li><a href="{!! url('/reportes/cotizacion/amortizaciones') !!}">Amortizaciones</a></li>
                          @endif
                      </ul>
                  </li>
              @endif
          </ul>
          </li>
          {{-- @if ($permisos->contains('IdPermiso', 7))
              <li class="menu-item-has-children <?= isset($uri_tail) && $uri_tail == 'soporte' ? 'active' : '' ?>"><a
                      href="{!! url('/soporte') !!}"><i class="list-icon material-icons">settings</i> <span
                          class="hide-menu">Soporte</span></a>
              @else
              <li class="menu-item-has-children d-none"><a href="{!! url('/soporte') !!}"><i
                          class="list-icon material-icons">settings</i> <span class="hide-menu">Soporte</span></a>
          @endif
          </li> --}}
      </ul>
      <!-- /.side-menu -->
  </nav>
  <!-- /.sidebar-nav -->
