PROJE TANIMI:

PHP (Slim Framework) ve React kullanarak geliştirilmiş bir post yönetim sistemi. 
Kullanıcıların postlarını görüntüleme ve silme işlemlerini gerçekleştirebilen 
bir post yönetme uygulaması.

1- ARAŞTIRMA VE PLANLAMA:

- PHP Slim Framework document: https://www.slimframework.com/docs/v4/

- React : https://mui.com/material-ui/getting-started/learn/

- İlk adımda JSONPlaceholder'daki verileri inceledim. Post'lar için tek tablo
userlar için ise içi içe geçmiş veriler olduğu için toplamda 4 tablodan oluşturma kararı aldım.

2- VERİTABANI TASARIMI: 

- XAMPP kullanarak MySQL ayağa kaldırdım. Sql scriptleri ile tabloları oluşturdum.

3- VERİ YÜKLEME:

- JSONPlaceholder'dan veri çekmek için script yazdım(loadData.php).
- Her tablo için ayrı fonksiyon.
- Transaction kullandım.

4- BACKEND:

- Repository pattern kullandım.
- Controller katmanımda RESTful endpointleri oluşturdum.
- React için CORS ayarını yaptım.

5- FRONTEND:

- Vite ile React projesi oluşturdum.
- Material-UI component'leri kullandım.
- PostTable component'i ile dinamik tablo oluşturdum.

TEKNOLOJİLER:
- PHP
- Slim Framwork
- React
- MySQL
- XAMPP

UYGULAMA VİDEO:
https://drive.google.com/drive/folders/1sIJHyEpQJl54wNTWcooi3Ete3KO3AUop?usp=sharing
