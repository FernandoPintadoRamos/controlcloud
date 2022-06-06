@extends('layouts.app')

@section('content')
<div class="container">
        @if(session('message'))
            <div class="alert alert-{{ session('message')[0] }}"> {{ session('message')[1] }} </div> 
        @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nuevo Marcaje</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('marcajes.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="nature_of_work" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de trabajo:') }}</label>

                            <div class="col-md-6">
                            <input id="nature_of_work" type="text" class="form-control{{ $errors->has('nature_of_work') ? ' is-invalid' : '' }}" name="nature_of_work" value="{{ old('nature_of_work') }}" autofocus>

                                @if ($errors->has('nature_of_work'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nature_of_work') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="entrance" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de entrada:') }}</label>
                            
                            <div class="col-md-6">
                                <input id="entrance" type="date" class="form-control{{ $errors->has('entrance') ? ' is-invalid' : '' }}" name="entrance" value="Prueba" required autofocus>
                                   
                                    @if ($errors->has('entrance'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('entrance') }}</strong>
                                        </span>
                                    @endif
                                    
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label for="check_in_time" class="col-md-4 col-form-label text-md-right">{{ __('Entrada') }}</label>

                            <div class="col-md-6">
                            <input id="check_in_time" type="time" class="form-control{{ $errors->has('check_in_time') ? ' is-invalid' : '' }}" name="check_in_time" required>

                                @if ($errors->has('check_in_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('check_in_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="entrance_note" class="col-md-4 col-form-label text-md-right">{{ __('Nota de entrada') }}</label>
        
                            <div class="col-md-6">
                            <textarea class="form-control{{ $errors->has('entrance_note') ? ' is-invalid' : '' }}" id="entrance_note" rows="3" name="entrance_note"></textarea>

                                @if ($errors->has('entrance_note'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('entrance_note') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> <br><br>

                        <div class="form-group row">
                          <label for="exit" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de salida') }}</label>

                          <div class="col-md-6">
                              <input id="exit" type="date" class="form-control{{ $errors->has('exit') ? ' is-invalid' : '' }}" name="exit" required autofocus>

                                  @if ($errors->has('exit'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('exit') }}</strong>
                                      </span>
                                  @endif
                          </div>
                        </div>

                        <div class="form-group row">
                            <label for="departure_time" class="col-md-4 col-form-label text-md-right">{{ __('Salida') }}</label>

                            <div class="col-md-6">
                            <input id="departure_time" type="time" class="form-control{{ $errors->has('departure_time') ? ' is-invalid' : '' }}" name="departure_time" required>

                                @if ($errors->has('departure_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('departure_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <<div class="form-group row">
                            <label for="exit_note" class="col-md-4 col-form-label text-md-right">{{ __('Nota de salida') }}</label>
        
                            <div class="col-md-6">
                            <textarea class="form-control{{ $errors->has('exit_note') ? ' is-invalid' : '' }}" id="exit_note" rows="3" name="exit_note"></textarea>

                                @if ($errors->has('exit_note'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('exit_note') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> <br><br>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar') }}
                                </button>
                                <a href="{{ route('misMarcajes')}}" class="btn btn-secondary">Atrás</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
