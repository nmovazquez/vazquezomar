@extends('layouts.app', ['activePage' => 'productos', 'menuParent' => 'operaciones', 'titlePage' => __('Listado Productos')])

@section('content')
<style>
input[type="text"],
input[type="email"],
input[type="number"],
input[type="date"],
input[type="file"],
select,
textarea {
    color: #000 !important;
    background-color: #fff !important;
}



</style>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Productos</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <label>Filtro</label>
                      <select id="activo" class="form-control" onchange="loadGrid();">
                        <option value="x">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                      </select>
                    </div>
                    <div class="col-6" align="right">
                      <button class="btn btn-primary btn-sm" onclick="nuevo();">Nuevo +</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table" id="gridProductos">
                          <thead class=" text-primary">
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Desc.Corta</th>
                            <th>Precio Pesos</th>
                            <th>Precio Dolares</th>
                            <th>Stock</th>
                            <th>Vigencia</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


<div class="modal fade" id="modalProducto">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="tituloModal">Nuevo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
      <form id="formProducto" enctype="multipart/form-data">
        <input type="hidden" id="producto_id" name="id">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group filled">
              <label class="">SKU: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="sku" name="sku" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group filled">
              <label class="">Nombre: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group filled">
              <label class="">Descripción Corta: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="descripcion_corta" name="descripcion_corta" required>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group filled">
              <label class="">Descripción Larga: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="descripcion_larga" name="descripcion_larga" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label class="p-0 m-0" for="precioDolares">Precio dolares: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text pl-0">
                        <i class="fa fa-dollar"></i>
                    </span>
                </div>
                <input type="number" class="form-control" id="precioDolares" name="precioDolares" placeholder="0.00">
            </div>
          </div>
          <div class="col-4">
            <label class="p-0 m-0" for="precioPesos">Precio Pesos: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text pl-0">
                        <i class="fa fa-dollar"></i>
                    </span>
                </div>
                <input type="number" class="form-control" id="precioPesos" name="precioPesos" placeholder="0.00" readonly>
            </div>
          </div>
          <div class="col-4">
            <label class="p-0 m-0" for="tipoCambio">Tipo cambio:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text pl-0">
                        <i class="fa fa-dollar"></i>
                    </span>
                </div>
                <input type="number" class="form-control" id="tipoCambio" placeholder="0.00" value="{{ $data['tipoCambio'] }}" readonly>
            </div>            
          </div>
        </div>
        <div class="row">
          <div class="col-12">
         
              <div class="input-group">
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
              </div>            

            <!--<div class="form-group form-file-upload form-file-multiple">
              <input type="file" multiple="" class="inputFileHidden" >
              <div class="input-group">
                  <input type="text" class="form-control inputFileVisible" id="imagen" name="imagen" placeholder="Single File">
                  <span class="input-group-btn">
                      <button type="button" class="btn btn-fab btn-round btn-primary">
                          <i class="material-icons">attach_file</i>
                      </button>
                  </span>
              </div>
            </div>-->
          </div>
        </div>
        <div class="row">
          <div class="col-12" align="center">
            <div class="card" style="width: 20rem;">
              <img class="card-img-top" src="#" rel="nofollow" alt="Card image cap" id="previewImagen">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group filled">
              <label class="">Stock: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="stock" name="stock" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group filled">
              <label class="">Fecha Vigencia: <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="fecha_vigencia" name="fecha_vigencia" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group filled">
              <label class="">Status: <span class="text-danger">*</span></label>
              <select class="form-control" id="activo" name="activo">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="btnGuardar">Guardar</button>
      </div>
       </form>
    </div>
  </div>
</div>
<!-- Modal de carga -->
<div class="modal fade" id="modalCargando" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <div class="spinner-border text-primary mb-3" role="status"></div>
      <h5 class="mb-0">Guardando producto...</h5>
    </div>
  </div>
</div>


