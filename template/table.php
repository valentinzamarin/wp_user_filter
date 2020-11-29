<h1><?php the_title(); ?></h1>
<table class="table table-hover">
<thead>
    <tr>
        <th scope="col">
        <select class="custom-select" id="names">
            <option value="ASC">Name</option>
            <option value="ASC">Sort by ASC</option>
            <option value="DESC">Sort by DESC</option>
        </select>
        </th>
        <th scope="col">Email</th>
        <th scope="col">
        <select class="custom-select" id="roles">
            <option value="">Role</option>
            <option value="Subscriber">Subscriber</option>
            <option value="Author">Author</option>
            <option value="Editor">Editor</option>
        </select>
        </th>
    </tr>
</thead>

<?php
$users_per_page = 8;
$args = array(
    'exclude' => array('1'), // ислючаем admin
    'number' => $users_per_page,
    'paged' => 1,
);

// The Query
$user_query = new WP_User_Query( $args );

// User Loop
echo '<tbody id="table-response">';
if ( ! empty( $user_query->get_results() ) ) {
    foreach ( $user_query->get_results() as $user ) {
        $user_meta = get_userdata($user->ID);
        $user_role = $user_meta->roles[0];
        echo '<tr>';
            echo '<td>' . $user->display_name . '</td>';
            echo '<td>' . $user->user_email . '</td>';
            echo '<td>' . $user_role . '</td>';
        echo '</tr>';
    }
}
echo '</tbody>';
?>
</table>
<?php
    $total_users = $user_query->total_users;
    $page_count = ceil( $total_users / $users_per_page );
?>
<nav class="d-flex justify-content-center mt-3">
    <ul class="pagination">
        <?php
            for ($i = 1; $i <= $page_count; $i++) {
                echo '<li class="page-item"><a class="page-link" href="#">'.$i.'</a></li>';
            }
        ?>
    </ul>
</nav>
