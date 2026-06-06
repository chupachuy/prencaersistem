<?php
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance()->getConnection();
$nuevaPassword = 'Admin123!';
$hashNuevo = password_hash($nuevaPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE usuarios SET password = ?");
$stmt->execute([$hashNuevo]);
$actualizados = $stmt->rowCount();

$stmt = $db->query("SELECT id, nombre, apellido, email, password, rol_id, activo FROM usuarios ORDER BY id");
$usuarios = $stmt->fetchAll();

$stmt = $db->query("SELECT id, nombre FROM roles");
$roles = [];
foreach ($stmt->fetchAll() as $r) {
    $roles[$r['id']] = $r['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Passwords — PreNacer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f0f2f5; padding: 40px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px; }
        h2 { font-size: 20px; color: #1d1d1f; margin-bottom: 16px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 13px; font-weight: 600; }
        .badge-ok { background: #d1fae5; color: #065f46; }
        .badge-fail { background: #fee2e2; color: #991b1b; }
        .info { color: #6b7280; font-size: 14px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background: #f9fafb; font-weight: 600; color: #374151; }
        .warning-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-top: 16px; font-size: 14px; color: #92400e; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Fix Passwords</h2>
        <p class="info"><strong><?= $actualizados ?></strong> usuario(s) actualizado(s) con el nuevo hash bcrypt.</p>
        <p class="info">Contrasena para todos: <strong>Admin123!</strong></p>
    </div>

    <div class="card">
        <h2>Verificacion</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th>Verify</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($roles[$u['rol_id']] ?? '?') ?></td>
                    <td><?= $u['activo'] ? 'Si' : 'No' ?></td>
                    <td>
                        <?php if (password_verify($nuevaPassword, $u['password'])): ?>
                            <span class="badge badge-ok">OK</span>
                        <?php else: ?>
                            <span class="badge badge-fail">FALLO</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="warning-box">
        <strong>IMPORTANTE:</strong> Elimina este archivo (<code>fix_passwords.php</code>) del servidor inmediatamente despues de usarlo.
    </div>
</div>
</body>
</html>
