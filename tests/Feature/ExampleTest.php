<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_the_admin_dashboard(): void
    {
        $this->get('/')
            ->assertRedirect('/admin/dashboard');
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
