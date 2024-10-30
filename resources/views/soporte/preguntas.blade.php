		@extends('layouts.app')
		@section('title', 'Soporte')
		@section('content')		
            <div class="container">
                <div class="widget-list">
                    <div class="row justify-content-center">
                        <div class="col-md-8 widget-holder">
                            <div class="widget-bg">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading1">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1" style="white-space: normal">
                                                Soy un usuario nuevo del sistema, ¿Cuáles son los primeros pasos a seguir?
                                            </button>
                                        </h2>
                                      </div>
                                      <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Sigue estos simples pasos para empezar a usar nuestro sistema:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <p>1. Llena el inventario de tus productos en el sistema, lo puedes realizar dirigiéndote al módulo: Administración / Almacén y crear tus categorías, Marcas, Servicios y    Productos y/o subiéndolos desde tu Excel.</p>
                                                </div>
                                                <div class="pb-4">
                                                    <p>2. Abre tu caja para iniciar el día</p>
                                                     <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                                <div class="pb-4">
                                                    <p>3. Empieza tus ventas</p>
                                                     <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/IMfy01bOx54" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                                <div class="pb-4">
                                                    <p>4. Cierra tu caja al final de tu turno y/o día</p>
                                                     <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading2">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" style="white-space: normal">
                                              ¿Puedo también vender servicios o solo productos?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Por supuesto, nuestro ERP está hecho para que puedas vender tus productos y a la vez comercializar cualquier servicio que desees.</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/QB3Qkoj7hFk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading3">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3" style="white-space: normal">
                                             ¿Cómo creo un cliente?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Es bastante simple, da click aquí y mira el video.</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">                                                        
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/RRZcMCIO0-Y" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading4">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4" style="white-space: normal">
                                             ¿Puedo hacer el inventario de mis productos en Excel?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Asi es, contamos con un módulo de importación donde podrás fácilmente inventariar tus productos y luego importando a nuestro sistema, ingresa aquí y entérate de cómo hacerlo.</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/rJuqPzDWCQ0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading5">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5" style="white-space: normal">
                                             ¿Cómo empezar mi día en MiFacturita aperturando mi caja?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Es bastante simple, mira nuestro video para que te guie pasoa paso como hacerlo.</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading6">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6" style="white-space: normal">
                                             ¿Cómo hacer una venta exitosa?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Nuestro sistema esta hecho para que realices una venta en menos de 1 minuto, aumentando la productividad y operatividad de tu personal, ingresa aquí y entérate como:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/IMfy01bOx54" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading7">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7" style="white-space: normal">
                                             ¿Cómo registrar una compra a mi proveedor?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="container">
                                                <div class="pb-4">
                                                    <p>Ingresa fácilmente todo lo que compras a tu proveedor ya sea una boleta, factura y/o ticket, nuestro sistema lo almacenera automáticamente aumentando el stock de tus productos; ah y no olvides que también te permitiremos modificar el precio de la compra  de tus nuevos productos al momento de ingresarla.</p>
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/v9lfp0Hw30s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                                <div class="pb-4">
                                                    <p>No olvides que debes de tener registrado a tu proveedor, si aún no lo has hecho guiate como hacerlo ingresando aquí:
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/g6r5fuvva24" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading8">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8" style="white-space: normal">
                                             ¿Si mi proveedor cambió sus precios, como modifico los precios de compra de mis nuevos productos?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse8" class="collapse" aria-labelledby="heading8" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Mi Facturita te permite cambiar los costos de los nuevos productos que estés comprando, revisa el video y entérate como:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/4dxmHgPqVtw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading9">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9" style="white-space: normal">
                                             ¿Cómo consulto los precios y stock de mis productos?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse9" class="collapse" aria-labelledby="heading9" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Es super simple hacerlo, solo mira nuestro video aquí mismo:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/2hWUlShHJEk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading10">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10" style="white-space: normal">
                                             ¿Cómo consulto una venta hecha semanas atrás?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse10" class="collapse" aria-labelledby="heading10" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Aquí encontrarás como ver el detalle de que productos vendiste ingresando tan solo el nombre del cliente, super fácil y sencillo.</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/PbpWkVJjZrE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading11">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11" style="white-space: normal">
                                             ¿Cómo consulto las compras realizadas a mi proveedor?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse11" class="collapse" aria-labelledby="heading11" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Encuentra el detalle exacto de los productos y cantidad que compraste a tu proveedor, solo mira el video y entérate como:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/PbpWkVJjZrE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading12">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12" style="white-space: normal">
                                             ¿Cómo puedo cuadrar parcialmente mi dinero en caja?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse12" class="collapse" aria-labelledby="heading12" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Rectifica tu caja en todo momento sin problema alguno, mira cómo hacerlo aquí:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/jEwlf07XV0o" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading13">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13" style="white-space: normal">
                                             ¿Cómo hago el cierre y cuadre total de mi turno?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse13" class="collapse" aria-labelledby="heading13" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Con el modulo de cierre de caja, podrás finalizar tu turno en menos de 1 minuto, entérate como aquí:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading14">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14" style="white-space: normal">
                                             ¿Puedo imprimir mi cierre total de productos vendidos?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse14" class="collapse" aria-labelledby="heading14" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Si, lleva un control de todas las ventas de tu caja imprimiendo y/o enviando al correo todo tu detalle, te lo mostramos aquí:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/wT4ktZ-ifZw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading15">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse15" aria-expanded="false" aria-controls="collapse15" style="white-space: normal">
                                             ¿Como puedo cambiar mi contraseña y a la vez poner un logo a mis boletas y/o facturas?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse15" class="collapse" aria-labelledby="heading15" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Solo debes tener disponible tu logo en formato jpg, ten mucho cuidado en modificar tu RUC y Razón Social esto es indispensable para girar tus documentos y esta enlazado directamente a Sunat, luego de ello sigue estas opciones en nuestro video:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/Y6ExgndK85w" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading16">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse16" aria-expanded="false" aria-controls="collapse16" style="white-space: normal">
                                             ¿Si tengo productos vencidos que debo hacer para descargarlo de mi stock?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse16" class="collapse" aria-labelledby="heading16" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Si tienes algún producto vencido, extraviado y/o fue un consumo interno propio, puedes ver como modificar tu stock aquí:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/CdmBLn5sHBU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header bg-celeste" id="heading17">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed fw-600" type="button" data-toggle="collapse" data-target="#collapse17" aria-expanded="false" aria-controls="collapse17" style="white-space: normal">
                                             ¿Mi operador olvido su contraseña, que puedo hacer?
                                          </button>
                                        </h2>
                                      </div>
                                      <div id="collapse17" class="collapse" aria-labelledby="heading17" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>Solo debes ingresar con tu cuenta administradora y reiniciarla, te lo mostramos aquí:</p>
                                            <div class="container">
                                                <div class="pb-4">
                                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/NVTpA-EzfU8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
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
	@stop


