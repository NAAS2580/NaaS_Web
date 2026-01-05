<?php
$page_title = "الرئيسية";
include 'includes/header.php';

// Fetch latest activities (Projects & News) for display
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
$projects = $stmt->fetchAll();

// Fetch counts for stats
$total_projects = $pdo->query("SELECT COUNT(*) FROM posts WHERE type='project' OR type='news'")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$starting_year = 2010; // Can be moved to settings later
$years_of_giving = date('Y') - $starting_year;
?>

<!-- Hero Section -->
<?php
    $hero_img = !empty($site['hero_bg']) && file_exists('assets/uploads/' . $site['hero_bg']) 
                ? 'assets/uploads/' . $site['hero_bg'] 
                : 'assets/images/hero1.png';
?>
<section class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?php echo $hero_img; ?>'); background-size: cover; background-position: center;">
    <div class="hero-content" data-aos="fade-up">
        <h1><?php echo htmlspecialchars($site['site_name']); ?></h1>
        <p><?php echo htmlspecialchars($site['site_description']); ?></p>
        <div class="hero-btns">
            <a href="projects.php" class="btn-donate secondary">استكشف مشاريعنا</a>
            <a href="contact.php" class="btn-donate">اتصل بنا الآن</a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats glass" style="margin: -60px 10% 80px; padding: 50px; border-radius: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center; position: relative; z-index: 10;">
    <div class="stat-item" data-aos="zoom-in" data-aos-delay="100">
        <div style="font-size: 3.5rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;"><?php echo $total_projects; ?></div>
        <p style="font-weight: 600; color: var(--text-muted);">مشروع إنساني</p>
    </div>
    <div class="stat-item" data-aos="zoom-in" data-aos-delay="200">
        <div style="font-size: 3.5rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;"><?php echo $years_of_giving; ?></div>
        <p style="font-weight: 600; color: var(--text-muted);">عاماً من العطاء</p>
    </div>
</section>

<!-- Latest Projects -->
<section class="projects" style="padding: 100px 8%;">
    <div class="section-title" data-aos="fade-down" style="margin-bottom: 60px;">
        <span style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">نشاطاتنا الأخيرة</span>
        <h2 style="font-size: 3rem; margin-top: 10px;">مشاريعنا الميدانية</h2>
        <div style="width: 80px; height: 4px; background: var(--primary); margin: 20px auto; border-radius: 2px;"></div>
    </div>

    <div class="project-grid">
        <?php if(!empty($projects)): ?>
            <?php $i = 0; foreach($projects as $proj): 
                // Determine image path
                $img = "";
                if (!empty($proj['image']) && file_exists('assets/uploads/' . $proj['image'])) {
                    $img = 'assets/uploads/' . $proj['image'];
                } elseif (!empty($proj['main_image']) && file_exists('assets/uploads/' . $proj['main_image'])) {
                     $img = 'assets/uploads/' . $proj['main_image'];
                } else {
                    $img = ($i == 0) ? 'assets/images/p1.png' : (($i == 1) ? 'assets/images/p2.png' : 'assets/images/p1.png');
                }
                $i++;
            ?>
            <div class="project-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="project-img" style="background-image: url('<?php echo $img; ?>');">
                    <div style="padding: 10px 20px; background: var(--primary); color: white; display: inline-block; border-radius: 0 0 15px 0; font-size: 0.8rem; font-weight: 700;">
                        <?php echo isset($proj['type']) ? $proj['type'] : 'مشروع'; ?>
                    </div>
                </div>
                <div class="project-info">
                    <h3 style="margin-bottom: 15px;"><?php echo htmlspecialchars($proj['title']); ?></h3>
                    <p style="color: var(--text-muted); margin-bottom: 25px;"><?php echo mb_substr(strip_tags($proj['content'] ?? $proj['description']), 0, 100) . '...'; ?></p>
                    <a href="project-details.php?id=<?php echo $proj['id']; ?>" style="color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 10px;">
                        تفاصيل المشروع <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1/-1; color: var(--text-muted);">لا توجد مشاريع مضافة حالياً.</p>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section style="background: var(--primary-deep); padding: 80px 5%; text-align: center; color: white;">
    <div data-aos="zoom-in">
        <h2 style="font-size: 2.5rem; margin-bottom: 20px;">كن جزءاً من التغيير اليوم</h2>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto 40px;">مساهمتك مهما كانت بسيطة، تصنع فرقاً كبيراً في حياة من هم في أمس الحاجة إليها.</p>
        <a href="contact.php" class="btn-donate" style="background: var(--accent); color: var(--primary-deep); font-size: 1.2rem; padding: 15px 50px;">تواصل معنا للمساهمة</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

