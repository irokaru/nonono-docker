<?php

namespace Tests\Feature;

use App\Models\History;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HistoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_index_ok()
    {
        $histories = factory(History::class, 5)->create();
        $response  = $this->get(route('history.index'));

        $history_array    = $histories->toArray();
        $expect_histories = [];
        foreach ($history_array as $history) {
            unset($history['created_at']);
            unset($history['updated_at']);

            array_push($expect_histories, $history);
        }

        $response->assertOk();
        $response->assertExactJson($expect_histories);
    }

}
