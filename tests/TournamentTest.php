<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;

class TournamentTest extends BaseTestCase
{
    public function test_tournament_index(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_index.json')));

        $response = $this->challonge->getTournaments();

        $this->assertCount(2, $response);
    }

    public function test_tournament_create(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_create.json')));

        $response = $this->challonge->createTournament();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_fetch(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $response = $this->challonge->fetchTournament('9044420');

        $this->assertEquals('challongephp test', $response->name);
    }
}