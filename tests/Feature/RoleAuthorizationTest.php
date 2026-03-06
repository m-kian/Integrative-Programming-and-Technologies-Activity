<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_admin_area(): void
    {
        $this->seed();

        $student = User::where('email', 'student@example.com')->firstOrFail();

        $response = $this->actingAs($student, 'web')->get('/admin-area');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_area(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $response = $this->actingAs($admin, 'web')->get('/admin-area');

        $response->assertOk();
        $response->assertSee('Admin access granted');
    }
}
