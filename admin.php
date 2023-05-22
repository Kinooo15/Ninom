<?php
$hostname="localhost";
$database="shopee";
$db_login="root";
$db_pass="";

$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

$user_type = $_COOKIE['type'] ?? '';
$user_id = $_COOKIE['user_id'] ?? null;

$product_action = $_REQUEST['product_action'] ?? null;
$admin_action = $_REQUEST['admin_action'] ?? null;
$product_id = $_REQUEST['product_id'] ?? null;

if ($admin_action === "delete_product" && isset($_REQUEST["id"])) {
    $delete_id = $_REQUEST['id'] ?? null;
    $delete_query = "DELETE FROM products where prodid=$delete_id";
    mysqli_query($dlink, $delete_query);
} elseif ($admin_action === "edit_product" && isset($_REQUEST["id"])) {
    $edit_id = $_REQUEST["id"] ?? null;
    $upload_image = $_FILES['image']['name'] ?? null;

    $new_image = "images/$upload_image";
    $new_category = $_REQUEST['product_category'] ?? null;
    $new_name = $_REQUEST['product_name'] ?? null;
    $new_description = $_REQUEST['product_description'] ?? null;
    $new_quantity = $_REQUEST['available_quantity'] ?? null;
    $new_orig = $_REQUEST['original_price'] ?? null;
    $new_promo = $_REQUEST['promo_price'] ?? null;

    if (isset($new_category) && isset($new_name) && isset($new_description) && isset($new_quantity) && isset($new_orig) && isset($new_promo)) {

        $edit_query = "UPDATE products SET prodcat='$new_category', productname='$new_name', productdesc='$new_description', quantity=$new_quantity, lastprice=$new_orig, ourprice=$new_promo WHERE prodid=$edit_id";

        if (!empty($upload_image)) {
            $edit_query = "UPDATE products SET prodcat='$new_category', productname='$new_name', productdesc='$new_description', productimage='$new_image', quantity=$new_quantity, lastprice=$new_orig, ourprice=$new_promo WHERE prodid=$edit_id";

            /**
             * Uploads image to project "images" directory
             */
            move_uploaded_file($_FILES['image']['tmp_name'], $new_image);
        }

        mysqli_query($dlink, $edit_query);
    }
} elseif ($admin_action === "new_category") {
    $newcategory_stmnt = "INSERT INTO products(prodcat, productname, productdesc, productlink, productimage, quantity, lastprice, ourprice) VALUES('New Category', 'New Product', 'Product Description', 'www.google.com', 'images/newproduct.jpg', 0, 0, 0)";
    mysqli_query($dlink, $newcategory_stmnt);
    echo <<<HTML
    <script>window.location.href="admin.php"</script>
    HTML;
} elseif ($admin_action === "edit_category") {
    $old_category = $_REQUEST['prod_cat'] ?? null;

    $dialog_modal = <<<HTML
        <dialog id="modal_form">
            <form action="admin.php" method="post" style="display: flex; flex-direction: column; justify-content: center; align-items: center;" enctype="multipart/form-data">
                <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                    <label>
                        Category
                        <input type="text" name="new_category" value="$old_category" required>
                    </label>
                </div>
                <input type="hidden" name="old_category" value="$old_category">
                <input type="hidden" name="admin_action" value="edited_category">
                <button type="submit">UPDATE</button>
            </form>
            <button id="discard_btn">Discard</button>
        </dialog>

        <script>
            document.getElementById("modal_form").showModal();

            var discard_btn = document.getElementById("discard_btn");

            discard_btn.addEventListener("click", () => {
                document.getElementById("modal_form").close();
            })
        </script>
    HTML;
    echo $dialog_modal;
} elseif ($admin_action === "edited_category") {
    $old_category = $_REQUEST['old_category'];
    $new_category = $_REQUEST['new_category'];

    $editcategory_stmnt = "UPDATE products SET prodcat='$new_category' WHERE prodcat='$old_category'";
    mysqli_query($dlink, $editcategory_stmnt);
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
                
                <?php if ($user_type == 'admin'):?>
                    <li class="nav-item"><a class="nav-link" href="admin.php"><i aria-hidden="true"></i><span>Manage Products</span></a></li>
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

  <section class="fruit_section layout_padding">
        <div class="fashion_section" style="display: flex; flex-direction: column;">

            <?php
            if ($product_action === "edit") {
                $edited_product = mysqli_fetch_assoc(mysqli_query($dlink, "SELECT * FROM products where prodid=$product_id"));
                $edited_category = $edited_product['prodcat'];
                $edited_name = $edited_product['productname'];
                $edited_description = $edited_product['productdesc'];
                $edited_image = $edited_product['productimage'];
                $edited_quantity = $edited_product['quantity'];
                $edited_orig = $edited_product['lastprice'];
                $edited_promo = $edited_product['ourprice'];

                $dialog_modal = <<<HTML
                    <dialog id="modal_form">
                        <form action="admin.php" method="post" style="display: flex; flex-direction: column; justify-content: center; align-items: center;" enctype="multipart/form-data">
                            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <img src="$edited_image" alt="$edited_name">
                                <input type="file" name="image" id="image" accept="image/*">
                                <label>
                                    Category
                                    <input type="text" name="product_category" value="$edited_category" required>
                                </label>
                                <label>
                                    Name
                                    <input type="text" name="product_name" value="$edited_name" required>
                                </label>
                                <label>
                                    Description
                                    <input type="text" name="product_description" value="$edited_description" required>
                                </label>
                                <label>
                                    Quantity
                                    <input type="number" name="available_quantity" value="$edited_quantity" required>
                                </label>
                                <label>
                                    Last Price
                                    <input type="number" name="original_price" value="$edited_orig" required>
                                </label>
                                <label>
                                    Current Price
                                    <input type="number" name="promo_price" value="$edited_promo" required>
                                </label>
                            </div>
                            <input type="hidden" name="id" value="$product_id">
                            <input type="hidden" name="admin_action" value="edit_product">
                            <button type="submit">UPDATE</button>
                        </form>
                        <button id="discard_btn">Discard</button>
                    </dialog>

                    <script>
                        document.getElementById("modal_form").showModal();

                        var discard_btn = document.getElementById("discard_btn");

                        discard_btn.addEventListener("click", () => {
                            document.getElementById("modal_form").close();
                        })
                    </script>
                    HTML;
                echo $dialog_modal;
            } elseif ($product_action === "delete") {
                $script = <<<HTML
                        <script>
                            var delete_confirm = confirm("Delete Product #$product_id?")
                            if (delete_confirm) {
                                window.location.href = "?admin_action=delete_product&id=$product_id";
                            }
                        </script>
                    HTML;
                echo $script;
            } elseif ($product_action === "insert") {
                $insert_category = $_REQUEST['product_category'];
                $insert_query = "INSERT INTO products(prodcat, productname, productdesc, productlink, productimage, quantity, lastprice, ourprice) VALUES('$insert_category', 'New Product', 'Product Description', 'www.google.com', 'images/newproduct.jpg', 0, 0, 0)";
                mysqli_query($dlink, $insert_query);
            }
            ?>
            <!-- Start of database generated content -->
            <?php
            /** 
             * Queries all product categories and loops through each category to display the category and its respective products
             */
            $selected_category = $_REQUEST['product_category'] ?? '';
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
                <div class="fashion_section">
                    <div class="carousel-item active">
                        <div class="container">
                            <a href="?prod_cat=<?php echo $product_category ?>&admin_action=edit_category">
                                <h1 class="fashion_taital">
                                    <?php echo strtoupper($product_category) ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 30px; height: 30px;">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                    <!-- Category  -->
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
                                            <div class="box_main">
                                                <h4 class="shirt_text">
                                                    <?php echo $name ?> <!-- Product Name  -->
                                                </h4>

                                                <!-- Original or Promo Price  -->
                                                <p class="price_text">Price
                                                    <span style="color: #262626;">
                                                        $
                                                        <?php echo $price ?>
                                                    </span>
                                                </p>

                                                <div class="tshirt_img">
                                                    <img src=<?php echo $image ?>> <!-- Image Link  -->
                                                </div>

                                                <div class="btn_main">
                                                    <div class="buy_bt">
                                                        <form action="admin.php" method="post">
                                                            <select name="product_action" id="manage_option" onchange="this.form.submit()">
                                                                <option value="default" style="display: none" selected>-----</option>
                                                                <option value="insert">insert</option>
                                                                <option value="edit">edit</option>
                                                                <option value="delete">delete</option>
                                                            </select>
                                                            <input type="hidden" name="product_id" value="<?php echo $id ?>">
                                                            <input type="hidden" name="product_category" value="<?php echo $category ?>">
                                                        </form>
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
            <!-- Add new category button -->
            <div style="text-align: center;">
                <a href="?admin_action=new_category"><button id="newcategory_btn">ADD A NEW CATEGORY</button></a>
            </div>
            <!-- End of database generated content -->
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
