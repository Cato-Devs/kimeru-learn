# Data dictionary

**Database:** SQLite, a single file at `database/database.sqlite` (not committed to Git; rebuilt with `php artisan migrate --seed`).
**Source of truth:** the migrations in `database/migrations/`. This document describes the schema they create, checked against the actual database on 2026-11-25 (updated when the word lists were added).
**Diagram:** see the [ERD](erd/README.md).

## How to read this document

| Column heading | Meaning |
|---|---|
| **Laravel type** | The method used in the migration, e.g. `string()`, `foreignId()` |
| **SQLite type** | What SQLite actually stores. SQLite has few types: `INTEGER`, `TEXT`, `varchar` (text; Laravel's default limit is 255 characters but SQLite does not enforce it) and `datetime` (stored as text, e.g. `2026-11-25 10:30:00`) |
| **Null?** | `No` = a value is required; `Yes` = the column may be empty (`NULL`) |
| **Key** | `PK` primary key · `FK` foreign key · `UQ` unique · `IX` indexed for faster lookups |

The tables fall into three groups:

1. **Application tables:** designed for Kimeru Learn (`numbers`, `quiz_attempts`, `categories`, `words`)
2. **Laravel tables the app relies on:** `users`, `sessions`, `password_reset_tokens`
3. **Framework support tables:** created by Laravel and not used directly by our code (`cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`)

---

## 1. Application tables

### `numbers`

The numbers 1 to 100 and their Kimeru words. Read by the `/numbers` page and by the quiz, which never hard-codes Kimeru words.

- **Model:** `App\Models\Number`
- **Migration:** `2026_11_24_204910_create_numbers_table.php`
- **Filled by:** `NumberSeeder`, which builds each word with `App\Support\KimeruNumber::toWords()`. It has exactly 100 rows.

| Column | Laravel type | SQLite type | Null? | Key | Description | Example |
|---|---|---|---|---|---|---|
| `id` | `id()` | INTEGER | No | PK | Auto-increment row number | `22` |
| `value` | `unsignedTinyInteger()` | INTEGER | No | UQ | The number itself, 1–100. Unique, so each number appears once | `22` |
| `kimeru` | `string()` | varchar(255) | No | | The number written in Kimeru | `mĩrongo ĩrĩ na iyĩrĩ` |
| `created_at` | `timestamps()` | datetime | Yes | | When the row was first seeded | `2026-11-24 20:51:03` |
| `updated_at` | `timestamps()` | datetime | Yes | | When the row last changed (e.g. a spelling fix was re-seeded) | `2026-11-24 20:51:03` |

**Rules:**
- `value` is from 1 to 100. The seeder only creates 1–100, and `KimeruNumber::toWords()` refuses anything else. The database itself does not check the range.
- The Kimeru words follow a fixed pattern, e.g. 11–19 are `ĩkũmi na` + unit, and 21–99 are tens + `na` + unit. See [database-plan.md](database-plan.md#numbers-1100-seeded-by-numberseeder).

### `quiz_attempts`

One row for every finished quiz round, saved when the learner presses "See my score".

- **Model:** `App\Models\QuizAttempt`
- **Migration:** `2026_11_24_204911_create_quiz_attempts_table.php`
- **Written by:** `QuizController@next`

| Column | Laravel type | SQLite type | Null? | Key | Description | Example |
|---|---|---|---|---|---|---|
| `id` | `id()` | INTEGER | No | PK | Auto-increment row number | `1` |
| `user_id` | `foreignId()->nullable()->constrained()->nullOnDelete()` | INTEGER | Yes | FK → `users.id` | Who played. Always `NULL` for now, because everyone plays as a guest. If a user is deleted, their attempts are kept and this becomes `NULL` | `NULL` |
| `quiz_type` | `string()` | varchar(255) | No | | Which quiz was played. Currently always `arithmetic` (later maybe `vocabulary`) | `arithmetic` |
| `score` | `unsignedSmallInteger()` | INTEGER | No | | Questions answered correctly | `3` |
| `total` | `unsignedSmallInteger()` | INTEGER | No | | Questions in the round. Currently always 5 | `5` |
| `created_at` | `timestamps()` | datetime | Yes | | When the round was finished | `2026-11-25 09:14:52` |
| `updated_at` | `timestamps()` | datetime | Yes | | Same as `created_at`; rows are never edited | `2026-11-25 09:14:52` |

**Rules:**
- `0 ≤ score ≤ total`. The app guarantees this; the database does not check it.
- Exactly one row per finished round. The controller marks the round as finished before saving, so pressing the button twice cannot save twice.

### `categories`

Groups of words, e.g. Greetings and Foods. Each one has its own page at `/words/{slug}`.

- **Model:** `App\Models\Category` (has many `words`)
- **Migration:** `2026_11_24_213812_create_categories_table.php`
- **Filled by:** `WordSeeder` (Greetings and Foods). There is no page for adding categories; new ones are added in the seeder.

| Column | Laravel type | SQLite type | Null? | Key | Description | Example |
|---|---|---|---|---|---|---|
| `id` | `id()` | INTEGER | No | PK | Auto-increment row number | `1` |
| `name` | `string()` | varchar(255) | No | | Name shown on the page | `Greetings` |
| `slug` | `string()->unique()` | varchar(255) | No | UQ | Short name used in the URL; unique, so each URL points to one category | `greetings` |
| `description` | `string()->nullable()` | varchar(255) | Yes | | One-line description shown under the name | `Greet family and elders politely.` |
| `created_at`, `updated_at` | `timestamps()` | datetime | Yes | | When the row was created and last changed | |

### `words`

Kimeru words and phrases, each with its English meaning. Anyone can read them; logged-in editors can add, edit and delete them.

- **Model:** `App\Models\Word` (belongs to one `category`)
- **Migration:** `2026_11_24_213813_create_words_table.php`
- **Written by:** `WordController` (store, update, destroy) and `WordSeeder` (the first greeting, *Muuga*)

| Column | Laravel type | SQLite type | Null? | Key | Description | Example |
|---|---|---|---|---|---|---|
| `id` | `id()` | INTEGER | No | PK | Auto-increment row number | `1` |
| `category_id` | `foreignId()->constrained()->restrictOnDelete()` | INTEGER | No | FK → `categories.id` | The category the word is in. Set from the URL, never from the form. A category that still has words can't be deleted | `1` |
| `kimeru` | `string()` | varchar(255) | No | UQ with `category_id` | The Kimeru word or phrase. Unique within its category (the same word may appear in two categories) | `Muuga` |
| `english` | `string()` | varchar(255) | No | | English meaning | `How are you?` |
| `notes` | `text()->nullable()` | TEXT | Yes | | Dialect, pronunciation or source | `Source: Wikipedia sample phrases…` |
| `created_at`, `updated_at` | `timestamps()` | datetime | Yes | | When the word was added and last edited | |

**Rules** (checked by `WordController` before saving):
- `kimeru`: required, at most 100 characters, unique within the category (the database also enforces this with a unique index on `category_id` + `kimeru`).
- `english`: required, at most 150 characters.
- `notes`: optional, at most 500 characters.

---

## 2. Laravel tables the app relies on

### `users`

Created by Laravel. **Every user is an editor**: someone who can add, edit and delete words. There is no sign-up page; editors are created with `php artisan editor:create`, and no users are seeded. Learners never log in, so `quiz_attempts.user_id` stays `NULL`.

| Column | Laravel type | SQLite type | Null? | Key | Description |
|---|---|---|---|---|---|
| `id` | `id()` | INTEGER | No | PK | Auto-increment row number |
| `name` | `string()` | varchar(255) | No | | Display name |
| `email` | `string()->unique()` | varchar(255) | No | UQ | Login email; no two users can share one |
| `email_verified_at` | `timestamp()->nullable()` | datetime | Yes | | When the email was confirmed; `NULL` if not yet |
| `password` | `string()` | varchar(255) | No | | **Hashed** password (bcrypt), never the plain text |
| `remember_token` | `rememberToken()` | varchar(100) | Yes | | Token for the "Keep me logged in" checkbox on the login page |
| `created_at`, `updated_at` | `timestamps()` | datetime | Yes | | When the account was created and last changed |

### `sessions`

Laravel stores each visitor's session here, because `.env` has `SESSION_DRIVER=database`. **This is where an in-progress quiz round lives.** The questions and correct answers are saved inside `payload`, on the server, and never sent to the browser. The browser only holds a cookie containing the session `id`.

| Column | Laravel type | SQLite type | Null? | Key | Description |
|---|---|---|---|---|---|
| `id` | `string()->primary()` | varchar | No | PK | Random session ID, also stored in the visitor's cookie |
| `user_id` | `foreignId()->nullable()->index()` | INTEGER | Yes | IX | The logged-in editor, if any (`NULL` for learners). Indexed but not a database-enforced foreign key |
| `ip_address` | `string(45)` | varchar(45) | Yes | | Visitor's IP address (45 characters fits IPv6) |
| `user_agent` | `text()` | TEXT | Yes | | Visitor's browser description |
| `payload` | `longText()` | TEXT | No | | All session data, serialised and base64-encoded, **including the quiz round** (see below) |
| `last_activity` | `integer()->index()` | INTEGER | No | IX | Unix time of the last request. Sessions idle for more than `SESSION_LIFETIME` (120 minutes) expire |

### `password_reset_tokens`

Created by Laravel for "forgot password" emails. Not used yet.

| Column | Laravel type | SQLite type | Null? | Key | Description |
|---|---|---|---|---|---|
| `email` | `string()->primary()` | varchar | No | PK | The account asking for a reset |
| `token` | `string()` | varchar | No | | Hashed reset token |
| `created_at` | `timestamp()->nullable()` | datetime | Yes | | When the reset was requested (tokens expire) |

---

## 3. Data kept in the session (not a table)

The quiz keeps the current round under the session key `quiz` (inside `sessions.payload`). It is listed here because it is real application data, with rules just like a table.

| Key | PHP type | Description | Example |
|---|---|---|---|
| `quiz.questions` | array of 5 | The questions in this round | |
| `quiz.questions.*.a` | int, 1–100 | First number | `22` |
| `quiz.questions.*.b` | int, 1–100 | Second number | `57` |
| `quiz.questions.*.operator` | string | `+` or `−` (the minus sign U+2212). For `−`, `a > b` | `+` |
| `quiz.questions.*.answer` | int, 1–100 | The correct result. **Never sent to the browser before checking** | `79` |
| `quiz.questions.*.choices` | array of 3 ints | The answer plus two near misses (±1, ±2 or ±10), all different, 1–100, in random order | `[78, 79, 69]` |
| `quiz.questions.*.chosen` | int or `null` | The learner's choice. `null` until they press Check; it cannot be changed after | `78` |
| `quiz.current` | int, 0–4 | Index of the question on screen | `0` |
| `quiz.finished` | bool | `true` once the score has been saved to `quiz_attempts` | `false` |

How the flow uses these keys is explained in [quiz.md](quiz.md).

---

## 4. Framework support tables

Laravel creates these, and they are not used directly by Kimeru Learn's code.

| Table | Purpose | Main columns |
|---|---|---|
| `migrations` | Records which migrations have run, so `php artisan migrate` only runs new ones | `id`, `migration` (file name), `batch` |
| `cache` | Laravel's cache store (`CACHE_STORE=database`) | `key` (PK), `value`, `expiration` |
| `cache_locks` | Locks that stop two processes doing the same cached work at once | `key` (PK), `owner`, `expiration` |
| `jobs` | Background jobs waiting to run (`QUEUE_CONNECTION=database`) | `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at` |
| `job_batches` | Groups of jobs run together | `id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, … |
| `failed_jobs` | Jobs that crashed, kept for retrying | `id`, `uuid` (UQ), `connection`, `queue`, `payload`, `exception`, `failed_at` |
