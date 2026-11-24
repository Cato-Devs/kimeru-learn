# ICS 3104 — Team Project: Kimeru Learn

> **Team:** _<team name>_ · **Language:** Kimeru (Kĩmĩĩrũ) · **Members:** Brian Njehia and Cato Jaloch

Kimeru Learn is a small Laravel web application that helps beginners learn everyday Kimeru: counting from 1 to 10, simple addition with Kimeru numbers, greetings and common foods. It is built step by step across the term for ICS 3104 (Internet Application Programming), and the repository holds everything the project rubric asks for.

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

| # | Feature | Why it's in scope |
|---|---------|-------------------|
| 1 | **Home / About Kimeru**: a short intro to the language | Static Blade page with a shared layout |
| 2 | **Word lists by category** (Numbers, Greetings, Foods) | Read data from the DB (Model → Controller → View) |
| 3 | **Numbers & arithmetic quiz**: e.g. *ijĩrĩ + ithatũ = ?* and you pick the Kimeru answer | Form handling, validation, simple logic |
| 4 | **Add / edit a word** (team members only) | Full CRUD, validation, CSRF |
| 5 | **Save quiz scores** | Write to the DB and show a simple "my scores" page |
| 6 | *(stretch)* **Login** via a Laravel starter kit | Authentication, needed for the security checklist |

## Tech stack

- **Laravel 13** (PHP 8.3+), with Blade templates for the views
- **SQLite**, Laravel's default database (a single file, so no server to install)
- **Vite + Node/npm** for front-end assets, plus plain CSS
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

## Run it locally (once the Laravel app exists, Week 4+)

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Then open http://127.0.0.1:8000.

See `docs/setup-guide.md` for installing the tools and setting up Git.
