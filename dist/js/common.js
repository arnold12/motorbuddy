$( document ).ready(function() {

    $("#shop_all").click(function () {
        $(".shop_time").prop('checked', $(this).prop('checked'));
    });

    $("#payment_all").click(function () {
        $(".payment_method").prop('checked', $(this).prop('checked'));
    });

    $("#service_all").click(function () {
        $(".shop_service").prop('checked', $(this).prop('checked'));
    });

    $("#insurance_all").click(function () {
        $(".insurance").prop('checked', $(this).prop('checked'));
    });

    $("#amenities_all").click(function () {
        $(".amenities").prop('checked', $(this).prop('checked'));
    });

    $("#brand_all").click(function () {
        $(".brand").prop('checked', $(this).prop('checked'));
    });

});

function validate_dealer_info() {

  var phone_match = /^[-+]?[0-9]+$/;
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#dealer_name").val()) == "") {
    $("#err_msg_dealer_name").show();
    $("#err_msg_dealer_name").html("Enter Delaer Name 1.");
    $("#dealer_name").focus();
    return false;
  }

  if ($.trim($("#dealer_name2").val()) == "") {
    $("#err_msg_dealer_name2").show();
    $("#err_msg_dealer_name2").html("Enter Delaer Name 2.");
    $("#dealer_name2").focus();
    return false;
  }

  if ($.trim($("#address").val()) == "") {
    $("#err_msg_address").show();
    $("#err_msg_address").html("Enter Address.");
    $("#address").focus();
    return false;
  }

  if ($.trim($("#landmark").val()) == "") {
    $("#err_msg_landmark").show();
    $("#err_msg_landmark").html("Enter Landmark.");
    $("#landmark").focus();
    return false;
  }

  if ($.trim($("#city").val()) == "") {
    $("#err_msg_city").show();
    $("#err_msg_city").html("Enter City.");
    $("#city").focus();
    return false;
  }

  if ($.trim($("#state").val()) == "") {
    $("#err_msg_state").show();
    $("#err_msg_state").html("Enter State.");
    $("#state").focus();
    return false;
  }

  if ($.trim($("#pincode").val()) == "") {
    $("#err_msg_pincode").show();
    $("#err_msg_pincode").html("Enter Pincode.");
    $("#pincode").focus();
    return false;
  }

  if ($.trim($("#mobile_no").val()) == "") {
    $("#err_msg_mobile_no").show();
    $("#err_msg_mobile_no").html("Enter Mobile Number.");
    $("#mobile_no").focus();
    return false;
  }

  if ($.trim($("#mobile_no").val()) != "") {
    if (!$.trim($("#mobile_no").val()).match(phone_match)) {
      $("#err_msg_mobile_no").show();
      $("#err_msg_mobile_no").html("Inavalid Mobile Number.");
      $("#mobile_no").focus();
      return false;
    }

    if (
      $.trim($("#mobile_no").val()).length > 10 ||
      $.trim($("#mobile_no").val()).length < 10
    ) {
      $("#err_msg_mobile_no").show();
      $("#err_msg_mobile_no").html("Mobile Number Must be 10 Digit.");
      $("#mobile_no").focus();
      return false;
    }
  }

  if ($.trim($("#establishment_year").val()) == "") {
    $("#err_msg_establishment_year").show();
    $("#err_msg_establishment_year").html("Enter Establishment year.");
    $("#establishment_year").focus();
    return false;
  }

  if ($.trim($("#lat").val()) == "") {
    $("#err_msg_lat").show();
    $("#err_msg_lat").html("Enter Latitude.");
    $("#lat").focus();
    return false;
  }

  if ($.trim($("#long").val()) == "") {
    $("#err_msg_long").show();
    $("#err_msg_long").html("Enter Longitude.");
    $("#long").focus();
    return false;
  }

  if ($.trim($("#lat").val()) != "") {
    if (isNaN($.trim($("#lat").val()))) {
      $("#err_msg_lat").show();
      $("#err_msg_lat").html("Invalid Latitude.");
      $("#lat").focus();
      return false;
    }
  }

  if ($.trim($("#long").val()) != "") {
    if (isNaN($.trim($("#long").val()))) {
      $("#err_msg_long").show();
      $("#err_msg_long").html("Invalid Longitude.");
      $("#long").focus();
      return false;
    }
  }

  if ($.trim($("#gstn").val()) == "") {
    $("#err_msg_gstn").show();
    $("#err_msg_gstn").html("Enter gstn.");
    $("#gstn").focus();
    return false;
  }

  var shop_time_checkboxs=document.getElementsByClassName("shop_time");
  var shop_time_cond=false;
  for(var i=0,l=shop_time_checkboxs.length;i<l;i++)
  {
      if(shop_time_checkboxs[i].checked)
      {
          shop_time_cond=true;
          break;
      }
  }
  if(!shop_time_cond)
  {
    $('html, body').animate({scrollTop: $('.shop_time').offset().top -100 }, 'slow');
    $("#err_msg_shop_time").show();
    $("#err_msg_shop_time").html("Please check atleast one checkbox in shope timings.");
    return false;
  }

  var payment_method_checkboxs=document.getElementsByClassName("payment_method");
  var payment_method_cond=false;
  for(var i=0,l=payment_method_checkboxs.length;i<l;i++)
  {
      if(payment_method_checkboxs[i].checked)
      {
          payment_method_cond=true;
          break;
      }
  }
  if(!payment_method_cond)
  {
    $('html, body').animate({scrollTop: $('.payment_method').offset().top -100 }, 'slow');
    $("#err_msg_payment_method").show();
    $("#err_msg_payment_method").html("Please check atleast one checkbox in payment method.");
    return false;
  }

  var shop_service_checkboxs=document.getElementsByClassName("shop_service");
  var shop_service_cond=false;
  for(var i=0,l=shop_service_checkboxs.length;i<l;i++)
  {
      if(shop_service_checkboxs[i].checked)
      {
          shop_service_cond=true;
          break;
      }
  }
  if(!shop_service_cond)
  {
    $('html, body').animate({scrollTop: $('.shop_service').offset().top -100 }, 'slow');
    $("#err_msg_shop_service").show();
    $("#err_msg_shop_service").html("Please check atleast one checkbox in shop service.");
    return false;
  }


  /*var insurance_checkboxs=document.getElementsByClassName("insurance");
  var insurance_cond=false;
  for(var i=0,l=insurance_checkboxs.length;i<l;i++)
  {
      if(insurance_checkboxs[i].checked)
      {
          insurance_cond=true;
          break;
      }
  }
  if(!insurance_cond)
  {
    $('html, body').animate({scrollTop: $('.insurance').offset().top -100 }, 'slow');
    $("#err_msg_shop_insurance").show();
    $("#err_msg_shop_insurance").html("Please check atleast one checkbox in insurance tie ups.");
    return false;
  }*/


  /*var amenities_checkboxs=document.getElementsByClassName("amenities");
  var amenities_cond=false;
  for(var i=0,l=amenities_checkboxs.length;i<l;i++)
  {
      if(amenities_checkboxs[i].checked)
      {
          amenities_cond=true;
          break;
      }
  }
  if(!amenities_cond)
  {
    $('html, body').animate({scrollTop: $('.amenities').offset().top -100 }, 'slow');
    $("#err_msg_shop_amenities").show();
    $("#err_msg_shop_amenities").html("Please check atleast one checkbox in shop amenities.");
    return false;
  }*/

  var brand_checkboxs=document.getElementsByClassName("brand");
  var brand_cond=false;
  for(var i=0,l=brand_checkboxs.length;i<l;i++)
  {
      if(brand_checkboxs[i].checked)
      {
          brand_cond=true;
          break;
      }
  }
  if(!brand_cond)
  {
    $('html, body').animate({scrollTop: $('.brand').offset().top -100 }, 'slow');
    $("#err_msg_brand").show();
    $("#err_msg_brand").html("Please check atleast one checkbox in multi brand.");
    return false;
  }

  //$('input:submit').attr("disabled", true);
  $(".succes_msg").html("Please Wait...");
  return true;
}


