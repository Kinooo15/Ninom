<?php
$user_type = $_COOKIE['type'] ?? '';
include 'carti.php';
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
                  <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="product.php">Product</a>
                </li>
                <?php if ($user_type == 'admin'):?>
                           <li class="nav-item"><a class="nav-link" href="admin.php?admin=true"><i aria-hidden="true"></i><span>Manage Products</span></a></li>
                <?php endif ?>
                <?php if ($user_type == 'customer'):?>
                           <li class="nav-item"><a class="nav-link" href="cart.php?cart=true"><i aria-hidden="true"></i><span>Cart </span></a></li>
                           <li class="nav-item"><a class="nav-link" href="myorders.php?cart=true"><i aria-hidden="true"></i><span>My Orders </span></a></li>
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

  <!-- fruit section -->

  <section class="fruit_section layout_padding">

<?php

$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";

$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

/** 
 * Queries all product categories and loops through each category to display the category and its respective products
 */

$selected_category = $_REQUEST['prod_cat'] ?? '';
$category_query = 'SELECT prodcat FROM products GROUP BY prodcat';

/**
 * quries only the category that was pressed by the user
 */

if ($selected_category != "") {
    $category_query = "SELECT prodcat FROM PRODUCTS WHERE prodcat='$selected_category' GROUP BY prodcat";
}

$category_search = mysqli_query($dlink, $category_query);
$category_list = mysqli_fetch_all($category_search);
foreach ($category_list as $category) {
    $product_category = $category[0]; ?>
    <div>
        <div>
            <div class="container">
                <a href="?prod_cat=<?php echo $product_category ?>">
                    <h1 class="fashion_taital">
                        <?php echo strtoupper($product_category) ?> <!-- Category  -->
                    </h1>
                </a>
                <div class="fashion_section_2">
                    <div class="row">
                        <?php
                        /**
                         * Queries all products in a certain category and loops through each product to display
                         */
                        $product_query = "SELECT * FROM products where prodcat='$product_category'";
                        $product_search = mysqli_query($dlink, $product_query);
                        $product_list = mysqli_fetch_all($product_search);
                        foreach ($product_list as $product) {
                            $id = $product[0];
                            $category = $product[1];
                            $name = $product[2];
                            $image = $product[5];
                            $quantity = $product[6];
                            /**
                             * If promo price is greater than 0, set display price to promo price
                             * Else, display original price
                             */
                            $price = $product[8] > 0 ? $product[8] : $product[7];
                            ?>
                            <div class="col-lg-4 col-sm-4">
                                <div>
                                    <h4>
                                        <?php echo $name ?> <!-- Product Name  -->
                                    </h4>

                                    <!-- Original or Promo Price  -->
                                    <p class="price_text">Price
                                        <span style="color: #262626;">
                                            $
                                            <?php echo $price ?>
                                        </span>
                                    </p>

                                    <div>
                                        <img src=<?php echo $image ?>> <!-- Image Link  -->
                                    </div>

                                    <div>
                                        <div>
                                            <?php if ($quantity > 0) {
                                              if (isset($_COOKIE['email']) && $_COOKIE['type'] == 'customer' && isset($_COOKIE['user_id'])) {?>
                                                <a href=<?php echo "product.php?prod_id=$id" ?>>Buy Now</a> <!-- "Buy now" Button -->
                                              <?php } ?>
                                            <?php } else { ?>
                                                <p>Out of stock</p>
                                            <?php } ?>
                                        </div>
                                        <p>
                                            Quantity:
                                            <?php echo $quantity ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
  
    <div class="container">
      <div class="heading_container">
        <hr>
        <h2>
        </h2>
      </div>
    </div>

    <!-- <div class="container-fluid">

      <div class="fruit_container">
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
        <div class="box">
          <div class="link_box">
            <h5>
            </h5>
          </div>
        </div>
      </div>
    </div> -->
  </section>

  <!-- end fruit section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>

</body>

</html>