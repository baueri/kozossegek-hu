# Követelmények
- php7.4
- mysql
- composer
# Projekt inicializálása
1. Másold le a .env.example tartalmát a .env fájlba
2. A projekt klónozása után terminal-ban lépj be a könyvtárba és futtasd: `composer install`

# Adatbázis:
1. Hozz létre a gépeden (phpmyadmin-ban) egy új adatbázist
2. a .env fájlban állítsd be a DB_USER-t a phpmyadmin user-re (azt hiszem alapból root), a DB_PASSWORD-öt annak jelszavára, és ha az adatbázis neve eltér a "kozossegek"-től, akkor a DB_DATABASE-t is állítsd be. 
3. Futtasd: `composer migrate`

# Weboldal futtatása lokálisan

    php -S localhost:8000 -t public/
Így a gépeden ezen az url-en lesz elérhető a projekt: http://localhost:8000
