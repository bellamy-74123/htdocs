<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Models\Medicine;
use Models\User;

final class ModelConstructorTest extends TestCase
{
    public function testMedicineConstructorMatchesRealModel(): void
    {
        $medicine = new Medicine(
            7,
            'Panadol',
            'Paracetamol',
            'Analgesic',
            15.5,
            20,
            3,
            '2027-12-31'
        );

        $this->assertSame(7, $medicine->id);
        $this->assertSame('Panadol', $medicine->name);
        $this->assertSame('Paracetamol', $medicine->generic_name);
        $this->assertSame('Analgesic', $medicine->category);
        $this->assertSame(15.5, $medicine->price);
        $this->assertSame(20, $medicine->stock_quantity);
        $this->assertSame(3, $medicine->supplier_id);
        $this->assertSame('2027-12-31', $medicine->expiry_date);
    }

    public function testMedicineDefaultsMatchRealModel(): void
    {
        $medicine = new Medicine();

        $this->assertNull($medicine->id);
        $this->assertSame('', $medicine->name);
        $this->assertSame('', $medicine->generic_name);
        $this->assertSame('', $medicine->category);
        $this->assertSame(0.0, $medicine->price);
        $this->assertSame(0, $medicine->stock_quantity);
        $this->assertNull($medicine->supplier_id);
        $this->assertNull($medicine->expiry_date);
    }

    public function testUserConstructorMatchesRealModel(): void
    {
        $user = new User(5, 'ali', 'ali@example.com', 'manager');

        $this->assertSame(5, $user->id);
        $this->assertSame('ali', $user->username);
        $this->assertSame('ali@example.com', $user->email);
        $this->assertSame('manager', $user->role);
    }

    public function testUserDefaultRoleIsCustomer(): void
    {
        $user = new User();
        $this->assertSame('customer', $user->role);
    }
}
