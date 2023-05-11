<?php 
include 'carti.php';
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
    <div class="container-fluid">
    <?php
    // * Cart page/view
    $to_cart = $_REQUEST['cart'] ?? false; // $to_cart = isset($_REQUEST['cart']) ? $_REQUEST['cart'] : false;
    if ($to_cart) { ?>
        <p style="font-size: x-large; font-weight: bold; text-align: center; margin: 100px;">CART</p>
        <div style="display: flex; justify-content: center; align-items: center;">
            <form action="cart.php?cart=true" method="post">
                <table>
                    <!-- Cart header -->
                    <thead>
                        <tr>
                            <th colspan="2" style="padding-left: 70px; padding-right: 70px;"></th>
                            <th style="padding-left: 70px; padding-right: 70px;">Description</th>
                            <th style="padding-left: 70px; padding-right: 70px;">Name</th>
                            <th style="padding-left: 70px; padding-right: 70px;">Quantity</th>
                            <th style="padding-left: 70px; padding-right: 70px;">Total Price</th>
                            <th style="padding-left: 70px; padding-right: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <!-- Carted products -->
                    <tbody>
                        <?php
                        $total_price = 0;
                        foreach ($products_cart as $id => $in_cart) {
                            $product_id = $in_cart[0];
                            $product_name = $in_cart[2];
                            $product_description = $in_cart[3];
                            $product_img = $in_cart[4];
                            $carted_quantity = $in_cart[7];
                            $product_price = $in_cart[6] * $carted_quantity;
                            $total_price += $product_price;
                            ?>
                            <tr>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <input type="checkbox" name="cart_product[]" value=<?php echo $product_id ?>>
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <img src="<?php echo $product_img ?>" alt="product">
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <?php echo $product_description ?>
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <?php echo $product_name ?>
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <form action="cart.php?cart=true" method="post">
                                        <select name="product_quantity" onchange="this.form.submit()">
                                            <?php
                                            // Queries available quantity in database for product, using its product id
                                            $quantity_query = "SELECT quantity FROM products where prodid=$product_id";
                                            $quantity_search = mysqli_query($dlink, $quantity_query);
                                            $product_quantity = mysqli_fetch_array($quantity_search);

                                            // dynamically creates options for the select object based on the quantity of the product in the products database
                                            // @product_quantity - product quantity of the carted product
                                            for ($range = 1; $range <= $product_quantity[0]; $range++) {
                                                if ($range == $carted_quantity) { ?>
                                                    <option value=<?php echo $range ?> selected><?php echo $range ?></option>
                                                    <?php continue;
                                                } ?>
                                                <option value=<?php echo $range ?>><?php echo $range ?></option>
                                            <?php } ?>

                                            <!-- @update_prod - product id of the quantity to be updated when select option for quantity  -->
                                        </select>
                                        <input type="hidden" name="update_prod" value=<?php echo $product_id ?>>
                                    </form>
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px; padding-bottom: 100px;">
                                    <?php echo $product_price ?>
                                </td>
                                <td style="padding-left: 70px; padding-right: 70px;">
                                  <button type="submit" name="del_prod" value="<?php echo $product_id ?>" style="background-color: red; color: white;">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding-top: 10px; padding-bottom: 70px;">
                                <strong>TOTAL: </strong>
                                <?php echo $total_price ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input style="display: block; margin: 0 auto; padding-top: 10px; padding-bottom: 10px; background-color: yellow;" type="submit" value="Place orders">
            </form>
        </div>
    <?php } ?>
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