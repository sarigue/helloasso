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
        h1 > input
        {
            visibility: hidden;
            display: none;
        }
        h1 > label
        {
            cursor: pointer;
        }
        h1:has(input:checked) + div
        {
            display: none;
        }
        h1:has(input:checked) > label::before
        {
            content: "▾\00a0";
        }
        h1:has(input:not(:checked)) > label::before
        {
            content: "▴\00a0";
        }
    </style>
</head>
<body>
<h1>
    <input type="checkbox" id="ip"/>
    <label for="ip">IP serveur</label>
</h1>
<div>
    <?php execute('curl https://api.ipify.org', false); ?>
    <hr>
</div>
<h1>
    <input type="checkbox" id="auth-test"/>
    <label for="auth-test">Test cURL d'appel authentification</label>
</h1>
<div>
    <?php execute('uname -a'); ?>
    <?php execute('curl --version'); ?>
    <?php execute('curl -v'
        .' https://api.helloasso.com/oauth2/token'
    ); ?>
    <hr>
</div>
<h1>
    <input type="checkbox" id="test-script"/>
    <label for="test-script">Script de test</label>
</h1>
<div>
    <?php execute(PHP_PATH . ' ./v5.php'); ?>
    <hr>
</div>
<h1>
    <input type="checkbox" id="code-source"/>
    <label for="code-source">Code source</label>
</h1>
<div>
    <h2>../V5/Api/Authentication.php</h2>
    <pre class="source_code">
        <?php highlight_file('../V5/Api/Authentication.php'); ?>
    </pre>
    <h2>./v5.php</h2>
    <pre class="source_code">
        <?php highlight_file('./v5.php'); ?>
    </pre>
    <hr>
</div>
<h1>
    <input type="checkbox" id="php-info"/>
    <label for="php-info">PHP Info</label>
</h1>
<div>
    <?php
    @ob_start();
    phpinfo();
    $phpinfo = @ob_get_clean();
    $phpinfo = preg_replace('/<!DOCTYPE[^>]+>/i', '', $phpinfo);
    $phpinfo = preg_replace('/<\/?html[^>]*>/', '', $phpinfo);
    $phpinfo = preg_replace('/<\/?head[^>]*>/', '', $phpinfo);
    $phpinfo = preg_replace('/<\/?meta[^>]*>/', '', $phpinfo);
    $phpinfo = preg_replace('/<title>.+?<\/title>/', '', $phpinfo);
    $phpinfo = preg_replace('/<\/?body[^>]*>/', '', $phpinfo);
    $phpinfo = preg_replace('/<(\/?)h4/', '<$1h5', $phpinfo);
    $phpinfo = preg_replace('/<(\/?)h3/', '<$1h4', $phpinfo);
    $phpinfo = preg_replace('/<(\/?)h2/', '<$1h3', $phpinfo);
    echo $phpinfo;
    ?>
</div>
</body>
</html>

