<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class CriptografiaService
{
    /**
     * @param string|null $data
     * @return string|null
     */
    public function encryptPII(?string $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        return Crypt::encryptString($data);
    }

    /**
     * @param string|null $payload
     * @return string|null
     */
    public function decryptPII(?string $payload): ?string
    {
        if (empty($payload)) {
            return null;
        }

        try {
            return Crypt::decryptString($payload);
        } catch (\Exception $e) {
            return $payload; // Retornar fallback
        }
    }
}
