<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = $_POST['site_name'];
    $site_description = $_POST['site_description'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $facebook = $_POST['facebook_url'];
    $twitter = $_POST['twitter_url'];
    $instagram = $_POST['instagram_url'];

    // About Us Content
    $about_story_title = $_POST['about_story_title'] ?? '';
    $about_story_content = $_POST['about_story_content'] ?? '';
    $about_vision = $_POST['about_vision'] ?? '';
    $about_mission = $_POST['about_mission'] ?? '';
    $about_years_exp = $_POST['about_years_exp'] ?? 15;

    // Handle Logo Upload
    $logo_sql = "";
    if (!empty($_FILES['logo']['name'])) {
        $logo_name = time() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], "../assets/uploads/" . $logo_name);
        $logo_sql = ", site_logo = '$logo_name'";
    }

    // Handle Background Images Upload
    $hero_bg_sql = "";
    if (!empty($_FILES['hero_bg']['name'])) {
        $img_name = 'hero_bg_' . time() . '_' . $_FILES['hero_bg']['name'];
        move_uploaded_file($_FILES['hero_bg']['tmp_name'], "../assets/uploads/" . $img_name);
        $hero_bg_sql = ", hero_bg = '$img_name'";
    }

    $about_bg_sql = "";
    if (!empty($_FILES['about_bg']['name'])) {
        $img_name = 'about_bg_' . time() . '_' . $_FILES['about_bg']['name'];
        move_uploaded_file($_FILES['about_bg']['tmp_name'], "../assets/uploads/" . $img_name);
        $about_bg_sql = ", about_bg = '$img_name'";
    }

    $contact_bg_sql = "";
    if (!empty($_FILES['contact_bg']['name'])) {
        $img_name = 'contact_bg_' . time() . '_' . $_FILES['contact_bg']['name'];
        move_uploaded_file($_FILES['contact_bg']['tmp_name'], "../assets/uploads/" . $img_name);
        $contact_bg_sql = ", contact_bg = '$img_name'";
    }

    $news_bg_sql = "";
    if (!empty($_FILES['news_bg']['name'])) {
        $img_name = 'news_bg_' . time() . '_' . $_FILES['news_bg']['name'];
        move_uploaded_file($_FILES['news_bg']['tmp_name'], "../assets/uploads/" . $img_name);
        $news_bg_sql = ", news_bg = '$img_name'";
    }

    $sql = "UPDATE settings SET 
            site_name = ?, 
            site_description = ?, 
            phone = ?, 
            email = ?, 
            address = ?, 
            facebook_url = ?, 
            twitter_url = ?, 
            instagram_url = ?,
            about_story_title = ?,
            about_story_content = ?,
            about_vision = ?,
            about_mission = ?,
            about_years_exp = ?
            $logo_sql 
            $hero_bg_sql
            $about_bg_sql
            $contact_bg_sql
            $news_bg_sql
            WHERE id = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $site_name, $site_description, $phone, $email, $address, $facebook, $twitter, $instagram,
        $about_story_title, $about_story_content, $about_vision, $about_mission, $about_years_exp
    ]);
    
    $success = "تم تحديث الإعدادات بنجاح";
    
    // Refresh settings
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-gauge-high"></i> لوحة التحكم</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-chart-pie"></i> الإحصائيات</a></li>
        <li><a href="news.php"><i class="fas fa-newspaper"></i> الأخبار والمشاريع</a></li>
        <li><a href="messages.php"><i class="fas fa-envelope"></i> الرسائل الواردة</a></li>
        <li><a href="settings.php" class="active"><i class="fas fa-sliders"></i> إعدادات الموقع</a></li>
        <li><a href="../index.php" target="_blank" style="color: var(--primary);"><i class="fas fa-external-link-alt"></i> عرض الموقع</a></li>
        <li style="margin-top: auto;"><a href="logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
    </ul>
</div>

<div class="overlay" id="overlay"></div>

