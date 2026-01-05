<?php
include 'includes/db.php';

echo "<h2>جاري تحديث قاعدة البيانات...</h2>";

try {
    // 1. Add background image columns
    $cols = [
        'hero_bg' => "VARCHAR(255) DEFAULT 'hero1.png'",
        'about_bg' => "VARCHAR(255) DEFAULT 'hero1.png'",
        'contact_bg' => "VARCHAR(255) DEFAULT 'hero1.png'",
        'news_bg' => "VARCHAR(255) DEFAULT 'hero1.png'"
    ];

    foreach ($cols as $col => $def) {
        try {
            $pdo->exec("ALTER TABLE settings ADD COLUMN $col $def");
            echo "✅ تم إضافة العمود: $col <br>";
        } catch (PDOException $e) {
            echo "ℹ️ العمود $col موجود مسبقاً<br>";
        }
    }

    // 2. Add About Us content columns
    $about_cols = [
        'about_story_title' => "VARCHAR(255) DEFAULT 'قصة العطاء'",
        'about_story_content' => "TEXT",
        'about_vision' => "TEXT",
        'about_mission' => "TEXT",
        'about_years_exp' => "INT DEFAULT 15"
    ];

    foreach ($about_cols as $col => $def) {
        try {
            $pdo->exec("ALTER TABLE settings ADD COLUMN $col $def");
            echo "✅ تم إضافة العمود: $col <br>";
        } catch (PDOException $e) {
            echo "ℹ️ العمود $col موجود مسبقاً<br>";
        }
    }

    // 3. Set default data
    $story = "تأسست منظمة تهامة الخيرية كاستجابة إنسانية عاجلة للاحتياجات المتزايدة في منطقة تهامة واليمن بشكل عام.";
    $vision = "أن نكون المنظمة الرائدة في تحويل التحديات الإنسانية في تهامة إلى فرص نمو وازدهار.";
    $mission = "تمكين الأفراد والمجتمعات من خلال تنفيذ مشاريع إبداعية في التعليم والصحة.";
    
    $update = $pdo->prepare("UPDATE settings SET about_story_content = ?, about_vision = ?, about_mission = ? WHERE id = 1");
    $update->execute([$story, $vision, $mission]);

    echo "<h3>🎉 تم التحديث بنجاح! يمكنك الآن العودة للوحة التحكم.</h3>";
    echo "<a href='admin/settings.php' style='padding:10px 20px; background:#0f766e; color:white; text-decoration:none; border-radius:5px;'>العودة للإعدادات</a>";

} catch (PDOException $e) {
    echo "❌ خطأ فادح: " . $e->getMessage();
}
?>
