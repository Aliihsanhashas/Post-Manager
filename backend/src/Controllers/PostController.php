<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController
{
    public function __construct(private PostRepository $repository)
    {
    }

    public function getAllPosts(Request $request, Response $response): Response
    {
        try {
            $posts = $this->repository->getAllPosts();

            $response->getBody()->write(json_encode($posts));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function deletePost(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];

            $deleted = $this->repository->deletePost($id);

            if ($deleted) {
                $result = ['success' => true, 'message' => "Post $id silindi"];
                $status = 200;
            } else {
                $result = ['success' => false, 'message' => "Post $id bulunamadı"];
                $status = 404;
            }

            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);

        } catch (\Exception $e) {
            $error = ['success' => false, 'error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}