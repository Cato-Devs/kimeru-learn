# Database plan (draft for Week 5 data dictionary + ERD)

Database: **SQLite** (Laravel default; one file at `database/database.sqlite`).

## Tables

### `users` (comes with Laravel)
id, name, email, password, timestamps

### `categories`
| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| name | string | e.g. Numbers, Greetings, Foods |
| slug | string, unique | e.g. `numbers` (used in URLs) |
| timestamps | | |

### `words`
| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| category_id | FK → categories.id | |
| kimeru | string | the Kimeru word or phrase |
| english | string | meaning |
| number_value | integer, nullable | only for the Numbers category (1–10); powers the arithmetic quiz |
| notes | text, nullable | dialect, pronunciation, usage |
| timestamps | | |

### `quiz_attempts`
| Column | Type | Notes |
|---|---|---|
| id | integer PK | |
| user_id | FK → users.id, nullable | nullable so guests can play |
| quiz_type | string | `arithmetic` or `vocabulary` |
| score | integer | |
| total | integer | |
| timestamps | | |

## Relationships
- One **category** has many **words**.
- One **user** has many **quiz_attempts**.

## Seed data: ⚠️ DRAFT, must be checked by a fluent Kimeru speaker

The number words below are a starting draft only. Spelling and the ĩ/ũ marks may be wrong or may differ by dialect (Imenti, Tigania, Igembe…). **Do not seed until verified.**

| Value | Draft Kimeru | Verified? |
|---|---|---|
| 1 | ĩmwe | ☐ |
| 2 | ijĩrĩ | ☐ |
| 3 | ithatũ | ☐ |
| 4 | inya | ☐ |
| 5 | ithano | ☐ |
| 6 | ithanthatũ | ☐ |
| 7 | mũgwanja | ☐ |
| 8 | inyanya | ☐ |
| 9 | kenda | ☐ |
| 10 | ikũmi | ☐ |

Greetings found in a published source (Wikipedia "Meru language" sample phrases), still to be checked:
- *Muuga*: "How are you"
- *Mpa rũũji*: "Give me water"

Foods: to be collected by the team from speakers (aim for 8–10 items).
