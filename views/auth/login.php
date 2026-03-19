<?php
require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/Url.php';

$error = Session::getFlash('error');
$success = Session::getFlash('success');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PreNacer</title>
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

        * {
            box-sizing: border-box;
        }

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

        .login-container {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background: var(--apple-card);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-logo img {
            height: 56px;
        }

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
            margin: 0;
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

        .form-control::placeholder {
            color: var(--apple-gray);
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

        .btn-apple:hover {
            background: var(--apple-blue-hover);
            transform: translateY(-1px);
        }

        .btn-apple:active {
            transform: translateY(0);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--apple-blue);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--apple-blue-hover);
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            border: none;
        }

        .alert-danger {
            background: #ffebe6;
            color: #bf2b2b;
        }

        .alert-success {
            background: #d1e7dd;
            color: #155724;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--apple-gray);
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--apple-border);
        }

        .divider span {
            padding: 0 12px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <div class="login-logo">
                <img src="logos/logocolor.svg" alt="PreNacer">
            </div>

            <h1 class="login-title">Iniciar sesión</h1>
            <p class="login-subtitle">Accede a tu cuenta de PreNacer</p>

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

            <form action="<?php echo Url::to('/login'); ?>" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>

                <a href="<?php echo Url::to('/forgot-password'); ?>" class="forgot-link">¿Olvidaste tu contraseña?</a>

                <div class="divider"></div>

                <button type="submit" class="btn-apple">
                    Continuar <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



