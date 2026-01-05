<?php
include 'includes/db.php';

echo "<div style='direction: rtl; font-family: Cairo, sans-serif; padding: 20px;'>";
echo "<h2>فحص الجداول في قاعدة بيانات: " . $db . "</h2>";

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);
    
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table[0] . "</li>";
        
        // Check columns of 'admins' or 'priv'
        if ($table[0] == 'admins' || $table[0] == 'priv') {
            echo "<ul>";
            $cols = $pdo->query("DESCRIBE " . $table[0])->fetchAll();
            foreach ($cols as $col) {
                echo "<li>" . $col['Field'] . " (" . $col['Type'] . ")</li>";
            }
            echo "</ul>";
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage();
}
echo "</div>";
?>
