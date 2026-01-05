<?php
$page_title = "مشاريعنا";
include 'includes/header.php';

// Fetch all projects from database
// Note: We use the same fallback logic as index.php
$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll();

if (empty($projects)) {
    $stmt = $pdo->query("SELECT * FROM posts WHERE type='project' OR type='news' ORDER BY created_at DESC");
    $projects = $stmt->fetchAll();
}
?>

<!-- Page Header -->
<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/hero1.png'); background-size: cover; background-position: center;">
    <div class="container" data-aos="fade-up">
        <h1 class="responsive-title">مشاريعنا وتدخلاتنا</h1>
        <p class="responsive-p" style="max-width: 800px; margin: 0 auto;">نعمل على الأرض لنصنع الفرق. استعرض قائمة المشاريع التي قمنا بتنفيذها والمشاريع قيد التنفيذ.</p>
    </div>
</section>

<!-- Projects Filtering -->
<section class="projects-filter-section secondary-bg">
    <div class="filter-container">
        <button class="filter-btn active">الكل</button>
        <button class="filter-btn">إغاثية</button>
        <button class="filter-btn">تنموية</button>
        <button class="filter-btn">تعليمية</button>
        <button class="filter-btn">صحية</button>
    </div>
</section>

<!-- Projects Grid -->
<section class="projects-section">
    <div class="project-grid">
        <?php if(!empty($projects)): ?>
            <?php $i = 0; foreach($projects as $proj): 
                $img = "";
                if (!empty($proj['image']) && file_exists('assets/uploads/' . $proj['image'])) {
                    $img = 'assets/uploads/' . $proj['image'];
                } elseif (!empty($proj['main_image']) && file_exists('assets/uploads/' . $proj['main_image'])) {
                    $img = 'assets/uploads/' . $proj['main_image'];
                } else {
                    $img = ($i % 2 == 0) ? 'assets/images/p1.png' : 'assets/images/p2.png';
                }
                $i++;
            ?>
            <div class="project-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100; ?>">
                <div class="project-img" style="background-image: url('<?php echo $img; ?>'); position: relative; height: 280px;">
                    <span style="position: absolute; top: 20px; left: 20px; background: var(--secondary); color: var(--primary-deep); padding: 5px 15px; border-radius: 50px; font-weight: 800; font-size: 0.8rem;">
                        <?php echo isset($proj['status']) ? $proj['status'] : 'مكتمل'; ?>
                    </span>
                </div>
                <div class="project-info" style="padding: 30px;">
                    <div style="color: var(--primary); font-size: 0.9rem; font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-folder"></i> <?php echo isset($proj['type']) ? $proj['type'] : 'مشروع عام'; ?>
                    </div>
                    <h3 style="font-size: 1.6rem; margin-bottom: 15px;"><?php echo htmlspecialchars($proj['title']); ?></h3>
                    <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 25px; min-height: 80px;">
                        <?php echo mb_substr(strip_tags($proj['content'] ?? $proj['description']), 0, 150) . '...'; ?>
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #eee;">
                        <a href="project-details.php?id=<?php echo $proj['id']; ?>" class="btn-donate" style="padding: 8px 25px; font-size: 0.9rem;">عرض التفاصيل</a>
                        <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="far fa-calendar-alt"></i> <?php echo date('Y-m-d', strtotime($proj['created_at'])); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px;">
                <i class="fas fa-box-open" style="font-size: 4rem; color: #eee; margin-bottom: 20px;"></i>
                <p style="color: var(--text-muted);">لا توجد مشاريع مضافة حالياً في قاعدة البيانات.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Donation CTA -->
<section class="cta-section">
    <div class="cta-content">
        <h2 style="font-size: 2.2rem; margin-bottom: 15px;">هل لديك فكرة لمشروع جديد؟</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem;">نحن في منظمة تهامة الخيرية نرحب دائماً بالشراكات والمقترحات التي تخدم المجتمع المحلي.</p>
    </div>
    <div class="cta-action">
        <a href="contact.php" class="btn-donate large-btn">تواصل معنا الآن</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
