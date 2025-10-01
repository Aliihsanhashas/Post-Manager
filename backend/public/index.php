<?php
declare(strict_types=1);

use App\Database;
use App\Repositories\PostRepository;
use App\Controllers\PostController;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
        ->withHeader('Access-Control-Allow-Methods', 'GET, DELETE, OPTIONS');
});

$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$database = new Database();
$postRepository = new PostRepository($database);
$postController = new PostController($postRepository);


$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('API calisiyor...');
    return $response;
});


$app->get('/api/posts', function (Request $request, Response $response) use ($postController) {
    return $postController->getAllPosts($request, $response);
});


$app->delete('/api/posts/{id}', function (Request $request, Response $response, array $args) use ($postController) {
    return $postController->deletePost($request, $response, $args);
});


$app->run();

?>