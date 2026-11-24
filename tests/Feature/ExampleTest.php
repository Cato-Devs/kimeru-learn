<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Kimeru Learn');
        $response->assertSee('Learn everyday Kimeru');
    }

    public function test_the_home_page_cards_link_to_the_numbers_and_the_quiz(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('href="'.route('numbers').'"', $html);
        $this->assertStringContainsString('href="'.route('quiz').'"', $html);
        $this->assertStringContainsString('Numbers 1–100', $html);
        $this->assertStringNotContainsString('Numbers 1–10<', $html);

        // Greetings and Foods are still to come.
        $this->assertSame(2, substr_count($html, 'Coming soon'));
    }
}
