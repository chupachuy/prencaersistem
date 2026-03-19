<?php
require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/Url.php';

$error = Session::getFlash('error');
$success = Session::getFlash('success');
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - PreNacer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --apple-bg: #f5f5f7;
            --apple-text: #1d1d1f;
            --apple-gray: #86868b;
            --apple-blue: #367d84;
            --apple-blue-hover: #2a6369;
            --apple-border: #d2d2d7;
            --apple-card: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--apple-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container { width: 100%; max-width: 380px; }

        .login-card {
            background: var(--apple-card);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .login-logo { text-align: center; margin-bottom: 30px; }
        .login-logo img { height: 56px; }

        .login-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--apple-text);
            text-align: center;
            margin: 0 0 8px 0;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--apple-gray);
            text-align: center;
            margin: 0 0 30px 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--apple-text);
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid var(--apple-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
            background: #f5f5f7;
            transition: all 0.2s ease;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--apple-blue);
            box-shadow: 0 0 0 3px rgba(54, 125, 132, 0.15);
            background: #fff;
            outline: none;
        }

        .btn-apple {
            width: 100%;
            background: var(--apple-blue);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-apple:hover { background: var(--apple-blue-hover); transform: translateY(-1px); }
        .btn-apple:active { transform: translateY(0); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--apple-blue);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--apple-blue-hover); }

        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger { background: #ffebe6; color: #bf2b2b; }
        .alert-success { background: #d1e7dd; color: #367d84; }
        .alert-warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <div class="login-logo">
                <img src="logos/logocolor.svg" alt="PreNacer">
            </div>

            <h1 class="login-title">Nueva contraseña</h1>
            <p class="login-subtitle">Crea una nueva contraseña para tu cuenta</p>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (!$token): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Token no proporcionado
                </div>
                <a href="<?php echo Url::to('/login'); ?>" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Volver a iniciar sesión
                </a>
            <?php else: ?>
                <form action="<?php echo Url::to('/reset-password'); ?>" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Repite tu contraseña" required minlength="6">
                    </div>

                    <button type="submit" class="btn-apple">
                        Guardar contraseña <i class="fa-solid fa-check"></i>
                    </button>

                    <a href="<?php echo Url::to('/login'); ?>" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i> Volver a iniciar sesión
                    </a>
                </form>
            <?php endif; ?>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>