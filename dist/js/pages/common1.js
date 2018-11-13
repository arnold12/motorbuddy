function getServiceForm(serviceid){

	var param;
	$("#at_row_count").val("0");
	if(serviceid == 0){
		$("#displayserviceform").html("");	
		return false;
	} else if(serviceid == 1){		
		$("#err_msg_service").show();
		$("#err_msg_service").html("Please Wait...");
		param = 'serviceid='+serviceid+'&action=getATForm';
		$("#at_row_count").val("1");
		server_request(param);
	}
	return false;

}


function server_request(param){

	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){
			$("#err_msg_service").hide();
			$("#displayserviceform").append(result);	
			$(".at_deparature").datepicker();	
			$(".at_deparature").datepicker( "option", "dateFormat", "yy-mm-dd");
			$(".at_deparature").datepicker( "option", "minDate", 0);
		}
	});
	return false;
}

function add_air_ticket_row(){		
	var html = '';
	var at_row_count = parseInt($('#at_row_count').val());
	var next_at_row_count = at_row_count + 1;
	
	html += "<div class='form-group' id='at_row_"+next_at_row_count+"'><div class='col-sm-2'><input type='text' class='form-control input-sm at_deparature' id='air_deparature_"+next_at_row_count+"' name='air_deparature[]' placeholder='Deparature'><label id='err_msg_air_deparature_"+next_at_row_count+"' for='air_deparature_"+next_at_row_count+"' class='control-label err_msg' style='color: #dd4b39;font-size: 11px;display: none;'></label></div><div class='col-sm-2'><select class='form-control select2' id='air_class_"+next_at_row_count+"' name='air_class[]' style='width: 100%;height: 30px'><option value='0' selected='selected'> Class </option><option value='1'>Economy Class</option><option value='2'>Business Class</option><option value='3'>First Class</option><option value='4'>Premium Economy</option></select><label id='err_msg_air_class_"+next_at_row_count+"' for='air_class_"+next_at_row_count+"' class='control-label err_msg' style='color: #dd4b39;font-size: 11px;display: none;'></label></div><div class='col-sm-2'><input type='text' class='form-control input-sm' id='air_sector_from_"+next_at_row_count+"' name='air_sector_from[]' placeholder='From'><label id='err_msg_air_sector_from_"+next_at_row_count+"' for='air_sector_from_"+next_at_row_count+"' class='control-label err_msg' style='color: #dd4b39;font-size: 11px;display: none;'></label></div><div class='col-sm-2'><input type='text' class='form-control input-sm' id='air_sector_to_"+next_at_row_count+"' name='air_sector_to[]' placeholder='To'><label id='err_msg_air_sector_to_"+next_at_row_count+"' for='air_sector_to_"+next_at_row_count+"' class='control-label err_msg' style='color: #dd4b39;font-size: 11px;display: none;'></label></div><div class='col-sm-2' id='add_remove_btn_"+next_at_row_count+"'><a href='#' onclick='add_air_ticket_row();return false;'><img src='/sandeshtours/dist/img/plus.jpg' alt='Add'></a>&nbsp;&nbsp;<a href='#' id='remove_air_ticket' onclick='remove_air_ticket_row();return false;'><img src='/sandeshtours/dist/img/minus.jpg' alt='Remove'></a></div></div>";
		
	$("#displayserviceform").append(html);	
	$("#add_remove_btn_"+at_row_count).hide();
	$("#at_row_count").val(next_at_row_count);
	$('.at_deparature').datepicker();
	$(".at_deparature").datepicker( "option", "dateFormat", "yy-mm-dd");
	$(".at_deparature").datepicker( "option", "minDate", 0);
	
	return false;
	
}

function remove_air_ticket_row(){

	var at_row_count = parseInt($('#at_row_count').val());
	var prev_at_row_count = at_row_count - 1;
	
	$('#at_row_' + at_row_count + '').remove();
	$('#add_remove_btn_'+prev_at_row_count+'').show();
	if(prev_at_row_count == 1){
		$('#row_remove_btn_'+prev_at_row_count+'').hide();
	}
	$("#at_row_count").val(prev_at_row_count);
	
	return false;
	
}

