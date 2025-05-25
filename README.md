# DietApp

Aplikacja webowa do zarządzania dietą, liczenia kalorii, prowadzenia listy zakupów oraz własnej bazy produktów.

## Spis treści

- [Opis](#opis)
- [Wymagania](#wymagania)
- [Instalacja](#instalacja)
- [Struktura katalogów](#struktura-katalogów)
- [Funkcjonalności](#funkcjonalności)
- [Bezpieczeństwo](#bezpieczeństwo)
- [Kontakt](#kontakt)

---

## Opis

DietApp to aplikacja napisana w PHP z wykorzystaniem MySQL, umożliwiająca użytkownikom:
- rejestrację i logowanie,
- prowadzenie własnej bazy produktów,
- planowanie i podsumowanie posiłków z liczeniem kalorii i makroskładników,
- tworzenie listy zakupów na wybrany dzień.

## Wymagania

- PHP 7.4+
- MySQL
- Serwer WWW

## Instalacja

1. Sklonuj repozytorium lub skopiuj pliki na serwer.
2. Skonfiguruj połączenie z bazą danych w pliku [`db.php`](db.php).
3. Utwórz bazę danych i tabele (`users`, `products`, `calorie_entries`, `shopping_lists`).
4. Ustaw katalog główny serwera na folder `publc_html` lub odpowiednio skonfiguruj ścieżki.

## Struktura katalogów

```
publc_html/
│   db.php
│   index.php
├── account/
│   add_product.php
│   calendar_component.html
│   calendar_component.php
│   calorie_calculator.php
│   calorie_day.php
│   db.php
│   delete_entry.php
│   logout.php
│   product_details.php
│   products_list.php
│   shopping_list.php
│   welcome.php
└── start_page/
    db.php
    index.php
    login.php
    register.php
```

## Funkcjonalności

- **Rejestracja i logowanie**  
  [`start_page/register.php`](start_page/register.php), [`start_page/login.php`](start_page/login.php)
- **Panel główny**  
  [`account/welcome.php`](account/welcome.php)
- **Kalkulator kalorii i kalendarz**  
  [`account/calorie_calculator.php`](account/calorie_calculator.php), [`account/calorie_day.php`](account/calorie_day.php), [`account/meal_edit.php`](account/meal_edit.php)
- **Baza produktów**  
  [`account/products_list.php`](account/products_list.php), [`account/add_product.php`](account/add_product.php), [`account/product_details.php`](account/product_details.php)
- **Lista zakupów**  
  [`account/shopping_list.php`](account/shopping_list.php)
- **Wylogowanie**  
  [`account/logout.php`](account/logout.php)

## Bezpieczeństwo

- Hasła są haszowane przy rejestracji.
- Wszystkie operacje na bazie danych wykorzystują przygotowane zapytania.
- Dostęp do panelu użytkownika wymaga zalogowania.

## Kontakt

- Paweł Goleń
- Janusz Kowalski
- Dawid Płużyński

---