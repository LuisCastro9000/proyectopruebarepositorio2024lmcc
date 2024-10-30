@extends('layouts.app')
@section('title', 'Editar Tipo')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">{{ $titulo }}</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
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
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            {!! Form::open(['url' => 'vehicular/' . $url . '' . $tipo->id, 'method' => 'PUT', 'class' => 'form-material']) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" id="nombre" placeholder="Nombre" type="text"
                                            name="nombre" value="{{ $tipo->nombre }}">
                                        <label for="nombre">Nombre</label>
                                        <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-info" type="submit">actualizar</button>
                                <a href="../../{{ $controller }}"><button class="btn btn-outline-default"
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
    <script>
        $(function() {
            $('#archivo').change(function(e) {
                addImage(e);
            });

            function addImage(e) {
                var file = e.target.files[0],
                    imageType = /image.*/;

                if (!file.type.match(imageType))
                    return;

                var reader = new FileReader();
                reader.onload = fileOnload;
                reader.readAsDataURL(file);
            }

            function fileOnload(e) {
                var result = e.target.result;
                $('#imgPrevia').attr("src", result);
            }

            $(document).on('click', '.generar', function() {
                var codigo = $("#codBarra").val();
                JsBarcode("#barcode", codigo);
                $("#print").show();
            });

        });
    </script>
@stop
