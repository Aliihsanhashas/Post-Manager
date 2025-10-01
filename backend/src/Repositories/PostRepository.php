<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class PostRepository
{
    public function __construct(private Database $database)
    {

    }
    public function getAllPosts(): array
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT 
                    p.id,
                    p.title,
                    p.body,
                    u.username,
                    u.name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                ORDER BY p.id";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function deletePost(int $id): bool
    {
        $pdo = $this->database->getConnection();

        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }


}


?>