function delete_air_ticket_row(at_row_id){
	var at_row_count = parseInt($('#at_row_count').val());
	var prev_at_row_count = at_row_count - 1;
	
	var param = 'at_row_id='+at_row_id+'&action=deleteATrow';
	
	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){
			if(result == 1){
				$('#at_row_' + at_row_count + '').remove();
				$('#add_remove_btn_'+prev_at_row_count+'').show();
				if(prev_at_row_count == 1){
					$('#row_remove_btn_'+prev_at_row_count+'').hide();
				}
				$("#at_row_count").val(prev_at_row_count);		
			} else {
				alert(result);
			}
			
		}
	});
	
	return false;	
}

function showCloseReason(inquiry_status){
	$("#divCloseReason").hide();
	if(inquiry_status == 3){
		$("#divCloseReason").show();
	}
	return false;	
}

function validate_inquiry(){
	
	var email_match = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var phone_match = /^[-+]?[0-9]+$/;	
	var number_match = /^[0-9]+$/;

	$(".err_msg").html('');
	$(".err_msg").hide();
	if($("#prefix").val() == ''){
		$("#err_msg_prefix").show();		
		$("#err_msg_prefix").html('Select Prefix.');
		$("#prefix").focus();
		return false;
	}
	
	if($.trim($("#fname").val()) == ''){
		$("#err_msg_fname").show();
		$("#err_msg_fname").html('Enter First name.');
		$("#fname").focus();
		return false;
	}
	
	if($.trim($("#lname").val()) == ''){
		$("#err_msg_lname").show();
		$("#err_msg_lname").html('Enter Last name.');
		$("#lname").focus();
		return false;
	}
	
	if($.trim($("#contactno").val()) == ''){
		$("#err_msg_contactno").show();
		$("#err_msg_contactno").html('Enter Contact no.');
		$("#contactno").focus();
		return false;
	}
	
	if(!$.trim($("#contactno").val()).match(phone_match)){
		$("#err_msg_contactno").show();
		$("#err_msg_contactno").html('Inavalid Contact No.');
		$("#contactno").focus();
		return false;
	}
	
	if($.trim($("#contactno").val()).length > 10 || $.trim($("#contactno").val()).length < 10){
		$("#err_msg_contactno").show();
		$("#err_msg_contactno").html('Contact Number Must be 10 Digit.');
		$("#contactno").focus();
		return false;
	}
	
	if($.trim($("#landlineno").val()) != '' && isNaN(parseInt($('#landlineno').val()))){
		$("#err_msg_landlineno").show();
		$("#err_msg_landlineno").html('Invalid Landline No.');
		$("#landlineno").focus();
		return false;
	}
	
	if($.trim($("#email").val()) == ''){
		$("#err_msg_email").show();
		$("#err_msg_email").html('Enter Email.');
		$("#email").focus();
		return false;
	}
	
	if(!$.trim($("#email").val()).match(email_match)){
		$("#err_msg_email").show();
		$("#err_msg_email").html('Invalid Email Id.');
		$("#email").focus();
		return false;
	}
	
	if($.trim($("#NoOfAdult").val()) == ''){
		$("#err_msg_NoOfAdult").show();
		$("#err_msg_NoOfAdult").html('Enter No of Adults.');
		$("#NoOfAdult").focus();
		return false;
	}
	if(!$.trim($("#NoOfAdult").val()).match(number_match)){
		$("#err_msg_NoOfAdult").show();
		$("#err_msg_NoOfAdult").html('Enter Number Only.');
		$("#NoOfAdult").focus();
		return false;
	}
	
	if($.trim($("#NoOfChilds").val()) == ''){
		$("#err_msg_NoOfChilds").show();
		$("#err_msg_NoOfChilds").html('Enter No of Childs.');
		$("#NoOfChilds").focus();
		return false;
	}
	
	if(!$.trim($("#NoOfChilds").val()).match(number_match)){
		$("#err_msg_NoOfChilds").show();
		$("#err_msg_NoOfChilds").html('Enter Number Only.');
		$("#NoOfChilds").focus();
		return false;
	}
	
	if($.trim($("#NoOfInfants").val()) == ''){
		$("#err_msg_NoOfInfants").show();
		$("#err_msg_NoOfInfants").html('Enter No of Infants.');
		$("#NoOfInfants").focus();
		return false;
	}
	
	if(!$.trim($("#NoOfInfants").val()).match(number_match)){
		$("#err_msg_NoOfInfants").show();
		$("#err_msg_NoOfInfants").html('Enter Number Only.');
		$("#NoOfInfants").focus();
		return false;
	}
	
	if($("#service").val() == 0){
		$("#err_msg_service").show();
		$("#err_msg_service").html('Select Service.');
		$("#service").focus();
		return false;
	}
	
	if(!validate_inquiry_services($("#service").val())){
		return false;
	}
	
	if($.trim($("#guest_discussion").val()) == ''){
		$("#err_msg_guest_discussion").show();
		$("#err_msg_guest_discussion").html('Enter Discussion.');
		$("#guest_discussion").focus();
		return false;
	}
	
	if($("#follup_date").val() == ''){
		$("#err_msg_follup_date").show();
		$("#err_msg_follup_date").html('Enter Date.');
		$("#follup_date").focus();
		return false;
	}
	
	if($("#inquiry_status").val() == 3 && $("#close_reason").val() == ''){
		$("#err_msg_close_reason").show();
		$("#err_msg_close_reason").html('Select Reason.');
		$("#close_reason").focus();
		return false;
	}
	
	inquiry_submit();
	return false;
}

