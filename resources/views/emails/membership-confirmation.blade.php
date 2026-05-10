<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a2e4a; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .content { background: #f5f0e8; padding: 30px; border-radius: 0 0 4px 4px; }
        .data { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #c9a84c; }
        .data p { margin: 8px 0; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Solicitud de Membresía</h1>
        </div>
        <div class="content">
            <p>Estimado/a {{ $membership->full_name }},</p>
            <p>Hemos recibido exitosamente tu solicitud de membresía en <strong>Casa de Oración</strong>.</p>

            <div class="data">
                <p><strong>Nombre:</strong> {{ $membership->full_name }}</p>
                <p><strong>Email:</strong> {{ $membership->email }}</p>
                <p><strong>Teléfono:</strong> {{ $membership->phone }}</p>
                <p><strong>Fecha de envío:</strong> {{ $membership->submission_date->format('d/m/Y') }}</p>
            </div>

            <p>Nos pondremos en contacto contigo pronto para confirmar tu solicitud.</p>
            <p>Que Dios te bendiga,</p>
            <p><strong>Casa de Oración</strong></p>

            <div class="footer">
                <p>© {{ date('Y') }} Casa de Oración · Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</body>
</html>
