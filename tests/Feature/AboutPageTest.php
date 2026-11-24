<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_the_about_page_loads(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<title>About Kimeru · Kimeru Learn</title>', false);
        $response->assertSee('Njuri Ncheke');
        $response->assertSee('Imenti');
    }

    public function test_the_navigation_links_to_the_about_page(): void
    {
        $this->get('/')
            ->assertSee(route('about'))
            ->assertSee('About Kimeru');
    }

    public function test_the_about_link_is_marked_as_the_current_page(): void
    {
        $this->get('/about')
            ->assertSee('aria-current="page"', false);
    }
}
