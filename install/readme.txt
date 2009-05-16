1. Wymagania:

Apache 2
PHP 5.x
MySQL 5.x

-----

Zalecane jest korzystanie z Linuksa i zainstalowanie tych pakietów z repozytorium.

Np. dla Fedory:

yum install httpd php php-gd php-mysql php-mbstring mysql mysql-server

Dla Debiana/Ubuntu/Minta:

sudo apt-get install apache2 libapache2-mod-php5 php5-mysql mysql-server phpmyadmin

Użytkownikom Windows zalecamy instalację pakietu WAMP.

-----

2. Licencja
Informacje licencyjne znajdują się w pliku install/authors.txt

3. Szybka instalacja:

1. Skopiuj na serwer pliki z katalogu orodlin_engine_v_1.0/
2. Załóż bazę danych o wybranej nazwie zachowując:
	- System kodowania znaków dla MySQL: UTF-8 Unicode (utf8)
	- Metodę porównywania znaków utf8-polish_ci
3. Do świeżo utworzonej bazy danych zaimportuj plik ze strukturą wszystkich tabel oraz podstawowych insertów z katalogu:
	orodlin_engine_v_1.0/install/db/OroDatabase.sql
4. W pliku orodlin_engine_v_1.0/install/config.php konfigurujemy dostęp do DB i plików, nazwę gry, miast, e-maile administracyjne. Przenosimy plik do katalogu orodlin_engine_v_1.0/includes/
5. Możesz zalogowac się na konto:
	login: admin@xxx.pl
	hasło: 123456