<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root URL redirects to login.
     */
    public function test_the_application_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
