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
            <div class="col-12">
              <h1 class="text-center">Top 3 vendidos</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div style="width: 600px; height: 600px; margin: auto;">
               <canvas id="graficaPastel" width="10" height="10"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>






@endsection
@push('js')
<script>
    const datos = @json($datos);

    const labels = datos.map(d => d.nombre);
    const cantidades = datos.map(d => d.total);

    const ctx = document.getElementById('graficaPastel').getContext('2d');
    const grafica = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cantidad vendida',
                data: cantidades,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush