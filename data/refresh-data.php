<?php 
//delete this file from remote host when not in use
require_once("../../../../wp-load.php");
require_once( ABSPATH . 'wp-admin/includes/post.php' );
do_action('buildProductData');
echo "action done.<br>";
echo 'check the <a href="logs.txt">log file</a><br>';
echo 'view the <a href="view-data.php">data</a><br>';
echo "refresh the page to run again.";
