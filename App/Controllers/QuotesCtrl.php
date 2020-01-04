<?php

namespace App\Controllers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\Cfg;
use App\Models\Data;

class QuotesCtrl implements Cfg
{
    /**
     * @param array<string> $params
     * @return string
     * Install `jq` to easily format the output
     * @example
     * curl http://localhost/quotes -X GET -d '{"query":"query{page(data:10){author year text}}"}' | jq .
     * curl http://localhost/quotes -X GET -d '{"query":"query{random{author year text}}"}' | jq .
     */
    public static function post(array $params = []): string
    {
        $msgType = new ObjectType([
            'name' => 'Quote',
            'description' => 'Chat message',
            'fields' => [
                'author' => Type::string(),
                'year' => Type::int(),
                'text' => Type::string(),
            ]
        ]);

        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'page' => [
                        'type' => Type::listOf(Type::listOf($msgType)),
                        'args' => [
                            'data' => ['type' => Type::int()],
                        ],
                        'resolve' => static function ($unused, $args) {
                            /** @noinspection UselessUnsetInspection */
                            unset($unused);
                            $data = new Data(Cfg::DATA);
                            $quotes = $data->fetchPage($args['data']);
                            return ['quotes' => $quotes];
                        },
                    ],
                    'random' => [
                        'type' => Type::listOf(Type::listOf($msgType)),
                        'resolve' => static function () {
                            $data = new Data(Cfg::DATA);
                            $quotes = $data->fetchRandom();
                            return ['quotes' => $quotes];
                        },
                    ],
                ]
            ]);

            $schema = new Schema([
                'query' => $queryType
            ]);
            $rawInput = file_get_contents('php://input') ?: '';
            $input = json_decode($rawInput, true);
            $query = $input['query'];

            $rootValue = ['prefix' => ''];
            $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $params);
            $output = $result->toArray();
        } catch (\Exception $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage()
                ]
            ];
        }

        if (! defined('PHPUNIT_COMPOSER_INSTALL') && ! defined('__PHPUNIT_PHAR__')) {
            header('Content-Type: application/json'); // is not PHPUnit
        }
        return json_encode($output) ?: '{"error_code": 401, "error_msg": "Unauthorized"}';
    }
}
