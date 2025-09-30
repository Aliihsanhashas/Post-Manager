<?php
declare(strict_types=1);
use App\Database;
use PDO;

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

    } catch (PDOException $e) {
        echo "Hata : " . $e->getMessage();
        return [];
    }
}



?>