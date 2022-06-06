@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-1">
            <h4>Vacaciones y permisos</h4>
            <p>Días totales de absentismo: <strong>{{ $use->absenteeism }}</strong></td>
            <p>Días restantes de vacaciones: <strong>{{ $use->days_holidays }}</strong></td>
        </div>
        <div class="col md 4">
            <a href="{{ route('absenteeisms.create')}}" class="btn btn-outline-secondary">Solicitar Permisos o Vacaciones</a>
            <a href="{{ route('misMarcajes')}}" class="btn btn-outline-success">Volver a Marcajes</a>
            <a href="{{ route('users.index')}}" class="btn btn-outline-warning">Volver al Menú</a>
        </div>
    </div>
</div> <br><br>
<div class="container">
    <table class="table record_table" id="myTable">
      <thead>
        @if($absenteeism)
          <tr>
            <td align="center"><strong>Productor</strong></td>
            <td align="center"><strong>Ausencia por...</strong></td>
            <td align="center"><strong>Fecha de baja</strong></td>
            <td align="center"><strong>Días</strong></td>
            <td align="center"><strong>Fecha de alta</strong></td>
            @if($use->role == 'empleado')
                <td align="center"><strong>Justificada</strong></td>
            @else
                <td align="center"><strong>Justificar si procede</strong></td>
            @endif
          </tr>
        @endif
      </thead>
        @forelse ($absenteeism as $index)
        <tbody>
            <tr>
            @foreach($index as $a)
                @if($use->role == 'empleado')
                    <td><strong>{{$use->name}}</strong></td>
                @else
                    @foreach($users as $user)
                        @if($user->id == $a['id_worker'])
                            <td><strong>{{$user->name}}</strong></td>
                        @endif
                    @endforeach
                @endif
                @foreach ($absences as $absence)
                    @if($absence->id == $a['id_absence'])
                        <td>{{ $absence->type }}</td>
                    @endif
                @endforeach
                <td>{{$a['withdrawal_date']}}</td>
                @if($a['id_absence']==2 or $a['id_absence']==3)
                    <td>{{$a['absenteeism_days']}}</td>
                @elseif($a['id_absence']==1)
                    <td>{{$a['holidays_days']}}</td>
                @endif
                <td>{{$a['discharge_date']}}</td>
                @if($use->role == 'empleado')
                    @if($a['justify']==0)
                        <td>No justificada</td>
                    @else
                        <td>Justificada</td>
                    @endif
                @else
                    @if($a['justify']==0)
                        <td>
                        <form method="POST" action="{{ route('justify', ['id' => $a['id']]) }}">
                            @csrf
                            @method('POST')
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="justify{{$a['id']}}" name="justify" value=1>
                                <label class="custom-control-label" for="justify">Justificar</label>
                                <br>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                        </td>   
                    @endif
                @endif
                @if($use->role == 'supervisor')
                <td>          <!-- Button trigger modal-->
                    
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalConfirmDelete{{$a['id']}}"><span class="glyphicon glyphicon-trash"></span></button>

                    <!--Modal: modalConfirmDelete-->
                    <div class="modal fade" id="modalConfirmDelete{{$a['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                      aria-hidden="true">
                      <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
                        <!--Content-->
                        <div class="modal-content text-center">
                          <!--Header-->
                          <div class="modal-header d-flex justify-content-center btn-danger">
                            <p class="heading"><strong>¿Eliminar registro?<br></strong>No será posible recuperarlo.</strong></p>
                          </div>

                          <!--Body-->
                          <div class="modal-body">

                            <i class="fas fa-times fa-4x animated rotateIn equis"></i>
                            <form action="{{ route('absenteeisms.destroy', $a['id'])}}" method="POST" id="formDelete{{$a['id']}}">
                                @csrf   
                                @method('DELETE')                                 
                            </form>  
                          </div>

                          <!--Footer-->
                          <div class="modal-footer flex-center">
                            
                            <button type="submit" class="btn  btn-outline-danger" form="formDelete{{$a['id']}}">Si</button>
                            <a href="" class="btn btn-danger waves-effect">No</a> 
                          </div>
                        </div>
                        <!--/.Content-->
                      </div>
                    </div>
                    <!--Modal: modalConfirmDelete-->
                </td>
                @endif
            </tr>
            @endforeach
        @empty
            <div class="text-center">
                <br><br>
                @if($use->role == 'empleado')
                    <h4>No tienes registro de absentismo.</h4>
                    <a href="{{ route('absenteeisms.create')}}">Solicitar permiso o vacaciones</a><br>
                @else
                    <h4>No hay registros de absentismo pendientes de justificar.</h4>
                @endif
                <a href="{{ route('misMarcajes')}}">Volver</a>
            </div>
        </tbody>
        @endforelse
    </table>
</div>
@endsection
@section('script')
<script>
 $(document).ready(function() {
    $('#myTable').DataTable({
      
    });
  });
// Control del checkbox
$(document).ready(function () {
    $('.record_table td').click(function (event) {
        if (event.target.type !== 'submit') {
            $(':checkbox', this).trigger('click');
        }
    });

    $("input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('td').addClass("highlight_row");
        } else {
            $(this).closest('td').removeClass("highlight_row");
        }
    });
});

</script>
@endsection