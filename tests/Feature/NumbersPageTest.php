<?php

namespace Tests\Feature;

use App\Models\Number;
use Database\Seeders\NumberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumbersPageTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = NumberSeeder::class;

    public function test_the_seeder_stores_1_to_100(): void
    {
        $this->assertSame(100, Number::count());
        $this->assertSame('mĩrongo ĩrĩ na iyĩrĩ', Number::where('value', 22)->value('kimeru'));
        $this->assertSame('ĩgana', Number::where('value', 100)->value('kimeru'));
    }

    public function test_the_numbers_page_lists_1_to_100(): void
    {
        $this->get('/numbers')
            ->assertOk()
            ->assertSee('ĩmwe')
            ->assertSee('ĩkũmi na iyĩrĩ')
            ->assertSee('mĩrongo ĩthano na mũgwanja')
            ->assertSee('ĩgana');
    }
}
