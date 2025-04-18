<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Customer;

use App\Models\Customer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerShowControllerTest extends TestCase
{
    #[Test]
    public function it_can_show_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get('/customer/show/'.$customer->id);

        $response->assertOk();
        $response->assertViewIs('lead_customer.show');
        $response->assertSee($customer->name);
        $response->assertSee($customer->business_name);
        $response->assertSee($customer->phone);
        $response->assertSee(__('Contacts'));
    }
}
