# Documentarian's Process Log

| Date | What happened | Decisions | Next step |
|------|---------------|-----------|-----------|
| 2026-11-24 | Chose language and app concept; drafted README, personas, problem statement, charter, setup guide | Language: **Kimeru**. App: **Kimeru Learn** (numbers + arithmetic quiz, greetings, foods). Stack: Laravel 13 + SQLite + Blade | Install tools, run the checks, create the GitHub repo, push docs |
| 2026-11-24 | Created the GitHub repo and pushed the docs. Scaffolded Laravel 13 and built the home page (PR #2), then the About Kimeru page | One branch and pull request per feature. Styling with Tailwind CSS, which comes with Laravel 13, instead of plain CSS | Build the numbers and the quiz |
| 2026-11-24 | Built the arithmetic quiz (PR #3): `numbers` table with 1–100 in Kimeru, `/numbers` page, 5-question addition/subtraction quiz in Kimeru with an answer reveal, scores saved to `quiz_attempts`, unit and feature tests | Numbers go up to 100 instead of 10. Questions and answers stay in the session so the answer never reaches the browser. Guests only for now | Update the docs; check the number words with a fluent speaker |
| 2026-11-25 | Updated the README, problem statement, database plan and project context to match the quiz; added `docs/quiz.md` | | Greetings and foods word lists; data dictionary and ERD (Week 5) |
