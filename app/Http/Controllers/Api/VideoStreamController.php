<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Diffuse un fichier vidéo hébergé. La route est protégée par une URL signée
 * (générée dans VideoResource) et les droits sont revérifiés à chaque appel.
 * BinaryFileResponse gère les en-têtes Range, ce qui permet l'avance rapide dans le lecteur.
 */
class VideoStreamController extends Controller
{
    public function __invoke(Request $request, Video $video, User $user): BinaryFileResponse
    {
        abort_unless($video->isUploaded() && $video->file_path, 404);
        abort_unless($user->is_active && $user->canWatch($video), 403, 'Un abonnement actif est requis pour regarder cette vidéo.');

        $disk = Storage::disk(Video::VIDEO_DISK);
        abort_unless($disk->exists($video->file_path), 404);

        return response()->file($disk->path($video->file_path), [
            'Content-Type'              => $disk->mimeType($video->file_path) ?: 'video/mp4',
            'Content-Disposition'       => 'inline; filename="video"',
            // Empêche le navigateur de sauvegarder le fichier dans son cache disque
            'Cache-Control'             => 'private, no-store, no-cache, must-revalidate',
            'Pragma'                    => 'no-cache',
            'X-Content-Type-Options'    => 'nosniff',
            // Bloque l'intégration dans une iframe extérieure
            'X-Frame-Options'           => 'SAMEORIGIN',
        ]);
    }
}
