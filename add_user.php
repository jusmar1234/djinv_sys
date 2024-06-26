<?php
$page_title = 'Add User';
require_once ('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
$groups = find_all('user_groups');
?>
<?php
if (isset($_POST['add_user'])) {

  $req_fields = array('full-name', 'username', 'password', 'level');
  validate_fields($req_fields);

  if (empty($errors)) {
    $name = remove_junk($db->escape($_POST['full-name']));
    $username = remove_junk($db->escape($_POST['username']));
    $password = remove_junk($db->escape($_POST['password']));
    $user_level = (int) $db->escape($_POST['level']);
    $password = sha1($password);
    $query = "INSERT INTO users (";
    $query .= "name,username,password,user_level,status";
    $query .= ") VALUES (";
    $query .= " '{$name}', '{$username}', '{$password}', '{$user_level}','1'";
    $query .= ")";
    if ($db->query($query)) {
      // success
      $session->msg('s', "User account has been created!");
      redirect('add_user.php', false);
    } else {
      // failed
      $session->msg('d', ' Sorry failed to create account!');
      redirect('add_user.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_user.php', false);
  }
}
?>
<?php include_once ('layouts/header.php'); ?>
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="row justify-content-center w-100">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h3>Add New User</h3>
        </div>
        <div class="card-body">
          <?php echo display_msg($msg); ?>
          <form method="post" action="add_user.php">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="full-name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="level">User Role</label>
              <select class="form-control" name="level" required>
                <?php foreach ($groups as $group): ?>
                  <option value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary btn-block">Add User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once ('layouts/footer.php'); ?>