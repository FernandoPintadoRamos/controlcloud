@extends('layouts.app')

@section('content')
<style>
    .tarjeta{
      border: 1px solid #E2870C;
      min-height: 280px;
      min-width: 420px;
      height: auto;
      width: auto;
      border-top-right-radius: 50px;
      border-bottom-left-radius: 50px;
      float:left;
      transition: all .3s ease-in-out;
    }

    .center{
        display: flex;
        justify-content: center;
        width:100%;
    }

    .titulo{
        display: flex;
        justify-content: center;
        padding: 20px;
        border-top-right-radius: 50px;
    }

    .pop{
        padding:10px;
        margin:10px;
        border: 1px solid #E2870C;
        border-radius: 30px;
    }

    .pop:hover{
        background-color:#F9A958;
        transform: scale(1.1);
        transition: all .3s ease-in-out;
    }

    .pop_alt{
        padding:10px;
        margin:10px;
        border: 1px solid #E2870C;
        border-radius: 30px;
        background-color:#F9A958;
    }

    .pop_alt:hover{
        background-color:white;
        transform: scale(1.1);
        transition: all .3s ease-in-out;
    }

    .enlace{
        color:#E2870C;
    }

    .enlace:hover{
        color: white;
        text-decoration:none;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:#E2870C;
        text-decoration:none;
    }

    .enlace_descarga{
        color:#E2870C;
    }

    .enlace_descarga:hover{
        color: #DC7E00;
        text-decoration:none;
    }

    .cuerpo{
        padding: 10px;
        margin: 10px;
    }

    .cuerpo div{
        display: flex;
    }

    .principal{
        background-color:#F7AD62;
    }

    .linea{
        background-color:#FAC896;
    }

    table{
        border: 1px solid #F7942F;
        -moz-border-radius: 20px;
    }

    tbody tr:nth-child(odd) {
      background: #FAC896; 
    }

    th, td{
        padding: 3px;
        border: 1px solid #F7942F;
    }
</style>


<div class="center">
  <div class="tarjeta">

    <div class="titulo" style="background-color:#F7AB40">
      @isset($tipo)
        <h5><strong>{{$tipo}}</strong></h5>
      @endisset
    </div>
    <div style="overflow: auto; padding:10px">
    <table id="myTable" data-order='[[ 0, "desc" ]]'>
      <thead>
        <tr style="background-color:#F7AD62">
          <td>
            Empleado
          </td>

          <td>
            Nombre archivo
          </td>

          <td>
            Descripción
          </td>

          <td>
            Borrar
          </td>

          <td>
            Descargar
          </td>
        </tr>
      </thead>
      <tbody>
      @foreach($documents as $document)
        @if($document->oculto == 0)
        <tr>
          <td>
            @if(strlen($document->doc) > 20)
              @foreach($users as $user)@if($user->id == $document->id_worker){{ $user->NOM }} @endif @endforeach
            @else
              @foreach($users as $user)@if($user->id == $document->id_worker){{ $user->NOM }} @endif @endforeach
            @endif
          </td>

          <td>
            {{ substr($document->doc,0,20) }}
          </td>

          <td id="td{{ $document->id}}" style="text-align:center">
            {{-- @if(strlen($document->description) <) --}}
            {{$document->description}}
          </td>
          <td>
            <button class="btn btn-danger" data-toggle="modal" data-target="#modalConfirmDelete{{$document['id']}}"><span class="glyphicon glyphicon-trash"></span></button>
            <!--Modal: modalConfirmDelete-->
            <div class="modal fade" id="modalConfirmDelete{{$document['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
                <!--Content-->
                <div class="modal-content text-center">
                  <!--Header-->
                  <div class="modal-header d-flex justify-content-center btn-danger">
                    <p class="heading"><strong>¿Eliminar documento?<br></strong>No será posible recuperarlo.</strong></p>
                  </div>

                  <!--Body-->
                  <div class="modal-body">

                    <i class="fas fa-times fa-4x animated rotateIn equis"></i>
                    <form action="{{ route('documents.destroy', $document['id'])}}" method="POST" id="formDelete{{$document['id']}}">
                        @csrf   
                        @method('DELETE')                                 
                    </form>  
                  </div>

                  <!--Footer-->
                  <div class="modal-footer flex-center">
                    
                    <button type="submit" class="btn  btn-outline-danger" form="formDelete{{$document['id']}}">Si</button>
                    <a href="" class="btn btn-danger waves-effect">No</a> 
                  </div>
                </div>
                <!--/.Content-->
              </div>
            </div>
            <!--Modal: modalConfirmDelete-->
          </td>

          <td>
              <a href="{{ route('descarga', ['nombre' =>$document->doc])}}" class="enlace_descarga">Descargar</a><br><br>
          </td>
          
        </tr>
        @endif
      @endforeach
      </tbody>
    </table>
    </div>
    <div class="center">
      <div class="pop_alt">
        <a href="{{ route('documents.index')}}" class="enlace_alt">Volver al Menú</a>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="offset-md-5">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('script')
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      
    });
  });
</script>
@endsection