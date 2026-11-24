# ICS 3104 — Team Project: Kimeru Learn

> **Team:** _<team name>_ · **Language:** Kimeru (Kĩmĩĩrũ) · **Members:** Brian Njehia and Cato Jaloch

Kimeru Learn is a small Laravel web application that helps beginners learn everyday Kimeru: counting from 1 to 100, adding and subtracting with Kimeru numbers, greetings and common foods. It is built step by step across the term for ICS 3104 (Internet Application Programming), and the repository holds everything the project rubric asks for.

---

## About the language

**Kimeru (Kĩmĩĩrũ)** is a Bantu language spoken by the **Ameru** people. They live on the eastern and northern slopes of Mount Kenya and in the Nyambene ranges, mostly in **Meru County** and **Tharaka-Nithi County**, Kenya.

- **Speakers:** about 2 million (2019 Kenya census, as reported on Wikipedia — verify from KNBS before quoting it in a submission).
- **Relatives:** Kimeru is closely related to its neighbours Kikuyu and Embu.
- **Dialects:** sources commonly list sub-groups such as Igembe, Tigania, Imenti, Igoji, Miutini, Mwimbi, Muthambi, Chuka and Tharaka. Lists differ between sources. **Imenti** is usually described as the most widely used dialect.
- **Writing:** Kimeru uses the Latin alphabet with the extra vowels **ĩ** and **ũ**. It doesn't use the letters f, p, q, s, v, x or z.
- **Origins (oral tradition):** the best-known origin story is the **Mbwaa** tradition. It says the Ameru were once held captive at a place called Mbwaa, usually placed on or near the Kenyan coast (some accounts say Manda Island, Lamu), before they escaped and migrated inland to Mount Kenya. This is oral history and historians read it in different ways.
- **Governance:** the **Njuri Ncheke** council of elders is the traditional apex of Meru social and judicial life.

> ⚠️ Kimeru is *not* the same language as the Kimeru spoken by the Wameru (Rwa) people of Mount Meru in Tanzania. Same name, different peoples.

## What the site does (minimum viable features)

| # | Feature | Why it's in scope | Status |
|---|---------|-------------------|--------|
| 1 | **Home / About Kimeru** (`/`, `/about`): a short intro to the language | Static Blade pages with a shared layout | ✅ Done |
| 2 | **Numbers 1–100** (`/numbers`): every number with its Kimeru words | Read data from the DB (Model → Controller → View) | ✅ Done |
| 3 | **Arithmetic quiz** (`/quiz`): 5 addition and subtraction questions written only in Kimeru, e.g. *mĩrongo ĩrĩ na iyĩrĩ + mĩrongo ĩthano na mũgwanja = ?* Pick one of 3 Kimeru answers and see straight away whether you were right. See [docs/quiz.md](docs/quiz.md). | Forms, validation, CSRF, sessions, server-side logic | ✅ Done |
| 4 | **Save quiz scores**: every finished round is saved in `quiz_attempts` | Write to the DB | ✅ Saving done. A "my scores" page would need learner accounts; only editors log in for now |
| 5 | **Word lists by category** (`/words`, `/words/greetings`, `/words/foods`) | Read data from the DB, one-to-many relationship | ✅ Done |
| 6 | **Add / edit / delete a word** (editors only) | Full CRUD, validation, CSRF, PUT/DELETE forms | ✅ Done |
| 7 | **Editor login** (`/login`): email and password, built by hand, no public sign-up | Authentication, sessions, password hashing, rate limiting | ✅ Done |

## Tech stack

- **Laravel 13** (PHP 8.3+), with Blade templates for the views
- **SQLite**, Laravel's default database (a single file, so no server to install)
- **Vite + Node/npm** for front-end assets, styled with **Tailwind CSS** (it comes set up with Laravel 13)
- **Git + GitHub**, with one branch per feature and a pull request for each

## Where things live

| Deliverable | Lives in | Due around |
|---|---|---|
| Team charter | `docs/charter.md` | Week 3 (written Week 1) |
| User personas | `docs/personas.md` | Week 3 |
| Problem statement | `docs/problem-statement.md` | Week 3 |
| The Laravel application | repo root | Week 4 onward |
| Data dictionary | `docs/data-dictionary.md` | Week 5 (then grows) |
| ERD | `docs/erd/` | Week 5 |
| Process log | `docs/process-log.md` | ongoing |
| Reflections | `docs/reflections/<username>.md` | ongoing |
| Security checklist | `docs/security-checklist.md` | Week 13 |
| User-testing findings | `docs/user-testing.md` | Week 14 |
| AI-assistance disclosure | `AI-DISCLOSURE.md` | ongoing |

## Run it locally

After cloning (or on a new computer), set it up once:

```bash
composer install            # PHP packages → vendor/
npm install                 # front-end tools → node_modules/
cp .env.example .env        # local settings
php artisan key:generate    # secret APP_KEY in .env
php artisan migrate --seed  # create the tables and add the numbers 1–100
npm run build               # build the CSS and JS
```

To add or edit words you need an editor account. There is no sign-up page; create one from the terminal (it asks for a name, email and password):

```bash
php artisan editor:create
```

Then, every time:

```bash
php artisan serve
```

Log in at http://127.0.0.1:8000/login (there's also an "Editor login" link in the footer).

Open http://127.0.0.1:8000. To start the database from scratch, run `php artisan migrate:fresh --seed` (this deletes all local data: saved quiz scores, words added through the site and editor accounts).

Run the tests with:

```bash
php artisan test
```

See `docs/setup-guide.md` for installing the tools and setting up Git.
