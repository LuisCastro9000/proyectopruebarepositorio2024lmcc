  @extends('layouts.app')
  @section('title', 'Soporte')
  @section('content')
      <div class="container">
          <section
              class="jumbotron jumbotron-fluid p-4 d-flex justify-content-center justify-content-sm-between flex-wrap align-items-center  flex-column flex-md-row my-4 rounded">
              <h6>Área de Soporte</h6>
              <article>
                  <a href="{{ route('registro-pago.create') }}" target="_blank"><button
                          class="btn btn--celeste ripple">Registrar
                          Pagos</button></a>
                  <a href="{{ route('consultarFacturas') }}"><button class="btn btn--verde ripple">Consultar
                          Pagos</button></a>
              </article>
          </section>

          <section class="jumbotron bg-jumbotron--white p-0">
              <div class="p-4">
                  <article class="text-center mb-4">
                      <h3 class="title fs-22 fw-500 sub-heading-font-family">¿Cómo contactarse con nosotros?</h3>
                  </article>

                  <article class="borderDashed borderDashed--turquesa">
                      <div class="widget-body clearfix">
                          <div>
                              <h5 class="mt-2 mb-1 fs-15 fw-400">Si tiene alguna consulta, sugerencia, requerimiento o
                                  cualquier comentario por favor no dude en comunicarse con nosotros por cualquiera de
                                  las siguientes vías: </h5>
                          </div>
                          <div>
                              <ul>
                                  <li>Envíanos un <span class="fw-600">correo</span> a <span
                                          class="text-primary">informes@autocontrol.pe</span></li>
                                  <li>Visita <span class="fw-600">nuestra web</span> y absuelve tus preguntas en: <a
                                          href="http://autocontrol.pe/" target="_blank">www.autocontrol.pe</a></li>
                                  <li>Envíanos un mensaje a nuestra cuenta <span class="fw-600">Facebook</span>: <a
                                          href="https://www.facebook.com/easyfactperu"
                                          target="_blank">www.facebook.com/easyfactperu</a></li>
                                  <li>Síguenos en <span class="fw-600">Instagram</span> como: <span
                                          class="fw-600">#autocontrolsoftware</span></li>
                              </ul>
                          </div>
                          <hr style="background-color: #0275d8">
                          <div>
                              <h5 class="mt-2 mb-1 fs-15 fw-400 text-justify">Además, si se trata de una urgencia o de algún
                                  tema que necesites comunicate con nosotros a los siguientes teléfonos:</h5>
                          </div>
                          <div>
                              <ul>
                                  <li>Callcenter: (01) 6429818</li>
                                  <li>Consultas de la plataforma y ventas: 930300534</li>
                                  <li>Ventas clientes nuevos: 922483630</li>
                                  <li>Soporte Técnico: 913253636</li>
                              </ul>
                          </div>
                      </div>
                  </article>

                  <br><br><br>

                  <article class="borderDashed borderDashed--turquesa text-center">
                      <h3 class="title fs-22 fw-500 sub-heading-font-family text-center">Soporte Personal</h3>
                      <span>Contactate con:</span>
                      <br><br>
                      <div class="row d-flex">
                          <div class="col-12 col-md-4 text-center">
                              @if ($isMobileDevice)
                                  <a id="whatsAppConMarco" class="p-2"
                                      href="https://wa.me/913253636?text=Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, tengo una consulta técnica, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @else
                                  <a class="p-2"
                                      href="https://web.whatsapp.com/send?phone=51913253636&text=Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, tengo una consulta técnica, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @endif
                              <div class="text-center fs-16 mt-2 card-bg--celeste">
                                  <span class="fs-14"><strong>Marco</strong> para obtener Soporte <br> Técnico</span>
                              </div>
                          </div>
                          <div id="whatsAppConJean" class="col-12 col-md-4 text-center">
                              @if ($isMobileDevice)
                                  <a class="p-2"
                                      href="https://wa.me/930300534?text= Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, necesito ayuda en el manejo de la plataforma, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @else
                                  <a class="p-2"
                                      href="https://web.whatsapp.com/send?phone=51930300534&text=Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, necesito ayuda en el manejo de la plataforma, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @endif
                              <div class="text-center fs-16 mt-2 card-bg--celeste">
                                  <span class="fs-14"><strong>Jean Pierre</strong> para consultas inductivas de la
                                      plataforma y asesoria Comercial</span>
                              </div>
                          </div>
                          <div class="col-12 col-md-4 text-center">
                              @if ($isMobileDevice)
                                  <a class="p-2"
                                      href="https://wa.me/922483630?text=Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, actualmente ya cuento con un sistema pero desearía contratar otro mas, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @else
                                  <a class="p-2"
                                      href="https://web.whatsapp.com/send?phone=51922483630&text=Hola soy de la Empresa *{{ $datosEmpresa->Nombre }}* con RUC: *{{ $datosEmpresa->Ruc }}*, actualmente ya cuento con un sistema pero desearía contratar otro mas, agradecería ayudarme en:"
                                      target="_blank"><img class="logo-expand" alt="" width="40"
                                          src="{{ asset('assets/img/whatsapp.png') }}"></a>
                              @endif
                              <div class="text-center fs-16 mt-2 card-bg--celeste">
                                  <span class="fs-14"><strong>Raúl</strong> para aquirir nuevas Plataforma para tu
                                      empresa</span>
                              </div>
                          </div>
                      </div>
                  </article>

                  <br><br><br>

                  <article class="mb-4 borderDashed borderDashed--turquesa">
                      <h3 class="title fs-22 fw-500 sub-heading-font-family text-center mb-4">Software para Soporte Remoto
                      </h3>
                      <br>
                      <div class="row d-flex">
                          <div class="col-4 text-center">
                              <a href="https://www.teamviewer.com/es-mx/descarga/windows/" target="_blank"><img
                                      src="{{ asset('assets/img/teamviewer.png') }}" class="rounded-circle mb-1"
                                      width="80" height="80"></a>
                              <div class="text-center fs-16">
                                  <a href="https://www.teamviewer.com/es-mx/descarga/windows/" target="_blank">Team
                                      Viewer</a>
                              </div>
                          </div>
                          <div class="col-4 text-center">
                              <a href="https://showmypc.com/" target="_blank"><img
                                      src="{{ asset('assets/img/showmypc.jpg') }}" class="rounded-circle mb-1"
                                      width="80" height="80"></a>
                              <div class="text-center fs-16">
                                  <a href="https://showmypc.com/" target="_blank">Show My PC</a>
                              </div>
                          </div>
                          <div class="col-4 text-center">
                              <a href="https://anydesk.com/es" target="_blank"><img
                                      src="{{ asset('assets/img/anydesk.jpg') }}" class="rounded-circle mb-1"
                                      width="80" height="80"></a>
                              <div class="text-center fs-16">
                                  <a href="https://anydesk.com/es" target="_blank">Any Desk</a>
                              </div>
                          </div>
                      </div>
                  </article>
              </div>

              <br><br>
              <x-notaInformativa>
                  <h5 class="mt-2 fs-15 fw-400 m-0 blockquote-footer">Nuestra aplicación esta optimizada para trabajar
                      con <span class="fw-600">Google Chrome</span>.
                  </h5>
                  <h5 class="mt-2 fs-15 fw-400 m-0 blockquote-footer">Si va a solicitar la atención de <span
                          class="fw-600">Soporte Remoto</span>, es
                      necesario elegir e instalar uno de los <span class="fw-600">software</span> de la sección de
                      soporte remoto.
                  </h5>
              </x-notaInformativa>
          </section>
      </div>
  @stop

  @section('scripts')

  @stop
