<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

//$ddd = date("Y-m-d", time());

function add_address($name, $value, $comment, $address_type, $address_value, $machine, $list) {
	echo "<br><br>";
	$datestring = date("ymd", time());

	foreach($machine as $key => $selected_vdom){
		echo $key."<br>";
		$machine_address=$list[$key]['machine_address'];		
		echo $machine_address."<br>";
		$api_key=$list[$key]['api-key'];
		foreach($selected_vdom as $values){
			$url = "https://$machine_address/api/v2/cmdb/firewall/address/?vdom=$values";
			$postfield = '{"name":'.'"'.$name.'"'.', "type":'.'"'.$address_type.'"'.', '.'"'.$address_value.'"'.': '.'"'.$value.'"'.', '.'"comment": '.'"'.$datestring.' '.$comment.'"'.'}';
		
			echo "정의된 데이터<br>".$postfield."<br><br>";

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $api_key", "Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			echo "결과<br>".$data."<br><br>";
			curl_close($ch);

		}
		echo "<br>";

	}
}

function add_to_blacklist($name, $machine, $list){
        foreach($machine as $key => $selected_vdom){
		$machine_address=$list[$key]['machine_address'];
                $api_key=$list[$key]['api-key'];
		foreach($selected_vdom as $values){
                        $blacklist = json_decode(get_list($machine_address, $api_key, "group", $values, ''), true);
			
			$postfield='{"member": [';

			echo "blacklist IP list<br>";
                        $addresslist = array();
                        for($memcount = 0; $memcount < sizeof($blacklist['results'][0]['member']); $memcount++) {
                                $addresslist[$memcount] = $blacklist['results'][0]['member'][$memcount]['name'];
				
                                echo $addresslist[$memcount]."<br>\n";
				$postfield = $postfield.'{"name": '.'"'.$addresslist[$memcount].'"}, ';				
                        }
	
			$postfield = $postfield.'{ "name": '.'"'.$name.'"'.' }]}';
                        echo "<br>정의된 데이터<br>".$postfield."<br><br>";
		
			$url = "https://$machine_address/api/v2/cmdb/firewall/addrgrp/blacklist/?vdom=$values";
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $api_key", "Content-Type: application/json"));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS,$postfield);
                        $data = curl_exec($ch);
                        echo "blacklist 추가<br>".$data."<br><br>";
                        curl_close($ch);
		}
	}				
}

?>

<?php
include("./machine-info.php");

$name = $_POST['name'];
$value = $_POST['value'];
$comment = $_POST['comment'];
$address_type = $_POST['address_type'];

$machine = Array();
$machine = $_POST['machine'];

if($value == "KR"){
	echo '<script type="text/javascript">alert("절대로 KR을 입력하지 마세요"); history.go(-1); </script>';
	exit();
}

if(strpos($value, "0.0.0.0") !== false) {
	echo '<script type="text/javascript">alert("0.0.0.0 을 입력하지 마세요"); history.go(-1); </script>';
	exit();
}

if($address_type == "ipmask" or $address_type == "iprange" ){
	$address_value = "subnet";
}
elseif($address_type == "geography"){
	$address_value = "country";
}
else{
	echo '<script type="text/javascript">alert("입력 오류"); history.go(-1); </script>';
	exit();
}

$list = machine_list();

echo '<a href="./index.php">뒤로가기</a><br><br>';

add_address($name, $value, $comment, $address_type, $address_value, $machine, $list);
add_to_blacklist($name, $machine, $list);
echo '<a href="./index.php">뒤로가기</a><br><br>';

?>