function validate_inquiry_services(serviceid){
	if(serviceid == 1){
		var at_row_count = $("#at_row_count").val();
		for(var i=1 ; i<=at_row_count ; i++){
	
			if($("#air_deparature_"+i).val() == ''){
				$("#err_msg_air_deparature_"+i).show();
				$("#err_msg_air_deparature_"+i).html('Select date.');
				$("#air_deparature_"+i).focus();
				return false;
			} 
			
			if($("#air_class_"+i).val() == 0){
				$("#err_msg_air_class_"+i).show();
				$("#err_msg_air_class_"+i).html('Select class.');
				$("#air_class_"+i).focus();
				return false;
			}
			
			if($.trim($("#air_sector_from_"+i).val()) == ''){
				$("#err_msg_air_sector_from_"+i).show();
				$("#err_msg_air_sector_from_"+i).html('Enter Sector From.');
				$("#air_sector_from_"+i).focus();
				return false;
			}
			
			if($.trim($("#air_sector_to_"+i).val()) == ''){
				$("#err_msg_air_sector_to_"+i).show();
				$("#err_msg_air_sector_to_"+i).html('Enter Sector To.');
				$("#air_sector_to_"+i).focus();
				return false;
			}
			
		}
			
		return true;
	}
	
}

function inquiry_submit(){
	var param = $("#service_inqu_frm").serialize();
	$("#submit").attr("disabled",true);	
	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){
			if($("#inquiry_status").val() != '1'){
			alert(result);
			location.reload();
			}
			$(".succes_msg").show();
			$(".succes_msg").html(result);		
		}
	});
	return false;
	
}


function totalPax(){
	var NoOfAdult  = 0;
	var NoOfChilds = 0;
	var NoOfInfants = 0;
	
	if(!isNaN(parseInt($('#NoOfAdult').val()))){
		NoOfAdult = parseInt($('#NoOfAdult').val());
	}
	
	if(!isNaN(parseInt($('#NoOfChilds').val()))){
		NoOfChilds = parseInt($('#NoOfChilds').val());
	}
	
	if(!isNaN(parseInt($('#NoOfInfants').val()))){
		NoOfInfants = parseInt($('#NoOfInfants').val());
	}
	
	var noOfPax = NoOfAdult + NoOfChilds + NoOfInfants;
	$("#pax").val(noOfPax);
	
}

