# Setup guide (Windows)

## Step 1: Install the tools

| Tool | Why | How |
|---|---|---|
| **Git for Windows** (includes Git Bash) | version control; Git Bash runs the `cp`/`rm` commands | https://git-scm.com/download/win (accept the defaults) |
| **PHP 8.3+, Composer, Laravel installer** | Laravel 13 needs PHP 8.3 or newer | Option A (official one-liner): open **PowerShell as Administrator** and run the command below. Option B: install **Laravel Herd** (https://herd.laravel.com), a graphical installer |
| **Node.js (LTS)** | builds CSS/JS with Vite | https://nodejs.org (choose LTS) |
| **VS Code** | editor | https://code.visualstudio.com |
| *(optional)* **DB Browser for SQLite** | look inside the database file | https://sqlitebrowser.org |

Option A command (from the Laravel 13 installation docs; check https://laravel.com/docs/installation in case it has changed):

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

**Close and reopen the terminal afterwards.**

> If you already use **XAMPP**, check its PHP version with `php -v`. Older XAMPP builds ship PHP 8.2 or lower, which is too old for Laravel 13. Having two PHPs on the PATH causes confusion, so use one or the other.

## Step 2: Check everything installed

Open a **new** terminal (PowerShell or Git Bash) and run each line:

```bash
git --version          # expect: git version 2.x
php -v                 # expect: PHP 8.3.x or higher
composer -V            # expect: Composer version 2.x
laravel --version      # expect: Laravel Installer 5.x (any version is fine)
node -v                # expect: v20 or higher
npm -v                 # expect: a version number
php -m                 # scroll the list: must include pdo_sqlite, sqlite3, mbstring, openssl
```

On PowerShell you can filter the module list with: `php -m | findstr /i "sqlite mbstring openssl"`

**If a command says "not recognized":** the tool isn't on your PATH. Close all terminals and reopen. If it still fails, reinstall that tool.

## Step 3: Tell Git who you are (once per computer)

```bash
git config --global user.name "Your Name"
git config --global user.email "you@example.com"   # same email as your GitHub account
git config --global init.defaultBranch main
```

## Step 4: Create the repository and push the docs

1. On **github.com**, click **New repository** and name it `ics3104-kimeru-learn`. Leave it **empty**: no README, .gitignore or licence. Then add your teammates under *Settings → Collaborators*.
   *(If your lecturer gives you a GitHub Classroom repo instead, clone that one and copy these files into it.)*
2. Unzip this starter folder, then in **Git Bash**:

```bash
cd ~/Documents/ics3104-kimeru-learn       # wherever you unzipped it
git init
git add -A
git commit -m "Add project docs: README, charter, personas, problem statement"
git remote add origin https://github.com/<your-username>/ics3104-kimeru-learn.git
git push -u origin main
```

3. Refresh GitHub. You should see the README with the Kimeru background.

> The `context/` folder holds the brief screenshots. Delete it before pushing if you don't want it in the team repo.

## Step 5: Add Laravel (Week 4)

Follow the "Adding Laravel to this repo" section of `CONTRIBUTING.md`. Then check that it runs:

```bash
php artisan serve
```

Open http://127.0.0.1:8000. If you see the Laravel welcome page, it worked.
