<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">



<?php
$page_title = 'Admin Home Page';
require_once ('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
?>
<?php
$c_categorie = count_by_id('categories');
$c_product = count_by_id('products');
$c_sale = count_by_id('sales');
$c_user = count_by_id('users');
$products_sold = find_higest_saleing_product('10');
$recent_products = find_recent_product_added('5');
$recent_sales = find_recent_sale_added('5')
  ?>
<?php include_once ('layouts/header.php'); ?>
<div class="container mt-3">
  <h2 class="heading text-center">DASHBOARD</h2>
  <div class="row">
    <div class="col-md-6">
      <?php echo display_msg($msg); ?>
    </div>
  </div>
  <div class="row">
    <!-- Existing Panels for Users, Categories, Products, Sales -->
    <div class="col-md-3">
      <a href="users.php" style="color:black;">
        <div class="panel panel-box">
          <div class="panel-icon bg-secondary1">
            <i class="glyphicon glyphicon-user"></i>
          </div>
          <div class="panel-value">
            <h2><?php echo $c_user['total']; ?></h2>
            <p>Users</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="categorie.php" style="color:black;">
        <div class="panel panel-box">
          <div class="panel-icon bg-red">
            <i class="glyphicon glyphicon-th-large"></i>
          </div>
          <div class="panel-value">
            <h2><?php echo $c_categorie['total']; ?></h2>
            <p>Categories</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="product.php" style="color:black;">
        <div class="panel panel-box">
          <div class="panel-icon bg-blue2">
            <i class="glyphicon glyphicon-shopping-cart"></i>
          </div>
          <div class="panel-value">
            <h2><?php echo $c_product['total']; ?></h2>
            <p>Products</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="sales.php" style="color:black;">
        <div class="panel panel-box">
          <div class="panel-icon bg-green">
            <i class="glyphicon glyphicon-usd"></i>
          </div>
          <div class="panel-value">
            <h2><?php echo $c_sale['total']; ?></h2>
            <p>Sales</p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <?php include_once ('layouts/header.php'); ?>
  <div class="container mt-5">
    <h2 class="heading text-center">ANALYTICS</h2>
    <div class="row">
      <!-- Highest Selling Products Panel -->
      <div class="col-md-4">
        <div class="panel panel-default custom-panel">
          <div class="panel-heading custom-panel-heading">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Highest Selling Products</span>
            </strong>
          </div>
          <div class="panel-body custom-panel-body">
            <table class="table custom-table">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Total Sold</th>
                  <th>Total Quantity</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products_sold as $product_sold): ?>
                  <tr>
                    <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                    <td><?php echo (int) $product_sold['totalSold']; ?></td>
                    <td><?php echo (int) $product_sold['totalQty']; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Latest Sales Panel -->
      <div class="col-md-4">
        <div class="panel panel-default custom-panel">
          <div class="panel-heading custom-panel-heading">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Latest Sales</span>
            </strong>
          </div>
          <div class="panel-body custom-panel-body">
            <table class="table custom-table">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Product Name</th>
                  <th>Date</th>
                  <th>Total Sale</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recent_sales as $recent_sale): ?>
                  <tr>
                    <td class="text-center"><?php echo count_id(); ?></td>
                    <td>
                      <a href="edit_sale.php?id=<?php echo (int) $recent_sale['id']; ?>">
                        <?php echo remove_junk(first_character($recent_sale['name'])); ?>
                      </a>
                    </td>
                    <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
                    <td>$<?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Leading Category Panel -->
      <div class="col-md-4">
        <div class="panel panel-default custom-panel">
          <div class="panel-heading custom-panel-heading">
            <strong>
              <span class="glyphicon glyphicon-star"></span>
              <span>Leading Category</span>
            </strong>
          </div>
          <div class="panel-body custom-panel-body">
            <?php
            $leading_category = find_leading_category();
            if ($leading_category): ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">Category Name</th>
                      <th class="text-center">Total Sales</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-center"><?php echo remove_junk(ucfirst($leading_category['name'])); ?></td>
                      <td class="text-center"><?php echo (int) $leading_category['total_sales']; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <p class="text-center">No data available</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once ('layouts/footer.php'); ?>
  <br>
  <div class="container mt-5">
    <h2 class="heading text-center">Latest Added Product</h2>
    <div class="row">
      <?php foreach ($recent_products as $recent_product): ?>
        <div class="col-md-4">
          <div class="card product-card">
            <?php if ($recent_product['media_id'] === '0'): ?>
              <img class="img-avatar" src="uploads/products/no_image.png" alt="">
            <?php else: ?>
              <img class="img-avatar" src="uploads/products/<?php echo $recent_product['image']; ?>" alt="">
            <?php endif; ?>
            <div class="product-details">
              <h5 class="card-title">
                <?php echo remove_junk(first_character($recent_product['name'])); ?>
              </h5>
              <p class="product-category">
                <?php echo remove_junk(first_character($recent_product['categorie'])); ?>
              </p>

            </div>
            <div class="product-price">
              $<?php echo (int) $recent_product['sale_price']; ?>
            </div>
            <a href="edit_product.php?id=<?php echo (int) $recent_product['id']; ?>" class="btn btn-primary">Edit</a>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</div>
</div>
<div class="row">

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include_once ('layouts/footer.php'); ?>