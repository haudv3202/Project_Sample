<?php
@session_start();
include("controllers/c_product.php");
$c_product = new c_product();
$c_product->products();