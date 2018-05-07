<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>debug</title>
    <style>
        body{
            padding: 20px;
            margin: 0;
            word-break: break-all;
        }
        h1{
            margin: 0;
        }
        pre{
            padding: 16px;
            line-height: 1.45em;
            border-radius: 3px;
            background-color: #f7f7f7;
        }
    </style>
</head>
<body>
<h1>Debug</h1>
<p><?php $trace = $e->getTrace()[0]; echo $trace['file'];?> line <?php echo $trace['line'];?></p>
<pre><?php
$msg = $e->getMessage();
$counter = count($msg) - 1;
foreach($msg as $k=>$v) {
    if(is_bool($v)) {
        if($v) {
            echo 'true';
        }else{
            echo 'false';
        }
    }elseif(is_null($v)){
        echo 'null';
    }else{
        print_r($v);
    }
    if($counter> $k) echo '<br>';
}
?></pre>
</body>
</html>