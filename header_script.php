<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=SITE_TITLE?></title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.5 -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">	
<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery UI -->
<link rel="stylesheet" href="dist/css/jquery-ui.css">
<!-- Bootstrap Timepicker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Date Picker -->
<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">



<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="dist/js/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>		
<script src="dist/js/common.js"></script>
<!-- Timepicker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<input type="hidden" id="site_url_123" value="<?=SITE_URL?>">
<script>
    $( window ).load(function() {
    	var url_mapping = { '/index.php':'/index.php', '/add_dealer_info.php':'/index.php', '/add_dealer_ratings.php':'/index.php',
    						'/view_brand_model.php': '/view_brand_model.php', '/add_brand_model.php':'/view_brand_model.php',
    						'/view_insurance_company.php':'/view_insurance_company.php', '/add_insurance_company.php':'/view_insurance_company.php',
    						'/view_shop_amenities.php':'/view_shop_amenities.php', '/add_shop_amenities.php':'/view_shop_amenities.php',
    						'/view_shop_service.php':'/view_shop_service.php', '/add_shop_service.php':'/view_shop_service.php',
    						'/view_services_repair.php':'/view_services_repair.php', '/add_service_repair.php':'/view_services_repair.php',
    						'/view_pkg.php':'/view_pkg.php','/add_pkg_group.php':'/view_pkg.php',
    						'/view_cust_feedback.php':'/view_cust_feedback.php',
    						'/view_contact_us.php':'/view_contact_us.php',
    						'/reviews-ratings.php':'/reviews-ratings.php','/add-review-rating.php':'/reviews-ratings.php',
    						'/registered-users.php':'/registered-users.php',
    						'/call-tracking.php':'/call-tracking.php',
    						'/view_add_home_page_images.php':'/view_add_home_page_images.php',
    						'/view_recommedation_pdf.php':'/view_recommedation_pdf.php','/add_recommedation_pdf.php':'/view_recommedation_pdf.php',
    						'/appointments.php':'/appointments.php','/appointment_detail.php':'/appointments.php',
    						'/change_password.php':'/change_password.php' };
        
        var pathname = window.location.pathname;
        var arr = pathname.split('/');
        var pathname_new = '/'+arr[arr.length-1];
        var match_url = url_mapping[pathname_new];
        var url = $("#site_url_123").val()+match_url;
        
        // for sidebar menu but not for treeview submenu
        $('ul.sidebar-menu a').filter(function() {
        return this.href == url;
        }).parent().siblings().removeClass('active').end().addClass('active');
        // for treeview which is like a submenu
        $('ul.treeview-menu a').filter(function() {
        return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").siblings().removeClass('active menu-open').end().addClass('active menu-open');
    });
</script>