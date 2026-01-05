<?php
session_start();
include '../includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Special bypass for immediate access
    if ($username === 'admin' && $password === 'admin123') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if (!$admin) {
            $hashed = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO admins (username, password, full_name, role) VALUES ('admin', ?, 'المدير العام', 'superadmin')")->execute([$hashed]);
            $stmt->execute();
            $admin = $stmt->fetch();
        }

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: dashboard.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-container">
    <div class="login-box">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; background: var(--primary); color: white; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 20px; box-shadow: 0 10px 15px -3px rgba(15, 118, 110, 0.3);">
                <i class="fas fa-shield-halved"></i>
            </div>
            <h2>لوحة التحكم</h2>
            <p style="color: var(--text-muted); margin-top: -30px;">مرحباً بك مجدداً، يرجى تسجيل الدخول</p>
        </div>

        <?php if($error): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: var(--radius-md); margin-bottom: 25px; text-align: center; font-weight: 600; font-size: 0.9rem; border: 1px solid #fecaca; animation: shake 0.5s ease-in-out;">
                <i class="fas fa-exclamation-circle" style="margin-left: 8px;"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-user" style="margin-left: 8px; color: var(--primary);"></i> اسم المستخدم</label>
                <input type="text" name="username" class="form-control" placeholder="أدخل اسم المستخدم" required autofocus>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock" style="margin-left: 8px; color: var(--primary);"></i> كلمة المرور</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="أدخل كلمة المرور" required>
                    <button type="button" onclick="togglePassword()" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 5px; z-index: 10;">
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                تسجيل الدخول <i class="fas fa-sign-in-alt" style="margin-right: 10px; transform: rotate(180deg);"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 30px; font-size: 0.85rem; color: var(--text-muted);">
            &copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة لـ Tahma'a
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .form-control {
            padding-left: 45px !important; /* To make space for the eye icon */
        }
    </style>
</body>
</html>

