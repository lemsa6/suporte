<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class AttachmentController extends Controller
{
    /**
     * Download de anexo
     */
    public function download(Attachment $attachment): Response
    {
        // Verificar se o usuário pode acessar o ticket
        $this->authorize('view', $attachment->ticket);

        if (!Storage::disk($attachment->disk)->exists($attachment->file_path)) {
            abort(404, 'Arquivo não encontrado');
        }

        return Storage::disk($attachment->disk)->download(
            $attachment->file_path,
            $attachment->filename
        );
    }

    /**
     * Preview de anexo (para imagens)
     */
    public function preview(Attachment $attachment): Response
    {
        // Verificar se o usuário pode acessar o ticket
        $this->authorize('view', $attachment->ticket);

        if (!Storage::disk($attachment->disk)->exists($attachment->file_path)) {
            abort(404, 'Arquivo não encontrado');
        }

        // Se for imagem, retornar como imagem
        if ($attachment->isImage()) {
            $file = Storage::disk($attachment->disk)->get($attachment->file_path);
            return response($file, 200, [
                'Content-Type' => $attachment->file_type,
                'Content-Disposition' => 'inline; filename="' . $attachment->filename . '"'
            ]);
        }

        // Para outros tipos, redirecionar para download
        return redirect()->route('attachments.download', $attachment);
    }

    /**
     * Remover anexo
     */
    public function destroy(Attachment $attachment): \Illuminate\Http\RedirectResponse
    {
        // Verificar se o usuário pode remover o anexo
        $this->authorize('delete', $attachment->ticket);

        try {
            // Remover arquivo do storage
            if (Storage::disk($attachment->disk)->exists($attachment->file_path)) {
                Storage::disk($attachment->disk)->delete($attachment->file_path);
            }

            // Remover registro do banco
            $attachment->delete();

            return back()->with('success', 'Anexo removido com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover anexo: ' . $e->getMessage());
        }
    }
}
