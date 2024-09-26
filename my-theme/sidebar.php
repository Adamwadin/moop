<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<aside id="secondary" class="widget-area">

    <div class="widget login-widget">
        <?php if (is_user_logged_in()): ?>
            <h2 class="widget-title">Welcome, <?php echo wp_get_current_user()->display_name; ?></h2>
            <p><a href="<?php echo wp_logout_url(home_url('/')); ?>">Logout</a></p>
        <?php else: ?>
            <h2 class="widget-title">Login</h2>
            <?php
            wp_login_form(array(
                'redirect' => home_url(),
            ));
            ?>
            <p><a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a></p>
        <?php endif; ?>
    </div>



</aside>