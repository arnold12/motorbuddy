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
                    <i class="fa fa-bell-o"></i> <span>Masters</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Dealer Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Brand Model Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Insurance Company Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Shaop Amenities Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Shop Service Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Service Repair Master</a></li>
                    <li class=""><a href="{{ url('my-reminders') }}"><i class="fa fa-bell-o"></i>Package Master</a></li>
                </ul>
            </li>

			      <li>
              <a href="index.php">
                <i class="fa fa-user"></i> <span>Dealer</span>
              </a>
            </li>

            <li>
              <a href="view_brand_model.php">
                <i class="fa fa-btc"></i> <span>Brand Model Master</span>
              </a>
            </li>

            <!-- <li>
              <a href="view_payment_method.php">
                <i class="fa fa-th"></i> <span>Payment Method Master</span>
              </a>
            </li> -->

            <li>
              <a href="view_insurance_company.php">
                <i class="fa fa-building"></i> <span>Insurance Company Master</span>
              </a>
            </li>

            <li>
              <a href="view_shop_amenities.php">
                <i class="fa fa-shopping-cart"></i> <span>Shop Amenities Master</span>
              </a>
            </li>

            <li>
              <a href="view_shop_service.php">
                <i class="fa fa-bullhorn"></i> <span>Shop Service Master</span>
              </a>
            </li>

            <li>
              <a href="view_services_repair.php">
                <i class="fa fa-wrench"></i> <span>Services Repair Master</span>
              </a>
            </li>

            <li>
              <a href="view_cust_feedback.php">
                <i class="fa fa-bullhorn"></i> <span>Custmore Feedback</span>
              </a>
            </li>

            <li>
              <a href="view_contact_us.php">
                <i class="fa fa-list-alt"></i> <span>Custmore Contact Us</span>
              </a>
            </li>

            <li>
              <a href="view_add_home_page_images.php">
                <i class="fa fa-file-image-o"></i> <span>Home Page Images</span>
              </a>
            </li>
            <li>
              <a href="reviews-ratings.php">
                <i class="fa fa-th"></i> <span>Reviews & Ratings</span>
              </a>
            </li>
            <li>
              <a href="view_pkg.php">
                <i class="fa fa-th"></i> <span>Package Master</span>
              </a>
            </li>
            <li>
              <a href="view_recommedation_pdf.php">
                <i class="fa fa-th"></i> <span>Recommedation PDF</span>
              </a>
            </li>
            <?php
              }
            if ($_SESSION['role'] == 'dealer' || $_SESSION['role'] == 'superadmin') {
            ?>
            <li>
              <a href="appointments.php">
                <i class="fa fa-calendar"></i> <span>Appointment</span>
              </a>
            </li>
            <?php
            }
            ?>
            <li>
              <a href="change_password.php">
                <i class="fa fa-key"></i> <span>Changed Password</span>
              </a>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