// Delete dealer

function delete_dealer_info(val, status) {
  var action = "delete_dealer_info";
  if (confirm("Are you sure want to update this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, status:status, action: action },
      success: function(result) {
        alert(result);
        location.reload();
      }
    });
  } else {
    return false;
  }
}


function validate_brand_model() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#brand_name").val()) == "") {
    $("#err_msg_brand_name").show();
    $("#err_msg_brand_name").html("Enter Brands.");
    $("#brand_name").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_payment_method() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#payment_method").val()) == "") {
    $("#err_msg_payment_method").show();
    $("#err_msg_payment_method").html("Enter Payment method.");
    $("#payment_method").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_insurance_company() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#insurance_company").val()) == "") {
    $("#err_msg_insurance_company").show();
    $("#err_msg_insurance_company").html("Enter Insurance Company.");
    $("#insurance_company").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_shop_amenities() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#shop_amenities").val()) == "") {
    $("#err_msg_shop_amenities").show();
    $("#err_msg_shop_amenities").html("Enter Shop Amenities.");
    $("#shop_amenities").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_review_rating() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#rating").val()) == "") {
    $("#err_msg_rating").show();
    $("#err_msg_rating").html("Enter Rating.");
    $("#rating").focus();
    return false;
  }

  if ($.trim($("#review").val()) == "") {
    $("#err_msg_review").show();
    $("#err_msg_review").html("Enter Review.");
    $("#review").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_shop_service() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#shop_service").val()) == "") {
    $("#err_msg_shop_service").show();
    $("#err_msg_shop_service").html("Enter Shop Service.");
    $("#shop_service").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_service_repair() {
  $(".err_msg").html("");
  $(".err_msg").hide();

  if ($.trim($("#name").val()) == "") {
    $("#err_msg_name").show();
    $("#err_msg_name").html("Enter Name.");
    $("#name").focus();
    return false;
  }

  if ($.trim($("#type").val()) == "") {
    $("#err_msg_type").show();
    $("#err_msg_type").html("Enter Type.");
    $("#type").focus();
    return false;
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function delete_service_repair(val) {
  var action = "delete_service_repair";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}


function delete_brand_model(val, status) {
  var action = "delete_brand_model";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action , status: status },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}

function delete_payment_method(val) {
  var action = "delete_payment_method";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}

function delete_insurance_company(val) {
  var action = "delete_insurance_company";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}

function delete_shop_amenities(val) {
  var action = "delete_shop_amenities";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}

function delete_shop_service(val) {
  var action = "delete_shop_service";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}

function delete_review_rating(val) {
  var action = "delete_review_rating";
  if (confirm("Are you sure want to delete this record")) {
    $.ajax({
      url: "ajax_function.php",
      type: "POST",
      data: { id: val, action: action },
      success: function(result) {
        location.reload();
        alert(result);
      }
    });
  } else {
    return false;
  }
}


function delete_dealer_img(id, col_name) {
  var action = "delete_dealer_img";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, col_name: col_name, action: action },
    success: function(result) {
      location.reload();
    }
  });
}

