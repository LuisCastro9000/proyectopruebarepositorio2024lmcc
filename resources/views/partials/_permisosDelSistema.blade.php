 @foreach ($permisosDelSistema as $permiso)
     <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
         <section class="seccionPermisosUsuarios">
             <div class="custom-control custom-checkbox border-left--rojo">
                 @if (in_array($permiso->IdPermiso, $arrayPermisosHabilitados))
                     <input type="checkbox" checked
                         class="custom-control-input checkedPermisosSistema permiso subNivelCheckedPermiso-{{ $permiso->IdPermiso }}"
                         id="permiso-{{ $permiso->IdPermiso }}" value="{{ $permiso->IdPermiso }}" name="permisos[]"
                         onclick="activarYdesactivarCheckSubPermisoYsubNivel({{ $permiso->IdPermiso }})">
                 @else
                     <input type="checkbox"
                         class="custom-control-input checkedPermisosSistema permiso subNivelCheckedPermiso-{{ $permiso->IdPermiso }}"
                         id="permiso-{{ $permiso->IdPermiso }}" value="{{ $permiso->IdPermiso }}" name="permisos[]"
                         onclick="activarYdesactivarCheckSubPermisoYsubNivel({{ $permiso->IdPermiso }})">
                 @endif
                 <label class="custom-control-label check ml-3" for="permiso-{{ $permiso->IdPermiso }}">Menu
                     {{ $permiso->Descripcion }}</label>
             </div>
             <hr>
             @foreach ($permiso->SubPermisos as $subPermiso)
                 <section class="seccionSubPermisosUsuarios">
                     <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                         @if (in_array($subPermiso->IdSubPermiso, $arraySubPermisosHabilitados))
                             <input type="checkbox" checked
                                 class="custom-control-input checkPermiso-{{ $permiso->IdPermiso }} checkedPermisosSistema checkSubPermisos-{{ $permiso->IdPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $permiso->IdPermiso }} subNivelCheckedSubPermiso-{{ $subPermiso->IdSubPermiso }}"
                                 id="subPermiso-{{ $subPermiso->IdSubPermiso }}"
                                 value="{{ $subPermiso->IdSubPermiso }}" name="subPermisos[]"
                                 onclick="activarYdesactivarCheckPermisoYsubNivel({{ $subPermiso->IdSubPermiso }}, {{ $permiso->IdPermiso }})">
                         @else
                             <input type="checkbox"
                                 class="custom-control-input checkPermiso-{{ $permiso->IdPermiso }} checkedPermisosSistema checkSubPermisos-{{ $permiso->IdPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $permiso->IdPermiso }} subNivelCheckedSubPermiso-{{ $subPermiso->IdSubPermiso }}"
                                 id="subPermiso-{{ $subPermiso->IdSubPermiso }}"
                                 value="{{ $subPermiso->IdSubPermiso }}" name="subPermisos[]"
                                 onclick="activarYdesactivarCheckPermisoYsubNivel({{ $subPermiso->IdSubPermiso }}, {{ $permiso->IdPermiso }})">
                         @endif
                         <label class="custom-control-label"
                             for="subPermiso-{{ $subPermiso->IdSubPermiso }}">{{ $subPermiso->Descripcion }}</label>
                     </div>
                     @foreach ($subPermiso->SubNiveles as $subNivel)
                         <section class="seccionSubNivelesUsuarios">
                             <div class="custom-control custom-checkbox offset-4 offset-sm-2">
                                 @if (in_array($subNivel->IdSubNivel, $arraySubNivelesHabilitados))
                                     <input type="checkbox" checked
                                         class="custom-control-input checkPermiso-{{ $permiso->IdPermiso }} checkSubNivel-{{ $subPermiso->IdSubPermiso }} checkedPermisosSistema subPermisoCheckedSubNivel-{{ $subPermiso->IdSubPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $permiso->IdPermiso }}"
                                         onclick="activarYdesactivarCkeckSubPermisosYPermisos({{ $subNivel->IdSubNivel }}, {{ $subPermiso->IdSubPermiso }}, {{ $permiso->IdPermiso }})"
                                         id="subNivel-{{ $subNivel->IdSubNivel }}" value="{{ $subNivel->IdSubNivel }}"
                                         name="subNiveles[]">
                                 @else
                                     <input type="checkbox"
                                         class="custom-control-input checkPermiso-{{ $permiso->IdPermiso }} checkSubNivel-{{ $subPermiso->IdSubPermiso }} checkedPermisosSistema subPermisoCheckedSubNivel-{{ $subPermiso->IdSubPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $permiso->IdPermiso }}"
                                         onclick="activarYdesactivarCkeckSubPermisosYPermisos({{ $subNivel->IdSubNivel }}, {{ $subPermiso->IdSubPermiso }}, {{ $permiso->IdPermiso }})"
                                         id="subNivel-{{ $subNivel->IdSubNivel }}" value="{{ $subNivel->IdSubNivel }}"
                                         name="subNiveles[]">
                                 @endif
                                 <label class="custom-control-label"
                                     for="subNivel-{{ $subNivel->IdSubNivel }}">{{ $subNivel->DetalleNivel }}</label>
                             </div>
                         </section>
                     @endforeach
                 </section>
             @endforeach
         </section>
     </div>
 @endforeach
 <section aria-label="breadcrumb">
     <div class="breadcrumb d-flex justify-content-center">
         <h5 class="breadcrumb-item ">SECCIÓN PERMISOS BOTONES ADMINISTRATIVOS</h5>
     </div>
 </section>
 @foreach ($permisosBotonesDelSistema as $boton)
     <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
         <section class="seccionPermisosUsuarios">
             <div class="custom-control custom-checkbox border-left--rojo">
                 @if (in_array($boton->Id, $arrayPermisosBotonesHabilitados))
                     <input id="boton{{ $boton->Id }}" type="checkbox" checked
                         class="custom-control-input checkBoton" data-id-boton="{{ $boton->Id }}"
                         name="permisosBotonesChekeados[]" value="{{ $boton->Id }}">
                 @else
                     <input id="boton{{ $boton->Id }}" type="checkbox" class="custom-control-input checkBoton"
                         data-id-boton="{{ $boton->Id }}" name="permisosBotonesChekeados[]"
                         value="{{ $boton->Id }}">
                 @endif
                 <label class="custom-control-label check ml-3" for="boton{{ $boton->Id }}">BOTÓN
                     {{ $boton->Nombre }}</label>
             </div>
             <hr>
             @foreach ($boton->SubBotones as $subBoton)
                 <section class="seccionSubPermisosUsuarios">
                     <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                         @if (in_array($subBoton->Id, $arrayPermisosSubBotonesHabilitados))
                             <input id="subBoton{{ $subBoton->Id }}" type="checkbox" checked
                                 class="custom-control-input checkSubBoton checkSubBoton{{ $boton->Id }}"
                                 data-id-subboton="{{ $subBoton->Id }}" data-id-boton="{{ $boton->Id }}"
                                 name="permisosSubBotonesChekeados[]" value="{{ $subBoton->Id }}">
                         @else
                             <input id="subBoton{{ $subBoton->Id }}" type="checkbox"
                                 class="custom-control-input checkSubBoton checkSubBoton{{ $boton->Id }}"
                                 data-id-subboton="{{ $subBoton->Id }}" data-id-boton="{{ $boton->Id }}"
                                 name="permisosSubBotonesChekeados[]" value="{{ $subBoton->Id }}">
                         @endif
                         <label class="custom-control-label"
                             for="subBoton{{ $subBoton->Id }}">{{ $subBoton->Nombre }}</label>
                     </div>
                 </section>
             @endforeach
         </section>
     </div>
 @endforeach
