  @extends('layouts.app')
  @section('title', 'facturas')
  @section('content')
      <div class="container">
          <section class="mt-3">
              @if (session('error'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      {{ session('error') }}
                  </div>
              @endif
          </section>
          <section class="row my-4">
              <div class="col-12">
                  <label>Seleccion Año</label>
                  <select id="selectorAnio" class="form-control" name="anio">
                  </select>
              </div>
              <article class="m-auto">
                  <br><br>
                  <div class="loader"></div>
              </article>
          </section>

          <div class="jumbotron  bg-jumbotron--white my-4">
              <div class="container">
                  <section class="row">
                      @include('soporte._tabla')
                  </section>
              </div>
          </div>
      </div>
  @stop
  @section('scripts')
      <script>
          $(() => {
              const date = new Date();
              const anioActual = date.getFullYear();
              for (let index = anioActual; index >= 2018; index--) {
                  $('#selectorAnio').append(`<option value='${index}'>${index}</option>`);
              }

              $(document).ready(function() {
                  $('#table').DataTable({
                      responsive: true,
                      "paging": false,
                      "ordering": false,
                      "info": false,
                      "searching": false
                  });
              });

              $('.loader').hide();
          })

          $('#selectorAnio').change(() => {
              $.showLoading({
                  name: 'circle-fade',
              });
              const anio = $('#selectorAnio').val();
              $.ajax({
                  url: "{{ route('getFacturas') }}",
                  method: 'GET',
                  data: {
                      anio: anio
                  },
                  success: function(data) {
                      $('.datosTabla').html(data);
                      $.hideLoading();
                  },
                  error: function(xhr, status, error) {
                      console.error(error);
                  }
              });

          })
      </script>
  @stop
