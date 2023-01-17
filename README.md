# Koneksi Database

- ganti koneksi databse di file .env terutama pada port, databse, username, dan password agar terintegrasi
- buat database sesuai dengan .env atau sesuai keinginan sendiri.
- ketik perintah 'php artisan migrate' untuk memigrasi table.
- lalu ketik perintah 'php artisan db:seed' untuk seeding atau menginsert data awalan dari sistem, yaitu ReferenceSeeder.php dan SettingSeeder.php.

# Eksekusi Program
- Lalu
- menjalankan program masuk ke terminal lalu ketikkan perintah 'php artisan serve', dan jangan lupa untuk menstart database mysql.
- Setelah program berjalan masuk ke url http://localhost:8000/api/documentation#/Kledo%20Berhati%20Nyaman untuk mengakses dokumentasi API
- Ketikkan 
```sh
        php artisan l5-swagger:generate' 
```
untuk mengupdate / mengenerate dokumentasi API
- port 8000 adalah default port device ini, bisa disesuaikan dengan port masing-masing device.
- Terdapat 4 API yaitu api/setting, api/employee,api/overtimes,api/overtime-pays/calculate

# Penjelasan API
- Patch : api/setting :  untuk mengubah data setting yaitu value dan keys guna untuk mengkalkulasi metode lembur yg disesuaikan dengan table references
- POST : api/employees : untuk menambah data employee dengan request nama dan salary, yg menggunakan metode / method post yang mempunyai rule / validasi : Nama minimal 2 karakter, String, dan harus Unik. Sedangkan salaryn yaitu harus angka, minimal 2 juta dan maximal 10 juta. 
- POST : api/overtimes : untuk menambah data overtimes. Yang data tersebut terdiri dari employee_id, date, time_started, time_ended. Menggunakan metode / method POST, dan mempunyai rule / validasi : employee_id harus yg ada di table employee, dan integer. untuk date tidak boleh ada date yg sama, time_started format harus HH:mm dan tidak boleh lebih dari time_ended, time_ended : tidak boleh lebih dari time_started
- GET :api/overtime-pays/calculate : Menampilkan hasil perhitungan dari `overtimes` yang ada pada setiap `employees`. yaitu mengkalkulasi hasil request yaitu bulan dan tahun yang diinputkan, output yg dikirimkan berupa array / json yang terdiri dari data employee, overtimes, dan perhitungan upah tersebut.

