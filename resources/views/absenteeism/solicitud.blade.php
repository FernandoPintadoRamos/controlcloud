@extends('layouts.app')

@section('content')
<div class="container">
        @if(session('message'))
            <div class="alert alert-{{ session('message')[0] }}"> {{ session('message')[1] }} </div> 
        @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Solicitud Permisos y Vacaciones</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('absenteeisms.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="exampleFormControlSelect1" class="col-md-4 col-form-label text-md-right">{{ __('Motivo:') }}</label>

                            <div class="col-md-6">
                                <select class="form-control{{ $errors->has('id_absence') ? ' is-invalid' : '' }}" id="exampleFormControlSelect1" name="id_absence" required>
                                    <option selected>...</option>
                                    @foreach($absences as $ab)
                                        <option value="{{ $ab->id }}">{{ $ab->type }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('id_absence'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('id_absence') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="withdrawal_date" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de baja:') }}</label>

                            <div class="col-md-6">
                                <input id="withdrawal_date" type="date" class="form-control{{ $errors->has('withdrawal_date') ? ' is-invalid' : '' }}" name="withdrawal_date" value="{{ old('withdrawal_date') }}" required autofocus>

                                    @if ($errors->has('withdrawal_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('withdrawal_date') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="absenteeism_days" class="col-md-4 col-form-label text-md-right">{{ __('Días Permiso') }}</label>

                            <div class="col-md-6">
                            <input id="absenteeism_days" type="number" class="form-control" name="absenteeism_days" value=0>

                                @if ($errors->has('absenteeism_days'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('absenteeism_days') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="holidays_days" class="col-md-4 col-form-label text-md-right">{{ __('Días Vacaciones') }}</label>

                            <div class="col-md-6">
                            <input id="holidays_days" type="number" class="form-control" name="holidays_days" value=0>

                                @if ($errors->has('holidays_days'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('holidays_days') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                          <label for="discharge_date" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de alta') }}</label>

                          <div class="col-md-6">
                              <input id="discharge_date" type="date" class="form-control{{ $errors->has('discharge_date') ? ' is-invalid' : '' }}" name="discharge_date" required autofocus>

                                  @if ($errors->has('discharge_date'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('discharge_date') }}</strong>
                                      </span>
                                  @endif
                          </div>
                        </div> <br><br>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar') }}
                                </button>
                                <a href="{{ route('absenteeisms.index')}}" class="btn btn-secondary">Atrás</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
