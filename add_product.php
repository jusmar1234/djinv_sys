<?php
$page_title = 'Add Product';
require_once ('includes/load.php');

// Check what level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');

if (isset($_POST['add_product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'product-size');
  validate_fields($req_fields);

  if (empty($errors)) {
    // Prepare data for SheetDB
    // Prepare data for SheetDB
    $data = array(
      'name' => remove_junk($db->escape($_POST['product-title'])),
      'quantity' => remove_junk($db->escape($_POST['product-quantity'])),
      'buy_price' => remove_junk($db->escape($_POST['buying-price'])),
      'sale_price' => remove_junk($db->escape($_POST['saleing-price'])),
      'categorie_id' => remove_junk($db->escape($_POST['product-categorie'])),
      'media_id' => isset($_POST['product-photo']) ? remove_junk($db->escape($_POST['product-photo'])) : '',
      'date' => make_date(),
      'size' => remove_junk($db->escape($_POST['product-size']))
    );

    // Convert data to JSON
    $data_string = json_encode($data);

    // Make API request to SheetDB
    $ch = curl_init('https://sheetdb.io/api/v1/7ij29a1gtqcw4');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
      )
    );

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check API response
    if ($http_code == 201) {
      // SheetDB insertion successful
      $session->msg('s', 'Product added');

      // Insert data into MySQL database
      $query = "INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, media_id, date, size) ";
      $query .= "VALUES ('{$data['name']}', '{$data['quantity']}', '{$data['buy_price']}', '{$data['sale_price']}', '{$data['categorie_id']}', '{$data['media_id']}', '{$data['date']}', '{$data['size']}')";

      if ($db->query($query)) {
        // MySQL insertion successful
        // Redirect to success page or display a success message
        redirect('add_product.php', false);
      } else {
        // MySQL insertion failed
        $session->msg('d', 'Failed to add product to MySQL database');
        redirect('add_product.php', false);
      }
    } else {
      // SheetDB insertion failed
      $session->msg('d', 'Failed to add product to SheetDB');
      redirect('add_product.php', false);
    }

  }
}
?>
<?php include_once ('layouts/header.php'); ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php echo display_msg($msg); ?>
      <div class="card shadow-lg">
        <div class="card-header text-center bg-primary text-white">
          <h3 class="card-title">Add New Product</h3>
        </div>
        <div class="card-body">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <label for="product-title">Product Title</label>
              <div class="input-group">
                <span class="input-group-text"><i class="glyphicon glyphicon-th-large"></i></span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title">
              </div>
            </div>
            <div class="form-group">
              <label for="product-categorie">Product Category</label>
              <select class="form-control" name="product-categorie">
                <option value="">Select Category</option>
                <?php foreach ($all_categories as $cat): ?>
                  <option value="<?php echo (int) $cat['id'] ?>">
                    <?php echo $cat['name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="product-photo">Product Photo</label>
              <select class="form-control" name="product-photo">
                <option value="">Select Photo</option>
                <?php foreach ($all_photo as $photo): ?>
                  <option value="<?php echo (int) $photo['id'] ?>">
                    <?php echo $photo['file_name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="product-quantity">Product Quantity</label>
              <div class="input-group">
                <span class="input-group-text"><i class="glyphicon glyphicon-shopping-cart"></i></span>
                <input type="number" class="form-control" name="product-quantity" placeholder="Quantity">
              </div>
            </div>
            <div class="form-group">
              <label for="buying-price">Buying Price</label>
              <div class="input-group">
                <span class="input-group-text"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                <span class="input-group-text">.00</span>
              </div>
            </div>
            <div class="form-group">
              <label for="saleing-price">Selling Price</label>
              <div class="input-group">
                <span class="input-group-text"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                <span class="input-group-text">.00</span>
              </div>
            </div>
            <div class="form-group">
              <label for="product-size">Product Size</label>
              <div class="input-group">
                <span class="input-group-text"><i class="glyphicon glyphicon-cog"></i></span>
                <input type="text" class="form-control" name="product-size" placeholder="Product Size">
              </div>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary btn-block">Add Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once ('layouts/footer.php'); ?>