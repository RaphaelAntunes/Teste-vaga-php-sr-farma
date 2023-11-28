<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\EventoModel;


class EventoTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function test_user_login()
    {
        $response = $this->post('http://localhost:8000/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
    
        $response->assertStatus(302);
    
        $response->assertCookie('laravel_session');
    
        $token = $response->cookie('laravel_session');
    
        return $token;
    }

    public function test_create_event()
    {
        $token = $this->test_user_login();

        $response = $this->json('POST', 'http://localhost:8000/criar-evento', [
            'title' => 'Exemplo de Título',
            'description' => 'Exemplo de Descrição',
            'start' => '2023-09-07 03:00:00',
            'end' => '2023-09-08 03:00:00',
            'usr_responsavel' => 'john@example.com',
        ], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(201);
        
        $event = EventoModel::where([
            'title' => 'Exemplo de Título',
            'description' => 'Exemplo de Descrição',
            'start' => '2023-09-07 03:00:00',
            'end' => '2023-09-08 03:00:00',
            'usr_responsavel' => 'john@example.com',
        ])->first();

        $this->assertNotNull($event);
    }

    public function test_update_event()
    {
        $token = $this->test_user_login();

        $event = EventoModel::create([
            'title' => 'Evento para Edição',
            'description' => 'Descrição do Evento',
            'start' => '2023-09-20 03:00:00',
            'end' => '2023-09-27 03:00:00',
            'usr_responsavel' => 'john@example.com',
        ]);

        $data = [
            'title' => 'Evento Editado',
            'description' => 'Descrição Editada',
            'start' => '2023-09-20 03:00:00',
            'end' => '2023-09-27 03:00:00',
            'usr_responsavel' => 'john@example.com',
        ];

        $response = $this->put("http://localhost:8000/editar-evento/{$event->title}", $data, ['Authorization' => "Bearer $token"]);

        $response->assertStatus(200);
    }

    public function test_delete_event()
    {
        $token = $this->test_user_login();

        $event = EventoModel::create([
            'title' => 'Evento para Exclusão',
            'description' => 'Descrição do Evento',
            'start' => '2023-09-21 03:00:00',
            'end' => '2023-09-27 03:00:00',
            'usr_responsavel' => 'john@example.com',
        ]);

        $response = $this->delete("http://localhost:8000/excluir-evento/{$event->title}", [], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(200);

    }
}
