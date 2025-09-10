<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha - {{ $systemName }}</title>
    <style>
        body {
            font-family: 'Lato', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .content p {
            margin-bottom: 20px;
            color: #4a5568;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        .warning {
            background-color: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #c53030;
        }
        .info {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #2b6cb0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $systemName }}</h1>
            <p>Sistema de Gerenciamento de Tickets</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Recuperação de Senha</h2>
            
            <p>Olá <strong>{{ $user->name }}</strong>,</p>
            
            <p>Recebemos uma solicitação para redefinir a senha da sua conta no {{ $systemName }}.</p>
            
            <p>Para redefinir sua senha, clique no botão abaixo:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Redefinir Senha</a>
            </div>
            
            <div class="info">
                <strong>Informações importantes:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Este link é válido por 24 horas</li>
                    <li>Se você não solicitou esta recuperação, ignore este email</li>
                    <li>Sua senha atual continuará funcionando até ser alterada</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>⚠️ Segurança:</strong>
                <p>Não compartilhe este link com ninguém. Nossa equipe nunca solicitará sua senha por email.</p>
            </div>
            
            <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; background-color: #f7fafc; padding: 10px; border-radius: 4px; font-family: monospace;">
                {{ $resetUrl }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este email foi enviado automaticamente pelo {{ $systemName }}</p>
            <p>{{ $companyName }} - Sistema de Suporte Técnico</p>
            <p>Se você não solicitou esta recuperação, ignore este email.</p>
        </div>
    </div>
</body>
</html>