@endsection
@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  loadGrid();
  $('.selectpicker').selectpicker('refresh')
    $('#imagen').on('change', function () {
        const input = this;
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImagen')
                    .attr('src', e.target.result)
                    .show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#previewImagen')
                .attr('src', '#')
                .hide();
        }
    });

    $('#precioDolares').on('keyup', function () {
        let valor = $(this).val();
        pesos = parseFloat(valor) * parseFloat($('#tipoCambio').val())
        $('#precioPesos').val(pesos.toFixed(2))
    });

     $('#formProducto').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            urlAction = ($('#producto_id').val() == '') ? '{{ route("productos.store") }}' : '{{ route("productos.update") }}'
            $('#modalCargando').modal('show')
            $.ajax({
                url: urlAction,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                     $('#activo').val('x')
                    // Limpiar formulario
                    form.reset();
                    $('#previewImagen').hide();
                    $('#errores').html('');

                    // Cerrar modal
                   
                    setTimeout(function () {
                      $('#modalCargando').modal('hide')
                      $('#modalProducto').modal('hide')
                    }, 200);

                    
                    // Mostrar éxito
                     Swal.fire({
                            icon: 'success',
                            title: 'Exito!',
                            html: 'Guardado Correctamente',
                        });

                    
                    //Recarga el grid
                    loadGrid();
                },
                error: function (xhr) {
                   $('#modalCargando').modal('hide')
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        let mensaje = '';
                        $.each(errores, function (key, value) {
                            mensaje += '<div>' + value[0] + '</div>';
                        });

                        // Mostrar errores con SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Errores de validación',
                            html: mensaje,
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al guardar el producto.'
                        });
                    }
                }
            });
      });
});

  function loadGrid() {

    $('#gridProductos').dataTable().fnDestroy();
    var table = $('#gridProductos').DataTable({
      lengthMenu: [10, 20, 50, 100, 200, 500],
      dom: 'Blfrtip',
      ajax: {
        url: "{{route('productos.get')}}",
        type: "POST",
        data: {
          activo : $('#activo').val(),
        },
      },
      columns: [{
          data: "sku"
        },
        {
          data: "nombre"
        },
        {
          data: "descripcion_corta"
        },
        {
          data: "precioDolares",addClass: 'text-right',render: function ( data, type, row ) {
              return numberFormat(row.precioDolares)
          }
        },
        {
          data: "precioPesos",addClass: 'text-right',render: function ( data, type, row ) {
              return numberFormat(row.precioPesos)
          }
        },
        {
          data: "stock"
        },
        {
          data: "fecha_vigencia"
        },
        {
          data: "activo",render: function ( data, type, row ) {
              if(row.activo=='1'){
                return '<div class="text-center"><span class="badge badge-success">Activo</span></div>'
              }else{
                return '<div class="text-center"><span class="badge badge-warning">Inactivo</span></div>'
              }
            }
        },
        {
          data: "activo",render: function ( data, type, row ) {
              if(row.activo=='1'){
                btn = '<button class="btn btn-warning btn-sm" onclick="activacion('+row.id+',0);" title="Desactivar"><i class="fa fa-ban" aria-hidden="true"></i></button>'
              }else{
                btn = '<button class="btn btn-sm btn-success" onclick="activacion('+row.id+',1);" title="Activar"><i class="fa fa-check" aria-hidden="true"></i></button>'
              }
              return '<div class="text-center"><button class="btn btn-info btn-sm" onclick="editar('+row.id+');" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></button>'+btn+'<button class="btn btn-danger btn-sm" onclick="eliminar('+row.id+');" title="Eliminar"><i class="fa fa-times" aria-hidden="true"></i></button></div>'
            }
        },
      ],
      order: [
        [0, "desc"]
      ],
      buttons: [{
          extend: 'excelHtml5',
          text: 'EXCEL',
          titleAttr: 'Excel',
        },
        {
          extend: 'csvHtml5',
          text: 'CSV',
          titleAttr: 'CSV',
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF',
          titleAttr: 'PDF',
        },
        {
          extend: 'copyHtml5',
          text: 'COPIAR',
          titleAttr: 'Copiar',
        },

      ],

    });

  }

  function nuevo(){
    $('#tituloModal').text('Nuevo');
    $('#btnGuardar').text('Guardar');
    $('#producto_id').val('')
    $('#modalProducto').modal('show')
  }

  function editar(id){
    $.ajax({
      url: '{{ url('show') }}',
      type: 'POST',
      dataType: 'json',
      data: {id: id},
    })
    .done(function(resp) {
      console.log(resp)
      $('#sku').val(resp.sku)
      $('#nombre').val(resp.nombre)
      $('#precioDolares').val(resp.precioDolares)
      $('#precioPesos').val(resp.precioPesos)
      $('#stock').val(resp.stock)
      $('#fecha_vigencia').val(resp.fecha_vigencia)
      $('#descripcion_corta').val(resp.descripcion_corta)
      $('#descripcion_larga').val(resp.descripcion_larga)
      $('#producto_id').val(resp.id)
      $('#activo').val(resp.activo)
        // Mostrar imagen si hay
        url =  '{{ asset('storage') }}'+'/'+resp.imagen
        if (resp.imagen) {
            $('#previewImagen').attr('src', url).show();
        } else {
            $('#previewImagen').hide();
        }
      $('#tituloModal').text('Editar Producto');
      $('#btnGuardar').text('Actualizar');
      $('#modalProducto').modal('show')

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  }
   function numberFormat(num){
    return new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(num)
  }
  function eliminar(id){
    Swal.fire({
      title: "Deseas eliminar el producto?",
      text: "Una vez eliminado no lo podras recuperar",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar"
    }).then((result) => {
      if (result.value) {

        $.ajax({
          url: '{{ url('delete') }}',
          type: 'POST',
          dataType: 'json',
          data: {id: id},
        })
        .done(function(resp) {
          console.log("success");
            Swal.fire({
              title: "Eliminado!",
              text: resp.message,
              icon: "success"
            });
            loadGrid();
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
      }
    });
  }
  function activacion(id,tipo){
    if(tipo==1){
      leyenda =  'Deseas activar el producto?'
    }else{
      leyenda =  'Deseas desactivar el producto?'
    }
    Swal.fire({
      title: leyenda,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si"
    }).then((result) => {
      if (result.value) {

        $.ajax({
          url: '{{ url('activar') }}',
          type: 'POST',
          dataType: 'json',
          data: {
            id: id,
            tipo: tipo,
          },
        })
        .done(function(resp) {
          console.log("success");
            Swal.fire({
              title: "Procesado correctamente!",
              icon: "success"
            });
            loadGrid();
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
      }
    });
  }
</script>
@endpush