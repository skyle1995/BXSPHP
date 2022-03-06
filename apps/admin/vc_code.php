<?php
include("./vc_code.php");
$_vc = new ValidateCode();
$_vc->doimg();
$_SESSION['vc_code'] = $_vc->getCode();
?>