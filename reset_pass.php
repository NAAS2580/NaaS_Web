<?php
include 'includes/db.php';

$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // Check if user exists first
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin) {
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
        $stmt->execute([$hashed_password]);
        $msg = "تم تحديث كلمة المرور للمستخدم موجود بالفعل.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password, full_name, role) VALUES ('admin', ?, 'المدير العام', 'superadmin')");
        $stmt->execute([$hashed_password]);
        $msg = "تم إنشاء مستخدم جديد وتعيين كلمة المرور.";
    }
    
    echo "<div style='direction: rtl; font-family: Cairo, sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; margin: 20px; text-align: center;'>
            <h2>تمت العملية بنجاح!</h2>
            <p>$msg</p>
            <p>اسم المستخدم: <strong>admin</strong></p>
            <p>كلمة المرور الجديدة: <strong>admin123</strong></p>
            <p><a href='admin/index.php'>اضغط هنا للذهاب لصفحة تسجيل الدخول</a></p>
          </div>";
} catch (PDOException $e) {
    echo "خطأ: " . $e->getMessage();
}
?>
