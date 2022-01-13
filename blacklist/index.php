<?php include_once("machine-info.php");
?>

<head>
<style>
#h1,h1,h2,h3,h4,h5,a,div,table
#{
# font-family:"궁서체";
# font-weight:bold;
#}
</style>
</head>
<h1>블랙 리스트 관리</h1>
<h3>장비 리스트</h3>
<a href="./add-address.php">주소추가하기</a><br><br>

<?php

	$list = machine_list();

	foreach($list as $key => $value) {
		echo "<div style='border: 0px solid gold; float: left; width: 100%; padding:10px;);'>\n";
		echo "<div style='border: 0px solid red; float: left; width: 100%; padding:10px; background-color:rgba(255, 255, 255, 0.4);'>\n";
		echo "장비명: ".$key."<br>\n";
		echo "장비 IP: ".$value['machine_address']."<br>\n";
		echo "vdom-list: ";

		foreach($value['vdom'] as $vdom) {
			echo $vdom." ";
		}

		echo "</div>\n<br>\n<br>\n";

		foreach($value['vdom'] as $vdom) {
			$blacklist = json_decode(get_list($value['machine_address'], $value['api-key'], "group", $vdom, ''), true);
			$addresslist = array();

			echo "<div style='border: 0px solid red; float: left; width: 32%; padding:10px;'>\n";
			echo "<table border='0' style='background-color:rgba(0, 0, 0, 0);'>\n";
			echo "<tr bgcolor='#A9F5A9'>\n";
			//echo "<td colspan='".sizeof($blacklist['results'][0]['member'])."'> $vdom </td>\n";
			echo "<td colspan='20'> $vdom </td>\n";
			echo "</tr>\n";

			for($count = 0; $count < sizeof($blacklist['results'][0]['member']); $count++) {
				$addresslist[$count] = $blacklist['results'][0]['member'][$count]['name'];
				$rules = json_decode(get_list($value['machine_address'], $value['api-key'], "address", $vdom, $addresslist[$count]), true);
				$item = address_type($rules['results'][0]['type']);
				$num = $count + 1;

				echo "<tr>\n";
				echo "<td bgcolor='#EEEEEE' width='30'>".$num."</td>";
				echo "<td bgcolor='#A9F5F2'>".$addresslist[$count]."</td>";

				for ($i = 1; $i < sizeof($item); $i++) {
					switch ($item[$i]) {
						case "subnet":
							$color = "#F7D358"; break;
						case "country":
							$color = "#F78181"; break;
						default:
							$color = "#EEEEEE";
					}

					echo "<td bgcolor='".$color."'>";;
					echo $rules['results'][0][$item[$i]];
					echo "</td>\n";
				}

				echo "<td style='background-color:rgba(0, 0, 0, 0);'>";
				echo "<form action='delete-address.php' method='post' onsubmit=\"return confirm('".$addresslist[$count]."\\n모든 장비에서 위 IP를 삭제합니다.');\">\n";
				echo "<button type='submit' style='float:right' name='delete_address' value='".$addresslist[$count]."'>삭제</button>\n";
				echo "</form>\n";
				echo "</td>\n</tr>\n";
			}

			echo "</table>\n</div>\n";
		}

		echo "</div>\n";
		echo "<br>\n<br>\n<br>\n<br>\n";
	}

?>
