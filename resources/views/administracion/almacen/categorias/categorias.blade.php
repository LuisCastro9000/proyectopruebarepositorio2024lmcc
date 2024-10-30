
		@extends('layouts.app')
		@section('title', 'Categorías')
		@section('content')	
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Categorías</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../almacen/categorias/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">add</i> Agregar</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../almacen/categorias/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">add</i></button></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <!-- /.page-title -->
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="widget-list">
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg-transparent">
                                <div class="widget-body clearfix">
                                    <div class="form-material">
                                        <div class="form-group">
                                            <label class="form-control-label fs-14 fw-400">Buscar Categoría</label>
                                            <div class="input-group">
                                                <input type="text" id="inputBuscar" name="textoBuscar" class="form-control fs-16 fw-400 border-0" value="{{$textoBuscar}}">
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- Products List -->
                                    <div id="listaCategorias" class="ecommerce-products list-unstyled row">
                                        @foreach ($categorias as $categoria)
                                            <div class="product col-12 col-sm-6 col-md-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <a href="javascript:void(0);">
                                                            <img src="{{$categoria->Imagen}}" alt="">
                                                        </a>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="d-flex">
                                                            <h5 class="product-title">{{ $categoria->Nombre }}</h5>
                                                        </div>
                                                        <!-- /.d-flex --> <span class="text-muted">{{ $categoria->Descripcion }}</span>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <div class="card-footer">
                                                        <div class="product-info"><a href="categorias/{{$categoria->IdCategoria}}/edit"><i class="list-icon material-icons">edit</i>Editar</a>
                                                        </div>
                                                        <div class="product-info"><a onclick="modalEliminar({{$categoria->IdCategoria}})" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-footer -->
                                                </div>
                                                <!-- /.card -->
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- /.ecommerce-products -->
                                    <!-- Product Navigation -->
                                    <div class="col-md-12">
                                        <nav aria-label="Page navigation">
                                            <ul id="paginas" class="pagination pagination-md d-flex justify-content-center pagCat">
                                                @if ($categorias->onFirstPage())
                                                    <li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>
                                                    <li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="categorias?page=1" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>
                                                    <li class="page-item"><a class="page-link" href="{{$categorias->previousPageUrl()}}" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>
                                                @endif
                                                
                                                @if($categorias->currentPage() < 3)
                                                    @for ($i=1; $i<=5; $i++)
                                                        @if($i > 0 && $i <= $categorias->lastPage())
                                                            @if($i == $categorias->currentPage())
                                                                <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{$i}}</a></li>
                                                            @else
                                                                <li class="page-item"><a class="page-link" href="categorias?page={{$i}}">{{$i}}</a></li>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                @elseif($categorias->lastPage() > 2)
                                                    @if($categorias->currentPage() > $categorias->lastPage() - 2)
                                                        @for ($i=$categorias->currentPage()-4; $i<=$categorias->lastPage(); $i++)
                                                            @if($i > 0 && $i <= $categorias->lastPage())
                                                                @if($i == $categorias->currentPage())
                                                                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{$i}}</a></li>
                                                                @else
                                                                    <li class="page-item"><a class="page-link" href="categorias?page={{$i}}">{{$i}}</a></li>
                                                                @endif
                                                            @endif
                                                        @endfor
                                                    @endif
                                                @endif
                                                @if($categorias->currentPage() >= 3 && $categorias->currentPage() <= $categorias->lastPage() - 2)
                                                    @for ($i=$categorias->currentPage()-2; $i<=$categorias->currentPage()+2; $i++)
                                                        @if($i > 0 && $i <= $categorias->lastPage())
                                                            @if($i == $categorias->currentPage())
                                                                <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{$i}}</a></li>
                                                            @else
                                                                <li class="page-item"><a class="page-link" href="categorias?page={{$i}}">{{$i}}</a></li>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                @endif
                                                @if ($categorias->hasMorePages())
                                                    <li class="page-item"><a class="page-link" href="{{$categorias->nextPageUrl()}}" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>
                                                    <li class="page-item"><a class="page-link" href="categorias?page={{$categorias->lastPage()}}" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>
                                                    <li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- /.col-md-12 -->
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
    <script src="{{asset('assets/js/administracion/categorias.js')}}"></script>
	@stop




