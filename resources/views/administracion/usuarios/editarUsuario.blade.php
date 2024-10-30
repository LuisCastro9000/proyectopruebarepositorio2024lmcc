  @extends('layouts.app')
  @section('title', 'Editar Usuario')
  @section('content')
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"
          rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Editar Usuario</h6>
              </div>
              <!-- /.page-title-left -->
          </div>
          @if (session('error'))
              <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('error') }}
              </div>
          @endif
      </div>
      <!-- /.container -->
      <!-- =================================== -->
      <!-- Different data widgets ============ -->
      <!-- =================================== -->
      <div class="container">
          <div class="widget-list">
              <div class="row">
                  <div class="col-md-12 widget-holder">
                      <div class="widget-bg">
                          <div class="widget-body clearfix">
                              {!! Form::open([
                                  'url' => '/administracion/usuarios/' . $usuario->IdUsuario,
                                  'method' => 'PUT',
                                  'files' => true,
                                  'class' => 'formularioConFirma',
                              ]) !!}
                              {{ csrf_field() }}

                              {{-- SECCION DATOS DE USUARIO --}}
                              <fieldset class="fieldset fieldset--bordeCeleste">
                                  <legend class="legend legend--colorNegro">Datos de Usuario:
                                  </legend>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Nombres" type="text"
                                                  name="nombre" value="{{ $usuario->Nombre }}">
                                              <label for="nombre">Nombres</label>
                                              <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <select class="form-control" name="sucursal">
                                                  @if ($usuarioSelect->IdOperador == 1)
                                                      @foreach ($sucursales as $sucursal)
                                                          @if ($sucursal->CodigoCliente == null || $sucursal->CodigoCliente == $usuario->CodigoCliente)
                                                              @if ($usuario->IdSucursal == $sucursal->IdSucursal)
                                                                  <option selected value="{{ $sucursal->IdSucursal }}">
                                                                      {{ $sucursal->Nombre }}</option>
                                                              @else
                                                                  <option value="{{ $sucursal->IdSucursal }}">
                                                                      {{ $sucursal->Nombre }}</option>
                                                              @endif
                                                          @endif
                                                      @endforeach
                                                  @else
                                                      @foreach ($sucursales as $sucursal)
                                                          @if ($usuario->IdSucursal == $sucursal->IdSucursal)
                                                              <option selected value="{{ $sucursal->IdSucursal }}">
                                                                  {{ $sucursal->Nombre }}</option>
                                                          @else
                                                              <option value="{{ $sucursal->IdSucursal }}">
                                                                  {{ $sucursal->Nombre }}</option>
                                                          @endif
                                                      @endforeach
                                                  @endif
                                              </select>
                                              <label for="sucursal">Sucursal</label>
                                          </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              @if ($usuarioSelect->IdOperador == 1)
                                                  <select class="form-control" name="operador">
                                                      <option value="2">Administrador</option>
                                                  </select>
                                              @else
                                                  <select class="form-control" name="operador">
                                                      @foreach ($operadores as $operador)
                                                          @if ($usuario->IdOperador == $operador->IdOperador)
                                                              <option selected value="{{ $operador->IdOperador }}">
                                                                  {{ $operador->Rol }}</option>
                                                          @else
                                                              <option value="{{ $operador->IdOperador }}">
                                                                  {{ $operador->Rol }}
                                                              </option>
                                                          @endif
                                                      @endforeach
                                                  </select>
                                              @endif
                                              <label for="rol">Operador</label>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="DNI" type="text" name="dni"
                                                  value="{{ $usuario->DNI }}">
                                              <label for="dni">DNI</label>
                                              <span class="text-danger font-size">{{ $errors->first('dni') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="email" type="text" name="email"
                                                  value="{{ $usuario->Email }}">
                                              <label for="email">Email</label>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="telefono" type="text"
                                                  name="telefono" value="{{ $usuario->Telefono }}">
                                              <label for="telefono">Teléfono</label>
                                              <span class="text-danger font-size">{{ $errors->first('telefono') }}</span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Direccion" type="text"
                                                  name="direccion" value="{{ $usuario->Direccion }}">
                                              <label for="login">Direccion</label>
                                          </div>
                                          <input class="form-control" hidden type="text" name="loginHide"
                                              value="{{ $usuario->Login }}">
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              {{-- <input id="inputLogin" class="form-control" placeholder="Login" type="text"
                                                name="login" value="{{ $usuario->Login }}" oninput="validarLogin(this)"
                                                onclick="posicionarFocus(this)">
                                            <input hidden class="form-control" type="text" name="formatoLogin"
                                                value="{{ $loginUser }}"> --}}
                                              @if ($usuarioSelect->IdOperador == 1)
                                                  <input class="form-control" placeholder="Login" type="text"
                                                      name="login" value="{{ $usuario->Login }}">
                                              @else
                                                  @if ($usuarioSelect->FechaCreacion >= '2023-01-01')
                                                      <input id="inputLogin" class="form-control" type="text"
                                                          name="login" oninput="validarLogin(this)"
                                                          value="{{ $usuario->Login }}" onclick="posicionarFocus(this)"
                                                          data-toggle="tooltip" data-placement="top"
                                                          title="Ingrese solo nombre de Usuario">
                                                      <input hidden class="form-control" type="text"
                                                          name="formatoLogin" value="{{ $loginUser }}">
                                                  @else
                                                      <input class="form-control" placeholder="Login" type="text"
                                                          name="login" value="{{ $usuario->Login }}">
                                                  @endif
                                              @endif
                                              <label for="login">Login</label>
                                          </div>
                                          <input class="form-control" hidden type="text" name="loginHide"
                                              value="{{ $usuario->Login }}">
                                      </div>
                                      @if ($usuarioSelect->IdOperador == 1)
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <select class="form-control" name="selectPlanSuscripcionActual">
                                                      @foreach ($planesSuscripcion as $plan)
                                                          @if ($datosEmpresa->IdPlanSuscripcion == $plan->IdPlanSuscripcion)
                                                              <option value="{{ $plan->IdPlanSuscripcion }}" selected>
                                                                  {{ $plan->Nombre }}
                                                              </option>
                                                          @else
                                                              <option value="{{ $plan->IdPlanSuscripcion }}">
                                                                  {{ $plan->Nombre }}
                                                              </option>
                                                          @endif
                                                      @endforeach
                                                  </select>
                                                  <label for="selectPlanSuscripcion">PLANES DE SUSCRIPCIÓN</label>
                                              </div>
                                          </div>
                                      @endif
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <select class="form-control" name="selectEstado">
                                                  @if ($usuario->Estado == 'E')
                                                      <option value="1" selected>HABILITADO</option>
                                                      <option value="2">DESHABILITADO</option>
                                                  @elseif($usuario->Estado == 'D' || $usuario->Estado == 'Suscripcion Caducada')
                                                      <option value="1">HABILITADO</option>
                                                      <option value="2" selected>DESHABILITADO</option>
                                                  @endif
                                              </select>
                                              <label for="login">Estado</label>
                                          </div>
                                      </div>


                                      @if ($usuarioSelect->IdOperador == 1)
                                          {{-- Select activar Sucursal --}}
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <select class="selectpicker form-control" name="selectActivarSucursal[]"
                                                      multiple="multiple" data-style="btn btn-primary">
                                                      @foreach ($sucursales as $sucursal)
                                                          @if ($sucursal->CodigoCliente == $usuario->CodigoCliente)
                                                              @if ($sucursal->Estado == 'E')
                                                                  <option value="{{ $sucursal->IdSucursal }}" selected>
                                                                      {{ $sucursal->Nombre }}</option>
                                                              @else
                                                                  <option value="{{ $sucursal->IdSucursal }}">
                                                                      {{ $sucursal->Nombre }}</option>
                                                              @endif
                                                          @endif
                                                      @endforeach
                                                  </select>
                                                  <label for="selectActivarSucursal">Habilitar Sucursal</label>
                                              </div>
                                          </div>
                                          {{-- fin --}}
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <input class="form-control" placeholder="Total Usuarios" type="number"
                                                      min="1" value="{{ $usuario->TotalUsuarios }}"
                                                      name="totalUsuarios">
                                                  <label for="totalUsuarios">Total Usuarios</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <input class="form-control" placeholder="Total Sucursales"
                                                      type="number" min="1"
                                                      value="{{ $usuario->TotalSucursales }}" name="totalSucursales">
                                                  <label for="totalSucursales">Total Sucursales</label>
                                              </div>
                                          </div>
                                      @endif

                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <select class="form-control" name="rubro">
                                                  @foreach ($rubros as $rubro)
                                                      @if ($datosEmpresa->IdRubro == $rubro->IdRubro)
                                                          <option value="{{ $rubro->IdRubro }}" selected>
                                                              {{ $rubro->Descripcion }}</option>
                                                      @else
                                                          <option value="{{ $rubro->IdRubro }}">{{ $rubro->Descripcion }}
                                                          </option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                              <label for="rubro">Rubro</label>
                                          </div>
                                      </div>
                                  </div>
                              </fieldset>
                              {{-- SECCION RESTRICCIONES --}}
                              @if ($usuarioSelect->IdOperador != 1)
                                  <x-fieldset :legend="'Restricciones'" :legendClass="'px-2'">
                                      <div class="row">
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <input class="form-control" type="text" name="descuentoMaximoSoles"
                                                      data-toggle="validarOnInput" data-numero-entero="true"
                                                      data-maximo-digitos="4"
                                                      value="{{ $usuario->DescuentoMaximoSoles }}">
                                                  <label for="descuentoMaximoSoles">Descuento máximo X items (S/)</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <input class="form-control" type="text"
                                                      name="descuentoMaximoDolares" data-toggle="validarOnInput"
                                                      data-numero-entero="true" data-maximo-digitos="4"
                                                      value="{{ $usuario->DescuentoMaximoDolares }}">
                                                  <label for="descuentoMaximoDolares">Descuento máximo X items ($)</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="operacionGratuita">Operacion
                                                      gratuita</label><br>
                                                  <input type="checkbox" name="operacionGratuita"
                                                      {{ $usuario->OpcionOperacionGratuita == 1 ? 'checked' : '' }}><span
                                                      class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                      Desactivar</span>
                                              </div>
                                          </div>
                                      </div>
                                  </x-fieldset>
                              @endif
                              {{-- FIN --}}
                              {{-- SECCION HABILITAR PERMISOS --}}
                              <fieldset class="fieldset fieldset--bordeCeleste">
                                  <legend class="legend legend--colorNegro">Habilitar Permisos:
                                  </legend>
                                  <div class="row">
                                      @if ($usuarioSelect->IdOperador == 1 || $usuarioSelect->EditarPrecio == 1)
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="editPrecio">Editar Precio</label><br>
                                                  @if ($usuario->EditarPrecio == 1)
                                                      <input type="checkbox" name="editPrecio" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="editPrecio"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                      @endif
                                      @if ($usuarioSelect->IdOperador == 1)
                                          <div class="col-md-3">
                                              <label for="codigoProducto">Ventas Soles/Dólares</label>
                                              <div class="form-group">
                                                  @if ($datosEmpresa->VentaSolesDolares == 1)
                                                      <input type="checkbox" name="ventaSolesDolares" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="ventaSolesDolares"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="exonerar">Exoneración</label><br>
                                                  @if ($datosEmpresa->Exonerado > 0)
                                                      <input type="checkbox" name="exonerar" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Facturación
                                                          Exonerado</span>
                                                  @else
                                                      <input type="checkbox" name="exonerar"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Facturación
                                                          Exonerado</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="codigoProducto">Codigo Producto</label><br>
                                                  @if ($usuario->CodigoProducto == 1)
                                                      <input type="checkbox" name="codigoProducto" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Enlazar Codigo de
                                                          Barra</span>
                                                  @else
                                                      <input type="checkbox" name="codigoProducto"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Enlazar Codigo de
                                                          Barra</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="codigoProducto">Archivo PLE</label><br>
                                                  @if ($datosEmpresa->ArchivoPLE == 1)
                                                      <input type="checkbox" name="archivoPLE" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="archivoPLE"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="editPrecio">Activar Paquete Promocional</label><br>
                                                  @if ($usuario->ActivarPaquetePromo == 1)
                                                      <input type="checkbox" name="activarPaquetePromo" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="activarPaquetePromo"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="editPrecio">Activar Importar Excel Clientes</label><br>
                                                  @if ($usuario->OpcionImportarExcel == 1)
                                                      <input type="checkbox" name="activarImportacionExcelClientes"
                                                          checked><span class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar
                                                          /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="activarImportacionExcelClientes"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <label for="codigoProducto">Anticipos</label>
                                              <div class="form-group">
                                                  @if ($datosEmpresa->Anticipos == 1)
                                                      <input type="checkbox" name="anticipos" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="anticipos"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="checkActivarPrecioSinIgv">Precio Sin IGV-Vista
                                                      Cotización</label><br>
                                                  @if ($usuario->OpcionPrecioSinIgv == 1)
                                                      <input type="checkbox" name="checkActivarPrecioSinIgv"
                                                          value="chekeado" checked><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar
                                                          /
                                                          Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="checkActivarPrecioSinIgv"
                                                          value="chekeado"><span
                                                          class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar
                                                          /
                                                          Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                      @endif
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <label for="reestablecerContra">Reestablecer Contraseña</label><br>
                                              <input type="checkbox" name="reestablecerContra"><span
                                                  class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                  Desactivar</span>
                                          </div>
                                          <input class="form-control" placeholder="contrasena" hidden type="text"
                                              name="contrasena" value="{{ $usuario->Password }}">
                                      </div>
                                      {{-- Fin --}}
                                  </div>
                                  @if ($usuarioSelect->IdOperador == 1)
                                      <div class="row">
                                          <div class="col-sm-3 mb-3">
                                              <h5 class="box-title mr-b-0">Facturación
                                                  {{ $usuarioSelect->OpcionFactura }}
                                              </h5>
                                              <div class="form-group">
                                                  <div class="radiobox">
                                                      <label>
                                                          <input type="radio" name="radioOpcion" value="0"
                                                              {{ $usuario->OpcionFactura == 0 ? 'checked' : '' }}>
                                                          <span class="label-text">Desactivado</span>
                                                      </label>
                                                  </div>
                                                  <div class="radiobox radio-success">
                                                      <label>
                                                          <input type="radio" name="radioOpcion" value="1"
                                                              {{ $usuario->OpcionFactura == 1 ? 'checked' : '' }}>
                                                          <span class="label-text">Con Sunat</span>
                                                      </label>
                                                  </div>
                                                  <div class="radiobox radio-info">
                                                      <label>
                                                          <input type="radio" name="radioOpcion" value="2"
                                                              {{ $usuario->OpcionFactura == 2 ? 'checked' : '' }}>
                                                          <span class="label-text">Con OSE</span>
                                                      </label>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Nuevo codigo asignar modulos --}}
                                          <div class="col-md-8">
                                              <div class="form-group">
                                                  <label for="modulos">Módulos</label>
                                                  <select class="selectpicker form-control" name="modulos[]"
                                                      multiple="multiple" data-style="btn btn-primary">
                                                      @foreach ($modulosDelSistema as $modulo)
                                                          @if (in_array($modulo->IdModulo, $modulosDeUsuario))
                                                              <option selected value="{{ $modulo->IdModulo }}">
                                                                  {{ $modulo->IdModulo }} - {{ $modulo->Descripcion }}
                                                              </option>
                                                          @else
                                                              <option value="{{ $modulo->IdModulo }}">
                                                                  {{ $modulo->IdModulo }}
                                                                  - {{ $modulo->Descripcion }}</option>
                                                          @endif
                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                          {{-- fin --}}
                                      </div>
                                  @endif
                              </fieldset>

                              <input type="text" hidden name="IdOperadorUsuario"
                                  value="{{ $usuarioSelect->IdOperador }}">
                              <input type="text" hidden name="codigoCliente" value="{{ $usuario->CodigoCliente }}">
                              <input type="text" hidden name="numeroOrden" value="{{ $usuario->Orden }}">

                              {{-- SECCION SUSCRIPCION --}}
                              <input type="text" hidden name="inputPlanSuscripcionAnterior"
                                  value="{{ $datosEmpresa->IdPlanSuscripcion }}">
                              @if ($usuarioSelect->IdOperador == 1)
                                  <fieldset class="fieldset fieldset--bordeCeleste">
                                      <legend class="legend legend--colorNegro">Datos suscripcion:
                                      </legend>
                                      <div class="row">
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <select id="plan" class="form-control" name="plan">
                                                      <option value="1">Mensual</option>
                                                      <option value="2">Semestral</option>
                                                      <option value="3">Anual</option>
                                                  </select>
                                                  <label for="sucursal">Plan</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <div class="input-group">
                                                      <input id="datepicker2" type="text"
                                                          data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                          class="form-control datepicker" name="fechaContrato"
                                                          value="{{ empty($datosSuscripcion->FechaFinalContrato) ? '' : date('d/m/Y', strtotime($datosSuscripcion->FechaFinalContrato)) }}">
                                                      <div class="input-group-append">
                                                          <div class="input-group-text"><i
                                                                  class="list-icon material-icons">date_range</i></div>
                                                      </div>
                                                  </div>
                                                  <small class="text-muted"><strong>Fecha Fin Contrato</strong></small>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <div class="input-group">
                                                      <input id="datepicker2" type="text"
                                                          data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                          class="form-control datepicker" name="fechaCDT"
                                                          value="{{ empty($datosSuscripcion->FechaFinalCDT) ? '' : date('d/m/Y', strtotime($datosSuscripcion->FechaFinalCDT)) }}">
                                                      <div class="input-group-append">
                                                          <div class="input-group-text"><i
                                                                  class="list-icon material-icons">date_range</i></div>
                                                      </div>
                                                  </div>
                                                  <small class="text-muted"><strong>Fecha Fin CDT</strong></small>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <input class="form-control" type="number" min="1"
                                                      step="any" name="montoPago"
                                                      value="{{ $datosSuscripcion->MontoPago ?? '' }}">
                                                  <label for="montoPago">Monto de Pago</label>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <input class="form-control" type="number" min="1"
                                                      name="bloqueo" value="{{ $datosSuscripcion->Bloqueo ?? '' }}">
                                                  <label for="bloqueo">Días Bloqueo</label>
                                              </div>
                                          </div>
                                      </div>
                                      <hr>
                                      @foreach ($suscripcionesSucursales as $suscripcion)
                                          <div id="accordion" role="tablist">
                                              <div class="card">
                                                  <div class="card-header" role="tab"
                                                      id="heading-{{ $suscripcion->IdSucursal }}">
                                                      <b class="mb-0">
                                                          <div class="form-group" type="checkbox">
                                                              <input type="checkbox" name="checkSuscripcion[]"
                                                                  class="fs-28 mr-2"
                                                                  data-target="#collapse-{{ $suscripcion->IdSucursal }}"
                                                                  data-toggle="collapse"
                                                                  value="{{ $suscripcion->IdSucursal }}">Editar
                                                              Suscripción: <b class="fs-16">
                                                                  {{ $suscripcion->NombreSucursal }}
                                                              </b>
                                                          </div>
                                                      </b>
                                                  </div>
                                                  <div id="collapse-{{ $suscripcion->IdSucursal }}" class="collapse"
                                                      role="tabpanel"
                                                      aria-labelledby="heading-{{ $suscripcion->IdSucursal }}">

                                                      <div class="card-body">
                                                          <div class="row">
                                                              <div class="col-md-4">
                                                                  <div class="form-group">
                                                                      <select id="plan-{{ $suscripcion->IdSucursal }}"
                                                                          class="form-control"
                                                                          name="plan-{{ $suscripcion->IdSucursal }}">
                                                                          <option value="1">Mensual</option>
                                                                          <option value="2">Semestral</option>
                                                                          <option value="3">Anual</option>
                                                                      </select>
                                                                      <label for="sucursal">Plan</label>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-4">
                                                                  <div class="form-group">
                                                                      <div class="input-group">
                                                                          <input id="datepicker2" type="text"
                                                                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                              class="form-control datepicker"
                                                                              name="fechaContrato-{{ $suscripcion->IdSucursal }}"
                                                                              value="{{ empty($suscripcion->FechaFinalContrato) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalContrato)) }}">
                                                                          <div class="input-group-append">
                                                                              <div class="input-group-text"><i
                                                                                      class="list-icon material-icons">date_range</i>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                      <small class="text-muted"><strong>Fecha Fin
                                                                              Contrato</strong></small>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-4">
                                                                  <div class="form-group">
                                                                      <div class="input-group">
                                                                          <input id="datepicker2" type="text"
                                                                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                              class="form-control datepicker"
                                                                              name="fechaCDT-{{ $suscripcion->IdSucursal }}"
                                                                              value="{{ empty($suscripcion->FechaFinalCDT) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalCDT)) }}">
                                                                          <div class="input-group-append">
                                                                              <div class="input-group-text"><i
                                                                                      class="list-icon material-icons">date_range</i>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                      <small class="text-muted"><strong>Fecha Fin
                                                                              CDT</strong></small>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-6">
                                                                  <div class="form-group">
                                                                      <input class="form-control" type="number"
                                                                          min="1" step="any"
                                                                          name="montoPago-{{ $suscripcion->IdSucursal }}"
                                                                          value="{{ $suscripcion->MontoPago ?? '' }}">
                                                                      <label for="montoPago">Monto de Pago</label>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-6">
                                                                  <div class="form-group">
                                                                      <input class="form-control" type="number"
                                                                          min="1"
                                                                          name="bloqueo-{{ $suscripcion->IdSucursal }}"
                                                                          value="{{ $suscripcion->Bloqueo ?? '' }}">
                                                                      <label for="bloqueo">Días Bloqueo</label>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      @endforeach
                                  </fieldset>
                              @endif
                              {{-- FIN --}}

                              {{-- SECCION CARGAR FOTO Y FIRMA --}}
                              @if ($usuarioSelect->IdOperador != 1)
                                  <fieldset class="fieldset fieldset--bordeCeleste">
                                      <legend class="legend legend--colorNegro">Cargar Foto:
                                      </legend>
                                      <div class="row">
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <div class="fileUpload btnCambiarFotoPerfil">
                                                      <i class="list-icon material-icons">image</i><span
                                                          class="hide-menu">Cargar
                                                          Foto</span>
                                                      <input id="archivo" class="upload btn" type="file"
                                                          accept=".png, .jpg, .jpeg, .gif" name="foto">
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <img id="imgPrevia" src="" alt="Vista de Foto"
                                                      width="100%" />
                                              </div>
                                          </div>
                                      </div>
                                  </fieldset>
                                  <br>
                                  @if ($usuario->ImagenFirma != null)
                                      <section class="card rounded-0">
                                          <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                                              Firma
                                          </div>
                                          <div class="card-body">
                                              <input type="hidden" name="inputImagenFirmaAnterior"
                                                  value="{{ $usuario->ImagenFirma }}">
                                              <article class="p-4">
                                                  <div class="m-auto" style="width:100px; height:75px">
                                                      @if (
                                                          !empty($usuario->ImagenFirma) &&
                                                              !str_contains($usuario->ImagenFirma, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                                          <img src="{{ str_contains($usuario->ImagenFirma, config('variablesGlobales.urlDominioAmazonS3'))
                                                              ? $usuario->ImagenFirma
                                                              : config('variablesGlobales.urlDominioAmazonS3') . $usuario->ImagenFirma }}"
                                                              alt="Imagen Firma" style="width:100%; height:100%">
                                                      @endif
                                                  </div>
                                              </article>
                                          </div>
                                      </section>
                                  @endif
                                  <section class="col-12 mt-4">
                                      @include('lienzoFirma.lienzoFirma')
                                  </section>
                                  {{-- Fin --}}
                              @endif
                              <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                                  <button class="btn btn-primary" type="submit">Actualizar</button>
                                  <a href="../../usuarios"><button class="btn btn-outline-default"
                                          type="button">Cancelar</button></a>
                              </div>
                              {!! Form::close() !!}
                          </div>
                          <!-- /.widget-body -->
                      </div>
                      <!-- /.widget-bg -->
                  </div>
                  <!-- /.widget-holder -->
              </div>
              <!-- /.row -->
          </div>
          <!-- /.widget-list -->
      </div>
      <!-- /.container -->
  @stop

  @section('scripts')
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

      <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
      <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
      <script src="{{ asset('assets/js/lienzoFirma/lienzoFirma.js?v=' . time()) }}"></script>
      <script>
          $("#archivo").change(function(e) {
              addImage(e);
          });

          function addImage(e) {
              var file = e.target.files[0],
                  imageType = /image.*/;

              if (!file.type.match(imageType)) return;

              var reader = new FileReader();
              reader.onload = fileOnload;
              reader.readAsDataURL(file);
          }

          function fileOnload(e) {
              var result = e.target.result;
              $("#imgPrevia").attr("src", result);
          }
      </script>


      <script>
          const login = @json($loginUser);
          const regExp = @json($expreReg);

          function validarLogin(e) {
              if (!e.value.match(regExp)) {
                  e.value = login;
                  e.setSelectionRange(0, 0);
              }
          }

          function posicionarFocus(e) {
              const inputLogin = e.value;
              const tamañoTexto = login.length;
              if (inputLogin.length <= tamañoTexto) {
                  e.setSelectionRange(0, 0);
              }
          }
      </script>
      <script>
          $(function() {
              const datosSuscripcion = @json($datosSuscripcion);
              $('#plan option[value=' + datosSuscripcion.Plan + ']').prop('selected', true);

              const suscripcionesSucursales = @json($suscripcionesSucursales);
              suscripcionesSucursales.forEach(element => {
                  $('#plan-' + element.IdSucursal + ' option[value=' + element.Plan + ']').prop('selected',
                      true);
                  console.log(element);
              });
          })
      </script>

  @stop
