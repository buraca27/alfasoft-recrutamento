<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactValidationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    private function makeContact(array $overrides = []): Contact
    {
        return Contact::create(array_merge([
            'name' => 'Maria Silva',
            'contact' => '912345678',
            'email' => 'maria@example.com',
        ], $overrides));
    }

    public function test_index_is_public(): void
    {
        $this->makeContact();

        $this->get('/')->assertStatus(200)->assertSee('Maria Silva');
    }

    public function test_guest_cannot_access_create_page(): void
    {
        $this->get('/contacts/create')->assertRedirect('/login');
    }

    public function test_store_rejects_invalid_data(): void
    {
        $this->makeContact();

        $response = $this->actingAs($this->admin())->post('/contacts', [
            'name' => 'Ana',                 // menos de 6 caracteres
            'contact' => '12345',            // não tem 9 dígitos
            'email' => 'nao-e-email',        // email inválido
        ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
        $this->assertDatabaseCount('contacts', 1);
    }

    public function test_store_rejects_duplicate_contact_and_email(): void
    {
        $this->makeContact();

        $response = $this->actingAs($this->admin())->post('/contacts', [
            'name' => 'Joana Pereira',
            'contact' => '912345678',
            'email' => 'maria@example.com',
        ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    public function test_store_creates_valid_contact(): void
    {
        $response = $this->actingAs($this->admin())->post('/contacts', [
            'name' => 'Joana Pereira',
            'contact' => '934567890',
            'email' => 'joana@example.com',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('contacts', ['email' => 'joana@example.com']);
    }

    public function test_update_rejects_invalid_data(): void
    {
        $contact = $this->makeContact();

        $response = $this->actingAs($this->admin())->put("/contacts/{$contact->id}", [
            'name' => 'Ana',
            'contact' => 'abc',
            'email' => 'invalido',
        ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
    }

    public function test_update_allows_keeping_own_contact_and_email(): void
    {
        $contact = $this->makeContact();

        $response = $this->actingAs($this->admin())->put("/contacts/{$contact->id}", [
            'name' => 'Maria Santos',
            'contact' => '912345678',
            'email' => 'maria@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'Maria Santos']);
    }

    public function test_destroy_is_a_soft_delete(): void
    {
        $contact = $this->makeContact();

        $this->actingAs($this->admin())->delete("/contacts/{$contact->id}")
            ->assertRedirect('/');

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    public function test_show_page_has_edit_and_delete_for_admin(): void
    {
        $contact = $this->makeContact();

        $this->actingAs($this->admin())->get("/contacts/{$contact->id}")
            ->assertStatus(200)
            ->assertSee('maria@example.com')
            ->assertSee("/contacts/{$contact->id}/edit")
            ->assertSee('Eliminar');
    }
}
