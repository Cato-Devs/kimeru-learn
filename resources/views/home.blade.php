@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="bg-forest text-cream">
        <div class="mx-auto max-w-5xl px-6 py-20">
            <p class="text-sm font-medium uppercase tracking-widest text-ochre">Kĩmĩĩrũ · the language of the Ameru</p>
            <h1 class="mt-3 max-w-2xl text-4xl font-semibold leading-tight sm:text-5xl">
                Learn everyday Kimeru, one small step at a time.
            </h1>
            <p class="mt-5 max-w-2xl text-lg text-cream/80">
                Kimeru Learn helps beginners count from one to ten, practise simple addition,
                greet elders and name common foods, all in Kimeru.
            </p>
        </div>
    </section>

    {{-- What you'll learn --}}
    <section class="mx-auto max-w-5xl px-6 py-16">
        <h2 class="text-2xl font-semibold text-forest">What you'll learn</h2>
        <p class="mt-2 text-ink/70">These sections are being built and will appear here soon.</p>

        <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['title' => 'Numbers 1–10', 'text' => 'Count from one to ten in Kimeru.'],
                ['title' => 'Simple addition', 'text' => 'Add Kimeru numbers and pick the right answer.'],
                ['title' => 'Greetings', 'text' => 'Greet family and elders politely.'],
                ['title' => 'Foods', 'text' => 'Name the foods you eat every day.'],
            ] as $topic)
                <li class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">{{ $topic['title'] }}</h3>
                    <p class="mt-1 text-sm text-ink/70">{{ $topic['text'] }}</p>
                    <span class="mt-4 inline-block rounded-full bg-ochre/15 px-2.5 py-0.5 text-xs font-medium text-ochre-dark">Coming soon</span>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- About the language --}}
    <section class="border-t border-forest/10 bg-white">
        <div class="mx-auto grid max-w-5xl gap-12 px-6 py-16 lg:grid-cols-[1fr_1.4fr]">
            <div>
                <h2 class="text-2xl font-semibold text-forest">About Kimeru</h2>
                <p class="mt-4 text-ink/80">
                    Kimeru (Kĩmĩĩrũ) is a Bantu language spoken by the Ameru people on the eastern and
                    northern slopes of Mount Kenya and in the Nyambene ranges, mostly in Meru and
                    Tharaka-Nithi counties. It is closely related to its neighbours Kikuyu and Embu.
                </p>
            </div>

            <dl class="grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="font-semibold">Writing</dt>
                    <dd class="mt-1 text-sm text-ink/70">
                        Kimeru uses the Latin alphabet with two extra vowels, <strong>ĩ</strong> and <strong>ũ</strong>.
                        It doesn't use the letters f, p, q, s, v, x or z.
                    </dd>
                </div>
                <div>
                    <dt class="font-semibold">Dialects</dt>
                    <dd class="mt-1 text-sm text-ink/70">
                        Sub-groups include Igembe, Tigania, Imenti, Igoji, Miutini, Mwimbi, Muthambi, Chuka
                        and Tharaka. Imenti is usually described as the most widely used.
                    </dd>
                </div>
                <div>
                    <dt class="font-semibold">Origins</dt>
                    <dd class="mt-1 text-sm text-ink/70">
                        Oral tradition tells of <strong>Mbwaa</strong>, a place usually placed near the Kenyan coast,
                        where the Ameru were held before migrating inland to Mount Kenya.
                    </dd>
                </div>
                <div>
                    <dt class="font-semibold">Njuri Ncheke</dt>
                    <dd class="mt-1 text-sm text-ink/70">
                        The council of elders that traditionally sits at the top of Meru social and judicial life.
                    </dd>
                </div>
            </dl>
        </div>

        <div class="mx-auto max-w-5xl px-6 pb-16">
            <p class="rounded-lg border-l-4 border-ochre bg-cream px-4 py-3 text-sm text-ink/80">
                Not to be confused with the Kimeru spoken by the Rwa (Wameru) people of Mount Meru in Tanzania:
                same name, different peoples.
            </p>
        </div>
    </section>
@endsection
