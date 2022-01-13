<?php include_once("./machine-info.php");?>

<html>
<head>
</head>
<body>
<h1>블랙리스트 IP 추가</h1>
<h3>추가할 장비</h3>
<form action="add-address-function.php" method="post">

<?php
	$list=machine_list();

	foreach($list as $key => $value) {
		echo "$key"."<br>";
		echo "<ul>";
		$vdom_index=0;
		foreach($value['vdom'] as $vdom) {
			echo '<li>'.'<input type="checkbox" name="machine['.$key.']['.$vdom_index.']"'.' value='.'"'.$vdom.'"'.'>'." $vdom ".'</li>';
			$vdom_index++;
		}
		echo "</ul>";
	}
?>

<h3>유형</h3>
<label><input type="radio" name="address_type" value="ipmask" checked="checked"> ipmask</label>
<label><input type="radio" name="address_type" value="iprange"> iprange</label>
<label><input type="radio" name="address_type" value="geography"> geography</label>
<br><br>
NAME: <input type="text" name="name" /><br><br>
VALUE: <input type="text" name="value" /><br><br>
COMMENT: <input type="text"  name="comment"/><br>
<pre>
유형별 VALUE 입력예시
ipmask - 192.168.1.0 255.255.255.0
iprange - 192.168.1.0 192.168.1.200 
geography - JP
KR은 절대 입력하지 마시오.
</pre>
<input type="submit" value="생성"/><br><br>
<a href="./index.php">뒤로가기</a>
</form>
</body>
<html>

