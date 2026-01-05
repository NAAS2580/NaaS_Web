<?php
include 'includes/db.php';

try {
    $sql = "ALTER TABLE settings 
            ADD COLUMN hero_bg VARCHAR(255) DEFAULT 'hero1.png', 
            ADD COLUMN about_bg VARCHAR(255) DEFAULT 'hero1.png', 
            ADD COLUMN contact_bg VARCHAR(255) DEFAULT 'hero1.png', 
            ADD COLUMN news_bg VARCHAR(255) DEFAULT 'hero1.png'";
    
    $pdo->exec($sql);
    echo "إضافة أعمدة الصور بنجاح!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "الأعمدة موجودة بالفعل.";
    } else {
        echo "خطأ: " . $e->getMessage();
    }
}
?>
