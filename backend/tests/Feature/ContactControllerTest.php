<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Illuminate\Mail\Mailable;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;
 
    /**
     * Test contact creation with email sending failure.
     *
     * @return void
     */
    public function testContactCreationWithEmailSendingFailure()
    {
        Mail::fake();

        Mail::shouldReceive('raw')->andThrow(new \Exception('Email sending failed'));

        $response = $this->postJson('/api/contact', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]);

        $response->assertStatus(201)
                 ->assertExactJson(['Contact saved!']);

        $this->assertDatabaseHas('contacts', [
            'email' => 'johndoe@example.com',
        ]);
    }
}