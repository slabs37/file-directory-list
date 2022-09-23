<?php
parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
$name = $queries['name'];
sleep(300);
$output = shell_exec("rm '$name.jpg' 2>&1");
echo "<pre>$output</pre>";


?>


