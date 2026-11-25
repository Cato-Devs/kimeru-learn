# Project context (for the team and for future work sessions)

**Course:** ICS 3104 Internet Application Programming, Strathmore University. Group project.

**Brief (from class):** each team picks an underrepresented African language and builds one web application around it across the term, in a Git repository, following weekly stages. Languages already taken by other teams: Luo, Lingala, Yoruba, Xhosa, Bagisu, Oromo, Taita, Maasai, Shona, Kikuyu, Somali, Kuria, Hadza. Our choice is **Kimeru**, which isn't on the list. (Kikuyu is taken; Kimeru is related but a separate language.)

**Example team repo (from screenshots):** team "Gitgang", language Somali. The repo uses `docs/` for marked documents, `CONTRIBUTING.md` for the workflow, and Laravel added in Week 4 via `composer create-project` into `laravel-tmp` and then `cp -a`. Its Laravel structure shows Laravel 11+ style migrations (`0001_01_01_...`), Blade `layouts/` and `partials/`.

**Weekly stages (from the brief):**
- Week 1: team charter
- Week 3: `docs/personas.md`, `docs/problem-statement.md`, `docs/charter.md` (only these)
- Week 4: Laravel scaffold
- Week 5: data dictionary + ERD
- Ongoing: process log, per-member reflections, AI-DISCLOSURE.md
- Week 13: security checklist
- Week 14: user-testing findings

**Our app:** Kimeru Learn. Minimum features: about page, numbers 1–100, an arithmetic quiz (addition and subtraction in Kimeru), word lists by category, CRUD for words, saved quiz scores, and login. All of these are built, plus learner sign-up and a My scores page. Learners can sign up (or play as guests); editors are created from the terminal and are the only ones who can change words. Login is hand-built.
**Stack:** Laravel 13 (PHP 8.3+), Blade, SQLite, Vite, Tailwind CSS. Developed on Ubuntu (WSL).
**Open items:** team name and members; verifying the Kimeru words with a fluent speaker; confirming the course AI-use rule.

Screenshots of the brief and example repo are in this folder.
