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

# Configuração
define('TOTAL_USUARIOS', 20000);

# Objeto Facker
$faker = \Faker\Factory::create('pt_BR');

# Gera de posto e graduação
$rank = function () {
    $i = rand (10 , 20);
    $a = [
        10 => '3º Sgt',
        11 => '2º Sgt',
        12 => '1º Sgt',
        13 => 'ST',
        14 => 'Asp Of',
        15 => '2º Ten',
        16 => '1º Ten',
        17 => 'Cap',
        18 => 'Maj',
        19 => 'TC',
        20 => 'Cel'
    ];
    return $a[$i];
};

# Gera nome de guerra
$name = function () use ($faker) {
    $i = rand (1 , 2);
    $a = [
        1 => preg_replace('/\w*\.\s/', '', $faker->firstName()),
        2 => preg_replace('/\w*\.\s/', '', $faker->lastName())
    ];
    return $a[$i];
};

# Gera email
$email = function ($name) use ($faker) {
    $a = [
        preg_replace('/\W/', '', $name),
        preg_replace('/\W/', '', $faker->firstName())
    ];
    shuffle($a);
    return strtolower(implode('.', $a)) . '@' . $faker->freeEmailDomain;
};

# Cria usuário ADMIN
$data = [
    'rank'      => '2º Ten',
    'name'      => 'Fulano',
    'email'     => 'fulano@mail.com',
    'password'  => '12345678',
    'createdAt' => date('Y-m-d H:i:s'),
    'roles'     => ['ROLE_USER', 'ROLE_ADMIN']
];

# Cria CSV para servir de input no jMeter
$csv_login  = "militarId,senha\r\n";
$csv_login .= "1234567890,12345678\r\n";

# Cria CSV para Redis
$preload_redis = "1234567890 " . json_encode($data) . "\r\n";
#$preload_redis = "SET 1234567890 " . json_encode($data) . "\r\n";
#$preload_redis = "*3\r\n$5\r\nLPUSH\r\n$4\r\n1234567890\r\n$3\r\n" . json_encode($data) . "\r\n";

# Cria N usuários no Redis
foreach(range(1, TOTAL_USUARIOS-1) as $index) {
    $militarId = $faker->numberBetween(1000000000,9999999999);   # 10 dígitos
    $password  = $faker->numberBetween(10000000,99999999); # 08 dígitos
    $guerra  = $name();
    $data = [
        'rank'      => $rank(),
        'name'      => $guerra,
        'email'     => $email($guerra),
        'password'  => $password,
        'createdAt' => date('Y-m-d H:i:s'),
        'roles'     => ['ROLE_USER']
    ];

    $csv_login .= $militarId . "," . $password ."\r\n";
    $preload_redis .= "{$militarId} " . json_encode($data) . "\r\n";
    #$preload_redis .= "lpush {$militarId} " . json_encode($data) . "\r\n";
    #$preload_redis .= "*3\r\n$5\r\nLPUSH\r\n$4\r\n{$militarId}\r\n$3\r\n" . json_encode($data) . "\r\n";
}

# Grava CSV
file_put_contents('login.csv', $csv_login);
file_put_contents('login_redis.csv', $preload_redis);