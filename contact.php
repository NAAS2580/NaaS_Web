<?php
$page_title = "اتصل بنا / الشكاوى والمقترحات";
include 'includes/header.php';

$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $msg = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO messages (sender_name, email, phone, type, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $phone, $type, $msg])) {
        $message_sent = true;
    }
}
?>

<!-- Page Header -->
<?php
    $contact_bg = !empty($site['contact_bg']) && file_exists('assets/uploads/' . $site['contact_bg']) 
                ? 'assets/uploads/' . $site['contact_bg'] 
                : 'assets/images/hero1.png';
?>
<section class="page-header" style="background: linear-gradient(rgba(15, 118, 110, 0.8), rgba(6, 78, 59, 0.8)), url('<?php echo $contact_bg; ?>'); background-size: cover; background-position: center;">
    <div class="container" data-aos="fade-up">
        <h1 class="responsive-title">اتصل بنا</h1>
        <p class="responsive-p">نحن هنا للاستماع إليك، استفساراتك ومقترحاتك هي سر نجاحنا.</p>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="contact-section secondary-bg">
    <div class="contact-info-grid">
        <div class="card" data-aos="fade-up" style="padding: 30px; text-align: center; border-radius: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(15, 118, 110, 0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem;">
                <i class="fas fa-phone"></i>
            </div>
            <h4 style="margin-bottom: 10px;">اتصل بنا</h4>
            <p style="color: var(--text-muted);"><?php echo $site['phone']; ?></p>
        </div>
        <div class="card" data-aos="fade-up" data-aos-delay="100" style="padding: 30px; text-align: center; border-radius: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(15, 118, 110, 0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem;">
                <i class="fas fa-envelope"></i>
            </div>
            <h4 style="margin-bottom: 10px;">البريد الإلكتروني</h4>
            <p style="color: var(--text-muted);"><?php echo $site['email']; ?></p>
        </div>
        <div class="card" data-aos="fade-up" data-aos-delay="200" style="padding: 30px; text-align: center; border-radius: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(15, 118, 110, 0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem;">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4 style="margin-bottom: 10px;">الموقع</h4>
            <p style="color: var(--text-muted);"><?php echo htmlspecialchars($site['address']); ?></p>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="contact-form-grid">
        <div data-aos="fade-right">
            <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 25px;">أرسل لنا رسالة</h2>
            <p style="color: var(--text-muted); margin-bottom: 35px; line-height: 1.8;">سواء كان لديك مشروع مقترح، أو شكوى، أو مجرد استفسار، فريقنا جاهز للتواصل معك ومساعدتك في أقرب وقت ممكن.</p>
            <div style="background: white; padding: 40px; border-radius: 25px; box-shadow: 0 15px 45px rgba(0,0,0,0.05);">
                <?php if($message_sent): ?>
                    <div style="background: #ecfdf5; color: #065f46; padding: 25px; border-radius: 15px; text-align: center; border: 1px solid #a7f3d0; margin-bottom: 20px;">
                        <i class="fas fa-check-circle fa-3x" style="margin-bottom: 15px; display: block;"></i>
                        <h3 style="margin-bottom: 5px;">تم الإرسال بنجاح!</h3>
                        <p>شكراً لتواصلك معنا، سيقوم فريقنا بمراجعة رسالتك والرد عليك قريباً.</p>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label style="font-weight: 600; display: block; margin-bottom: 10px; font-size: 0.9rem;">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" required placeholder="أدخل اسمك" style="width: 100%; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; background: #f8fafc;">
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 600; display: block; margin-bottom: 10px; font-size: 0.9rem;">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" placeholder="00967..." style="width: 100%; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; background: #f8fafc;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 10px; font-size: 0.9rem;">نوع الرسالة</label>
                        <select name="type" class="form-control" style="width: 100%; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; background: #f8fafc;">
                            <option value="inquiry">استفسار عام</option>
                            <option value="complaint">شكوى</option>
                            <option value="suggestion">مقترح لتطوير مشروع</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 10px; font-size: 0.9rem;">نص الرسالة</label>
                        <textarea name="message" class="form-control" rows="6" required placeholder="كيف يمكننا مساعدتك؟" style="width: 100%; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; background: #f8fafc; resize: none;"></textarea>
                    </div>

                    <button type="submit" class="btn-donate" style="border: none; width: 100%; padding: 16px; font-size: 1.1rem; border-radius: 12px; margin-top: 30px; cursor: pointer;">إرسال الرسالة <i class="fas fa-paper-plane" style="margin-right: 10px;"></i></button>
                </form>
            </div>
        </div>
        <div data-aos="fade-left" style="height: 100%;">
            <div style="height: 500px; border-radius: 25px; overflow: hidden; box-shadow: 0 15px 45px rgba(0,0,0,0.05);">
                <!-- Placeholder for Map -->
                <div style="width: 100%; height: 100%; background: #e2e8f0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; text-align: center; padding: 40px;">
                    <i class="fas fa-map-marked-alt fa-5x" style="margin-bottom: 20px;"></i>
                    <h3>موقعنا الميداني</h3>
                    <p><?php echo htmlspecialchars($site['address']); ?></p>
                    <p style="font-size: 0.8rem; margin-top: 20px;">(يمكنك دمج خريطة جوجل هنا بسهولة عبر كود iframe من لوحة التحكم)</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

