<?php
/**
 * Login-data
 *
 * Gera dados fake de usuário para simular login
 * @author UnBMaster <unbmaster@outlook.com>
 * @license GNU General Public License (GPL)
 * @version 0.1.0
 */
namespace Test;

require __DIR__ . '/../../vendor/autoload.php';


# Objeto Redis
use Core\Redis;
$redis = new Redis();

$redis->delAll("*");

$csv = file_get_contents('login_redis.csv');
$dados = explode("\r\n", $csv);

for($i = 0; $i < sizeof($dados)-1; $i++) {
    $militarId = substr($dados[$i],0, 10);
    $info      = substr($dados[$i],11);
    $info = json_decode($info, true); // SET Redis espera Array
    $redis->set($militarId, $info);
}