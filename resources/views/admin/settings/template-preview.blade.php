<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview do Template - {{ ucfirst(str_replace('_', ' ', $templateName)) }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .preview-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .preview-header {
            background: #667eea;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        .preview-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h3>Preview: {{ ucfirst(str_replace('_', ' ', $templateName)) }}</h3>
        </div>
        <div class="preview-content">
            @php
                // Mapear nomes de templates para arquivos reais
                $templateMap = [
                    'ticket_created' => 'created',
                    'ticket_created_for_you' => 'created-for-you',
                    'ticket_replied' => 'replied',
                    'ticket_status_changed' => 'status-changed',
                    'ticket_closed' => 'closed'
                ];
                
                $templateFile = $templateMap[$templateName] ?? str_replace('_', '-', $templateName);
                
                try {
                    // Renderizar o template com dados de exemplo
                    $rendered = view('emails.tickets.' . $templateFile, $sampleData)->render();
                    echo $rendered;
                } catch (\Exception $e) {
                    echo '<div class="alert alert-danger">Erro ao renderizar template: ' . $e->getMessage() . '</div>';
                }
            @endphp
        </div>
    </div>
</body>
</html>
