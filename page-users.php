<?php /* Template Name: страница пользователей  */ ?>
<?php get_header(); ?>
<div class="container pt-5 pb-5">
    <?php
    $user = wp_get_current_user();
		$allowed_roles = array('administrator');
    if( array_intersect($allowed_roles, $user->roles ) ) {  ?>
      <?php echo do_shortcode('[user_roles]'); ?>
    <?php } else {
        echo '<div class="alert alert-primary">Здесь ничего нет</div>';
    } ?>
</div>
<?php get_footer(); ?>