<?php

function simple_log($log_msg) {

	$log_file_data = getcwd().'/logs';
	file_put_contents($log_file_data,  date('Y-m-d H:i:s').' - '.$log_msg . "\n", FILE_APPEND);
}