<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado — Casa de Oración</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 48px 40px;
            text-align: center;
            max-width: 440px;
            width: 100%;
        }
        .icon {
            font-size: 56px;
            margin-bottom: 16px;
        }
        .code {
            font-size: 72px;
            font-weight: 800;
            color: #c9a84c;
            line-height: 1;
            margin-bottom: 8px;
        }
        h1 {
            font-size: 20px;
            color: #1a2e4a;
            margin-bottom: 12px;
        }
        p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            background-color: #1a2e4a;
            color: #fff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn:hover { background-color: #c9a84c; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔒</div>
        <div class="code">403</div>
        <h1>Acceso Denegado</h1>
        <p>No tienes los permisos necesarios para acceder a esta sección. Contacta al administrador si crees que esto es un error.</p>
        <div style="display: flex; gap: 12px;">
            <button onclick="history.back()" class="btn" style="flex: 1;">
                Volver atrás
            </button>
            <a href="/" class="btn" style="flex: 1;">
                Ir al inicio
            </a>
        </div>
    </div>
</body>
</html>
