@extends('layouts.app')

@section('title', 'About Kimeru')

@section('content')
    {{-- Page header --}}
    <section class="bg-forest text-cream">
        <div class="mx-auto max-w-5xl px-6 py-14">
            <p class="text-sm font-medium uppercase tracking-widest text-ochre">Kĩmĩĩrũ</p>
            <h1 class="mt-2 text-4xl font-semibold">About Kimeru</h1>
            <p class="mt-4 max-w-2xl text-lg text-cream/80">
                The language of the Ameru people of Mount Kenya: where it is spoken, how it is written
                and the history its speakers carry with it.
            </p>
        </div>
    </section>

    <div class="mx-auto max-w-5xl space-y-16 px-6 py-16">
        {{-- The language --}}
        <section aria-labelledby="language">
            <h2 id="language" class="text-2xl font-semibold text-forest">The language</h2>
            <div class="mt-4 grid gap-8 lg:grid-cols-[1.4fr_1fr]">
                <div class="space-y-4 text-ink/80">
                    <p>
                        Kimeru (Kĩmĩĩrũ) is a Bantu language spoken by the <strong>Ameru</strong> people. They live
                        on the eastern and northern slopes of Mount Kenya and in the Nyambene ranges, mostly in
                        <strong>Meru County</strong> and <strong>Tharaka-Nithi County</strong>, Kenya.
                    </p>
                    <p>
                        Kimeru is closely related to its neighbours <strong>Kikuyu</strong> and <strong>Embu</strong>,
                        so speakers of those languages will recognise many words, but it is a language in its own right.
                    </p>
                </div>
                <dl class="grid grid-cols-2 gap-4 self-start rounded-xl border border-forest/10 bg-white p-5 text-sm">
                    <div>
                        <dt class="text-ink/60">Family</dt>
                        <dd class="font-semibold">Bantu</dd>
                    </div>
                    <div>
                        <dt class="text-ink/60">People</dt>
                        <dd class="font-semibold">Ameru</dd>
                    </div>
                    <div>
                        <dt class="text-ink/60">Region</dt>
                        <dd class="font-semibold">Mount Kenya, Kenya</dd>
                    </div>
                    <div>
                        <dt class="text-ink/60">Counties</dt>
                        <dd class="font-semibold">Meru, Tharaka-Nithi</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Dialects --}}
        <section aria-labelledby="dialects">
            <h2 id="dialects" class="text-2xl font-semibold text-forest">Dialects</h2>
            <p class="mt-4 max-w-3xl text-ink/80">
                Kimeru is spoken by several sub-groups, each with its own way of speaking. Sources list them
                slightly differently. <strong>Imenti</strong> is usually described as the most widely used.
            </p>
            <ul class="mt-5 flex flex-wrap gap-2">
                @foreach (['Igembe', 'Tigania', 'Imenti', 'Igoji', 'Miutini', 'Mwimbi', 'Muthambi', 'Chuka', 'Tharaka'] as $dialect)
                    <li @class([
                        'rounded-full px-3 py-1 text-sm',
                        'bg-forest text-cream' => $dialect === 'Imenti',
                        'border border-forest/20 bg-white' => $dialect !== 'Imenti',
                    ])>{{ $dialect }}</li>
                @endforeach
            </ul>
        </section>

        {{-- Writing --}}
        <section aria-labelledby="writing">
            <h2 id="writing" class="text-2xl font-semibold text-forest">How it is written</h2>
            <p class="mt-4 max-w-3xl text-ink/80">
                Kimeru uses the Latin alphabet, with two extra vowels marked with a tilde.
                Several English letters are not used at all.
            </p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">Extra vowels</h3>
                    <p class="mt-3 flex gap-4 text-4xl font-semibold text-forest">
                        <span>ĩ</span><span>ũ</span>
                    </p>
                    <p class="mt-3 text-sm text-ink/70">You will see them in the name of the language itself: Kĩmĩĩrũ.</p>
                </div>
                <div class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">Letters not used</h3>
                    <ul class="mt-3 flex flex-wrap gap-2" aria-label="Letters not used in Kimeru">
                        @foreach (['f', 'p', 'q', 's', 'v', 'x', 'z'] as $letter)
                            <li class="grid h-10 w-10 place-items-center rounded-lg bg-cream text-lg text-ink/50 line-through">{{ $letter }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        {{-- History and culture --}}
        <section aria-labelledby="history">
            <h2 id="history" class="text-2xl font-semibold text-forest">History and culture</h2>
            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <article class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">The Mbwaa story</h3>
                    <p class="mt-2 text-sm text-ink/70">
                        The best-known origin story says the Ameru were once held captive at a place called
                        <strong>Mbwaa</strong>, usually placed on or near the Kenyan coast (some accounts say
                        Manda Island, Lamu). They escaped and migrated inland to Mount Kenya. This is oral
                        history, and historians read it in different ways.
                    </p>
                </article>
                <article class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">Njuri Ncheke</h3>
                    <p class="mt-2 text-sm text-ink/70">
                        The <strong>Njuri Ncheke</strong> council of elders is the traditional apex of Meru
                        social and judicial life.
                    </p>
                </article>
            </div>
            <p class="mt-6 rounded-lg border-l-4 border-ochre bg-white px-4 py-3 text-sm text-ink/80">
                Not to be confused with the Kimeru spoken by the Rwa (Wameru) people of Mount Meru in Tanzania:
                same name, different peoples.
            </p>
        </section>

        {{-- About this project --}}
        <section aria-labelledby="project" class="rounded-2xl bg-forest/5 p-8">
            <h2 id="project" class="text-2xl font-semibold text-forest">Why Kimeru Learn?</h2>
            <p class="mt-4 max-w-3xl text-ink/80">
                Many young people who grow up in towns or abroad understand some Kimeru but can't count,
                greet elders or name common foods. There are very few free, interactive resources to help.
                Kimeru Learn is a small step towards closing that gap. It was built as a student project for
                ICS 3104 Internet Application Programming at Strathmore University.
            </p>
        </section>
    </div>
@endsection
