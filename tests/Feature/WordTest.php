<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Word;
use Database\Seeders\WordSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Runs before every test. Seed the Greetings and Foods categories and the word "Muuga" here, inside the test's
     * transaction, so every test starts with the same data.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(WordSeeder::class);
    }

    private function greetings(): Category
    {
        return Category::where('slug', 'greetings')->firstOrFail();
    }

    private function muuga(): Word
    {
        return Word::where('kimeru', 'Muuga')->firstOrFail();
    }

    private function editor(): User
    {
        return User::factory()->create();
    }

    // ---- Reading (anyone) ----

    public function test_anyone_can_see_the_categories(): void
    {
        $this->get('/words')
            ->assertOk()
            ->assertSee('Greetings')
            ->assertSee('Foods')
            ->assertSee('1 word')
            ->assertSee('0 words');
    }

    public function test_anyone_can_see_a_word_list(): void
    {
        $this->get('/words/greetings')
            ->assertOk()
            ->assertSee('Muuga')
            ->assertSee('How are you?')
            ->assertDontSee('Add a word')   // guests get no edit controls
            ->assertDontSee('Delete');
    }

    public function test_an_empty_category_says_so(): void
    {
        $this->get('/words/foods')->assertOk()->assertSee('No words here yet.');
    }

    public function test_an_unknown_category_is_404(): void
    {
        $this->get('/words/animals')->assertNotFound();
    }

    // ---- Guests can't change anything ----

    public function test_guests_are_sent_to_login_for_every_change(): void
    {
        $word = $this->muuga();

        $this->get('/words/greetings/create')->assertRedirect('/login');
        $this->post('/words/greetings', ['kimeru' => 'Ĩmwe', 'english' => 'One'])->assertRedirect('/login');
        $this->get("/words/greetings/{$word->id}/edit")->assertRedirect('/login');
        $this->put("/words/greetings/{$word->id}", ['kimeru' => 'X', 'english' => 'Y'])->assertRedirect('/login');
        $this->delete("/words/greetings/{$word->id}")->assertRedirect('/login');

        $this->assertSame(1, Word::count());
        $this->assertSame('How are you?', $word->fresh()->english);
    }

    // ---- Editors ----

    public function test_an_editor_sees_the_edit_controls(): void
    {
        $this->actingAs($this->editor())
            ->get('/words/greetings')
            ->assertSee('Add a word')
            ->assertSee('Edit')
            ->assertSee('Delete');
    }

    public function test_an_editor_can_add_a_word(): void
    {
        $this->actingAs($this->editor())
            ->post('/words/foods', ['kimeru' => 'Ngima', 'english' => 'Ugali', 'notes' => 'Test word'])
            ->assertRedirect('/words/foods')
            ->assertSessionHas('status', 'Added "Ngima".');

        $this->assertDatabaseHas('words', [
            'category_id' => Category::where('slug', 'foods')->value('id'),
            'kimeru' => 'Ngima',
            'english' => 'Ugali',
        ]);

        $this->get('/words/foods')->assertSee('Ngima')->assertSee('Ugali');
    }

    public function test_adding_requires_kimeru_and_english(): void
    {
        $this->actingAs($this->editor())
            ->post('/words/foods', ['kimeru' => '', 'english' => ''])
            ->assertSessionHasErrors(['kimeru', 'english']);

        $this->assertSame(1, Word::count());
    }

    public function test_the_same_word_cannot_be_added_twice_to_one_category(): void
    {
        $this->actingAs($this->editor())
            ->post('/words/greetings', ['kimeru' => 'Muuga', 'english' => 'Hello'])
            ->assertSessionHasErrors('kimeru');
    }

    public function test_the_same_word_can_be_in_two_categories(): void
    {
        $this->actingAs($this->editor())
            ->post('/words/foods', ['kimeru' => 'Muuga', 'english' => 'Just a test'])
            ->assertSessionHasNoErrors();
    }

    public function test_the_category_comes_from_the_url_not_the_form(): void
    {
        $foods = Category::where('slug', 'foods')->firstOrFail();

        $this->actingAs($this->editor())
            ->post('/words/greetings', ['kimeru' => 'Ĩmwe', 'english' => 'One', 'category_id' => $foods->id]);

        $this->assertSame($this->greetings()->id, Word::where('kimeru', 'Ĩmwe')->value('category_id'));
    }

    public function test_an_editor_can_edit_a_word(): void
    {
        $word = $this->muuga();

        $this->actingAs($this->editor())
            ->get("/words/greetings/{$word->id}/edit")
            ->assertOk()
            ->assertSee('value="Muuga"', false);

        $this->put("/words/greetings/{$word->id}", ['kimeru' => 'Muuga', 'english' => 'How are you? (greeting)'])
            ->assertRedirect('/words/greetings')
            ->assertSessionHasNoErrors();   // saving its own name again is allowed

        $this->assertSame('How are you? (greeting)', $word->fresh()->english);
    }

    public function test_an_editor_can_delete_a_word(): void
    {
        $word = $this->muuga();

        $this->actingAs($this->editor())
            ->delete("/words/greetings/{$word->id}")
            ->assertRedirect('/words/greetings')
            ->assertSessionHas('status', 'Deleted "Muuga".');

        $this->assertModelMissing($word);
    }

    public function test_a_word_cannot_be_reached_through_the_wrong_category(): void
    {
        $word = $this->muuga(); // a greeting

        $this->actingAs($this->editor())
            ->get("/words/foods/{$word->id}/edit")
            ->assertNotFound();

        $this->delete("/words/foods/{$word->id}")->assertNotFound();
        $this->assertModelExists($word);
    }

    public function test_html_in_a_word_is_shown_as_text_not_run(): void
    {
        $this->actingAs($this->editor())
            ->post('/words/foods', ['kimeru' => '<script>alert(1)</script>', 'english' => 'Test']);

        $this->get('/words/foods')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }
}
