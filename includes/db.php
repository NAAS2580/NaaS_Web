<?php
// إعدادات قاعدة البيانات - تم التعديل ليعمل على السيرفر المحلي والاستضافة تلقائياً
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // 1. إعدادات السيرفر المحلي (XAMPP)
    $host = 'localhost';
    $db   = 'charity_db';
    $user = 'root';
    $pass = '';
} else {
    // 2. إعدادات استضافة InfinityFree
    // ملاحظة: يجب عليك جلب "MySQL Hostname" من لوحة تحكم InfinityFree (Account Details)
    // غالباً ما يكون شيء مثل: sqlXXX.infinityfree.com
    $host = 'sql110.infinityfree.com'; // قم بتغيير هذا إلى Hostname الصحيح من لوحة التحكم
    $db   = 'if0_40825383';           // اسم قاعدة البيانات (تأكد منه من لوحة التحكم)
    $user = 'if0_40825383';           // اسم المستخدم (غالباً نفس رقم الحساب)
    $pass = 'BJd4HIANgT';             // كلمة المرور التي زودتني بها
}

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // في حالة الخطأ، نعرض رسالة واضحة
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        die("خطأ في الاتصال بقاعدة البيانات محلياً. تأكد من تشغيل XAMPP وإنشاء قاعدة البيانات باسم charity_db");
    } else {
        // في الاستضافة، يفضل عدم عرض تفاصيل الخطأ التقنية للزوار
        die("خطأ في الاتصال بقاعدة البيانات على الاستضافة. يرجى التأكد من بيانات MySQL في ملف includes/db.php");
    }
}
?>

