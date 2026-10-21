# Evidence nářadí a servisních zásahů (Laravel)

Evidence firemního nářadí: CRUD zařízení, servisní historie, filtr podle stavu,
součet nákladů, barevné stavy a export servisní historie do CSV.

## Datový model
- **equipment**: id, name, inventory_number (unique), status (`active|service|retired`), purchased_at
- **service_records**: id, equipment_id (FK, cascade), description, serviced_at, cost (decimal 10,2)
- Vztah: `Equipment hasMany ServiceRecord`

## Instalace
```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite      # výchozí DB_CONNECTION=sqlite
php artisan migrate --seed
php artisan serve
```
Aplikace běží na http://127.0.0.1:8000. Testy: `php artisan test`.

## Validace
- evidenční číslo unikátní (i při editaci),
- stav pouze z `active, service, retired`,
- datumy ve formátu `RRRR-MM-DD`, ne v budoucnosti; servis nejdříve v den nákupu,
- cena: číslo ≥ 0, max 2 desetinná místa (akceptuje i čárku).

## Stavy
Aktivní (zelená), V servisu (oranžová), Vyřazeno (šedá) – barva i text.
