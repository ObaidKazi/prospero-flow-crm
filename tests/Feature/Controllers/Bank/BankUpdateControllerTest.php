<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Bank;

use App\Models\Bank;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BankUpdateControllerTest extends TestCase
{
    #[Test]
    public function it_can_update_bank(): void
    {
        $bank = Bank::factory()->create();

        $response = $this->get('bank/update/'.$bank->uuid);
        $response->assertSee($bank->name);
        $response->assertSee($bank->country->flag);
        $response->assertSee($bank->phone);
        $response->assertSee($bank->email);
        $response->assertSee($bank->website);
    }
}