function add_air_ticket_esti_row(){
	var html = '';
	
	var esti_count = parseInt($("#esti_count").val());
	var next_esti_count = esti_count + 1;
	
	var param = 'inquiry_id='+$("#inquiry_id").val()+'&service_id='+$("#service_id").val()+'&action=getATEstiData';
	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){
			var result = result.split('/~/');
			
			html += "<tr id='esti_row_"+next_esti_count+"'><td>"+next_esti_count+"</td><td><select id='sub_inq_id_"+next_esti_count+"' class='form-control select2' name='sub_inq_id[]' style='width:100px'><option value='' selected>Select </option>"+result[1]+"</select></td><td><input type='text' class='input-sm' id='flight_no_"+next_esti_count+"' name='flight_no[]' value='' size='6'></td><td><input type='text' class='input-sm' id='airline_"+next_esti_count+"' name='airline[]' value='' size='6'></td><td><input type='text' class='input-sm date' id='dep_date_"+next_esti_count+"' name='dep_date[]' value='' size='8'></td><td><input type='text' class='input-sm' id='dep_time_"+next_esti_count+"' name='dep_time[]' value='' size='6'></td><td><input type='text' class='input-sm' id='dep_frm_"+next_esti_count+"' name='dep_frm[]' value='' size='6'></td><td><input type='text' class='input-sm date' id='arrvl_date_"+next_esti_count+"' name='arrvl_date[]' value='' size='8'></td><td><input type='text' class='input-sm' id='arrvl_time_"+next_esti_count+"' name='arrvl_time[]' value='' size='6'></td><td><input type='text' class='input-sm' id='arrvl_at_"+next_esti_count+"' name='arrvl_at[]' value='' size='6'></td><td><select id='refundable_"+next_esti_count+"' class='form-control select2' name='refundable[]' style='width:100px'><option value='' selected>Select</option><option value='0'>No</option><option value='1'>Yes</option></select></td><td><input type='text' class='input-sm' id='estim_price_"+next_esti_count+"' name='estim_price[]' value='' size='6'></td><td>NO</td><td><select id='supp_name_"+next_esti_count+"' class='form-control select2' name='supp_name[]' style='width:100px'><option value='0' selected>Select</option>"+result[0]+"</select></td><td><input type='text' class='input-sm' id='supp_price_"+next_esti_count+"' name='supp_price[]' value='' size='6' onchange='totalSuppEsti(1,"+next_esti_count+")'></td><td><input type='text' class='input-sm' id='supp_tax_"+next_esti_count+"' name='supp_tax[]' value='' size='6' onchange='totalSuppEsti(1,"+next_esti_count+")'></td><td><input type='text' class='input-sm' id='supp_total_"+next_esti_count+"' name='supp_total[]' value='' size='6' readonly></td><td size='6'><div id='add_esti_"+next_esti_count+"'><a onclick='add_air_ticket_esti_row();return false;'><img alt='Add' src='/sandeshtours/dist/img/plus.jpg'></a>&nbsp;&nbsp;<a onclick='remove_air_ticket_esti_row();return false;'><img alt='Remove' src='/sandeshtours/dist/img/minus.jpg'></a></div></td></tr>";
	
			$("#at_estimation").append(html);
			$("#esti_count").val(next_esti_count);
			
			$("#add_esti_"+esti_count).hide();
			
			$(".date").datepicker();		
			$(".date").datepicker( "option", "dateFormat", "yy-mm-dd");
			$(".date").datepicker( "option", "minDate", 0);
			
		}
	});
	return false;
	 
	
}
		
function remove_air_ticket_esti_row(){
	var esti_count = parseInt($("#esti_count").val());
	var prev_esti_count = esti_count - 1;
	
	$('#esti_row_'+esti_count+'').remove();	
	$("#esti_count").val(prev_esti_count);
	$("#add_esti_"+prev_esti_count).show();
}

