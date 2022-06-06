<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

    <h1 class="text-center">Registro de Jornada</h1>

    <div class="row">
        <div class="container">
                <table class="table table-striped" >
                  <thead>
                      <tr>
                        <td align="center"><strong>Productor</strong></td>
                        <td align="center"><strong>Tipo</strong></td>
                        <td align="center"><strong>Fecha (Entrada)</strong></td>
                        <td align="center"><strong>Hora de Entrada</strong></td>
                        <td align="center"><strong>Fecha (Salida)</strong></td>
                        <td align="center"><strong>Hora de Salida</strong></td>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($marcajes as $m)
                        @if($m->check_in_time !=null)  
                        <tr>
                            <td>{{$m->nameUser->name}}</td>
                            <td>{{$m->nature_of_work}}</td>
                            <td>{{$m->entrance}}</td>
                            <td>{{$m->check_in_time}}</td>
                            <td>{{$m->exit}}</td>
                            <td>{{$m->departure_time}}</td>
                        </tr>
                        @endif
                      @endforeach
                  </tbody>
                </table>
        </div>
</div>