<div class="main-content">
    <div class="mobile-header">
        <h3 style="margin:0; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-gauge-high" style="color: var(--primary);"></i>
            لوحة التحكم
        </h3>
        <button class="menu-toggle-btn" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="header-top">
        <div>
            <h2>إعدادات الموقع</h2>
            <p style="color: var(--text-muted);">تخصيص الهوية ومعلومات التواصل.</p>
        </div>
        <div class="user-info" style="display: flex; align-items: center; gap: 15px;">
            <div style="text-align: left;">
                <div style="font-weight: 700; font-size: 0.9rem;"><?php echo $_SESSION['admin_name']; ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">مدير النظام</div>
            </div>
            <div style="width: 45px; height: 45px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <?php if($success): ?>
        <div style="background: #ecfdf5; color: #065f46; padding: 16px; border-radius: var(--radius-md); margin-bottom: 30px; border: 1px solid #a7f3d0; font-weight: 600;">
            <i class="fas fa-check-circle" style="margin-left: 10px;"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 30px;">
                <div class="form-group">
                    <label><i class="fas fa-font" style="margin-left: 8px; color: var(--primary);"></i> اسم الموقع</label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name']); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-image" style="margin-left: 8px; color: var(--primary);"></i> شعار الموقع (Logo)</label>
                    <input type="file" name="logo" class="form-control">
                    <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 10px; border-radius: 8px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">الشعار الحالي:</span>
                        <span style="font-size: 0.85rem; font-weight: 600;"><?php echo $settings['site_logo']; ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-quote-right" style="margin-left: 8px; color: var(--primary);"></i> وصف الموقع (SEO)</label>
                <textarea name="site_description" class="form-control" rows="3"><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 30px;">
                <div class="form-group">
                    <label><i class="fas fa-phone" style="margin-left: 8px; color: var(--primary);"></i> رقم الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $settings['phone']; ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope" style="margin-left: 8px; color: var(--primary);"></i> البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $settings['email']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-location-dot" style="margin-left: 8px; color: var(--primary);"></i> العنوان النصي</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($settings['address']); ?>">
            </div>

            <div style="margin: 40px 0; border-top: 1px solid #f1f5f9; padding-top: 30px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 25px;"><i class="fas fa-images" style="margin-left: 10px; color: var(--primary);"></i> صور خلفيات الموقع</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label>خلفية الرئيسية (Hero)</label>
                        <input type="file" name="hero_bg" class="form-control">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">الحالي: <?php echo $settings['hero_bg'] ?? 'hero1.png'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>خلفية صفحة من نحن</label>
                        <input type="file" name="about_bg" class="form-control">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">الحالي: <?php echo $settings['about_bg'] ?? 'hero1.png'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>خلفية صفحة تواصل معنا</label>
                        <input type="file" name="contact_bg" class="form-control">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">الحالي: <?php echo $settings['contact_bg'] ?? 'hero1.png'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>خلفية المركز الإعلامي</label>
                        <input type="file" name="news_bg" class="form-control">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">الحالي: <?php echo $settings['news_bg'] ?? 'hero1.png'; ?></div>
                    </div>
                </div>
            </div>

            <div style="margin: 40px 0; border-top: 1px solid #f1f5f9; padding-top: 30px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 25px;"><i class="fas fa-share-nodes" style="margin-left: 10px; color: var(--primary);"></i> روابط التواصل الاجتماعي</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label><i class="fab fa-facebook" style="color: #1877f2;"></i> فيسبوك</label>
                        <input type="text" name="facebook_url" class="form-control" value="<?php echo $settings['facebook_url']; ?>" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-twitter" style="color: #1da1f2;"></i> تويتر</label>
                        <input type="text" name="twitter_url" class="form-control" value="<?php echo $settings['twitter_url']; ?>" placeholder="https://twitter.com/...">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-instagram" style="color: #e4405f;"></i> إنستقرام</label>
                        <input type="text" name="instagram_url" class="form-control" value="<?php echo $settings['instagram_url']; ?>" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </div>

            <div style="margin: 40px 0; border-top: 1px solid #f1f5f9; padding-top: 30px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 25px;"><i class="fas fa-address-card" style="margin-left: 10px; color: var(--primary);"></i> إدارة محتوى صفحة "من نحن"</h3>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label>عنوان القصة (مثل: قصة العطاء)</label>
                        <input type="text" name="about_story_title" class="form-control" value="<?php echo htmlspecialchars($settings['about_story_title'] ?? 'قصة العطاء'); ?>">
                    </div>
                    <div class="form-group">
                        <label>سنوات الخبرة</label>
                        <input type="number" name="about_years_exp" class="form-control" value="<?php echo $settings['about_years_exp'] ?? 15; ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>نص "قصة العطاء"</label>
                    <textarea name="about_story_content" class="form-control" rows="4"><?php echo htmlspecialchars($settings['about_story_content'] ?? ''); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>رؤيتنا</label>
                        <textarea name="about_vision" class="form-control" rows="4"><?php echo htmlspecialchars($settings['about_vision'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>رسالتنا</label>
                        <textarea name="about_mission" class="form-control" rows="4"><?php echo htmlspecialchars($settings['about_mission'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="padding: 14px 40px;">
                    <i class="fas fa-save" style="margin-left: 10px;"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Sidebar Toggle for Mobile
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.querySelector('.sidebar');
const overlay = document.getElementById('overlay');

function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

if(sidebarToggle) {
    sidebarToggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
}
</script>

</body>
</html>

