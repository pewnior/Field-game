# Field Game

Aplikacja webowa do organizowania terenowych gier QR. Gracze zdobywają punkty skanując kody QR ukryte w terenie i logując się na każdym punkcie kontrolnym. Administrator zarządza grą przez dedykowany panel.

---

## Demo

Podgląd wyglądu aplikacji bez instalacji:

**[Otwórz demo](demo/index.html)**

Demo zawiera wszystkie ekrany aplikacji:
- Strona główna gracza
- Ekran punktu po zeskanowaniu kodu QR
- Potwierdzenie zaliczenia punktu
- Logowanie administratora
- Panel administratora
- Statystyki i ranking graczy
- Formularz dodawania gracza

---

## Spis treści

- [Funkcjonalności](#funkcjonalności)
- [Wymagania](#wymagania)
- [Instalacja](#instalacja)
- [Struktura bazy danych](#struktura-bazy-danych)
- [Struktura projektu](#struktura-projektu)
- [Jak działa gra](#jak-działa-gra)
- [Panel administratora](#panel-administratora)
- [Licencja](#licencja)

---

## Funkcjonalności

**Dla graczy:**
- Logowanie na punkt kontrolny przez zeskanowanie kodu QR
- Automatyczne naliczanie punktów po zaliczeniu punktu
- Ochrona przed wielokrotnym zaliczeniem tego samego punktu
- Weryfikacja przynależności gracza do właściwej gry

**Dla administratora:**
- Panel zarządzania grą chroniony logowaniem
- Dodawanie i usuwanie graczy oraz punktów kontrolnych
- Generowanie i pobieranie kodów QR dla wszystkich punktów
- Pobieranie haseł graczy w pliku ZIP
- Podgląd statystyk i rankingu graczy w czasie rzeczywistym
- Podgląd logów odwiedzin każdego gracza
- Tworzenie nowej gry (kreator 4-krokowy)

---

## Wymagania

- PHP >= 8.1 z rozszerzeniami: `mysqli`, `curl`, `zip`
- MySQL >= 8.0 lub MariaDB >= 10.5
- Serwer WWW: Apache lub Nginx
- Dostęp do internetu na serwerze (generowanie kodów QR przez API)

---

## Instalacja

### 1. Sklonuj repozytorium

```bash
git clone https://github.com/uzytkownik/field-game.git
cd field-game
```

### 2. Skonfiguruj połączenie z bazą

Skopiuj plik przykładowej konfiguracji i uzupełnij dane:

```bash
cp connect.example.php connect.php
```

Edytuj plik `connect.php`:

```php
$host        = "localhost";
$db_user     = "root";
$db_password = "twoje_haslo";
$db_name     = "field-game";
```

### 3. Utwórz bazę danych i zaimportuj schemat

```bash
mysql -u root -p -e "CREATE DATABASE \`field-game\` CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci;"
mysql -u root -p field-game < field-game.sql
```

### 4. Skonfiguruj serwer WWW

Ustaw katalog główny (`DocumentRoot`) serwera na folder projektu. Przykład dla Apache:

```apache
<VirtualHost *:80>
    ServerName field-game.local
    DocumentRoot /var/www/field-game
    <Directory /var/www/field-game>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Utwórz wymagane katalogi i nadaj uprawnienia

```bash
mkdir -p qrcodes/codes administrator/hasla
chmod 755 qrcodes/codes administrator/hasla
```

### 6. Zaloguj się do panelu admina

```
http://twojadomena.pl/administrator/admin.php
```

Domyślne dane logowania:

| Login | Hasło |
|-------|-------|
| `admin` | `admin` |

> ⚠️ Zmień hasło po pierwszym logowaniu!

---

## 🗄️ Struktura bazy danych

### Tabela `administrators`

| Kolumna    | Typ           | Opis                              |
|------------|---------------|-----------------------------------|
| `id`       | INT (PK, AI)  | Unikalny identyfikator            |
| `name`     | VARCHAR(100)  | Login administratora (unikalny)   |
| `password` | VARCHAR(255)  | Hasło zahaszowane BCrypt          |
| `game_id`  | INT           | ID przypisanej gry                |

### Tabela `players`

| Kolumna    | Typ           | Opis                              |
|------------|---------------|-----------------------------------|
| `id`       | INT (PK, AI)  | Unikalny identyfikator            |
| `name`     | VARCHAR(100)  | Nazwa gracza                      |
| `password` | VARCHAR(255)  | Hasło do logowania na punkty      |
| `points`   | INT           | Aktualna liczba punktów           |
| `game_id`  | INT           | ID gry do której należy gracz     |

### Tabela `checkpoints`

| Kolumna    | Typ           | Opis                              |
|------------|---------------|-----------------------------------|
| `id`       | INT (PK, AI)  | Unikalny identyfikator            |
| `name`     | VARCHAR(100)  | Nazwa punktu kontrolnego          |
| `password` | VARCHAR(255)  | Unikalny token w kodzie QR        |
| `points`   | INT           | Punkty przyznawane za zaliczenie  |
| `game_id`  | INT           | ID gry do której należy punkt     |

### Tabela `logs`

| Kolumna         | Typ       | Opis                              |
|-----------------|-----------|-----------------------------------|
| `id`            | INT (PK)  | Unikalny identyfikator            |
| `player_id`     | INT       | ID gracza                         |
| `checkpoint_id` | INT       | ID zaliczonego punktu             |
| `timestamp`     | DATETIME  | Data i czas zaliczenia            |
| `game_id`       | INT       | ID gry                            |

### Relacje

```
administrators (1) ──── (N) players      [przez game_id]
administrators (1) ──── (N) checkpoints  [przez game_id]
players        (1) ──── (N) logs
checkpoints    (1) ──── (N) logs
```

---

## 📁 Struktura projektu

```
field-game/
├── demo/
│   └── index.html                  # Podgląd wyglądu aplikacji
│   └── photo.jpg                  # Tło aplikacji
├── administrator/
│   ├── dodawanie/
│   │   ├── dod_checkpoint.php      # Formularz dodania punktu
│   │   ├── dod_players.php         # Formularz dodania gracza
│   │   ├── new_checkpoint.php      # Zapis nowego punktu do bazy
│   │   ├── new_player.php          # Zapis nowego gracza do bazy
│   │   └── zakonczenie.php         # Potwierdzenie operacji
│   ├── nowa_gra/
│   │   ├── nowa.php                # Ostrzeżenie przed reset gry
│   │   ├── tworzenie.php           # Krok 1 — usunięcie starej gry
│   │   ├── new_game_names_players.php     # Krok 2 — nazwy graczy
│   │   ├── input_players.php              # Zapis graczy
│   │   ├── new_game_names_checkpoints.php # Krok 3 — nazwy punktów
│   │   ├── input_checkpoints.php          # Zapis punktów
│   │   └── zakonczenie.php                # Krok 4 — potwierdzenie
│   ├── usuwanie/
│   │   ├── usu_checkpoint.php      # Formularz usunięcia punktu
│   │   ├── usu_player.php          # Formularz usunięcia gracza
│   │   ├── old_checkpoint.php      # Usunięcie punktu z bazy
│   │   ├── old_player.php          # Usunięcie gracza z bazy
│   │   └── zakonczenie.php         # Potwierdzenie usunięcia
│   ├── admin.php                   # Strona logowania admina
│   ├── zaloguj_admin.php           # Logika logowania admina
│   ├── admin_panel.php             # Główny panel admina
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
├── connect.example.php             # Szablon konfiguracji bazy danych
├── helpers.php                     # Funkcje pomocnicze
├── index.php                       # Strona główna
├── point.php                       # Strona punktu (po zeskanowaniu QR)
├── zaloguj.php                     # Logowanie gracza na punkt
├── gra.php                         # Zaliczenie punktu i dodanie punktów
├── logout.php                      # Wylogowanie
├── style.css                       # Style aplikacji
├── photo-min.jpg                   # Tło aplikacji
└── field-game.sql                  # Schemat bazy danych
```

---

##  Jak działa gra

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

---

##  Panel administratora

Po zalogowaniu admin ma dostęp do:

| Funkcja | Opis |
|---|---|
|  Statystyki | Ranking graczy posortowany po punktach |
|  Logi graczy | Historia odwiedzonych punktów każdego gracza |
|  Linki do punktów | Bezpośrednie linki do stron punktów |
|  Pobierz kody QR | Generuje i pobiera kody QR jako plik ZIP |
|  Pobierz hasła | Pobiera hasła graczy jako plik ZIP |
|  Dodaj gracza / punkt | Dodawanie pojedynczych elementów |
|  Usuń gracza / punkt | Usuwanie z automatycznym czyszczeniem logów |
|  Nowa gra | Kreator nowej gry (resetuje aktualną) |

---

## 🔒 Bezpieczeństwo

- Hasła administratorów hashowane algorytmem **BCrypt** (`password_hash` / `password_verify`)
- Wszystkie zapytania SQL chronione przez **Prepared Statements** (ochrona przed SQL Injection)
- Walidacja danych wejściowych przez `filter_input()` i `htmlspecialchars()`
- Każda chroniona strona weryfikuje sesję przed wykonaniem jakiejkolwiek operacji
- `exit()` po każdym przekierowaniu `header()`

---

## 📄 Licencja

Ten projekt jest dostępny na licencji [MIT](LICENSE).

Autor: Maciej Pewniak 2026 — field.game.app@gmail.com
