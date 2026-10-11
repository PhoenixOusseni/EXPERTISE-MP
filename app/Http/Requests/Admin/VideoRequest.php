<?php

namespace App\Http\Requests\Admin;

use App\Enums\VideoSourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $video = $this->route('video');
        $creating = $this->isMethod('post') && ! $video;
        $required = $creating ? 'required' : 'sometimes';

        $sourceType = $this->input('source_type', $video?->source_type?->value ?? VideoSourceType::Upload->value);
        $isUpload = $sourceType === VideoSourceType::Upload->value;

        // Un fichier est obligatoire à la création, ou si on bascule vers "upload" sans fichier existant.
        $fileRequired = $isUpload && ($creating || ! $video?->file_path);
        $urlRequired = ! $isUpload && ($creating || ! $video?->external_url);

        return [
            'title' => [$required, 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('videos', 'slug')->ignore($video)],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'source_type' => ['sometimes', Rule::enum(VideoSourceType::class)],
            // Tous types de vidéos : mp4, webm, mkv, avi, mov, mpeg, flv, wmv, 3gp, ogg...
            'file' => [
                $fileRequired ? 'required' : 'nullable',
                $isUpload ? 'file' : 'prohibited',
                'mimetypes:video/*',
                'max:'.config('formation.max_video_size_kb'),
            ],
            'external_url' => [$urlRequired ? 'required' : 'nullable', 'url', 'max:2048'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'level' => ['nullable', 'string', Rule::in(['debutant', 'intermediaire', 'avance'])],
            'is_free' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Les formulaires multipart envoient les booléens sous forme de chaînes.
        foreach (['is_free', 'is_published'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => filter_var($this->input($field), FILTER_VALIDATE_BOOLEAN)]);
            }
        }
    }
}
