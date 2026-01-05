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
    $host = 'sql100.infinityfree.com'; 
    $db   = 'if0_40833135_charity_db';  
    $user = 'if0_40833135';            
    $pass = 'ZbnRDQN0oM';              // القيمة الدقيقة التي أرسلها المستخدم
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
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>