function delete_pkg(pkg_group_name, status) {
  var action = "delete_pkg";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { pkg_group_name: pkg_group_name, action: action, status: status },
    success: function(result) {
      alert(result);
      location.reload();
    }
  });
}

function delete_recom_pdf(id, status) {
  var action = "delete_recom_pdf";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, action: action, status: status },
    success: function(result) {
      alert(result);
      location.reload();
    }
  });
}

function delete_dealer_rating(id, dealer_id) {
  var action = "delete_dealer_rating";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, dealer_id : dealer_id, action: action },
    success: function(result) {
      alert(result);
      location.reload();
    }
  });
}

function read_feedback(id){
  var action = "read_feedback";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, action: action },
    success: function(result) {
      location.reload();
    }
  });
}

function read_contact_us(id){
  var action = "read_contact_us";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, action: action },
    success: function(result) {
      location.reload();
    }
  });
}

function validate_home_page_images(){
  $(".err_msg").html("");
  $(".err_msg").hide();

  for( var i = 1; i<=3; i++){
    if ($.trim($("#description_"+i).val()) == "") {
      $("#err_msg_description_"+i).show();
      $("#err_msg_description_"+i).html("Enter description.");
      $("#description_"+i).focus();
      return false;
    }

    /*if ($.trim($("#img_"+i).val()) == "") {
      $("#err_msg_img_"+i).show();
      $("#err_msg_img_"+i).html("Select Image.");
      $("#img_"+i).focus();
      return false;
    }*/  
  }

  $(".succes_msg").html("Please Wait...");
  return true;
}

