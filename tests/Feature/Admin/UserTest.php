<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    use RefreshDatabase;

    //会員一覧ページtest//
    public function test_guest_cannot_access_user_index()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_user_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_user_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    //会員詳細ページtest//
    public function test_guest_cannot_access_user_show()
    {
        $user = User::factory()->create();
        $response = $this->get(route('admin.users.show', $user));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_user_show()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.users.show', $user));
        $response->assertRedirect(route('admin.login'));
        }

    public function test_admin_can_access_user_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $user = User::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.show', $user));
        $response->assertStatus(200);
    }

}
