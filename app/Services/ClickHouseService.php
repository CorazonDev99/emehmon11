<?php

namespace App\Services;

use ClickHouseDB\Client;

class ClickHouseService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
            'port' => env('CLICKHOUSE_PORT', 8123),
            'username' => env('CLICKHOUSE_USERNAME', 'default'),
            'password' => env('CLICKHOUSE_PASSWORD', ''),
        ]);

        $this->client->database(env('CLICKHOUSE_DATABASE', 'default'));
    }


    public function select(string $query, array $bindings = []): array
    {
        $statement = $this->client->select($query, $bindings);

        return $statement->rows();
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
