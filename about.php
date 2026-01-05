<?php
$page_title = "من نحن";
include 'includes/header.php';
?>

<!-- Page Header -->
<?php
    $about_img = !empty($site['about_bg']) && file_exists('assets/uploads/' . $site['about_bg']) 
                 ? 'assets/uploads/' . $site['about_bg'] 
                 : 'assets/images/hero1.png';
?>
<section class="page-header" style="background: linear-gradient(rgba(15, 118, 110, 0.9), rgba(6, 78, 59, 0.9)), url('<?php echo $about_img; ?>'); background-size: cover; background-position: center;">
    <div class="container" data-aos="fade-up">
        <h1 class="responsive-title">من نحن</h1>
        <p class="responsive-p">تعرف على حكايتنا، ورؤيتنا لمستقبل أفضل في تهامة واليمن.</p>
    </div>
</section>

<!-- About Story Section -->
<section class="about-story-section">
    <div class="about-grid">
        <div class="about-text-content" data-aos="fade-right">
            <h2 class="about-title"><?php echo htmlspecialchars($site['about_story_title'] ?? 'قصة العطاء'); ?></h2>
            <div class="about-text">
                <?php echo nl2br(htmlspecialchars($site['about_story_content'] ?? '')); ?>
            </div>
        </div>
        <div data-aos="fade-left" class="about-image-container">
            <div style="position: relative; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <img src="assets/images/hero1.png" alt="فريق العمل" style="width: 100%; display: block;">
                <div style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; padding: 20px; border-radius: 30px 0 0 0;">
                    <span style="font-size: 2rem; font-weight: 800;"><?php echo $site['about_years_exp'] ?? '15'; ?>+</span>
                    <p style="margin: 0;">عاماً من العطاء</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="about-section secondary-bg">
    <div class="vision-grid">
        <!-- Vision -->
        <div class="card" data-aos="fade-up" style="padding: 50px; text-align: center; border-bottom: 6px solid var(--primary);">
            <div style="font-size: 3rem; color: var(--primary); margin-bottom: 20px;">
                <i class="fas fa-eye"></i>
            </div>
            <h3 style="font-size: 1.8rem; margin-bottom: 20px;">رؤيتنا</h3>
            <p style="color: var(--text-muted); line-height: 1.7;"><?php echo htmlspecialchars($site['about_vision'] ?? 'أن نكون المنظمة الرائدة في تحويل التحديات الإنسانية في تهامة إلى فرص نمو وازدهار، وبناء مجتمعات مكتفية ذاتياً وتتمتع بالكرامة.'); ?></p>
        </div>

        <!-- Mission -->
        <div class="card" data-aos="fade-up" data-aos-delay="100" style="padding: 50px; text-align: center; border-bottom: 6px solid var(--secondary);">
            <div style="font-size: 3rem; color: var(--secondary); margin-bottom: 20px;">
                <i class="fas fa-bullseye"></i>
            </div>
            <h3 style="font-size: 1.8rem; margin-bottom: 20px;">رسالتنا</h3>
            <p style="color: var(--text-muted); line-height: 1.7;"><?php echo htmlspecialchars($site['about_mission'] ?? 'تمكين الأفراد والمجتمعات من خلال تنفيذ مشاريع إبداعية في التعليم، الصحة، وتوفير سبل العيش الكريمة، مع ضمان وصول المساعدات لمستحقيها بكل أمانة.'); ?></p>
        </div>

        <!-- Values -->
        <div class="card" data-aos="fade-up" data-aos-delay="200" style="padding: 50px; text-align: center; border-bottom: 6px solid #6366f1;">
            <div style="font-size: 3rem; color: #6366f1; margin-bottom: 20px;">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3 style="font-size: 1.8rem; margin-bottom: 20px;">قيمنا</h3>
            <ul style="list-style: none; padding: 0; color: var(--text-muted); line-height: 2;">
                <li><i class="fas fa-check-circle" style="color: #6366f1; margin-left:10px;"></i> الشفافية المطلقة</li>
                <li><i class="fas fa-check-circle" style="color: #6366f1; margin-left:10px;"></i> الكرامة الإنسانية</li>
                <li><i class="fas fa-check-circle" style="color: #6366f1; margin-left:10px;"></i> الاستدامة والأثر</li>
            </ul>
        </div>
    </div>
</section>

<!-- Values List (Premium Icons) -->
<section class="about-section">
    <div class="section-title" data-aos="fade-down" style="margin-bottom: 60px;">
        <h2 class="responsive-h2">نعمل وفق معايير عالمية</h2>
        <div class="title-line"></div>
    </div>
    
    <div class="standards-grid">
        <div data-aos="zoom-in" style="width: 150px;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin: 0 auto 15px;">
                <i class="fas fa-shield-check"></i>
            </div>
            <h4 style="font-weight: 700;">المصداقية</h4>
        </div>
        <div data-aos="zoom-in" data-aos-delay="100" style="width: 150px;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin: 0 auto 15px;">
                <i class="fas fa-users-gear"></i>
            </div>
            <h4 style="font-weight: 700;">العمل الجماعي</h4>
        </div>
        <div data-aos="zoom-in" data-aos-delay="200" style="width: 150px;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin: 0 auto 15px;">
                <i class="fas fa-chart-line-up"></i>
            </div>
            <h4 style="font-weight: 700;">التطوير المستمر</h4>
        </div>
        <div data-aos="zoom-in" data-aos-delay="300" style="width: 150px;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin: 0 auto 15px;">
                <i class="fas fa-globe"></i>
            </div>
            <h4 style="font-weight: 700;">الأثر الإقليمي</h4>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
