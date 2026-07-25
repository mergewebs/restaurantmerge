<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_displays_the_restaurant_marketing_homepage(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('welcome')
            ->assertSee('Run every service', false)
            ->assertSee('with clarity.', false)
            ->assertSee('Request a Demo');
    }

    public function test_public_website_endpoints_are_not_available(): void
    {
        foreach ([
            '/menu',
            '/order',
            '/takeaway',
            '/reservation',
            '/robots.txt',
            '/sitemap.xml',
            '/locale',
        ] as $path) {
            $this->get($path)->assertNotFound();
        }
    }
}
