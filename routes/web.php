<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  Inicio. Página de entrada.
Route::get('/', 'HomeController@inicio')->name('portal');

//  Autenticación de usuario
Auth::routes(['verify' => true]);

Route::group(['middleware' => ['verified']], function () {
    // Panel de control
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('users', 'UserController');
    // Marcajes - CRUD
    Route::get('marcajes/Registros', 'RegistroController@show')->name('registros');
    Route::get('marcajes/Mis-Marcajes', 'MarcajesController@misMarcajes')->name('misMarcajes');
    Route::get('absentismos', 'AbsentismosController@show')->name('absentismos');

    Route::get('aceptar', 'AbsentismosController@aceptar')->name('aceptar');
    Route::get('rechazar', 'AbsentismosController@rechazar')->name('rechazar');

    
    Route::get('absentismos/solicitar', 'AbsentismosController@solicitud')->name('solicitarAbsentismo');
    Route::get('absentismos/ver', 'AbsentismosController@ver')->name('verAbsentismo');
    Route::get('absentismos/verAdmin', 'AbsentismosController@verAdmin')->name('verAbsentismoAdmin');
    Route::get('absentismos/año', 'AbsentismosController@cambAño')->name('cambAño');
    Route::get('absentismos/añoAdmin', 'AbsentismosController@cambAñoAdmin')->name('cambAñoAdmin');
    Route::post('absentismos/enviar', 'AbsentismosController@envSol')->name('envSol');
    Route::get('absentismos/volver', 'AbsentismosController@volver')->name('volver');
    Route::get('absentismos/faltas', 'AbsentismosController@asignarFaltas')->name('asignarFaltas');
    Route::post('absentismos/ponerFaltas', 'AbsentismosController@ponerFaltas')->name('ponerFaltas');
    Route::get('absentismos/modAbs', 'AbsentismosController@modAbs')->name('modAbs');

    Route::post('marcajes/registro-jornada','MarcajesController@pdf')->name('pdf');
    Route::post('marcajes/genCSV','MarcajesController@genCsv')->name('csv');
    Route::post('users/import','UserController@importCSV')->name('importCsv');
    Route::post('users/update','UserController@actualizarHorarioPersonal')->name('actualizarEmpleados');
    Route::post('users/importCen','CentroController@importCSV')->name('importCsvCentros');
    //Moverse entre empleados
    Route::get('user/sig','UserController@next')->name('siguiente');
    Route::get('user/ant','UserController@back')->name('anterior');
    Route::get('user/inicio','UserController@inicio')->name('inicio');
    Route::get('user/fin','UserController@fin')->name('fin');
    Route::get('user/buscarUse','UserController@buscarUser')->name('buscarUse');
    //Ver horarios de centros
    Route::get('user/horario','UserController@cambiarCentro')->name('cambioHorario');
    Route::get('user/verificacion','UserController@verificacion')->name('verificacion');
    //Cambiar minutos de cortesia
    Route::post('centros/cambiarCortesia','CentroController@cambiarCortesia')->name('cambiarCortesia');
    Route::post('centros/cambiarGeo','CentroController@cambiarGeo')->name('cambiarGeo');

    //Moverse entre centros
    Route::get('user/sigCen','CentroController@next')->name('siguienteCen');
    Route::get('user/antCen','CentroController@back')->name('anteriorCen');
    Route::get('user/inicioCen','CentroController@inicio')->name('inicioCen');
    Route::get('user/finCen','CentroController@fin')->name('finCen');
    Route::get('user/buscar','CentroController@buscarCentro')->name('buscarCen');

    Route::get('user/centros','CentroController@show')->name('centros');
    Route::resource('marcajes','MarcajesController');
    // Absentismos - Gestión de permisos y vacaciones. Controlará los estados available y on_holidays de los empleados
    Route::post('justify/{id}','AbsenteeismsController@update')->name('justify');
    Route::resource('absenteeisms','AbsenteeismsController');
    // Documentos - Subida de documentos a la plataforma y visualización de los mismos desde ahí
    Route::get('../storage/app/documents/{doc}','DocumentController@show')->name('download');
    Route::resource('documents','DocumentController');

    Route::get('documentsG','DocumentController@general')->name('general');
    Route::get('documentsN','DocumentController@nominas')->name('verNominas');
    Route::get('documentsC','DocumentController@documentosCertificados')->name('certificados');
    Route::get('decumento','DocumentController@verArchivo')->name('verArchivo');

    Route::get('avisos','AgendaController@show')->name('avisos');

    Route::get('avisos/marcado','AgendaController@marcarLeido')->name('marcarLeido');
    Route::get('avisos/noMarcado','AgendaController@marcarNoLeido')->name('marcarNoLeido');
    Route::get('avisos/todos','AgendaController@verTodos')->name('verTodos');
    Route::get('avisos/leidos','AgendaController@verLeidos')->name('verLeidos');

    Route::get('doc','DocumentController@adjuntar')->name('adjuntar');
    Route::get('doc/adjuntarEmpleado','DocumentController@adjuntarEmpleado')->name('adjuntarEmpleado');

    Route::get('noms','DocumentController@adjuntarNominas')->name('adjuntarNominas');
    Route::post('nominas','DocumentController@nomina')->name('nominas');    
    Route::post('mostrarNom','DocumentController@mostrarNominas')->name('mostrarNominas');
    Route::post('borrarNom','DocumentController@borrarNominas')->name('borrarNominas');

    Route::get('/doc/descarga' , 'DocumentController@downloadFile')->name('descarga');

});