function validate_ATEsti(){
	var esti_count = parseInt($("#esti_count").val());
	var prev_send_esti_count = parseInt($("#prev_send_esti_count").val());
	
	$("#succes_msg").hide();
	$("#succes_msg").css('color','green');
	$("#succes_msg").html('');
	
	if(parseInt($("#inquiry_status").val()) != 1){	
		var inq_status = '';
		if(parseInt($("#inquiry_status").val()) == 3){
			inq_status = 'Close';
			
			if($("#close_reason").val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Close Inquiry Reason');
				$("#close_reason").focus();
				return false;
			}
			
		} else {
			inq_status = 'Convert';
		}
		if(confirm('Are sure you want to '+inq_status+' this inquiry?')){
			
			estimation_send();
		} else {
			$("#succes_msg").show();
			$("#succes_msg").css('color','red');
			$("#succes_msg").html('Please Change Your inquiry status to open.');
		}
		return false;
	}
	
	for(var i=(prev_send_esti_count+1) ; i<=esti_count ; i++){
	
		if($("#sub_inq_id_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Sub Inquiry Id for Estimation No. '+i);
				$("#sub_inq_id_"+i).focus();
				return false;
		} 
		
		if($("#flight_no_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Flight No for Estimation No. '+i);
				$("#flight_no_"+i).focus();
				return false;
		} 
		
		if($("#airline_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Airline for Estimation No. '+i);
				$("#airline_"+i).focus();
				return false;
		} 
		
		if($("#dep_date_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Deparature date for Estimation No. '+i);
				$("#dep_date_"+i).focus();
				return false;
		} 
		
		if($("#dep_time_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Deparature time for Estimation No. '+i);
				$("#dep_time_"+i).focus();
				return false;
		} 
		
		if($("#dep_frm_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Deparature from for Estimation No. '+i);
				$("#dep_frm_"+i).focus();
				return false;
		} 
		
		if($("#arrvl_date_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Arrival Date for Estimation No. '+i);
				$("#arrvl_date_"+i).focus();
				return false;
		} 
		
		if($("#arrvl_time_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Arrival time for Estimation No. '+i);
				$("#arrvl_time_"+i).focus();
				return false;
		} 
		
		if($("#arrvl_at_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Arrival At for Estimation No. '+i);
				$("#arrvl_at_"+i).focus();
				return false;
		} 
		
		if($("#refundable_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Refundable for Estimation No. '+i);
				$("#refundable_"+i).focus();
				return false;
		} 
		
		if($("#estim_price_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Estimation Price for Estimation No. '+i);
				$("#estim_price_"+i).focus();
				return false;
		} 
		
		if(isNaN(parseFloat($("#estim_price_"+i).val()))){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Valid Estimation Price for Estimation No. '+i);
				$("#estim_price_"+i).focus();
				return false;
		} 
		
		if($("#supp_name_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Select Supplier for Estimation No. '+i);
				$("#supp_name_"+i).focus();
				return false;
		} 
		
		if($("#supp_price_"+i).val() == ''){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Supplier Price Estimation No. '+i);
				$("#supp_price_"+i).focus();
				return false;
		}
		
		if(isNaN(parseFloat($("#supp_price_"+i).val()))){
				$("#succes_msg").show();
				$("#succes_msg").css('color','red');
				$("#succes_msg").html('Enter Valid Supplier Price for Estimation No. '+i);
				$("#supp_price_"+i).focus();
				return false;
		} 
		
	}
	if(confirm('Are sure you want to send this estimation to guest?')){
		estimation_send();
	}
	return false;
	
}

function estimation_send(){

	var param = $("#inqu_esti_frm").serialize();
	$("#submit").attr("disabled",true);	
	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){	
			
			if(result == "1"){
				window.location.assign('view_service_inquiry.php');
			} else {
				alert(result);
				//$(".succes_msg").show();
				//$(".succes_msg").html(result);	
				location.reload();
			}
		}
	});
	return false;
	
}

function totalSuppEsti(serviceid, esti_count){
	
	var supp_price  = 0;
	var supp_tax = 0;
	
	if(!isNaN(parseFloat($('#supp_price_'+esti_count).val()))){
		supp_price = parseFloat($('#supp_price_'+esti_count).val());		
	}
	
	if(!isNaN(parseFloat($('#supp_tax_'+esti_count).val()))){
		supp_tax = parseFloat($('#supp_tax_'+esti_count).val());
	}
	
	
	var supp_total = supp_price + supp_tax;
	$("#supp_total_"+esti_count).val(supp_total);
	
}

function updateAT_Esti_GuestAgree(val, id, esti_count_no){
	$("#guest_aggr_"+esti_count_no).prop("disabled",true);
	
	var param = 'id='+id+'&val='+val+'&action=updateATEstiGuestAgree';
	$.ajax({
		url: 'ajax_service_inquiry.php', 
		type: 'POST',
		data: param,
		success: function(result){
		}
	});
	
}
