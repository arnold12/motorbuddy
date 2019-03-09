<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php
              if($_SESSION['role'] == 'superadmin'){
            ?>

            <li class="treeview">
                <a href="#">
                    <i class=""></i> <span>Masters</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?=SITE_URL?>/index.php"><i class=""></i>Dealer Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_brand_model.php"><i class=""></i>Brand Model Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_insurance_company.php"><i class=""></i>Insurance Company Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_shop_amenities.php"><i class=""></i>Shaop Amenities Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_shop_service.php"><i class=""></i>Shop Service Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_services_repair.php"><i class=""></i>Service Repair Master</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_pkg.php"><i class=""></i>Package Master</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class=""></i> <span>Customers</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?=SITE_URL?>/view_cust_feedback.php"><i class=""></i>Customer Feedback</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_contact_us.php"><i class=""></i>Customer Contact Us</a></li>
                    <li class=""><a href="<?=SITE_URL?>/reviews-ratings.php"><i class=""></i>Customer Reviews & Ratings</a></li>
                    <li class=""><a href="#"><i class=""></i>Registered Users</a></li>
                    <li class=""><a href="#"><i class=""></i>Customer Call Tracking</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class=""></i> <span>Images & PDF</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?=SITE_URL?>/view_add_home_page_images.php"><i class=""></i>Home page Images</a></li>
                    <li class=""><a href="<?=SITE_URL?>/view_recommedation_pdf.php"><i class=""></i>Recomendation PDF</a></li>
                </ul>
            </li>
			      
            <!-- <li>
              <a href="view_payment_method.php">
                <i class="fa fa-th"></i> <span>Payment Method Master</span>
              </a>
            </li> -->

            <?php
              }
            if ($_SESSION['role'] == 'dealer' || $_SESSION['role'] == 'superadmin') {
            ?>

            <li class="treeview">
                <a href="#">
                    <i class=""></i> <span>Bookings</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?=SITE_URL?>/appointments.php"><i class=""></i>Booking List</a></li>
                </ul>
            </li>

            <?php
            }
            ?>
            <li class="treeview">
                <a href="#">
                    <i class=""></i> <span>Settings</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?=SITE_URL?>/change_password.php"><i class=""></i>Changed Password</a></li>
                </ul>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
