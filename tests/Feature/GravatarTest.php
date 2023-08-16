<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gravatar_can_generate_default_image_if_email_not_found_first_char()
    {
        $user = User::factory()->create([
            "name" => "Furrukh",
            "email" => "afake@fakeemail.com"
        ]);

        $gravatarUrl = $user->getAvatar();
        $this->assertEquals("https://gravatar.com/avatar/" . md5($user->email) . "?s=200&d=https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-1.png", $user->getAvatar());

        $response = Http::get($user->getAvatar());
        $this->assertTrue($response->successful());
    }

    /** @test */
    public function gravatar_can_generate_default_image_if_email_not_found_last_char()
    {
        $user = User::factory()->create([
            "name" => "Furrukh",
            "email" => "zfake@fakeemail.com"
        ]);

        $gravatarUrl = $user->getAvatar();
        $this->assertEquals("https://gravatar.com/avatar/" . md5($user->email) . "?s=200&d=https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-26.png", $user->getAvatar());
        $response = Http::get($user->getAvatar());
        $this->assertTrue($response->successful());
    }

    /** @test */
    public function gravatar_can_generate_default_image_if_email_not_found_first_char_number()
    {
        $user = User::factory()->create([
            "name" => "Furrukh",
            "email" => "1fake@fakeemail.com"
        ]);

        $gravatarUrl = $user->getAvatar();
        $this->assertEquals("https://gravatar.com/avatar/" . md5($user->email) . "?s=200&d=https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-28.png", $user->getAvatar());
        $response = Http::get($user->getAvatar());
        $this->assertTrue($response->successful());
    }

    /** @test */
    public function gravatar_can_generate_default_image_if_email_not_found_first_char_9()
    {
        $user = User::factory()->create([
            "name" => "Furrukh",
            "email" => "9fake@fakeemail.com"
        ]);

        $gravatarUrl = $user->getAvatar();
        $this->assertEquals("https://gravatar.com/avatar/" . md5($user->email) . "?s=200&d=https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-36.png", $user->getAvatar());
        $response = Http::get($user->getAvatar());
        $this->assertTrue($response->successful());
    }
}
