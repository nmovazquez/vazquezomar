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
                  <h4 class="card-title ">Prepedidos</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="form-group">
                      <label>Rango de fechas:</label>
                      <input type="text" class="form-control daterange " id="desde_hasta" placeholder="10/01/2022 - 10/01/2022 " onchange="loadGrid()"/>
                    </div>                  
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table" id="gridPedidos">
                          <thead class=" text-primary">
                            <th>ID</th>
                            <th>Folio</th>
                            <th>Correo</th>
                            <th>Subtotal</th>
                            <th>Impuestos</th>
                            <th>Total</th>
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


<div class="modal fade" id="modalDetalle">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modalTitle">Prepedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <label>Correo</label>
            <input type="text" class="form-control" id="correoPedido" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="container">
              <div class="row text-center">
                <!-- Subtotal -->
                <div class="col">
                  <div class="d-flex flex-column align-items-center">
                    <strong class="mb-1">Subtotal</strong>
                    <span id="subtotalLabel">$0.00</span>
                  </div>
                </div>

                <!-- Impuestos -->
                <div class="col">
                  <div class="d-flex flex-column align-items-center">
                    <strong class="mb-1">Impuestos</strong>
                    <span id="ivaLabel">$0.00</span>
                  </div>
                </div>

                <!-- Total -->
                <div class="col">
                  <div class="d-flex flex-column align-items-center">
                    <strong class="mb-1">Total</strong>
                    <span id="totalLabel">$0.00</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Cantidad</th>
                    <th>Desc.Corte</th>
                    <th>Precio Dolares</th>
                    <th>Precio Pesos</th>
                  </tr>
                </thead>
                <tbody id="bodyDetalle">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
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

    $('.daterange').daterangepicker({
        locale: {
          format: 'DD/MM/YYYY', // formato visible para el usuario
          separator: ' - ',
          applyLabel: 'Aplicar',
          cancelLabel: 'Cancelar',
          fromLabel: 'Desde',
          toLabel: 'Hasta',
          daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          firstDay: 1
        }
      }, function(start, end, label) {
        // Al aplicar el rango, cambia el formato internamente
        const inicio = start.format('YYYY-MM-DD');
        const fin = end.format('YYYY-MM-DD');

        $('#desde_hasta').val(inicio + ' - ' + fin);

      });

 

});

  function loadGrid() {

    $('#gridPedidos').dataTable().fnDestroy();
    var table = $('#gridPedidos').DataTable({
      lengthMenu: [10, 20, 50, 100, 200, 500],
      dom: 'Blfrtip',
      ajax: {
        url: "{{ url('get_prepedidos') }}",
        type: "POST",
        data: {
          activo : $('#activo').val(),
          fecha: $('#desde_hasta').val()
        },
      },
      columns: [{
          data: "id"
        },
        {
          data: "folio"
        },
        {
          data: "correo"
        },
        {
          data: "subtotal",addClass: 'text-right',render: function ( data, type, row ) {
              return numberFormat(row.subtotal)
          }
        },
        {
          data: "impuestos",addClass: 'text-right',render: function ( data, type, row ) {
              return numberFormat(row.impuestos)
          }
        },
        {
          data: "total",addClass: 'text-right',render: function ( data, type, row ) {
              return numberFormat(row.total)
          }
        },
        {
          data: "id",render: function ( data, type, row ) {
                return '<div class="text-center"><button class="btn btn-info btn-sm" title="Ver detalle" onclick="ver('+row.id+')"><i class="fa fa-eye" aria-hidden="true"></i></button></div>'
              
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

  function ver(id){
    $.ajax({
      url: '{{ url('get_prepedido') }}',
      type: 'POST',
      dataType: 'json',
      data: {id: id},
    })
    .done(function(resp) {
      console.log("success");
      $('#subtotalLabel').html(numberFormat(resp.data.subtotal))
      $('#ivaLabel').html(numberFormat(resp.data.impuestos))
      $('#totalLabel').html(numberFormat(resp.data.total))
      $('#correoPedido').val(resp.data.correo)
      $('#modalTitle').html(resp.data.folio)
      html = ''
      $('#bodyDetalle').html(html)
      $.each(resp.data.detalles, function(index, val) {
        html+='<tr>'
        html+='<td>'+val.cantidad+'</td>';
        html+='<td>'+val.articulo.nombre+'</td>';
        html+='<td>'+numberFormat(val.precioDolares)+'</td>';
        html+='<td>'+numberFormat(val.precioPesos)+'</td>';
        html+='</tr>'
      });
      $('#bodyDetalle').html(html)
      $('#modalDetalle').modal('show')
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


</script>
@endpush