# Database plan (draft for Week 5 data dictionary + ERD)

> The finished Week 5 versions are the [data dictionary](data-dictionary.md) and the [ERD](erd/README.md). This plan is kept for the design notes and the seed data.

Database: **SQLite** (Laravel default; one file at `database/database.sqlite`).

## Tables

### `users` (comes with Laravel) ✅ built
id, name, email, password, timestamps

### `numbers` ✅ built
Migration: `database/migrations/2026_11_24_204910_create_numbers_table.php` · Model: `App\Models\Number`

| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| value | unsigned tiny integer, unique | 1–100 |
| kimeru | string | the Kimeru words, e.g. `mĩrongo ĩrĩ na iyĩrĩ` for 22 |
| timestamps | | |

Filled by `NumberSeeder`, which builds the words with `App\Support\KimeruNumber`. The rest of the app (the `/numbers` page and the quiz) always reads the words from this table.

### `quiz_attempts` ✅ built
Migration: `database/migrations/2026_11_24_204911_create_quiz_attempts_table.php` · Model: `App\Models\QuizAttempt`

| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| user_id | FK → users.id, nullable | null for now because everyone plays as a guest; set to null if the user is deleted |
| quiz_type | string | `arithmetic` for now (later maybe `vocabulary`) |
| score | unsigned small integer | questions answered correctly |
| total | unsigned small integer | questions in the round (5) |
| timestamps | | `created_at` = when the round was finished |

One row is saved per finished quiz round.

### `categories` ✅ built
| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| name | string | e.g. Greetings, Foods |
| slug | string, unique | e.g. `greetings` (used in URLs) |
| description | string, nullable | one line shown under the name (added while building) |
| timestamps | | |

### `words` ✅ built
| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| category_id | FK → categories.id | |
| kimeru | string | the Kimeru word or phrase |
| english | string | meaning |
| notes | text, nullable | dialect, pronunciation, usage |
| timestamps | | |

Numbers are no longer stored here: they have their own `numbers` table, so the old `number_value` column is gone.

## Relationships
- One **user** has many **quiz_attempts** (optional: guests have none).
- One **category** has many **words**. A Kimeru word is unique within its category.
- `numbers` stands alone. The quiz keeps its questions in the session, not in the database (see [quiz.md](quiz.md)).

## Seed data

### Numbers 1–100 (seeded by `NumberSeeder`)

| Number | Kimeru |
|---|---|
| 1–9 | ĩmwe, iyĩrĩ, ithatũ, inya, ithano, ithanthatũ, mũgwanja, inyanya, kenda |
| 10 | ĩkũmi |
| 11–19 | ĩkũmi na + unit, e.g. 12 = *ĩkũmi na iyĩrĩ* |
| 20, 30 … 90 | mĩrongo ĩrĩ, mĩrongo ĩthatũ, mĩrongo ĩna, mĩrongo ĩthano, mĩrongo ĩthatathũ, mĩrongo mũgwanja, mĩrongo ĩnaana, mĩrongo kenda |
| 21–99 | tens + na + unit, e.g. 22 = *mĩrongo ĩrĩ na iyĩrĩ*, 57 = *mĩrongo ĩthano na mũgwanja* |
| 100 | ĩgana |

Confirmed with a fluent Kimeru speaker: ☐ (tick once checked; any correction goes in `app/Support/KimeruNumber.php`, then run `php artisan db:seed --class=NumberSeeder`)

### Greetings (still to be checked)
Seeded by `WordSeeder`: *Muuga*. Found in a published source (Wikipedia "Meru language" sample phrases):
- *Muuga*: "How are you"
- *Mpa rũũji*: "Give me water"

### Foods
To be collected by the team from speakers (aim for 8–10 items) and added through the site by an editor.
