<?php 
ob_start();
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

<body>
  <div class="hero_area">
    <!-- header section strats -->
    <div class="brand_box">
      <a class="navbar-brand" href="index.php">
        <span>
          Ninom
        </span>
      </a>
    </div>
    <!-- end header section -->
    <!-- slider section -->
    <section class=" slider_section position-relative">
      <div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="img-box">
              <img src="images/slider-img.jpg" alt="">
            </div>
          </div>
          <div class="carousel-item">
            <div class="img-box">
              <img src="images/slider-img.jpg" alt="">
            </div>
          </div>
          <div class="carousel-item">
            <div class="img-box">
              <img src="images/slider-img.jpg" alt="">
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="sr-only">Next</span>
        </a>
      </div>
    </section>
    <!-- end slider section -->
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
                           <li class="nav-item"><a class="nav-link" href="admin.php?admin=true"><i aria-hidden="true"></i><span>Manage Products</span></a></li>
                <?php endif ?>
                <?php
                if (!isset($_COOKIE['email'])): ?>
                      <li class="nav-item"><a class="nav-link" href="index.php?action=login&#login_form">Log in</a></li>
                      <li class="nav-item"><a class="nav-link" href="index.php?action=register&#login_form">Register</a></li>
                <?php else: ?>
                      <li class="nav-item"> <a class="nav-link">Welcome, <?php echo $_COOKIE['email'] . ' (' . $_COOKIE['type'] . ')' ?></a></li>
                      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
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

  <!-- php section -->

<div id="login_form">

<?php
    $hostname="localhost";
    $database="shopee";
    $db_login="root";
    $db_pass="";

    $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

    // Register
    $action = $_REQUEST['action'] ?? '';
    $register_name = $_REQUEST['name'] ?? '';
    $register_email = $_REQUEST['email2'] ?? '';
    $register_password = $_REQUEST['password'] ?? '';
    $register_contact = $_REQUEST['contact'] ?? '';
    $register_address = $_REQUEST['address'] ?? '';
    if ($register_name != "" && $register_email != "" && $register_password != "" && $register_contact != "" && $register_address != "") {
      $register_query = "select * from user where email='" . $_REQUEST['email2'] . "'";
      $register_result = mysqli_query($dlink, $register_query) or die(mysqli_error($dlink));
      $total_registered_accounts = mysqli_num_rows($register_result);
  
      if ($total_registered_accounts == 0) {
          $query_all = "select * from user";
          $all_results = mysqli_query($dlink, $query_all) or die(mysqli_error($dlink));
          $total_registered_accounts = mysqli_num_rows($all_results);
          if ($total_registered_accounts == 0):
              $register_usertype = 'admin';
          else:
              $register_usertype = 'customer';
          endif;
          $register_query = "insert into user(email, paswrd, contact, custname, address, usertype, user_date, user_ip) values('" . $_REQUEST['email2'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['contact'] . "', '" . $_REQUEST['name'] . "' ,'" . $_REQUEST['address'] . "', '" . $register_usertype . "', '" . date("Y-m-d h:i:s") . "', '" . $_SERVER['REMOTE_ADDR'] . "')";
          $update_query = mysqli_query($dlink, $register_query) or die(mysqli_error($dlink));
          echo '<meta http-equiv="refresh" content="0; url=index.php?action=login&#login_form">';
      } else {
          echo '<meta http-equiv="refresh" content="0;url=index.php?action=register&#login_form">';
          echo '<script>alert("Account already registered!!!")</script>';
      }
  }

    // End of Register

    // Login

    $logging_in = $_REQUEST['logging_in'] ?? false;
    $action = $_REQUEST['action'] ?? '';

    if ($logging_in) {
        $login_query = "select * from user where email='" . $_REQUEST['email2'] . "' and paswrd='" . $_REQUEST['password'] . "'";
        $login_result = mysqli_query($dlink, $login_query) or die(mysqli_error($dlink));
        $total_accounts = mysqli_num_rows($login_result);
        if ($total_accounts == 0) {
            echo '<meta http-equiv="refresh" content="0;url=index.php?action=register&#login_form">';
            echo '<script>alert("Account not registered, please sign up :>")</script>';
        } else {
            $account = mysqli_fetch_array($login_result);
            setcookie("user_id", $account['userid'], time() + 86400, '/');
            setcookie("email", $account['email'], time() + 86400, '/');
            setcookie("type", $account['usertype'], time() + 86400, '/');
            echo '<meta http-equiv="refresh" content="0; url=index.php">';
        }
    }

    // End of Login

    // Register Form

    if ($action == 'register'){
        print('<h1>Registration Form</h1>');
        print('<form action=index.php method=get>');
        print('Enter Name<input type=text name=name><br>');
        print('Enter Email<input type=text name=email2><br>');
        print('Enter Password<input type=text name=password><br>');
        print('Enter Contact<input type=text name=contact><br>');
        print('Enter Address<input type=text name=address><br>');
        print('<input type=submit value=submit>');
        print('</form>');
    }

    // End of Register Form

    // Login Form

    if ($action == 'login'){
      print ('<h1 id="login">Login</h1>');
      print('<form action=index.php?logging_in=true method=post>');
      print('Enter Email<input type=text name=email2><br>');
      print("Enter Password<input type=text name=password><br>");
      print('<input type=submit value=submit name=submit>');
      print('</form>');
    }

    // End of Login Form

    // Exercise 2

      // $user_type = $_COOKIE['type'] ?? '';
      $user_type = isset($_COOKIE['type']) ? $_COOKIE['type'] : '';

      if ($user_type == 'admin'){
        include 'calendar.php';
      }else{

    // End of Exercise 2
  ?>

</div>

  <!-- end php section -->

  <!-- shop section -->

  <section class="shop_section layout_padding">
    <div class="container">
      <div class="box">
        <div class="detail-box">
          <h2>
            Fruit shop
          </h2>
          <p>
            There are many variations of passages of Lorem Ipsum available
          </p>
        </div>
        <div class="img-box">
          <img src="images/shop-img.jpg" alt="">
        </div>
        <div class="btn-box">
          <a href="product.php">
            Buy Now
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- end shop section -->

  <?php }?>


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
  <section class="container-fluid footer_section ">
    <p>
      &copy; <span id="displayYear"></span> All Rights Reserved. Design by
      <a href="https://html.design/">Free Html Templates</a>
    </p>
  </section>
  <!-- footer section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>

</body>

</html>

<?php ob_end_flush() ?>