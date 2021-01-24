<?php
$fields = get_option("umcerts_verify_fields", "");
$fields = trim($fields, ",");
$fields = explode(",", $fields);

$users = array();

foreach ($fields as $field)
{
    $args = array(
        'role'          =>  'subscriber',
        'meta_key'      =>  $field.'_verified',
        'meta_value'    =>  array('Pending', 'No')
    );

    if (!isset($users[$field]))
        $users[$field] = array();

    $users[$field] = get_users($args);
}

//print_r($users);

$um_form = get_page_by_title("User Profile", OBJECT, 'um_form');
?>
<div class="um-certs-wrapper">
    <div class="um-certs-wrapper-pending">
        <h4>Pending Approvals</h4>
        <table class="umcerts-table">
            <thead>
            <tr>
                <th>Business</th>
                <th>User</th>
                <th>Certificate</th>
                <th>View Cert</th>
                <th>Activation</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $field => $user_list)
            {
                foreach ($user_list as $user)
                {
                    um_fetch_user($user->id);
                    $rclass = (um_user($field.'_verified') == "Pending") ? 'pending' : 'bad';
                    ?>
                    <tr class="<?php echo $rclass; ?>">
                        <td><?php echo um_user('business_name'); ?></td>
                        <td><?php echo $user->display_name; ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $field)); ?></td>
                        <td>
                            <a href="<?php echo UM()->files()->get_download_link($um_form->ID, $field, $user->id); ?>">Open</a>
                        </td>
                        <td>
                            <a href="<?php echo um_ag_edit_profile_url($user->id); ?>">Edit Profile</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    $days = (int) get_option("umcerts_notify_days", 7);
    if ($days <= 0)
        $days = 7;
    $end_date = time() + ($days * (60*60*24));

    $users = array();

    foreach ($fields as $field)
    {
        $args = array(
            'role'          =>  'subscriber',
            'meta_key'      =>  $field.'_expiry_date',
            'meta_value'    =>  date("Y/m/d", $end_date),
            'meta_compare'  => '<=',
            'type'          => 'date',
            'orderby'       => $field.'_expiry_date'
        );

        if (!isset($users[$field]))
            $users[$field] = array();

        $users[$field] = get_users($args);
    }
    $um_form = get_page_by_title("User Profile", OBJECT, 'um_form');
    ?>
    <div class="um-certs-wrapper-soon">
        <h4>Expired / Expiring Soon</h4>
        <table class="umcerts-table">
            <thead>
            <tr>
                <th>Business</th>
                <th>User</th>
                <th>Certificate</th>
                <th>Expires</th>
                <th>View Cert</th>
                <th>Activation</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $field => $user_list)
            {
                foreach ($user_list as $user)
                {
                    ?>
                    <tr>
                        <?php um_fetch_user($user->id); ?>
                        <td><?php echo um_user('business_name'); ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $field)); ?></td>
                        <td><?php echo $user->display_name; ?></td>
                        <td><?php echo um_user($field.'_expiry_date'); ?></td>
                        <td>
                            <a href="<?php echo UM()->files()->get_download_link($um_form->ID, $field, $user->id); ?>">Open</a>
                        </td>
                        <td>
                            <a href="<?php echo um_ag_edit_profile_url($user->id); ?>">Edit Profile</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="um-certs-wrapper-expired">
        <h4>Expired?</h4>
        <table></table>
        <!-- <p>Break expired and exprining upto with a cut off? Will cut these two tables off in prod!! (AG)</p> -->
    </div>
</div>
<?php
function um_ag_edit_profile_url($user_id) {
    $url = um_user_profile_url($user_id);
    $url = remove_query_arg( 'profiletab', $url );
    $url = remove_query_arg( 'subnav', $url );
    $url = add_query_arg( 'um_action', 'edit', $url );
    return $url;
}
?>