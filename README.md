# 🎯 Field Game

Aplikacja webowa do organizowania terenowych gier QR. Gracze zdobywają punkty skanując kody QR ukryte w terenie i logując się na każdym punkcie kontrolnym. Administrator zarządza grą przez dedykowany panel.

\---

## 📋 Spis treści

* [Funkcjonalności](#funkcjonalności)
* [Zrzuty ekranu](#zrzuty-ekranu)
* [Wymagania](#wymagania)
* [Instalacja](#instalacja)
* [Struktura bazy danych](#struktura-bazy-danych)
* [Struktura projektu](#struktura-projektu)
* [Jak działa gra](#jak-działa-gra)
* [Panel administratora](#panel-administratora)
* [Licencja](#licencja)

\---

## ✨ Funkcjonalności

**Dla graczy:**

* Logowanie na punkt kontrolny przez zeskanowanie kodu QR
* Automatyczne naliczanie punktów po zaliczeniu punktu
* Ochrona przed wielokrotnym zaliczeniem tego samego punktu
* Weryfikacja przynależności gracza do właściwej gry

**Dla administratora:**

* Panel zarządzania grą chroniony logowaniem
* Dodawanie i usuwanie graczy oraz punktów kontrolnych
* Generowanie i pobieranie kodów QR dla wszystkich punktów
* Pobieranie haseł graczy w pliku ZIP
* Podgląd statystyk i rankingu graczy w czasie rzeczywistym
* Podgląd logów odwiedzin każdego gracza
* Tworzenie nowej gry (kreator 4-krokowy)

\---

## 📸 Zrzuty ekranu

> Strona główna / punkt logowania gracza

!\[Strona główna](photo-min.jpg)

\---

## ✅ Wymagania

* PHP >= 8.1 z rozszerzeniami: `mysqli`, `curl`, `zip`
* MySQL >= 8.0 lub MariaDB >= 10.5
* Serwer WWW: Apache lub Nginx
* Dostęp do internetu na serwerze (generowanie kodów QR przez API)

\---

## 🚀 Instalacja

### 1\. Sklonuj repozytorium

```bash
git clone https://github.com/uzytkownik/field-game.git
cd field-game
```

### 2\. Utwórz bazę danych i zaimportuj schemat

```bash
mysql -u root -p -e "CREATE DATABASE \\\\`field-game\\\\` CHARACTER SET utf8mb4 COLLATE utf8mb4\\\_polish\\\_ci;"
mysql -u root -p field-game < field-game.sql
```

### 3\. Skonfiguruj połączenie z bazą

Edytuj plik `connect.php`:

```php
$host      = "localhost";
$db\\\_user   = "root";
$db\\\_password = "twoje\\\_haslo";
$db\\\_name   = "field-game";
```

### 4\. Skonfiguruj serwer WWW

Ustaw katalog główny (`DocumentRoot`) serwera na folder projektu. Przykład dla Apache:

```apache
<VirtualHost \\\*:80>
    ServerName field-game.local
    DocumentRoot /var/www/field-game
    <Directory /var/www/field-game>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5\. Utwórz wymagane katalogi i nadaj uprawnienia

```bash
mkdir -p qrcodes/codes administrator/hasla
chmod 755 qrcodes/codes administrator/hasla
```

### 6\. Zahaszuj hasła administratorów (jednorazowo)

Po uruchomieniu serwera odwiedź w przeglądarce:

```
http://twojadomena.pl/migracja\\\_hasel.php
```

> ⚠️ \\\*\\\*Po wykonaniu migracji natychmiast usuń ten plik z serwera!\\\*\\\*

```bash
rm migracja\\\_hasel.php
```

### 7\. Zaloguj się do panelu admina

```
http://twojadomena.pl/administrator/admin.php
```

Domyślne dane logowania (z pliku `field-game.sql`):

|Login|Hasło|
|-|-|
|`tworca`|`test`|
|`pan`|`pan`|

> ⚠️ Zmień hasła po pierwszym logowaniu!

\---

## 🗄️ Struktura bazy danych

### Tabela `administrators`

|Kolumna|Typ|Opis|
|-|-|-|
|`id`|INT (PK, AI)|Unikalny identyfikator|
|`name`|VARCHAR(255)|Login administratora|
|`password`|VARCHAR(255)|Hasło zahaszowane BCrypt|
|`game\\\_id`|INT|ID przypisanej gry|

### Tabela `players`

|Kolumna|Typ|Opis|
|-|-|-|
|`id`|INT (PK, AI)|Unikalny identyfikator|
|`name`|VARCHAR(255)|Nazwa gracza|
|`password`|VARCHAR(255)|Hasło do logowania na punkty|
|`points`|INT|Aktualna liczba punktów|
|`game\\\_id`|INT|ID gry do której należy gracz|

### Tabela `checkpoints`

|Kolumna|Typ|Opis|
|-|-|-|
|`id`|INT (PK, AI)|Unikalny identyfikator|
|`name`|VARCHAR(255)|Nazwa punktu kontrolnego|
|`password`|VARCHAR(255)|Unikalny token w kodzie QR|
|`points`|INT|Punkty przyznawane za zaliczenie|
|`game\\\_id`|INT|ID gry do której należy punkt|

### Tabela `logs`

|Kolumna|Typ|Opis|
|-|-|-|
|`id`|INT (PK)|Unikalny identyfikator|
|`player\\\_id`|INT|ID gracza|
|`checkpoint\\\_id`|INT|ID zaliczonego punktu|
|`timestamp`|DATETIME|Data i czas zaliczenia|
|`game\\\_id`|INT|ID gry|

### Relacje

```
administrators (1) ──── (N) players      \\\[przez game\\\_id]
administrators (1) ──── (N) checkpoints  \\\[przez game\\\_id]
players        (1) ──── (N) logs
checkpoints    (1) ──── (N) logs
```

\---

## 📁 Struktura projektu

```
field-game/
├── administrator/
│   ├── dodawanie/
│   │   ├── dod\\\_checkpoint.php      # Formularz dodania punktu
│   │   ├── dod\\\_players.php         # Formularz dodania gracza
│   │   ├── new\\\_checkpoint.php      # Zapis nowego punktu do bazy
│   │   ├── new\\\_player.php          # Zapis nowego gracza do bazy
│   │   └── zakonczenie.php         # Potwierdzenie operacji
│   ├── nowa\\\_gra/
│   │   ├── nowa.php                # Ostrzeżenie przed reset gry
│   │   ├── tworzenie.php           # Krok 1 — usunięcie starej gry
│   │   ├── new\\\_game\\\_names\\\_players.php    # Krok 2 — nazwy graczy
│   │   ├── input\\\_players.php             # Zapis graczy
│   │   ├── new\\\_game\\\_names\\\_checkpoints.php # Krok 3 — nazwy punktów
│   │   ├── input\\\_checkpoints.php         # Zapis punktów
│   │   └── zakonczenie.php               # Krok 4 — potwierdzenie
│   ├── usuwanie/
│   │   ├── usu\\\_checkpoint.php      # Formularz usunięcia punktu
│   │   ├── usu\\\_player.php          # Formularz usunięcia gracza
│   │   ├── old\\\_checkpoint.php      # Usunięcie punktu z bazy
│   │   ├── old\\\_player.php          # Usunięcie gracza z bazy
│   │   └── zakonczenie.php         # Potwierdzenie usunięcia
│   ├── admin.php                   # Strona logowania admina
│   ├── zaloguj\\\_admin.php           # Logika logowania admina
│   ├── admin\\\_panel.php             # Główny panel admina
│   ├── hasla.php                   # Pobieranie haseł graczy (ZIP)
│   ├── linki.php                   # Linki do punktów
│   ├── logi.php                    # Lista graczy z logami
│   ├── log.php                     # Logi konkretnego gracza
│   └── statystyki.php              # Ranking graczy
├── qrcodes/
│   ├── generator.php               # Generowanie kodów QR
│   ├── pakowanie.php               # Pakowanie QR do ZIP
│   ├── sprzatanie.php              # Czyszczenie plików tymczasowych
│   └── zapis.php                   # Klasa QrCode (API qrserver.com)
├── connect.php                     # Konfiguracja bazy danych
├── helpers.php                     # Funkcje pomocnicze (prepared statements, autoryzacja)
├── index.php                       # Strona główna
├── point.php                       # Strona punktu (po zeskanowaniu QR)
├── zaloguj.php                     # Logowanie gracza na punkt
├── gra.php                         # Zaliczenie punktu i dodanie punktów
├── logout.php                      # Wylogowanie
├── style.css                       # Style aplikacji
├── photo-min.jpg                   # Tło aplikacji
└── field-game.sql                  # Schemat i dane startowe bazy
```

\---

## 🎮 Jak działa gra

```
1. Admin tworzy grę — dodaje graczy i punkty kontrolne
2. Admin generuje kody QR i ukrywa je w terenie
3. Admin rozdaje graczom ich hasła (plik ZIP z hasłami)
4. Gracz skanuje kod QR telefonem
         ↓
   Otwiera się strona point.php
         ↓
   Gracz wpisuje swoje hasło
         ↓
   System weryfikuje gracza i punkt
         ↓
   Punkt zostaje zaliczony, gracz otrzymuje punkty
5. Admin śledzi postępy przez panel statystyk
```

\---

## 🔧 Panel administratora

Po zalogowaniu admin ma dostęp do:

|Funkcja|Opis|
|-|-|
|📊 Statystyki|Ranking graczy posortowany po punktach|
|📋 Logi graczy|Historia odwiedzonych punktów każdego gracza|
|🔗 Linki do punktów|Bezpośrednie linki do stron punktów|
|📥 Pobierz kody QR|Generuje i pobiera kody QR jako plik ZIP|
|🔑 Pobierz hasła|Pobiera hasła graczy jako plik ZIP|
|➕ Dodaj gracza / punkt|Dodawanie pojedynczych elementów|
|➖ Usuń gracza / punkt|Usuwanie z automatycznym czyszczeniem logów|
|🆕 Nowa gra|Kreator nowej gry (resetuje aktualną)|

\---

## 🔒 Bezpieczeństwo

* Hasła administratorów hashowane algorytmem **BCrypt** (`password\\\_hash` / `password\\\_verify`)
* Wszystkie zapytania SQL chronione przez **Prepared Statements** (ochrona przed SQL Injection)
* Walidacja danych wejściowych przez `filter\\\_input()` i `htmlspecialchars()`
* Każda chroniona strona weryfikuje sesję przed wykonaniem jakiejkolwiek operacji
* `exit()` po każdym przekierowaniu `header()`

\---

## 📄 Licencja

Ten projekt jest dostępny na licencji [MIT](LICENSE).

© Maciej Pewniak 2023 — field.game.app@gmail.com



