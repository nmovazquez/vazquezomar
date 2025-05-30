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

.img-fija {
    width: 100%;         /* o un ancho fijo como 300px */
    height: 200px;       /* altura fija */
    object-fit: cover;   /* para recortar y mantener proporción sin deformar */
}

</style>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-7">
              <div class="row">
                @foreach ($data['productos'] as $p)
                  <div class="col" onclick="addProduct({{ $p->id }});">
                    <div class="card" style="width: 20rem;">
                      <img class="card-img-top img-fija" src="{{  asset('storage').'/'.$p->imagen }}" rel="nofollow" alt="Card image cap">
                      <div class="card-body text-cener">
                        <p class="card-text text-center">{{ $p->nombre }}</p>
                        <p class="card-text text-center">${{ number_format($p->precioDolares,2) }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="col-md-5">
              <div class="row" style="height: 55rem;">
                <div class="card">
                  <div class="card-body">
                    <div class="row" style="height: 38rem;">
                      <div class="col-12">
                        <div class="table-responsive">
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>Cantidad</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Accion</th>
                              </tr>
                            </thead>
                            <tbody id="bodyProductos">
 
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <h3>Subtotal:</h3>
                      </div>
                      <div class="col-6 text-right">
                        <h3 id="labelSubtotal">$0.00</h3>
                        <input type="hidden" id="totalSubtotal">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <h3>IVA:</h3>
                      </div>
                      <div class="col-6 text-right">
                        <h3 id="labelIva">$0.00</h3>
                        <input type="hidden" id="totalIva">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <h3>Total:</h3>
                      </div>
                      <div class="col-6 text-right">
                        <h3 id="labelTotal">$0.00</h3>
                        <input type="hidden" id="totalPedido">

                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <button class="btn btn-success btn-block btn-lg" onclick="guardar();">Guardar</button>
                      </div>
                    </div>  
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<div class="modal fade" id="modalCrearPedido">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Crear Prepedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <label>Correo</label>
            <input type="text" class="form-control" id="correo">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="btnGuardaPedido">Guardar</button>
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
})
function ajusta(id){

  console.log('jajajaj')
    var cantidad = $('#cantidad_'+id).val();

    $.ajax({
      url: '{{ url('show') }}',
      type: 'POST',
      dataType: 'json',
      data: {id: id},
    })
    .done(function(resp) {
      console.log("success");
            actual  = cantidad
            subtonew = parseFloat(parseInt(actual)) * parseFloat(resp.precioDolares)
            subtonewPesos = parseFloat(parseInt(actual)) * parseFloat(resp.precioPesos)

            $('#subtoRow_'+resp.id).html(numberFormat(subtonew))
            $('#subto_'+resp.id).val(subtonew)
            $('#subto_pesos_'+resp.id).val(subtonewPesos)
            calculaTotales();
      if(parseInt(cantidad) > parseInt(resp.stock)){
        Swal.fire({
              title: "Error",
              text: 'No existe el stock sufuciente',
              icon: "error"
            });
            $('#cantidad_'+id).val(resp.stock)
            actual  = resp.stock
            subtonew = parseFloat(parseInt(actual)) * parseFloat(resp.precioDolares)
            subtonewPesos = parseFloat(parseInt(actual)) * parseFloat(resp.precioPesos)

            $('#subtoRow_'+resp.id).html(numberFormat(subtonew))
            $('#subto_'+resp.id).val(subtonew)
            $('#subto_pesos_'+resp.id).val(subtonewPesos)
            calculaTotales();
        return false
      }/*else{
            actual  = cantidad
            $('#cantidad_'+resp.id).val(cantidad)
            subtonew = parseFloat(parseInt(actual)) * parseFloat(resp.precioDolares)
            subtonewPesos = parseFloat(parseInt(actual)) * parseFloat(resp.precioPesos)

            $('#subtoRow_'+resp.id).html(numberFormat(subtonew))
            $('#subto_'+resp.id).val(subtonew)
            $('#subto_pesos_'+resp.id).val(subtonewPesos)
      }*/
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
}
    


///esto es para evitar el doble click
let clicado =  false
$('#btnGuardaPedido').on('click', function () {
    if(clicado==false){

      //clicado = true

      var subtotal = $('#totalSubtotal').val()
      var impuestos = $('#totalIva').val()
      var total = $('#totalPedido').val()

      var correo = $('#correo').val()
      var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!regexCorreo.test(correo)) {
            Swal.fire({
              title: "Error",
              text: 'Correo Invalido',
              icon: "error"
            });
          clicado =  false
      }
        var items = new Array
        $('.productos').each(function(index, el) {
            idrow = $(this).attr('idproducto')
            dat = {
              articulo_id: idrow,
              cantidad: $('#cantidad_'+idrow).val(),
              precioDolares: $('#subto_'+idrow).val(),
              precioPesos: $('#subto_pesos_'+idrow).val(),
            }
            items.push(dat)         
        });
      $.ajax({
        url: '{{ url('crear_pedido') }}',
        type: 'POST',
        dataType: 'json',
        data: {
          correo: correo,
          items: items,
          subtotal: subtotal,
          impuestos: impuestos,
          total: total,
        },
      })
      .done(function(resp) {
        console.log("success");
        clicado =  false
        $('#modalCrearPedido').modal('hide')
        limpia();
         Swal.fire({
              title: "Prepedido generado",
              text: 'Se genero corretamente',
              icon: "success"
            });
      })
      .fail(function(resp) {
        clicado =  false
        var str = '';
        if(resp.responseJSON.items!=''){
          $.each(resp.responseJSON.items, function(index, val) {
             str+=val+'\n'
          });
        }
         Swal.fire({
              title: "Error",
              text: resp.responseJSON.message+'\n'+str,
              icon: "error"
            });
      })
      .always(function() {
        console.log("complete");
      });
      
    }
});

//agrega productos a la cesta
function addProduct(id){

  $.ajax({
      url: '{{ url('show') }}',
      type: 'POST',
      dataType: 'json',
      data: {id: id},
    })
    .done(function(resp) {
      ////si el producto existe lo suman, si no lo agrega
        if(resp.stock > 0){

          existe = false
          $('.productos').each(function(index, el) {
              idrow = $(this).attr('idproducto')
              if(idrow == id){
                existe = true
              }
          });
          if(existe==false){
            html = '<tr class="productos" id="row_'+resp.id+'" idproducto="'+resp.id+'">';
            html += '<td><input class="form-control cantidades" value="1" id="cantidad_'+resp.id+'" onkeyup="ajusta('+resp.id+')"></td>';
            html += '<td>'+resp.nombre+'</td>';
            html += '<td>'+numberFormat(resp.precioDolares)+'</td>';
            html += '<td><input type="hidden" id="subto_'+resp.id+'" value="'+resp.precioDolares+'"><p id="subtoRow_'+resp.id+'">'+numberFormat(resp.precioDolares)+'</p>';
            html +='<input type="hidden" id="subto_pesos_'+resp.id+'" value="'+resp.precioPesos+'" ></td>'
            html += '<td><button class="btn btn-danger btn-sm" onclick="eliminar('+resp.id+');" title="Eliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
            html += '</tr>';
            $('#bodyProductos').append(html)
          }else{
            actual  = $('#cantidad_'+resp.id).val()
            $('#cantidad_'+resp.id).val(parseInt(actual) + 1)
            subtonew = parseFloat(parseInt(actual) + 1) * parseFloat(resp.precioDolares)
            subtonewPesos = parseFloat(parseInt(actual) + 1) * parseFloat(resp.precioPesos)

            $('#subtoRow_'+resp.id).html(numberFormat(subtonew))
            $('#subto_'+resp.id).val(subtonew)
            $('#subto_pesos_'+resp.id).val(subtonewPesos)
            
          }
          calculaTotales()
        }else{
          Swal.fire({
              title: "Producto sin Existecia",
              text: 'El producto seleccionado se encuentra sin existecnia',
              icon: "error"
            });
        }
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
    $('#row_'+id).remove()
    calculaTotales()
  }
  ////funcion para calcular los totales
  function calculaTotales(){
    var subtotal = 0
    $('.productos').each(function(index, el) {
        idrow = $(this).attr('idproducto')
        subtotal+= parseFloat($('#subto_'+idrow).val())
    });
    var iva = parseFloat(subtotal) * 0.16
    var total = parseFloat(subtotal) + parseFloat(iva)

    $('#labelSubtotal').html(numberFormat(subtotal))
    $('#labelIva').html(numberFormat(iva))
    $('#labelTotal').html(numberFormat(total))

    $('#totalPedido').val(total)
    $('#totalSubtotal').val(subtotal)
    $('#totalIva').val(iva)
  
  }
  function guardar(){
   var rows = $('.productos').length;

   if(rows!=0){
    $('#modalCrearPedido').modal('show')
   }else{
      Swal.fire({
        title: "Error",
        text: 'Agrega al menos un producto',
        icon: "error"
      });
   }
  }
  function limpia(){
    $('#correo').val('')
    $('.productos').remove()
    $('#labelSubtotal').html('$0.00')
    $('#labelIva').html('$0.00')
    $('#labelTotal').html('$0.00')

    $('#totalPedido').val(0)
    $('#totalSubtotal').val(0)
    $('#totalIva').val(0)
  }
</script>
@endpush