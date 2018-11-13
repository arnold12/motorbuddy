<?php
function sanitize($str) {
    return strip_tags(trim(($str)));
}

function isUserLoggedIn(){
	if(isset($_SESSION['username']) && $_SESSION['username'] != '' ){
		return true;
	}
	return false;
}

function convertDate($date){
	$date = explode('-',$date);	
	$date1 = $date[1].'/'.$date[2].'/'.$date[0];	
	return $date1;
}

function dmytoymd($date)
{
	$date1 = explode('-',$date);
	return $date1['2']."-".$date1['1']."-".$date1['0'];
}

function ymdtodmy($date)
{
	$date1 = explode('-',$date);
	return $date1['2']."-".$date1['1']."-".$date1['0'];
}

function ymdtodmy_withtime($date)
{
	$date1 = explode('-',$date);
	$dat1 = substr($date1['2'],0,2);
	$dat2 = substr($date1['2'],3);
	return $dat1."-".$date1['1']."-".$date1['0']." ".$dat2;
}

function bitwisesummation($bitwise_array , $data)
{
	// print_r($bitwise_array);
	// print_r($data);
	// exit();
	$sum = 0;
	foreach($bitwise_array AS $key => $value)
	{
		if(isset($data[$key])){
			if($data[$key])
			{
				$sum += $value;
			}	
		}
		
	}
	return $sum;
}

function fetchbitwisevalues($bitwisearr , $data , $str=0)
{
	$subsresult = array();
	foreach($bitwisearr AS $key=>$value)
	{
		if( $data & $value )
		{		
			$subsresult[$key] = 1;
		}
		else
		{
			$subsresult[$key] = 0;
		}
	}
	return $subsresult;
}

function randomString($length = 12) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function otp($length = 4) {
	$str = "";
	$characters = range('0','9');
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}


?>