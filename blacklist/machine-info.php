<?php
	
function machine_list() {
	$file = "machine-list.json";
	if(!file_exists($file)) {
		echo "not exists api file\n";
		exit;
	}
	
	$machine_list = json_decode(file_get_contents($file), true);
	return $machine_list;
}

function get_list($machine_address, $api_key, $type, $vdom, $name) {
        $ch = curl_init();

        switch($type) {
                case "group":
                        $api_type = "addrgrp/blacklist";
                        break;
                case "address":
			$name = rawurlencode($name);
                        $api_type = "address/$name";
                        break;
                default:
                        return "Type Error";
        }

        $url = "https://$machine_address/api/v2/cmdb/firewall/$api_type?vdom=$vdom";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $api_key"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($ch);
        if (curl_error($ch)) {
                exit('CURL Error('.curl_errno( $ch ).') '.curl_error($ch));
        }

        curl_close($ch);
        return $output;
}

function address_type($type) {
	switch($type) {
		case "ipmask":
			return ["name", "subnet", "comment"];
		case "iprange":
			return ["name", "subnet", "comment"];
		case "geography":
			return ["name", "country", "comment"];
		case "fqdn":
			return 0;
		case "wildcard":
			return 0;
		case "wildcard-fqdn":
			return 0;
		case "dynamic":
			return 0;
		default:
			echo "Input Error";
	}
}

?>
