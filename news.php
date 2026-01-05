<?php
$page_title = "المركز الإعلامي";
include 'includes/header.php';

// Fetch all news/announcements from database
$stmt = $pdo->query("SELECT * FROM posts WHERE type='news' OR type='announcement' ORDER BY created_at DESC");
$news = $stmt->fetchAll();
?>

<!-- Page Header -->
<?php
    $news_bg = !empty($site['news_bg']) && file_exists('assets/uploads/' . $site['news_bg']) 
                ? 'assets/uploads/' . $site['news_bg'] 
                : 'assets/images/hero1.png';
?>
<section class="page-header" style="background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('<?php echo $news_bg; ?>'); background-size: cover; background-position: center;">
    <div class="container" data-aos="fade-up">
        <h1 class="responsive-title">المركز الإعلامي</h1>
        <p class="responsive-p">تابع أحدث أخبارنا، تقاريرنا، وإعلاناتنا الرسمية.</p>
    </div>
</section>

<!-- News Section -->
<section class="news-section">
    <div class="news-grid">
        <?php if(!empty($news)): ?>
            <?php foreach($news as $item): ?>
            <div class="news-card" data-aos="fade-up">
                <div class="news-img-container">
                    <?php 
                    $img_src = "";
                    if(!empty($item['image']) && file_exists('assets/uploads/' . $item['image'])) {
                        $img_src = 'assets/uploads/' . $item['image'];
                    } else {
                        // Fallback to existing images
                        $img_src = 'assets/images/hero-bg.jpg';
                    }
                    ?>
                    <img src="<?php echo $img_src; ?>" class="news-img" style="<?php echo empty($item['image']) ? 'opacity: 0.6;' : ''; ?>">
                    
                    <span class="news-badge">
                        <?php echo $item['type'] == 'news' ? 'خبر' : 'إعلان'; ?>
                    </span>
                </div>
                <div class="news-content">
                    <div class="news-date">
                        <i class="far fa-calendar-alt" style="margin-left: 5px;"></i> <?php echo date('Y-m-d', strtotime($item['created_at'])); ?>
                    </div>
                    <h3 class="news-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="news-excerpt">
                        <?php echo mb_substr(strip_tags($item['content']), 0, 120) . '...'; ?>
                    </p>
                    <a href="news-details.php?id=<?php echo $item['id']; ?>" class="read-more-link">
                        اقرأ المزيد <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px;">
                <i class="fas fa-newspaper" style="font-size: 4rem; color: #eee; margin-bottom: 20px;"></i>
                <p style="color: var(--text-muted);">لا توجد أخبار للمعاينة حالياً.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Subscription Section (Simple) -->
<section class="subscription-section">
    <div data-aos="zoom-in">
        <h2 style="font-size: 2rem; margin-bottom: 15px;">اشترك في نشرتنا البريدية</h2>
        <p style="color: var(--text-muted); margin-bottom: 30px;">كن أول من يعلم بمشاريعنا وفعالياتنا القادمة عبر بريدك الإلكتروني.</p>
        <form class="subscribe-form">
            <input type="email" placeholder="بريدك الإلكتروني" class="subscribe-input">
            <button type="button" class="btn-donate subscribe-btn">اشترك</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
