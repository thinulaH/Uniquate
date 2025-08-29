<?php
// includes/footer.php
?>

<footer class="footer">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4 style="margin-bottom: 1rem; color: white;">University Hall Booking</h4>
                <p>Your premier destination for booking university halls and event spaces. Making event planning simple and efficient.</p>
            </div>
            
            <div>
                <h4 style="margin-bottom: 1rem; color: white;">Quick Links</h4>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="index.php" style="color: #ccc; text-decoration: none;">Home</a>
                    <a href="halls.php" style="color: #ccc; text-decoration: none;">Browse Halls</a>
                    <a href="help.php" style="color: #ccc; text-decoration: none;">Help & Support</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="my_bookings.php" style="color: #ccc; text-decoration: none;">My Bookings</a>
                    <?php endif; ?>
                    <a href="team.php" style="color: #ccc; text-decoration: none;">Team</a>
                </div>
            </div>
            
            <div>
                <h4 style="margin-bottom: 1rem; color: white;">Contact Info</h4>
                <p>📧 2023s20228@stu.cmb.ac.lk</p>
                <p>📞 (+94) 70 669 2736 </p>
                <p>📍 University of Colombo, 94 Cumaratunga Munidasawa Mw, Colombo 00700</p>
            </div>
            
            <div>
                <h4 style="margin-bottom: 1rem; color: white;">Operating Hours</h4>
                <p>Monday - Friday: 8:00 AM - 5:00 PM</p>
                <p>Saturday: 9:00 AM - 2:00 PM</p>
                <p>Sunday: Closed</p>
            </div>
        </div>
        
        <div style="border-top: 1px solid #555; padding-top: 2rem; text-align: center;">
            <p>&copy; <?= date('Y') ?> Uniquate. All rights reserved.</p>
            <p style="margin-top: 0.5rem; color: #999; font-size: 0.9rem;">
                Developed for CS2001 Project | Group 59 | University of Colombo
            </p>
        </div>
    </div>
</footer>

</body>
</html>