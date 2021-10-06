<?php

$contents = file_get_contents("product-data.json");
$list = json_decode($contents);

echo "<pre>";
print_r($list);
echo "</pre>";