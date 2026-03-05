<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\WindowsAlert;

class SystemAlertService
{
    /**
     * Stores a pending alert in the database.
     * The Windows polling script (alerta.bat) will pick it up and display it.
     *
     * @param string $message
     * @param string $targetMachine  Kept for API compatibility but no longer used directly.
     * @return bool
     */
    public function sendWindowsAlert(string $message, string $targetMachine = 'XY0202407'): bool
    {
        try {
            WindowsAlert::create([
                'message'   => $message,
                'delivered' => false,
            ]);

            Log::info("WindowsAlert queued for {$targetMachine}", ['message' => $message]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to queue Windows alert: " . $e->getMessage());
            return false;
        }
    }
}
