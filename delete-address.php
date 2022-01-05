<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

function delete_from_blacklist($delete_address, $list){
        foreach($list as $key => $vdom){
                $machine_address=$list[$key]['machine_address'];
                $api_key=$list[$key]['api-key'];

		echo $machine_address.'<br>';
		echo $api_key.'<br><br>';
                foreach($vdom['vdom'] as $values){
                        $blacklist = json_decode(get_list($machine_address, $api_key, "group", $values, ''), true);
				
			echo $values.'<br>';
			
                        $postfield='{"member": [';

                        $addresslist = array();
                        for($memcount = 0; $memcount < sizeof($blacklist['results'][0]['member']); $memcount++) {
                                $addresslist[$memcount] = $blacklist['results'][0]['member'][$memcount]['name'];
                        }
			
			if (($key = array_search($delete_address, $addresslist)) !== false ){
				unset($addresslist[$key]);
			}

			foreach($addresslist as $namelist){
				$postfield = $postfield.'{"name": '.'"'.$namelist.'"},';
			}
				
			$postfield = substr($postfield, 0, -1);
			$postfield = $postfield.']}';

			echo "정의된 데이터<br>";
			print_r($postfield);
			echo "<br><br>";
			

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
                        echo "blacklist 그룹에서  삭제<br>".$data."<br><br>";
                        curl_close($ch);
	
                        $url = "https://$machine_address/api/v2/cmdb/firewall/address/".rawurlencode($delete_address)."?vdom=$values";
			
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_VERBOSE, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $api_key"));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                        $data = curl_exec($ch);
                        echo "ip address 삭제<br>".$data."<br><br>";
                        curl_close($ch);

                }
        }
}

?>

<?php
include("./machine-info.php");

$delete_address = $_POST['delete_address'];

$list = machine_list();

echo '<a href="./index.php">뒤로가기</a><br><br>';

echo $delete_address.'<br><br>';

delete_from_blacklist($delete_address, $list);
	
echo '<a href="./index.php">뒤로가기</a><br><br>';

?>

