# ومضة | Wamda

A theatre-themed quotes library in Arabic and English, built on the QuoteIt project (Al-Qimmah Bootcamp, Tuwaiq Club - UQU).

## How to run

```
composer install
npm install
php artisan migrate:fresh --seed
composer run dev
```

Then open http://localhost:8000

If there is no `.env` file, copy `.env.example` to `.env` and run `php artisan key:generate` first.

## What it does

- Everyone can read the quotes, browse the five sections, search, and copy a quote.
- Press the name of who said a quote to open their page: who they are, a short bio, and all their quotes.
- "Tonight's show" (عرض الليلة): the speaker's quotes play on a stage, word by word, each followed by its meaning.
- Every quote has a short meaning you can open, and its own page with a link (/quotes/{id}).
- The story button turns a quote into a 1080x1920 image and copies the quote's link, so it can be added to an Instagram story as a link sticker.
- Users can sign up, log in, log out, and share, edit, and delete their own quotes (3 to 155 characters).
- Light a quote (like) and see how many people lit it.
- Quote of the day, filters (Arabic, English, most lit, my quotes, quotes I lit).
- My Shelf: add the books you read and save quotes under each book.
- Gift a quote: send a private link that opens on a stage with a curtain, and see when it was opened.
- REST API with Sanctum tokens (`/api/...`).

## Built with

Laravel 13, PHP, Blade, SQLite, HTML, CSS, JavaScript.
