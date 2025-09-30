<?php
declare(strict_types=1);

use App\Database;

//json place order linklerinde verilen datalari ilgili tablolara yükleyeceğiz.
//adress,companies,geos,users, posts adinda 5 tablo oluşturdum.
//Bunun sebebi users verilerinde iç içe geçmiş veriler vardi.

require __DIR__ . '/../vendor/autoload.php';

//Verilen linklerden veriyi dizi halinde çekmemize yarayacak.
function veriCek(string $url): array
{
    try {
        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception("Api'dan veri çekilemedi");
        }

        $data = json_decode($response, true);

        return $data;

    } catch (Exception $e) {
        echo "Hata : " . $e->getMessage();
        return [];
    }
}

function insertGeo(PDO $pdo, array $geo): int
{
    $sql = "INSERT INTO geos (lat, lng) VALUES (:lat, :lng)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':lat' => $geo['lat'],
        ':lng' => $geo['lng'],
    ]);

    return (int) $pdo->lastInsertId();
}

function insertAddress(PDO $pdo, array $address, int $geoId): int
{
    $sql = 'INSERT INTO addresses(street, suite, city, zipcode, geo_id) 
            VALUES (:street, :suite, :city, :zipcode, :geo_id)';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':street' => $address['street'],
        ':suite' => $address['suite'],
        ':city' => $address['city'],
        ':zipcode' => $address['zipcode'],
        ':geo_id' => $geoId,
    ]);
    return (int) $pdo->lastInsertId();
}

function insertCompany(PDO $pdo, array $company): int
{
    $sql = 'INSERT INTO companies (name, catchPhrase, bs)
            VALUES (:name, :catchPhrase, :bs)';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':name' => $company['name'],
        ':catchPhrase' => $company['catchPhrase'],
        ':bs' => $company['bs']
    ]);
    return (int) $pdo->lastInsertId();
}

function insertUser(PDO $pdo, array $user, int $addressId, int $companyId): void
{
    $sql = 'INSERT INTO users (id, name, username, email, phone, website, address_id, company_id)
            VALUES (:id, :name, :username, :email, :phone, :website, :address_id, :company_id)';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $user['id'],
        ':name' => $user['name'],
        ':username' => $user['username'],
        ':email' => $user['email'],
        ':phone' => $user['phone'],
        ':website' => $user['website'],
        ':address_id' => $addressId,
        ':company_id' => $companyId
    ]);
}

function insertPost(PDO $pdo, array $post): void
{
    $sql = 'INSERT INTO posts (id, user_id, title, body)
            VALUES (:id, :user_id, :title, :body)';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $post['id'],
        ':user_id' => $post['userId'],
        ':title' => $post['title'],
        ':body' => $post['body']
    ]);
}

//Verileri eklemek için gerekli fonksiyonları yazdık şimdi db bağlantısını yapıp verileri mysql db ye aktaracağım.

try {
    $database = new Database();
    $pdo = $database->getConnection();
    echo 'Veritabanına bağlandı.';

    $pdo->beginTransaction();

    $users = veriCek("https://jsonplaceholder.typicode.com/users");

    if ($users === null) {
        throw new Exception("Users verileri çekilemedi.");
    }

    $userCount = 0;

    foreach ($users as $user) {
        $geoId = null;

        if (isset($user['address']['geo'])) {
            $geoId = insertGeo($pdo, $user['address']['geo']);
        }

        $addressId = null;
        if (isset($user['address']) && $geoId !== null) {
            $addressId = insertAddress($pdo, $user['address'], $geoId);
        }

        $companyId = null;
        if (isset($user['company'])) {
            $companyId = insertCompany($pdo, $user['company']);
        }

        if ($addressId !== null && $companyId !== null) {
            insertUser($pdo, $user, $addressId, $companyId);
            $userCount++;
        }
    }

    $posts = veriCek("https://jsonplaceholder.typicode.com/posts");
    if ($posts === null) {
        throw new Exception("Posts verileri çekilemedi.");
    }

    $postsCount = 0;

    foreach ($posts as $post) {
        insertPost($pdo, $post);
        $postsCount++;
    }

    $pdo->commit();

    echo "Veriler başari ile yüklendi.";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        echo "İşlem geri alindi";
    }

    echo "$e->getMessage()";
    echo " $e->getLine()";
    exit(1);
}


?>