<?php 

$hostname="localhost";
$database="shopee";
$db_login="root";
$db_pass="";

$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

$user_type = $_COOKIE['type'] ?? '';

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
                           <li class="nav-item"><a class="nav-link" href="cart.php?cart=true"><i aria-hidden="true"></i><span>Cart</span></a></li>
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
    <div class="container-fluid">
        <div class="fashion_section" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
            <!-- Links to filter orders based on status -->
            <div style="display: flex; align-items: center; justify-content: center; columns: 100px 3;">
                <?php
                if (isset($_COOKIE['user_id'])) {
                    /**
                     * Query statements for count of pending, accepted, completed, and returned/refunded orders
                     */
                    $user_id = $_COOKIE['user_id'];
                    $pending_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='pending' AND userid=$user_id";
                    $accepted_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='accepted' AND userid=$user_id";
                    $completed_query = "SELECT COUNT(*) AS count FROM purchase WHERE status='completed' AND userid=$user_id";
                    $returned_refunded_query = "SELECT COUNT(*) AS count FROM purchase WHERE (status='returned' OR status='refunded') AND userid=$user_id";

                    /**
                     * contains the count of pending, accepted, completed, and returned/refunded orders
                     */
                    $count_pending = mysqli_fetch_assoc(mysqli_query($dlink, $pending_query))['count'];
                    $count_accepted = mysqli_fetch_assoc(mysqli_query($dlink, $accepted_query))['count'];
                    $count_completed = mysqli_fetch_assoc(mysqli_query($dlink, $completed_query))['count'];
                    $count_returned_refunded = mysqli_fetch_assoc(mysqli_query($dlink, $returned_refunded_query))['count'];
                ?>
                    <a href="?status=pending">
                        <p style="text-align: center; font-size: large; font-weight: 700;">
                            Pending(<?php echo $count_pending ?>)
                        </p>
                    </a>
                    <a href="?status=accepted">
                        <p style="text-align: center; font-size: large; font-weight: 700;">
                            Accepted(<?php echo $count_accepted ?>)
                        </p>
                    </a>
                    <a href="?status=completed">
                        <p style="text-align: center; font-size: large; font-weight: 700;">
                            Completed(<?php echo $count_completed ?>)
                        </p>
                    </a>
                    <a href="?status=return_refund">
                        <p style="text-align: center; font-size: large; font-weight: 700;">
                            Return/Refund(<?php echo $count_returned_refunded ?>)
                        </p>
                    </a>
                <?php } ?>
            </div>

            <table style="text-align: center;">
                <thead>
                    <tr>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Image</th>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Name</th>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Quantity</th>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Price</th>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Order Date</th>
                        <th style="padding-left: 120px; padding-right: 120px; padding-bottom: 25px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cummulative_price = 0;

                    $order_status = $_REQUEST['status'] ?? '';
                    if ($order_status === 'accepted') {
                        $orders_query_statement = "SELECT prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE userid=$user_id AND status='accepted'";
                    } elseif ($order_status === 'completed') {
                        $orders_query_statement = "SELECT prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE userid=$user_id AND status='completed'";
                    } elseif ($order_status === 'return_refund') {
                        $orders_query_statement =  "SELECT prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE userid=$user_id AND (status='returned' OR status='refunded')";
                    } else {
                        /**
                         * pending orders is the default status shown to the user
                         */
                        $orders_query_statement =  "SELECT prod.productimage AS image_link, prod.productname AS name, prod.prodcat AS category, pur.quantity AS quantity, pur.totalprice AS price, date, status FROM purchase pur INNER JOIN products prod ON pur.prodid=prod.prodid WHERE userid=$user_id AND status='pending'";
                    }
                    /**
                     * contains list of customer's orders based on selected status (see conditionals above)
                     */
                    $order_list = mysqli_fetch_all(mysqli_query($dlink, $orders_query_statement), MYSQLI_ASSOC);

                    /**
                     * List of ordered products by the customers, categorized by order's status
                     */
                    foreach ($order_list as $key => $order) {
                        $order_image = $order['image_link'];
                        $order_name = $order['name'];
                        $order_category = $order['category'];
                        $order_quantity = $order['quantity'] . 'x';
                        $total_price = $order['price'];
                        $order_date = $order['date'];
                        $order_status = $order['status'];
                        $cummulative_price += $total_price;

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
                                $order_status
                            </td>
                        </tr>
                        HTML;
                        echo $table_row;
                    }
                    ?>
                    <tr>
                        <td colspan="6" align="center">
                            <span style="font-size: large;"><strong>Total:</strong> <?php echo $cummulative_price ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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