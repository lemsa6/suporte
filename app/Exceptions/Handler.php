<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Se for uma requisição de API, retornar JSON
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        // Para requisições web, usar as páginas de erro personalizadas
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(Request $request, HttpException $e)
    {
        $status = $e->getStatusCode();

        // Verificar se existe uma view específica para o código de erro
        if (view()->exists("errors.{$status}")) {
            return response()->view("errors.{$status}", [
                'exception' => $e
            ], $status);
        }

        // Usar a view genérica de erro
        return response()->view('errors.error', [
            'exception' => $e
        ], $status);
    }

    /**
     * Handle API exceptions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException(Request $request, Throwable $exception)
    {
        $status = 500;
        $message = 'Erro interno do servidor';

        if ($this->isHttpException($exception)) {
            $status = $exception->getStatusCode();
            $message = $this->getHttpExceptionMessage($status);
        }

        return response()->json([
            'error' => true,
            'message' => $message,
            'status' => $status,
            'timestamp' => now()->toISOString(),
        ], $status);
    }

    /**
     * Get user-friendly message for HTTP status codes.
     *
     * @param  int  $status
     * @return string
     */
    protected function getHttpExceptionMessage(int $status): string
    {
        return match ($status) {
            400 => 'Solicitação inválida',
            401 => 'Não autorizado',
            403 => 'Acesso negado',
            404 => 'Recurso não encontrado',
            405 => 'Método não permitido',
            408 => 'Tempo esgotado',
            419 => 'Sessão expirada',
            422 => 'Dados inválidos',
            429 => 'Muitas solicitações',
            500 => 'Erro interno do servidor',
            502 => 'Gateway inválido',
            503 => 'Serviço indisponível',
            504 => 'Gateway timeout',
            default => 'Erro inesperado',
        };
    }
}
