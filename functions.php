<?php
define( 'TEMP_DIR_URI', get_template_directory_uri() );

function user_filter_assets() {

    wp_enqueue_script('userjs', TEMP_DIR_URI .'/javascript/users-app.js', '', null, true);

    wp_localize_script( 'userjs', 'users_filter', array(
        'nonce'    => wp_create_nonce( 'users_filter' ),
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
}
add_action('wp_enqueue_scripts', 'user_filter_assets', 100);

add_action('wp_ajax_user_filter', 'ajax_user_filter');
function ajax_user_filter(){

	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'users_filter' ) )
        die('Permission denied');

	$set_page = $_POST['set_page'];

	$users_per_page = 8;
	$new_args = array(
        'exclude' => array('1'),
        'number' => $users_per_page,
		'paged' => $set_page,
		'role' => $_POST['role'],
		'orderby' => 'user_login',
		'order' => $_POST['order'],
    );
	$new_user_query = new WP_User_Query( $new_args );
	
	$table = '';
    if ( ! empty( $new_user_query->get_results() ) ) {
        foreach ( $new_user_query->get_results() as $user ) {
            $user_meta = get_userdata($user->ID);
			$user_role = $user_meta->roles[0];
			
            $table .= '<tr>';
            $table .= '<td>' . $user->display_name . '</td>';
            $table .= '<td>' . $user->user_email . '</td>';
            $table .= '<td>' . $user_role . '</td>';
            $table .= '</tr>';
        }
	} 
	
	$total_users = $new_user_query->total_users; 
	$page_count = ceil( $total_users / $users_per_page );

	$new_pagination = '';
	for ($i = 1; $i <= $page_count; $i++) {
		$new_pagination .= '<li class="page-item"><a class="page-link" href="#">'.$i.'</a></li>';
	}

	$results = array(
		'table' =>  $table,
		'pagination' => $new_pagination,

	);
	echo json_encode( $results );;

	wp_die();
}