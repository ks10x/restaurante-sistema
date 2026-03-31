<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorService
{
    private $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::random(10) . '-' . Str::random(10);
        }
        return $codes;
    }

    public function getQRCodeUrl(string $companyName, string $companyEmail, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );
    }

    public function verifyKey(string $secret, string $otp): bool
    {
        return $this->google2fa->verifyKey($secret, $otp);
    }

    public function isRecoveryCodeValid(User $user, string $code): bool
    {
        if (!$user->two_factor_recovery_codes) return false;
        
        $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        if (($key = array_search($code, $codes)) !== false) {
            unset($codes[$key]);
            $user->update(['two_factor_recovery_codes' => encrypt(json_encode(array_values($codes)))]);
            return true;
        }
        return false;
    }
}
