<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    /**
     * Download de anexo
     */
    public function download(Attachment $attachment): BinaryFileResponse
    {
        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        $ticket = $attachment->ticket();
        
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este anexo.');
        }
        
        $filePath = Storage::disk($attachment->disk)->path($attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado.');
        }
        
        return response()->download($filePath, $attachment->filename);
    }
    
    /**
     * Preview de anexo
     */
    public function preview(Attachment $attachment): Response
    {
        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        $ticket = $attachment->ticket();
        
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este anexo.');
        }
        
        $filePath = Storage::disk($attachment->disk)->path($attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado.');
        }
        
        $mimeType = $attachment->file_type;
        $content = file_get_contents($filePath);
        
        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $attachment->filename . '"')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }
    
    /**
     * Verificar se arquivo pode ser previewed
     */
    public function canPreview(Attachment $attachment): bool
    {
        $previewableTypes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'text/plain',
            'text/html',
            'application/json',
            'text/csv',
            'text/xml'
        ];
        
        return in_array($attachment->file_type, $previewableTypes);
    }
}