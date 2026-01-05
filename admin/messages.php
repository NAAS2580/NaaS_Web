<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Mark as read if ID is provided
if (isset($_GET['read'])) {
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?")->execute([$_GET['read']]);
}

// Fetch site settings for header
$site_settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();

$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الرسائل - لوحة التحكم</title>
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
        <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> الرسائل الواردة</a></li>
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
            <h2>الرسائل الواردة</h2>
            <p style="color: var(--text-muted);">إدارة الشكاوى، المقترحات، والاستفسارات.</p>
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

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>المرسل</th>
                        <th>النوع</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($messages)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; display: block; opacity: 0.2;"></i>
                                لا توجد رسائل حالياً
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach($messages as $msg): ?>
                    <tr style="<?php echo $msg['is_read'] ? '' : 'background: rgba(15, 118, 110, 0.03); font-weight: 600;'; ?>">
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: #f1f5f9; color: var(--text-main); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div><?php echo htmlspecialchars($msg['sender_name']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;"><?php echo $msg['email']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                <?php 
                                    $types = [
                                        'complaint' => 'شكوى',
                                        'suggestion' => 'مقترح',
                                        'inquiry' => 'استفسار'
                                    ];
                                    echo isset($types[$msg['type']]) ? $types[$msg['type']] : $msg['type']; 
                                ?>
                            </span>
                        </td>
                        <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if($msg['is_read']): ?>
                                <span class="badge badge-success">مقروء</span>
                            <?php else: ?>
                                <span class="badge badge-warning">جديد</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button onclick='viewMessage(<?php echo json_encode($msg); ?>)' class="btn" style="padding: 6px 12px; background: #f1f5f9; font-size: 0.8rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if(!$msg['is_read']): ?>
                                    <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">
                                        تمت المراجعة
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Logic (Simplified for now) -->
<script>
function viewMessage(msg) {
    alert("من: " + msg.sender_name + "\nالموضوع: " + (msg.subject || 'بدون موضوع') + "\n\nالرسالة:\n" + msg.message);
}

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

