<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a2e4a; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .content { background: #f5f0e8; padding: 30px; border-radius: 0 0 4px 4px; }
        .credentials { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #c9a84c; }
        .credentials p { margin: 8px 0; }
        .button { display: inline-block; background: #1a2e4a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .button:hover { background: #111e30; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a Casa de Oración!</h1>
        </div>
        <div class="content">
            <p>Estimado/a {{ $user->name }},</p>
            <p>¡Excelentes noticias! Tu solicitud de membresía ha sido <strong>aprobada</strong> exitosamente.</p>

            <h2>Establece tu Contraseña</h2>
            <p>Para comenzar a usar tu cuenta, haz clic en el botón de abajo para establecer tu contraseña:</p>

            <center>
                <a href="{{ $resetLink }}" class="button" style="display: inline-block; background: #1a2e4a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; margin: 20px 0;">
                    Establecer Contraseña
                </a>
            </center>

            <p>Este enlace es válido por 60 minutos. Si no puedes hacer clic, copia y pega esta URL en tu navegador:</p>
            <p style="word-break: break-all; background: #f0f0f0; padding: 10px; border-radius: 4px; font-size: 12px;">
                {{ $resetLink }}
            </p>

            <p>Si tienes cualquier pregunta o necesitas ayuda, no dudes en contactarnos.</p>
            <p>Que Dios te bendiga abundantemente,</p>
            <p><strong>Casa de Oración</strong></p>

            <div class="footer">
                <p>© {{ date('Y') }} Casa de Oración · Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</body>
</html>
