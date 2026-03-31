<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadSeguroService
{
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function salvarImagem(UploadedFile $file, string $path): string
    {
        // Validação adicional de MIME para evitar spoofing
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Tipo de arquivo não permitido.');
        }

        $image = $this->manager->read($file->path());

        // O Intervention re-cria a imagem (removendo EXIF) e converte para webp
        $encoded = $image->toWebp(80);

        $filename = Str::uuid() . '.webp';
        $fullPath = $path . '/' . $filename;

        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }
}
