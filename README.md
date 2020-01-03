# QuoteQL
Simple Demo of GraphQL in PHP.
Enjoy the native English wit of George Bernard Shaw, as well as living artists
& scientists.

_This demo is written in a hybrid ReST, not GraphQL style!_

You can rewrite this as a proper single GraphQL controller.
Or you can use this to migrate legacy ReST code.

Includes a lite subset of the 
[YAF Router](https://www.php.net/manual/en/class.yaf-router.php)
specification. Unlike YAF, it does not require PECL.

## Requirements
Tested on MacOSX. Should work on Linux. Not tested on Windows.
* PHP 7.2+ (tested on 7.3)
* [Composer](https://getcomposer.org/)
* Apache | Nginx

### Recommended options
* curl | http | wget
* [editorconfig](https://editorconfig.org/)
* graphiql | graphql playground
* jsonlint
* jq
* [valet](https://laravel.com/docs/master/valet)

## Configuration
*Valet* (nginx on Mac OSX or Linux) needs no further configuration.

Others must configure your web server to rewrite paths to /index.php.

### Apache
Check that mod_rewrite is loaded:
`httpd -M | grep rewrite`

> .htaccess
```apacheconfig
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
RewriteRule ^.*$ index.php [NC,L]
``` 

### Nginx
> nginx.conf

```yaml
server {
  listen 80 default_server;
  server_name  localhost.dev;
  root   document_root;
  index  index.php index.html;

  if (!-e $request_filename) {
    rewrite ^/(.*)  /index.php/$1 last;
  }
}
```

## Installation
Move this code folder into a (virtual) web directory
* `composer install`

## Testing & Troubleshooting
### Check environment
* `composer validate`
* `composer diagnose`
* `composer dumpautoload`

### Check Web
Replace `localhost` with your actual domain & port, throughout this ReadMe
* `open http://localhost/`      # Mac OSX, Simple ReST query
* `browse http://graphql.dev`   # Linux, example domain

### Check GraphQL
Test with your favorite client. 
* `curl http://localhost/ -X POST -d '{"query":"query{echo(message:\"Hello World\")}"}'`
* `wget http://localhost/ --post-data='{"query":"query{echo(message:\"Hello World\")}"}'`
* `http http://localhost/ query='query{echo(message:"Hello World")}'`
* POST `{echo(message:"Hello World")}` in GraphiQL or GraphQL Playground app

### QA Code & Data
zsh's simple **/* syntax. Ask, if you need help for other shells
* `jsonlint db/quotes.json`
* `./vendor/bin/phpunit`
* `./vendor/bin/phpstan analyze --level=7 *.php App/**/*.php`
* `./vendor/bin/phpcs --standard=PSR12 *.php App/**/*.php`
* `./vendor/bin/testability . -x vendor,tests && open report/index.html`

### Integration test full system
At this point, Login queries indicate that the whole system is online.
If you have optional `jq` installed, it can cleanly format the output.
Here's the syntax, whether you have curl, wget, or http installed.
Remember to replace `localhost` with your actual domain
* `curl http://localhost/quotes -X GET -d '{"query":"query{page(data:10){author year text}}"}' | jq .`
* `wget http://localhost/quotes --method=GET --body-data='{"query":"query{page(data:10){author year text}}"}'`
* `http GET http://localhost/quotes query='{page(data:10){author year text}}'`
* Change controller code from get() to post() to test GraphiQL or GraphQL Playground

### Testing Notes
phpmd is not used, as it sadly has not (2019) kept up with changes to PHP.
Example: `public const SUFFIX = 'Ctrl';` is flagged as a warning in phpmd.

## Bugs
This is designed less as production code, but rather a minimal demo of GraphQL in
PHP, albeit with modern practices, including [PSR](https://www.php-fig.org/)s.
For production, I recommend [GraphQLite](https://graphqlite.thecodingmachine.io/)
or, if you use Laravel, [Lighthouse](https://lighthouse-php.com/)

Pagination might be available via 3rd party GraphQL libraries.

I welcome contributions of lite versions of a psr-11 container & a psr-16 cache,
to rewrite this project with [GraphQLite](https://graphqlite.thecodingmachine.io/docs/other-frameworks).

I recommend you read [why graphql is not used in public
](https://medium.com/@__xuorig__/why-we-dont-see-many-public-graphql-apis-ad972bcb201e)

### Security Holes
[Overview](https://blog.doyensec.com/2018/05/17/graphql-security-overview.html)

GraphQL offers type-checking & parameter validation. Like GraphQL itself, this project
does not check if queries are authorized, such as accessing other's data.
No DoS limits, such as pagination is provided

## Foot Lights
Quotes from Shaw are validated, as many popular quotes are unproven.
I provided 2 quotes, falsely attributed to Ben Franklin, but with known authors.
The last quote is valid & courtesy of the scientist & playwright 
[Carlos Jerome](https://www.aroundtheblock.org/)
