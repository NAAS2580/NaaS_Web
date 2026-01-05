<?php
include 'includes/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        content TEXT,
        main_image VARCHAR(255),
        type VARCHAR(50) DEFAULT 'تنموي',
        status ENUM('active', 'completed', 'ongoing', 'planned') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    
    $pdo->exec($sql);
    
    echo "<div style='direction: rtl; font-family: Cairo, sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; margin: 20px; text-align: center;'>
            <h2>تم إنشاء جدول المشاريع بنجاح!</h2>
            <p>يمكنك الآن العودة <a href='index.php'>للصفحة الرئيسية</a>.</p>
          </div>";
          
    // Delete this file after execution for security
    // unlink(__FILE__);
    
} catch (PDOException $e) {
    echo "<div style='direction: rtl; font-family: Cairo, sans-serif; padding: 20px; background: #fee2e2; color: #dc2626; border-radius: 10px; margin: 20px;'>
            <h2>خطأ أثناء تحديث قاعدة البيانات:</h2>
            <p>" . $e->getMessage() . "</p>
          </div>";
}
?>
