<?php
include 'includes/db.php';

try {
    $sql = "ALTER TABLE settings 
            ADD COLUMN about_story_title VARCHAR(255) DEFAULT 'قصة العطاء', 
            ADD COLUMN about_story_content TEXT, 
            ADD COLUMN about_vision TEXT, 
            ADD COLUMN about_mission TEXT, 
            ADD COLUMN about_years_exp INT DEFAULT 15";
    
    $pdo->exec($sql);
    
    // Set default content if empty
    $story = "تأسست **منظمة تهامة الخيرية** كاستجابة إنسانية عاجلة للاحتياجات المتزايدة في منطقة تهامة واليمن بشكل عام. نحن مجموعة من المتطوعين والمهنيين الذين آمنوا بأن التغيير يبدأ بخطوة، وأن العمل المؤسسي المنظم هو السبيل الوحيد لتحقيق أثر مستدام.";
    $vision = "أن نكون المنظمة الرائدة في تحويل التحديات الإنسانية في تهامة إلى فرص نمو وازدهار، وبناء مجتمعات مكتفية ذاتياً وتتمتع بالكرامة.";
    $mission = "تمكين الأفراد والمجتمعات من خلال تنفيذ مشاريع إبداعية في التعليم، الصحة، وتوفير سبل العيش الكريمة، مع ضمان وصول المساعدات لمستحقيها بكل أمانة.";
    
    $update = $pdo->prepare("UPDATE settings SET about_story_content = ?, about_vision = ?, about_mission = ? WHERE id = 1");
    $update->execute([$story, $vision, $mission]);

    echo "تمت إضافة أعمدة 'من نحن' بنجاح!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "الأعمدة موجودة بالفعل.";
    } else {
        echo "خطأ: " . $e->getMessage();
    }
}
?>
