<?php 

$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";

$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

$user_type = $_COOKIE['type'] ?? '';
$user_id = $_COOKIE['user_id'] ?? null;

$customer_id = $_REQUEST['customer_id'] ?? null;
$updated_status = $_REQUEST['order_status'] ?? null;
$updated_order_id = $_REQUEST['product_id'] ?? null;
$updated_order_date = $_REQUEST['order_date'] ?? null;

if (isset($updated_status) && isset($updated_order_id) && isset($updated_order_date)) {
    $update_status_statement = "UPDATE purchase SET status='$updated_status' WHERE userid=$customer_id AND prodid=$updated_order_id AND date='$updated_order_date'";
    mysqli_query($dlink, $update_status_statement);
}

?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Ninom</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Baloo+Chettan|Dosis:400,600,700|Poppins:400,600,700&display=swap" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section strats -->
    <div class="brand_box">
      <a class="navbar-brand" href="index.html">
        <span>
          Ninom
        </span>
      </a>
    </div>
    <!-- end header section -->
  </div>

  <!-- nav section -->

  <section class="nav_section">
    <div class="container">
      <div class="custom_nav2">
        <nav class="navbar navbar-expand custom_nav-container ">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex  flex-column flex-lg-row align-items-center">
              <ul class="navbar-nav  ">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="product.php">Product </a>
                </li>
                <?php if ($user_type == 'customer'):?>
                           <li class="nav-item"><a class="nav-link" href="myorders.php?cart=true"><i aria-hidden="true"></i><span>My Orders</span></a></li>
                <?php endif ?>
              </ul>
              <form class="form-inline my-2 my-lg-0 ml-0 ml-lg-4 mb-3 mb-lg-0">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit"></button>
              </form>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </section>

  <!-- end nav section -->

  <!-- about section -->

  <section class="about_section layout_padding">
  <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
    <div style="display: flex; align-items: center; justify-content: center; columns: 100px 3;">
        <?php
        
            $header_status = $_REQUEST['status'] ?? '';
            $order_month = $_REQUEST['month'] ?? null;
            $order_day = $_REQUEST['day'] ?? null;

            if (isset($_COOKIE['user_id'])) {
                /**
                 * Query statements for count of pending, accepted, completed, and returned/refunded orders
                 */
                $pending_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='pending' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%d')=$order_day)";
                $accepted_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='accepted' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%d')=$order_day)";
                $completed_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='completed' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%d')=$order_day)";
                $returned_refunded_query = "SELECT COUNT(*) AS count FROM purchase WHERE (status='returned' OR status='refunded') AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%d')=$order_day)";

                /**
                 * contains the count of pending, accepted, completed, and returned/refunded orders
                 */
                $count_pending = mysqli_fetch_assoc(mysqli_query($dlink, $pending_query))['count'];
                $count_accepted = mysqli_fetch_assoc(mysqli_query($dlink, $accepted_query))['count'];
                $count_completed = mysqli_fetch_assoc(mysqli_query($dlink, $completed_query))['count'];
                $count_returned_refunded = mysqli_fetch_assoc(mysqli_query($dlink, $returned_refunded_query))['count'];

                /**
                 * Headers/Links to show orders based on their status
                 */
                $status_headers = <<<HTML
                <a href="?status=pending&month=$order_month&day=$order_day">
                    <p style="text-align: center; font-size: large; font-weight: 700;">
                        Pending($count_pending)
                    </p>
                </a>
                <a href="?status=accepted&month=$order_month&day=$order_day">
                    <p style="text-align: center; font-size: large; font-weight: 700;">
                        Accepted($count_accepted)
                    </p>
                </a>
                <a href="?status=completed&month=$order_month&day=$order_day">
                    <p style="text-align: center; font-size: large; font-weight: 700;">
                        Completed($count_completed)
                    </p>
                </a>
                <a href="?status=return_refund&month=$order_month&day=$order_day">
                    <p style="text-align: center; font-size: large; font-weight: 700;">
                        Return/Refund($count_returned_refunded)
                    </p>
                </a>
                HTML;
                echo $status_headers;

            } ?>

        </div>

        <table style="text-align: center;">
            <thead>
                <tr>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Image</th>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Name</th>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Quantity</th>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Price</th>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Order Date</th>
                    <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $cummulative_price = 0;

                $orders_query_statement =  "SELECT userid, prod.prodid AS product_id, prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE status='pending' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%e')=$order_day)";

                if ($header_status === 'accepted') {
                    $orders_query_statement = "SELECT userid, prod.prodid AS product_id, prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE status='accepted' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%e')=$order_day)";
                }

                if ($header_status === 'completed') {
                    $orders_query_statement = "SELECT userid, prod.prodid AS product_id, prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE status='completed' AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%e')=$order_day)";
                }

                if ($header_status === 'return_refund') {
                    $orders_query_statement =  "SELECT userid, prod.prodid AS product_id, prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE (status='returned' OR status='refunded') AND (DATE_FORMAT(date, '%m')=$order_month AND DATE_FORMAT(date, '%e')=$order_day)";
                }

                /**
                 * contains list of customer's orders based on selected status (see conditionals above)
                 */
                $order_list = mysqli_fetch_all(mysqli_query($dlink, $orders_query_statement), MYSQLI_ASSOC);

                /**
                 * List of ordered products by the customers, categorized by order's status
                 */
                foreach ($order_list as $key => $order) {
                    $customer_id = $order['userid'];
                    $order_id = $order['product_id'];
                    $order_image = $order['image_link'];
                    $order_name = $order['name'];
                    $order_category = $order['category'];
                    $order_quantity = $order['quantity'] . 'x';
                    $total_price = $order['price'];
                    $order_date = $order['date'];
                    $order_status = $order['status'];
                    $cummulative_price += $total_price;

                    /**
                     * Is used to select a default option in the "select" element based on selected status category
                     * Cannot use ternary operators inside HEREDOC, truly saddening
                     */
                    $select_pending = $header_status === 'pending' ? 'selected' : '';
                    $select_accepted = $header_status === 'accepted' ? 'selected' : '';
                    $select_completed = $header_status === 'completed' ? 'selected' : '';
                    $select_return_refund = $header_status === 'return_refund' ? 'selected' : '';

                    /**
                     * Table row for an ordered product
                     */
                    $table_row = <<<HTML
                    <tr>
                        <td>
                            <img src="$order_image" alt="$order_name">
                        </td>
                        <td>
                            [$order_category] $order_name
                        </td>
                        <td>
                            $order_quantity
                        </td>
                        <td>
                            $total_price
                        </td>
                        <td>
                            $order_date
                        </td>
                        <td>
                            <form action="?status=$header_status&month=$order_month&day=$order_day" method="post">
                                <select name="order_status" id="order_status" onchange="this.form.submit()">
                                    <option value="pending" $select_pending>Pending</option>
                                    <option value="accepted" $select_accepted>Accepted</option>
                                    <option value="completed" $select_completed>Completed</option>
                                    <option value="" style="display: none;" $select_return_refund>Returned/Refunded</option>
                                    <option value="refunded">Refunded</option>
                                    <option value="returned">Returned</option>
                                </select>
                                <input type="hidden" name="customer_id" value="$customer_id">
                                <input type="hidden" name="product_id" value="$order_id">
                                <input type="hidden" name="order_date" value="$order_date">
                            </form>
                        </td>
                    </tr>
                    HTML;
                    echo $table_row;
                }
                ?>
                <td colspan="6" align="center">
                    <span style="font-size: large;"><strong>Total:</strong> <?php echo $cummulative_price ?></span>
                </td>
            </tbody>
        </table>
    </div>

  </section>

  <!-- end about section -->

  <!-- info section -->

  <section class="info_section layout_padding">
    <div class="container">
      <div class="info_logo">
        <h2>
          NiNom
        </h2>
      </div>
      <div class="info_contact">
        <div class="row">
          <div class="col-md-4">
            <a href="">
              <img src="images/location.png" alt="">
              <span>
                Passages of Lorem Ipsum available
              </span>
            </a>
          </div>
          <div class="col-md-4">
            <a href="">
              <img src="images/call.png" alt="">
              <span>
                Call : +012334567890
              </span>
            </a>
          </div>
          <div class="col-md-4">
            <a href="">
              <img src="images/mail.png" alt="">
              <span>
                demo@gmail.com
              </span>
            </a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-lg-9">
          <div class="info_form">
            <form action="">
              <input type="text" placeholder="Enter your email">
              <button>
                subscribe
              </button>
            </form>
          </div>
        </div>
        <div class="col-md-4 col-lg-3">
          <div class="info_social">
            <div>
              <a href="">
                <img src="images/facebook-logo-button.png" alt="">
              </a>
            </div>
            <div>
              <a href="">
                <img src="images/twitter-logo-button.png" alt="">
              </a>
            </div>
            <div>
              <a href="">
                <img src="images/linkedin.png" alt="">
              </a>
            </div>
            <div>
              <a href="">
                <img src="images/instagram.png" alt="">
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- end info section -->


  <!-- footer section -->
  <section class="container-fluid footer_section">
    <p>
      &copy; <span id="displayYear"></span> All Rights Reserved By
      <a href="https://html.design/">Free Html Templates</a>
    </p>
  </section>
  <!-- footer section -->


  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
</body>

</html>