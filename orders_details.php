<?php
  include_once 'orders_details_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
  <title>My Bike Ordering System : Order Details</title>
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>My Order</h2>
        </div>
        <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_orders_a150854, tbl_staffs_a150854,
                tbl_customers_a150854 WHERE
                tbl_orders_a150854.fld_staff_num = tbl_staffs_a150854.fld_staff_num AND
                tbl_orders_a150854.fld_customer_num = tbl_customers_a150854.fld_customer_num AND
                fld_order_num = :oid");
            $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
              $oid = $_GET['oid'];
            $stmt->execute();
            $readrow = $stmt->fetch(PDO::FETCH_ASSOC);
            }
          catch(PDOException $e) {
              echo "Error: " . $e->getMessage();
          }
          $conn = null;
          ?>
          <div class="form-horizontal">
          <div class="form-group">
            <label for="orderId" class="col-sm-3 control-label">Order ID</label>
            <div class="col-sm-9">
              <td><?php echo $readrow['fld_order_num'] ?></td>
            </div>
          </div>
          <div class="form-group">
            <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
            <div class="col-sm-9">
              <td><?php echo $readrow['fld_order_date'] ?></td>
            </div>
          </div>
          <div class="form-group">
            <label for="staff" class="col-sm-3 control-label">Staff</label>
            <div class="col-sm-9">
              <td><?php echo $readrow['fld_staff_fname']." ".$readrow['fld_staff_lname'] ?></td>
            </div>
          </div>
          <div class="form-group">
            <label for="customer" class="col-sm-3 control-label">Customer</label>
            <div class="col-sm-9">
              <td><?php echo $readrow['fld_customer_fname']." ".$readrow['fld_customer_lname'] ?></td>
            </div>
          </div>
          <form action="orders_details.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="product" class="col-sm-3 control-label">Product</label>
            <div class="col-sm-9">
              <select name="pid">
              <?php
              try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $stmt = $conn->prepare("SELECT * FROM tbl_products_a150854");
                $stmt->execute();
                $result = $stmt->fetchAll();
              }
              catch(PDOException $e){
                    echo "Error: " . $e->getMessage();
              }
              foreach($result as $productrow) {
              ?>
                <option value="<?php echo $productrow['fld_product_num']; ?>"><?php echo $productrow['fld_product_brand']." ".$productrow['fld_product_name']; ?></option>
              <?php
              }
              $conn = null;
              ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="quantity" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input name="quantity" type="number">
              <input name="oid" type="hidden" value="<?php echo $readrow['fld_order_num'] ?>"  min="0" required="">
              <button type="submit" name="addproduct"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add Product</button>
              <button type="reset"class="btn btn-danger btn-xs" role="button">Clear</button>
            </div>
          </div>
            </form>

        </div>
        </div>

      </div>
    </div>
  </div>
  <!-- </div> -->
  <div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="page-header">
        <h2>List Products</h2>
      </div>
      <table class="table table-striped table-bordered">
        <tr>
          <td>Order Detail ID</td>
          <td>Product</td>
          <td>Quantity</td>
          <td></td>
        </tr>
        <?php
        try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM tbl_orders_details_a150854,
              tbl_products_a150854 WHERE
              tbl_orders_details_a150854.fld_product_num = tbl_products_a150854.fld_product_num AND
            fld_order_num = :oid");
            $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
            $oid = $_GET['oid'];
          $stmt->execute();
          $result = $stmt->fetchAll();
        }
        catch(PDOException $e){
              echo "Error: " . $e->getMessage();
        }
        foreach($result as $detailrow) {
        ?>
        <tr>
          <td><?php echo $detailrow['fld_order_detail_num']; ?></td>
          <td><?php echo $detailrow['fld_product_name']; ?></td>
          <td><?php echo $detailrow['fld_order_detail_quantity']; ?></td>
          <td>
            <a href="orders_details.php?delete=<?php echo $detailrow['fld_order_detail_num']; ?>&oid=<?php echo $_GET['oid']; ?>" onclick="return confirm('Are you sure to delete?');"class="btn btn-danger btn-xs" role="button">Delete</a>
          </td>
        </tr>
        <?php
        }
        $conn = null;
        ?>

      </table>
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          <a href="invoices.php?oid=<?php echo $_GET['oid']; ?>" target="_blank" role="button" class="btn btn-primary btn-lg btn-block">Generate Invoice</a>
        </div>
      </div>
    </div>

  </div>









    </table>
    <hr>


  </center>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
