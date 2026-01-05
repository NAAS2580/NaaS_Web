<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Count stats
$msg_count = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
$post_count = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();

// Fetch site settings for header
$site_settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الرئيسية</title>
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
        <li><a href="dashboard.php" class="active"><i class="fas fa-chart-pie"></i> الإحصائيات</a></li>
        <li><a href="news.php"><i class="fas fa-newspaper"></i> الأخبار والمشاريع</a></li>
        <li><a href="messages.php"><i class="fas fa-envelope"></i> الرسائل الواردة</a></li>
        <li><a href="settings.php"><i class="fas fa-sliders"></i> إعدادات الموقع</a></li>
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
            <h2>الرئيسية</h2>
            <p style="color: var(--text-muted);">مرحباً بك، <?php echo $_SESSION['admin_name']; ?></p>
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

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="stat-info">
                <h4>رسائل جديدة</h4>
                <div class="number"><?php echo $msg_count; ?></div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-info">
                <h4>المشاريع النشطة</h4>
                <div class="number"><?php echo $post_count; ?></div>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h4>إجمالي الزيارات</h4>
                <div class="number">1,284</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="font-size: 1.1rem; font-weight: 700;"><i class="fas fa-clock-rotate-left" style="margin-left: 10px; color: var(--primary);"></i> آخر الرسائل الواردة</h3>
            <a href="messages.php" class="btn" style="padding: 8px 16px; background: #f1f5f9; color: var(--text-main); font-size: 0.85rem;">عرض الكل</a>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>المرسل</th>
                        <th>الموضوع</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
                    while ($row = $stmt->fetch()) {
                        $badgeClass = $row['is_read'] ? 'badge-success' : 'badge-warning';
                        $statusText = $row['is_read'] ? 'مقروء' : 'جديد';
                        $types = ['complaint' => 'شكوى', 'suggestion' => 'مقترح', 'inquiry' => 'استفسار'];
                        $typeText = isset($types[$row['type']]) ? $types[$row['type']] : $row['type'];
                        echo "<tr>
                                <td style='font-weight: 600;'>{$row['sender_name']}</td>
                                <td>" . (strlen($row['subject']) > 30 ? mb_substr($row['subject'], 0, 30) . '...' : $row['subject']) . "</td>
                                <td style='color: var(--text-muted); font-size: 0.85rem;'>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>
                                <td><span class='badge' style='background: #e0f2fe; color: #0369a1;'>{$typeText}</span></td>
                                <td><span class='badge {$badgeClass}'>{$statusText}</span></td>
                                <td>
                                    <a href='messages.php?id={$row['id']}' class='btn' style='padding: 6px 12px; background: var(--primary); color: white; font-size: 0.8rem;'>
                                        معاينة
                                    </a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
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

