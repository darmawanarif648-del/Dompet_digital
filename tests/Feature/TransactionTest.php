<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Dashboard dapat diakses oleh user yang login dan menampilkan saldo.
     */
    public function test_user_can_view_dashboard_with_balances(): void
    {
        $user = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'in',
            'amount' => 1000000,
            'category' => 'Pemasukan',
            'transacted_at' => now(),
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'out',
            'amount' => 250000,
            'category' => 'Makan & Minum',
            'transacted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Rp750.000'); // Saldo utama (1.000.000 - 250.000)
        $response->assertSee('Makan & Minum');
    }

    /**
     * User dapat mencatat transaksi manual.
     */
    public function test_user_can_create_manual_transaction(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/transactions', [
            'type' => 'out',
            'description' => 'Makan siang soto',
            'amount' => 30000,
            'category' => 'Makan & Minum',
            'source' => 'manual',
            'transacted_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => 30000,
            'category' => 'Makan & Minum',
            'source' => 'manual',
        ]);
    }

    /**
     * User dapat mencatat transaksi via voice (JSON endpoint).
     */
    public function test_user_can_create_voice_transaction_via_json(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/transactions', [
            'type' => 'out',
            'description' => 'Bensin pertamax 50 ribu',
            'amount' => 50000,
            'category' => 'Transportasi',
            'source' => 'voice',
            'transacted_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Transaksi berhasil dicatat!',
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => 50000,
            'source' => 'voice',
        ]);
    }

    /**
     * User dapat mengunggah struk belanja dan tersimpan di storage/public/receipts/{user_id}.
     */
    public function test_user_can_upload_receipt_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $receipt = UploadedFile::fake()->image('struk_belanja.jpg', 600, 800);

        $response = $this->actingAs($user)->post('/transactions', [
            'type' => 'out',
            'description' => 'Belanja Supermarket',
            'amount' => 150000,
            'category' => 'Belanja',
            'source' => 'camera',
            'transacted_at' => now()->format('Y-m-d H:i:s'),
            'receipt' => $receipt,
        ]);

        $response->assertRedirect('/dashboard');

        $tx = Transaction::where('user_id', $user->id)->first();
        $this->assertNotNull($tx->receipt_path);
        Storage::disk('public')->assertExists($tx->receipt_path);
    }

    /**
     * User dapat scan foto struk via endpoint scan-receipt.
     */
    public function test_user_can_scan_receipt_endpoint(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $receipt = UploadedFile::fake()->image('receipt_sample.png', 800, 1000);

        $response = $this->actingAs($user)->post('/transactions/scan-receipt', [
            'receipt' => $receipt,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'receipt_path',
            'receipt_url',
            'data',
        ]);
    }

    /**
     * User dapat memperbarui transaksinya sendiri.
     */
    public function test_user_can_update_own_transaction(): void
    {
        $user = User::factory()->create();
        $tx = Transaction::factory()->create([
            'user_id' => $user->id,
            'amount' => 20000,
            'description' => 'Es Teh Manis',
            'category' => 'Makan & Minum',
            'type' => 'out',
        ]);

        $response = $this->actingAs($user)->put("/transactions/{$tx->id}", [
            'type' => 'out',
            'description' => 'Es Teh Manis Jumbo',
            'amount' => 25000,
            'category' => 'Makan & Minum',
            'transacted_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('transactions', [
            'id' => $tx->id,
            'description' => 'Es Teh Manis Jumbo',
            'amount' => 25000,
        ]);
    }

    /**
     * Isolasi data: User A TIDAK BISA memperbarui transaksi User B (Policy 403 Forbidden).
     */
    public function test_user_cannot_update_other_user_transaction_policy(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $txB = Transaction::factory()->create([
            'user_id' => $userB->id,
            'amount' => 50000,
        ]);

        $response = $this->actingAs($userA)->put("/transactions/{$txB->id}", [
            'type' => 'out',
            'description' => 'Mencoba meretas',
            'amount' => 1000,
            'category' => 'Makan & Minum',
            'transacted_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(403);
    }

    /**
     * User dapat menghapus transaksinya sendiri.
     */
    public function test_user_can_delete_own_transaction(): void
    {
        $user = User::factory()->create();
        $tx = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/transactions/{$tx->id}");

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('transactions', ['id' => $tx->id]);
    }

    /**
     * Isolasi data: User A TIDAK BISA menghapus transaksi User B (Policy 403 Forbidden).
     */
    public function test_user_cannot_delete_other_user_transaction_policy(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $txB = Transaction::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->delete("/transactions/{$txB->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('transactions', ['id' => $txB->id]);
    }

    /**
     * Perhitungan saldo total dan bulanan akurat.
     */
    public function test_main_balance_calculation_is_accurate(): void
    {
        $user = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'in',
            'amount' => 5000000,
            'transacted_at' => now(),
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'out',
            'amount' => 1250000,
            'transacted_at' => now(),
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'out',
            'amount' => 750000,
            'transacted_at' => now(),
        ]);

        $in = $user->transactions()->where('type', 'in')->sum('amount');
        $out = $user->transactions()->where('type', 'out')->sum('amount');
        $balance = $in - $out;

        $this->assertEquals(3000000, $balance);
    }

    /**
     * User dapat mengatur saldo awal saat pertama login.
     */
    public function test_initial_balance_creates_income_transaction(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/transactions/initial-balance', [
            'initial_balance' => 2500000,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'in',
            'description' => 'Saldo Awal',
            'amount' => 2500000,
            'category' => 'Pemasukan',
        ]);
    }

    /**
     * User dapat mengunduh file ekspor CSV transaksi.
     */
    public function test_export_csv_transactions(): void
    {
        $user = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $user->id,
            'description' => 'Makan Bakso',
            'amount' => 20000,
        ]);

        $response = $this->actingAs($user)->get('/transactions/export-csv');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Makan Bakso', $response->streamedContent());
    }

    /**
     * User dapat mengatur anggaran bulanan per kategori.
     */
    public function test_user_can_set_monthly_budget(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/budgets', [
            'category' => 'Makan & Minum',
            'monthly_limit' => 1500000,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category' => 'Makan & Minum',
            'monthly_limit' => 1500000,
        ]);
    }
}
