<?php
// Configurações do projeto
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'peludinhos',
        'user' => 'root',
        'pass' => '' // ajuste conforme ambiente
    ],
    'jwt' => [
        'secret' => 'troque_esta_chave_por_uma_secreta_e_longa',
        'algo' => 'HS256',
        'issuer' => 'peludinhos.ufopa'
    ]
];
