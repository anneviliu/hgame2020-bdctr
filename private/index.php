<?php
error_reporting(0);

$token = @$_GET['token'];
if (!isset($token)) {
    die("请带上您的队伍token访问! /?token=");
}
$api = "http://checker/?token=".$token;
$t = file_get_contents($api);
if($t !== "ok") {
    die("队伍token错误");
}

highlight_file(__FILE__);

$sandbox = '/var/www/html/sandbox/'. md5("hgame2020" . $token);;
@mkdir($sandbox);
@chdir($sandbox);

$content = $_GET['v'];
if (isset($content)) {
    $cmd = substr($content,0,5);
    system($cmd);
}else if (isset($_GET['r'])) {
    system('rm -rf ./*');
}

/*   _____ _    _ ______ _      _        _____ ______ _______   _____ _______   _
  / ____| |  | |  ____| |    | |      / ____|  ____|__   __| |_   _|__   __| | |
 | (___ | |__| | |__  | |    | |     | |  __| |__     | |      | |    | |    | |
  \___ \|  __  |  __| | |    | |     | | |_ |  __|    | |      | |    | |    | |
  ____) | |  | | |____| |____| |____ | |__| | |____   | |     _| |_   | |    |_|
 |_____/|_|  |_|______|______|______( )_____|______|  |_|    |_____|  |_|    (_)
                                    |/

*/
