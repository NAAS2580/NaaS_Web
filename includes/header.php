<?php
include 'db.php';

// Fetch global settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$site = $stmt->fetch();

// Helper for SEO and titles
$page_title = isset($page_title) ? $page_title . " | " . $site['site_name'] : $site['site_name'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($site['site_description']); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link rel="shortcut icon" href="assets/uploads/<?php echo $site['site_favicon']; ?>" type="image/x-icon">
</head>
<body>

<header class="glass">
    <div class="logo-container" style="display: flex; align-items: center; gap: 15px;">
        <button class="mobile-menu-btn" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; gap: 15px;">
                <img src="assets/uploads/<?php echo $site['site_logo']; ?>" alt="<?php echo $site['site_name']; ?>">
                <span class="site-name"><?php echo $site['site_name']; ?></span>
            </a>
        </div>
    </div>

    <nav>
        <ul class="nav-links" id="navLinks">

            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="about.php">من نحن</a></li>
            <li><a href="projects.php">مشاريعنا</a></li>
            <li><a href="news.php">المركز الإعلامي</a></li>
            <li><a href="contact.php">اتصل بنا</a></li>
            <li class="mobile-only" style="display: none; margin-top: auto; padding: 20px; border-top: 1px solid #f1f5f9;">
                <a href="admin/index.php" class="btn-donate" style="display: block; text-align: center; background: var(--primary-deep); color: white; padding: 12px; border-radius: 12px;">
                    <i class="fas fa-user-lock" style="margin-left: 8px;"></i> تسجيل الدخول
                </a>
            </li>
        </ul>
    </nav>

    <div class="header-actions">
        <a href="admin/index.php" class="btn-donate logout-btn" style="background: var(--primary-deep);"><i class="fas fa-user-lock" style="margin-left: 8px;"></i> تسجيل الدخول</a>
    </div>
</header>
<div class="menu-overlay" id="menuOverlay"></div>

<script>
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    const menuOverlay = document.getElementById('menuOverlay');
    const closeMenu = document.getElementById('closeMenu');

    function toggleMenu() {
        navLinks.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        const icon = menuToggle.querySelector('i');
        if (navLinks.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }

    menuToggle.addEventListener('click', toggleMenu);
    menuOverlay.addEventListener('click', toggleMenu);
    if(closeMenu) closeMenu.addEventListener('click', toggleMenu);

    // Close menu when clicking links (especially for mobile)
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                toggleMenu();
            }
        });
    });
</script>
