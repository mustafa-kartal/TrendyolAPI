## Kurulum

Aşağıdaki listeyi sırayla yaparak kurulumu tamamlayabilirsiniz.

1 - 

    git clone https://github.com/mustafa-kartal/TrendyolAPI.git

2 - 

    composer install

3 - 

    cp .env.example .env

4 - 

    php artisan key:generate

5 - mysql veritabanını seçin, alttaki bilgileri yorum satırından çıkarın ve kendi veritabanı bilgileriniz ile değiştirin

    DB_CONNECTION=mysql

    DB_HOST=127.0.0.1

    DB_PORT=3306

    DB_DATABASE=denemeapi

    DB_USERNAME=root

    DB_PASSWORD=


6 - bu 4 satırı env dosyanıza ekleyin ve kendi api bilgilerinizi girin.

    TRENDYOL_API_URL=https://api.trendyol.com

    TRENDYOL_API_KEY=

    TRENDYOL_SECRET_KEY=

    TRENDYOL_SUPPLIER_ID=


7 - aşağıdaki kodu çalıştırarak mysql veritabanınızı oluşturun ve dosyalarınızı migrate edin.

    php artisan migrate

8 - aşağıdaki kodu çalıştırarak ürünlerinizin kuyruğa alınarak veritabanınıza eklenmesini sağlayın.

    php artisan queue:work

9 - aşağıdaki kod ile projeyi çalıştırın.

    php artisan serve

10 - aşağıdaki urle gidin.

    http://127.0.0.1:8000

11 - sayfa açıldığında sizi bir tablo karşılayacak. Ürünleri çek buttonuna tıklayın ve ürünler arka planda çekilmeye ve veritabanına kaydedilmeye başlasın.

## Proje içindeki aksiyonlar

1 - Stok ve fiyat güncelleme

    Tabloda, ürünlerin sağ tarafında bulunan mavi (içinde kalem ikonu olan) buttona tıklayın. 
    
    Stok veya fiyat güncellemesi yaptıktan sonra update buttonuna tıklayarak bu talebinizi trendyola bildirmiş olursunuz. 
    
    Birkaç dakika sonra sağ üstte bulunan Yenile buttonuna tıklayarak hangi barkodlu ürünün stok veya fiyat güncellemesini yaptığını 
    
    kontrol ederek size olumlu veya olumsuz bir yanıt dönecektir ve bu yanıtı ekranda bir alert şeklinde görebileceksiniz. 
    
    Ayrıca yanıt olumlu ise trendyolda stok ve fiyatlar güncellendiği gibi veritabanınızda da güncellenmektedir.


Teşekkürler.
