<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

header('Content-type: application/json');
require_once("../../../wp-load.php");
$user_ID = get_current_user_id();
$phone = get_user_meta($user_ID,'user_phone',true);
if($phone!=""){
    $response_array['status'] = 'success';  
}else if($phone == "") {
    $response_array['status'] = 'error';  
}
echo json_encode($response_array);
/* SKM -- Please don't change the location of this file on tthe server */
?>