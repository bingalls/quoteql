<?php

namespace App\Controllers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

/**
 * Class HomeCtrl: Default page to test & debug. Remove in production!
 * Requires no data. See README.md
 * @package App\Controllers
 */
class HomeCtrl
{
    /**
     * Demo of ReST API. Does not require the GraphQL libs
     * @param array<string> $params
     * @return string
     * @example curl http://quoteql.test/home/get/key/value
     * @see README.md
     */
    public static function get(array $params = []): string
    {
        if (! defined('PHPUNIT_COMPOSER_INSTALL') && ! defined('__PHPUNIT_PHAR__')) {
            header('Content-Type: application/json'); // is not PHPUnit
        }
        return '{method: "' . __METHOD__ . '", data: "' . var_export($params, true) . '"}';
    }

    /**
     * Simple Hello, World GraphQL demo
     * @param array<string> $params
     * @return string
     * @example curl http://localhost/home -X POST -d '{"query":"query{echo(message:\"Hello Graphql\")}"}'
     * @see README.md
     */
    public static function post(array $params = []): string
    {
        try {
            $queryType = new ObjectType([
                'name' => 'query',
                'fields' => [
                    'echo' => [
                        'type' => Type::string(),
                        'args' => [
                            'message' => ['type' => Type::string()],
                        ],
                        'resolve' => static function ($rootValue, $args) {
                            return $rootValue['prefix'] . $args['message'];
                        }
                    ],
                ],
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
