<?php

require 'exec.inc.php';
require 'ip-check.inc.php';

// ip check
clientMatchesGitHubIp() or die ('You aren\'t GitHub, you filthy liar.');

// require '../passwd/gh-key.inc.php';

// $_GET['ghk'] == $GH_KEY or die('You shall not pass');

$repo = '/home/www/skad';

list($return_code, $stdout, $stderr) = pipe_exec('cd ' . $repo . '; git pull; git submodule update --init --recursive; git submodule foreach --recursive "git checkout master; git pull";');

echo "Return Code: $return_code <br/>";
echo "stdout<br/>";
echo "<pre> $stdout</pre>";
echo "stderr<br/>";
echo "<pre>$stderr</pre>";

?>
