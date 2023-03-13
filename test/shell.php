<?php
require_once '../autoload.php';
require_once './config.php';

function my_shell_exec($cmd, &$stdout=null, &$stderr=null) {
    $descriptors = [
        1 => ['pipe','w'],
        2 => ['pipe','w'],
    ];
    $pipes = [];
    $proc = proc_open($cmd, $descriptors, $pipes);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    return proc_close($proc);
}

function execute($command, $show_stderr = true)
{
    $stdout = null;
    $stderr = null;
    my_shell_exec($command, $stdout, $stderr);

    echo PHP_EOL;
    echo PHP_EOL;
    echo '<pre class="console">';
    echo '<span class="prompt">$</span> ' . $command . PHP_EOL;
    echo PHP_EOL;
    if($show_stderr)
    {
        echo '<span class="error">';
        echo $stderr . PHP_EOL;
        echo '</span>';
    }
    echo $stdout . PHP_EOL;
    echo '</pre>';
    return $stdout;
}
?>
<html lang="fr">
<head>
    <title>HellAsso API test</title>
    <style>
        .source_code
        {
            background: #EEEEEE;
            border: 1px solid #BBBBBB;
            margin: auto;
            width: 90%;
            padding: 0.5em;
        }
        .prompt
        {
            color: #26a269;
            font-weight: bold;
        }
        .error
        {
            color: #CC0000;
        }
        .console
        {
            width: 90%;
            margin: auto;
            background: #300a24;
            color: #f9f8f9;
            padding: 0.5em;
        }
    </style>
</head>
<body>
<h1>IP serveur</h1>
<?php  execute('curl https://api.ipify.org', false); ?>
<hr>
<h1>Script de test</h1>
<?php execute(PHP_PATH . ' ./v5.php'); ?>
<hr>
<h1>Test cURL d'appel authentification</h1>
<?php execute('curl -v https://api.helloasso.com/oauth2/token'); ?>
<hr>
<h1>Code source</h1>
<pre class="source_code">
<?php highlight_file('../V5/Api/Authentication.php'); ?>
</pre>
<hr>
<h1>PHP Info</h1>
<?php phpinfo() ?>
</body>
</html>

