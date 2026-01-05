<footer>
    <div class="footer-grid">
        <div class="footer-about">
            <h3><?php echo htmlspecialchars($site['site_name']); ?></h3>
            <p><?php echo htmlspecialchars($site['site_description']); ?></p>
            <div class="social-links">
                <?php if($site['facebook_url']): ?><a href="<?php echo $site['facebook_url']; ?>"><i class="fab fa-facebook"></i></a><?php endif; ?>
                <?php if($site['twitter_url']): ?><a href="<?php echo $site['twitter_url']; ?>"><i class="fab fa-twitter"></i></a><?php endif; ?>
                <?php if($site['instagram_url']): ?><a href="<?php echo $site['instagram_url']; ?>"><i class="fab fa-instagram"></i></a><?php endif; ?>
            </div>
        </div>
        
        <div class="footer-links">
            <h4>روابط سريعة</h4>
            <ul>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="projects.php">مشاريعنا المنفذة</a></li>
                <li><a href="volunteers.php">انضم كمتطوع</a></li>
                <li><a href="reports.php">التقارير السنوية</a></li>
            </ul>
        </div>
        
        <div class="footer-contact">
            <h4>تواصل معنا</h4>
            <p><i class="fas fa-phone"></i> <?php echo $site['phone']; ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo $site['email']; ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo $site['address']; ?></p>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> | <?php echo htmlspecialchars($site['site_name']); ?></p>
        <div style="margin-top: 10px; font-size: 0.8rem; opacity: 0.6;">
            <a href="admin/index.php" style="color: white; border-bottom: 1px dashed rgba(255,255,255,0.3);">دخول الإدارة <i class="fas fa-lock"></i></a>
        </div>
    </div>
</footer>

<!-- JS Scripts -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
      duration: 1000,
      once: true
  });
</script>
<script src="assets/js/main.js"></script>
</body>
</html>
