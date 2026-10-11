<?php

namespace App\Providers;

use App\Services\AiReceiptReader;
use App\Services\NullReceiptReader;
use App\Services\ReceiptReader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReceiptReader::class, function () {
            $driver = strtolower(env('RECEIPT_READER', 'null'));

            return match ($driver) {
                'ai' => new AiReceiptReader,
                default => new NullReceiptReader,
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyHelper::rupiah({$expression}); ?>";
        });
    }
}
