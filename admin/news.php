<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$success = "";
$error = "";

// Handle Deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: news.php?success=deleted');
    exit;
}

// Handle Adding
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $type = $_POST['type'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'active';
    $image_name = "";

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        if (!is_dir("../assets/uploads/")) {
            mkdir("../assets/uploads/", 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image_name);
    }
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, type, status, image) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $content, $type, $status, $image_name])) {
        header('Location: news.php?success=added');
        exit;
    } else {
        $error = "حدث خطأ أثناء الإضافة";
    }
}

// Handle Editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_post'])) {
    $id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image_name);
        $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, type=?, status=?, image=? WHERE id=?");
        $stmt->execute([$title, $content, $type, $status, $image_name, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, type=?, status=? WHERE id=?");
        $stmt->execute([$title, $content, $type, $status, $id]);
    }
    header('Location: news.php?success=updated');
    exit;
}

// Fetch site settings for header
$site_settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'added') $success = "تم إضافة الخبر بنجاح";
    if ($_GET['success'] == 'updated') $success = "تم تحديث البيانات بنجاح";
    if ($_GET['success'] == 'deleted') $success = "تم حذف الخبر بنجاح";
}

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأخبار والمشاريع - لوحة التحكم</title>
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
        <li><a href="news.php" class="active"><i class="fas fa-newspaper"></i> الأخبار والمشاريع</a></li>
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
            <h2>الأخبار والمشاريع</h2>
            <p style="color: var(--text-muted);">إضافة وتعديل الأنشطة والمشاريع الخاصة بالمنظمة.</p>
        </div>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-left: 10px;"></i> إضافة جديد
        </button>
    </div>

    <?php if($success): ?>
        <div style="background: #ecfdf5; color: #065f46; padding: 16px; border-radius: var(--radius-md); margin-bottom: 30px; border: 1px solid #a7f3d0; font-weight: 600;">
            <i class="fas fa-check-circle" style="margin-left: 10px;"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($posts)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 15px; display: block; opacity: 0.2;"></i>
                                لا توجد بيانات حالياً
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach($posts as $post): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php if($post['image']): ?>
                                    <img src="../assets/uploads/<?php echo $post['image']; ?>" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight: 700;"><?php echo htmlspecialchars($post['title']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;"><?php echo mb_substr(strip_tags($post['content']), 0, 50) . '...'; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                <?php echo $post['type'] == 'news' ? 'خبر' : ($post['type'] == 'project' ? 'مشروع' : 'إعلان'); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo $post['status'] == 'active' ? 'badge-success' : 'badge-warning'; ?>">
                                <?php echo $post['status'] == 'active' ? 'نشط' : ($post['status'] == 'ongoing' ? 'قيد التنفيذ' : 'مكتمل'); ?>
                            </span>
                        </td>
                        <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo date('Y-m-d', strtotime($post['created_at'])); ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($post)); ?>)" class="btn" style="padding: 6px 12px; background: #f0f9ff; color: #0ea5e9; font-size: 0.8rem;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $post['id']; ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="btn" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="width: 100%; max-width: 600px; position: relative;">
        <button onclick="closeModal('addModal')" style="position: absolute; left: 20px; top: 20px; border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h3 style="margin-bottom: 25px;">إضافة جديد</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_post" value="1">
            <div class="form-group">
                <label>العنوان</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" class="form-control">
                        <option value="news">خبر</option>
                        <option value="project">مشروع</option>
                        <option value="announcement">إعلان</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="active">نشط</option>
                        <option value="ongoing">قيد التنفيذ</option>
                        <option value="completed">مكتمل</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الصورة</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>المحتوى</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">حفظ البيانات</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="width: 100%; max-width: 600px; position: relative;">
        <button onclick="closeModal('editModal')" style="position: absolute; left: 20px; top: 20px; border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h3 style="margin-bottom: 25px;">تعديل البيانات</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_post" value="1">
            <input type="hidden" name="post_id" id="edit_id">
            <div class="form-group">
                <label>العنوان</label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" id="edit_type" class="form-control">
                        <option value="news">خبر</option>
                        <option value="project">مشروع</option>
                        <option value="announcement">إعلان</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="active">نشط</option>
                        <option value="ongoing">قيد التنفيذ</option>
                        <option value="completed">مكتمل</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>تغيير الصورة</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>المحتوى</label>
                <textarea name="content" id="edit_content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">تحديث البيانات</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    function openEditModal(post) {
        document.getElementById('edit_id').value = post.id;
        document.getElementById('edit_title').value = post.title;
        document.getElementById('edit_content').value = post.content;
        document.getElementById('edit_type').value = post.type;
        document.getElementById('edit_status').value = post.status;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    // Close on outside click
    window.onclick = function(event) {
        if (event.target == document.getElementById('addModal')) closeModal('addModal');
        if (event.target == document.getElementById('editModal')) closeModal('editModal');
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
