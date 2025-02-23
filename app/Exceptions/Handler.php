<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\Exception\TransportException as MailTransportException;

class Handler extends ExceptionHandler
{
    /**
     * Niveles de registro personalizados para las excepciones.
     *
     * @var array
     */
    protected $levels = [];

    /**
     * Una lista de las excepciones que no deben ser reportadas.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Una lista de los atributos de entrada que nunca deben ser incluidos en la sesión flash.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registra los manejadores de excepciones para la aplicación.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Aquí puedes registrar cualquier lógica de reporte personalizada
        });
    }

    /**
     * Renderiza una excepción en una respuesta HTTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Manejo de errores de base de datos
        if ($exception instanceof QueryException) {
            return back()->with('error', 'Error de base de datos');
        }

        // Manejo de errores de validación
        if ($exception instanceof ValidationException) {
            return back()->with('error', 'Error de validación');
        }

        // Manejo de errores HTTP
        if ($exception instanceof HttpException) {
            return back()->with('error', 'Error de ruta');
        }

        // Manejo de errores de envío de correo
        if ($exception instanceof MailTransportException) {
            return back()->with('error', 'Error al enviar el correo');
        }

        // Renderiza la excepción usando el método padre
        return parent::render($request, $exception);
    }
}
