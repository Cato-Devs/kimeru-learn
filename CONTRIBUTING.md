# How this team works

Keep this file in the repo root. Laravel never creates or overwrites it.

## Git workflow

- **One branch per feature**, named `feature/<short-description>` (e.g. `feature/word-list`). Never commit straight to `main`.
- **Open a pull request** and get **one teammate's review** before merging. The reviewer role rotates weekly; see `docs/charter.md`.
- **Commit often, in your own name.** Individual marks come from the commit history.
- Write commit messages that say what changed and why: `Add quiz answer validation`, not `fix` or `update`.

## Commit standards

- **Never commit secrets.** `.env` holds `APP_KEY` and database settings and is git-ignored on purpose. Commit `.env.example` instead.
- **Never commit dependencies.** `vendor/` and `node_modules/` are git-ignored. If a push is hundreds of MB, check `.gitignore`.
- **Never commit the SQLite file** (`database/database.sqlite`). Everyone rebuilds it with `php artisan migrate --seed`.
- Keep `main` working. If it breaks, fixing it comes first.

## Adding Laravel to this repo (Week 4, once only, by one person)

This repo already contains `docs/`, `README.md` and this file, so `composer create-project laravel/laravel .` fails with "directory not empty". Install Laravel into a temporary sub-folder and copy it up instead. **Use Git Bash** on Windows, because `cp -a` doesn't exist in PowerShell.

```bash
# 1. From the repo root, on a new branch
git switch -c feature/scaffold-laravel
composer create-project laravel/laravel laravel-tmp

# 2. IMPORTANT: Laravel ships its own README.md, which would overwrite ours.
rm laravel-tmp/README.md

# 3. Copy everything (including hidden files like .env.example) up to the root
cp -a laravel-tmp/. .

# 4. Delete the temp folder, check, commit
rm -rf laravel-tmp
php artisan --version
git add -A
git commit -m "Scaffold Laravel application"
git push -u origin feature/scaffold-laravel
```

Do **not** use `laravel new` or `--force` here. They can create a nested `.git` folder that hides the whole app from this repository.

## AI use

Every time AI tools help with code, text or design, add an entry to `AI-DISCLOSURE.md` in the same commit. Check the course's AI rule before relying on generated code.