function appointment_action( id, user_id, booking_status ){
  var action = "appointment_action";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { id: id, booking_status: booking_status, user_id: user_id, action: action },
    success: function(result) {
      alert(result);
      location.reload();
    }
  }); 
}

function send_pickeup_otp(booking_id, user_id){
  var pickeup_person = $("#pickeup_person").val();

  if( pickeup_person == "" ){
    alert("Please select pickup person name");
    return false;
  }

  var action = "send_pickeup_otp";
  $.ajax({
    url: "ajax_function.php",
    type: "POST",
    data: { booking_id: booking_id, user_id: user_id, pickeup_person: pickeup_person, action: action },
    success: function(result) {
      alert(result);
      location.reload();
    }
  }); 
}

function validate_pkg_group(){

  $(".err_msg").html("");
  $(".err_msg").hide();

  for( var i = 1; i<=3; i++){

    if ($.trim($("#pkg_price_"+i).val()) == "" || isNaN($.trim($("#pkg_price_"+i).val()))) {
      $("#err_msg_pkg_price_"+i).show();
      $("#err_msg_pkg_price_"+i).html("Invalid Package Price.");
      $("#pkg_price_"+i).focus();
      return false;
    }

    if ($.trim($("#pkg_description_"+i).val()) == "") {
      $("#err_msg_pkg_description_"+i).show();
      $("#err_msg_pkg_description_"+i).html("Please Enter Package description.");
      $("#pkg_description_"+i).focus();
      return false;
    }

    if ($.trim($("#mb_tip_"+i).val()) == "") {
      $("#err_msg_mb_tip_"+i).show();
      $("#err_msg_mb_tip_"+i).html("Please Enter Motorbuddy Tip.");
      $("#mb_tip_"+i).focus();
      return false;
    }

    /*for(var j=1; j<=5; j++){

      if ($.trim($("#service_name_"+i+"_"+j).val()) == "") {
        $("#err_msg_service_name_"+i+"_"+j).show();
        $("#err_msg_service_name_"+i+"_"+j).html("Please Enter Service Name.");
        $("#service_name_"+i+"_"+j).focus();
        return false;
      }

      if ($.trim($("#service_action_"+i+"_"+j).val()) == "") {
        $("#err_msg_service_action_"+i+"_"+j).show();
        $("#err_msg_service_action_"+i+"_"+j).html("Please Select Service Action.");
        $("#service_action_"+i+"_"+j).focus();
        return false;
      }

    }*/

  }
  

  $(".succes_msg").html("Please Wait...");
  return true;

}

function validate_recom_pdf(){
  $(".err_msg").html("");
  $(".err_msg").hide();
  
  if ( $("#mode").val() == 'add' && $.trim($("#recom_pdf").val()) == "") {
    $("#err_msg_recom_pdf").show();
    $("#err_msg_recom_pdf").html("Select PDF.");
    $("#recom_pdf").focus();
    return false;
  }

  if ($.trim($("#recom_pdf").val()) != "") {
    var ext = $('#recom_pdf').val().split('.').pop().toLowerCase();
    if( ext != 'pdf' ) {
      $("#err_msg_recom_pdf").show();
      $("#err_msg_recom_pdf").html("Invalid file type");
      $("#recom_pdf").focus();
      return false;
    }
  } 

  $(".succes_msg").html("Please Wait...");
  return true;
}

function validate_dealer_rating(){
  $(".err_msg").html("");
  $(".err_msg").hide();
  
  /*if ( $.trim($("#user_id").val()) == "" || $.trim($("#merge").val()) == "") {
    $("#err_msg_user_id").show();
    $("#err_msg_user_id").html("Select User.");
    $("#user_id").focus();
    return false;
  }*/

  if ( $.trim($("#user_name_manually").val()) == "") {
    $("#err_msg_user_name_manually").show();
    $("#err_msg_user_name_manually").html("Enter User name.");
    $("#user_name_manually").focus();
    return false;
  }

  if ( $.trim($("#ratings").val()) == "") {
    $("#err_msg_ratings").show();
    $("#err_msg_ratings").html("Select Ratings.");
    $("#ratings").focus();
    return false;
  }

  if ( $.trim($("#comments").val()) == "") {
    $("#err_msg_comments").show();
    $("#err_msg_comments").html("Enter Comments.");
    $("#comments").focus();
    return false;
  }

  return true;
}