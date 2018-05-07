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
<h1><?php echo $e->getMessage();?></h1>
<p><?php echo $e->getFile();?> line <?php echo $e->getLine();?></p>
<pre><?php print_r(str_replace("\n", '<br>', $e->getTraceAsString()));?></pre>
</body>
</html>