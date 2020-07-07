<?php
/*
 * This file is part of the https://github.com/unbmaster
 * For demonstration purposes, use it at your own risk.
 * (c) UnBMaster <unbmaster@outlook.com>
 * License GNU General Public License (GPL)
 */

namespace Core;

use Predis;

/**
 * Redis class
 *
 * Operações básicas do Redis Client <https://redis.io/>
 * Alguns métodos suportam regex patterns: https://redis.io/commands
 * @author UnBMaster <unbmaster@outlook.com>
 * @version 0.1.0
 */
class Redis
{
    private $client;

    public function __construct() {
        # Config
        $cfg = new Config();

        $this->client = new Predis\Client([
            'scheme'   => $cfg('redis.scheme'),
            'host'     => $cfg('redis.host'),
            'password' => $cfg('redis.password'),
            'port'     => $cfg('redis.port')
        ]);
    }

    /**
     * get
     *
     * Obtém o valor de uma chave
     * @return string com valor da chave ou 'nil' se a chave não existir
     */
    public function get($key)
    {
        $value = $this->client->get($key);
        return json_decode($value, true);
    }

    /**
     * set
     *
     * Define uma chave com um valor
     * @return string 'OK' se gravar com sucesso ou Null se a operação não for realizada
     */
    public function set($key, $value)
    {
        $value = json_encode($value);
        return $this->client->set($key, $value);
    }

    /**
     * del
     *
     * Remove um par chave/valor com base em uma chave
     * @return integer do número de chaves que foram removidas.
     */
    public function del($key)
    {
        return $this->client->del($key);
    }

    /**
     * delAll
     *
     * Remove todos os valores com base em uma chave ou regex pattern, Ex: $key = '*'
     * @return integer número de chaves que foram removidas
     */
    public function delAll($key)
    {
        $keys = $this->client->keys($key);
        $total = 0;
        foreach ($keys as $key) {
            $total += $this->client->del($key);
        }
        return $total;
    }
}