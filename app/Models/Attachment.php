<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_message_id',
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'disk'
    ];

    protected $casts = [
        'file_size' => 'integer'
    ];

    // Relacionamentos
    public function ticketMessage(): BelongsTo
    {
        return $this->belongsTo(TicketMessage::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('file_type', 'like', "{$type}%");
    }

    public function scopeBySize($query, $minSize = null, $maxSize = null)
    {
        if ($minSize) {
            $query->where('file_size', '>=', $minSize);
        }
        
        if ($maxSize) {
            $query->where('file_size', '<=', $maxSize);
        }
        
        return $query;
    }

    // Métodos úteis
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute(): string
    {
        return match(true) {
            str_contains($this->file_type, 'image') => '📷',
            str_contains($this->file_type, 'pdf') => '📄',
            str_contains($this->file_type, 'word') => '📝',
            str_contains($this->file_type, 'excel') => '📊',
            str_contains($this->file_type, 'zip') => '📦',
            str_contains($this->file_type, 'text') => '📄',
            str_contains($this->file_type, 'log') => '📋',
            default => '📎'
        };
    }

    public function getFileTypeLabelAttribute(): string
    {
        return match(true) {
            str_contains($this->file_type, 'image') => 'Imagem',
            str_contains($this->file_type, 'pdf') => 'PDF',
            str_contains($this->file_type, 'word') => 'Documento',
            str_contains($this->file_type, 'excel') => 'Planilha',
            str_contains($this->file_type, 'zip') => 'Arquivo Compactado',
            str_contains($this->file_type, 'text') => 'Texto',
            str_contains($this->file_type, 'log') => 'Log',
            default => 'Arquivo'
        };
    }

    public function isImage(): bool
    {
        return str_contains($this->file_type, 'image');
    }

    public function isPdf(): bool
    {
        return str_contains($this->file_type, 'pdf');
    }

    public function isDocument(): bool
    {
        return str_contains($this->file_type, 'word') || 
               str_contains($this->file_type, 'excel') || 
               str_contains($this->file_type, 'powerpoint');
    }

    public function isArchive(): bool
    {
        return str_contains($this->file_type, 'zip') || 
               str_contains($this->file_type, 'rar') || 
               str_contains($this->file_type, 'tar');
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('attachments.download', $this->id);
    }

    public function getPreviewUrlAttribute(): string
    {
        if ($this->isImage()) {
            return route('attachments.preview', $this->id);
        }
        
        return $this->download_url;
    }
}
