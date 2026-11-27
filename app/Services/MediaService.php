<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaService
{
    public static function disk(): string
    {
        return config('cms.media.disk');
    }

    public static function kindFor(string $ext): string
    {
        return match (true) {
            in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true) => 'image',
            in_array($ext, ['mp4', 'webm'], true) => 'video',
            default => 'document',
        };
    }

    /** Validate (by extension AND detected content type), sanitise/optimise, store and record an upload. */
    public function store(UploadedFile $file, ?int $userId = null): Media
    {
        $maxKb = config('cms.media.max_kb');
        if (! $file->isValid()) {
            throw ValidationException::withMessages(['file' => 'The upload failed (' . $file->getErrorMessage() . ').']);
        }
        if ($file->getSize() > $maxKb * 1024) {
            throw ValidationException::withMessages(['file' => "{$file->getClientOriginalName()} is larger than " . round($maxKb / 1024, 1) . ' MB.']);
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $types = config('cms.media.types');
        if (! isset($types[$ext])) {
            throw ValidationException::withMessages(['file' => "Files of type .{$ext} are not allowed."]);
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getRealPath()) ?: 'application/octet-stream';
        if (! in_array($mime, $types[$ext], true)) {
            throw ValidationException::withMessages(['file' => "{$file->getClientOriginalName()} does not look like a real .{$ext} file."]);
        }

        $contents = null;
        $width = $height = null;
        if ($ext === 'svg') {
            $contents = SvgSanitizer::clean((string) file_get_contents($file->getRealPath()));
            if ($contents === null) {
                throw ValidationException::withMessages(['file' => 'That SVG could not be verified as safe and was rejected.']);
            }
            $mime = 'image/svg+xml';
            if (preg_match('/viewBox="\s*[\d.\-]+[\s,]+[\d.\-]+[\s,]+([\d.]+)[\s,]+([\d.]+)\s*"/', $contents, $m)) {
                $width = (int) round((float) $m[1]) ?: null;
                $height = (int) round((float) $m[2]) ?: null;
            }
        } elseif (self::kindFor($ext) === 'image') {
            $info = @getimagesize($file->getRealPath());
            if (! $info) {
                throw ValidationException::withMessages(['file' => "{$file->getClientOriginalName()} is not a valid image."]);
            }
            [$width, $height] = $info;
        }

        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $filename = Str::limit($base, 60, '') . '-' . Str::lower(Str::random(6)) . '.' . $ext;
        $path = 'media/' . now()->format('Y/m') . '/' . $filename;
        $disk = Storage::disk(self::disk());

        if ($contents !== null) {
            $disk->put($path, $contents);
        } else {
            $disk->putFileAs(dirname($path), $file, $filename);
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                [$width, $height] = $this->downscale($disk->path($path), $ext, $width, $height);
            }
        }

        return Media::create([
            'disk' => self::disk(), 'path' => $path, 'filename' => $filename,
            'original_name' => Str::limit($file->getClientOriginalName(), 240, ''), 'mime_type' => $mime, 'extension' => $ext,
            'kind' => self::kindFor($ext), 'size' => $disk->size($path), 'width' => $width, 'height' => $height,
            'alt_text' => null, 'title' => Str::of($base)->replace('-', ' ')->title()->limit(120, '')->toString(), 'uploaded_by' => $userId,
        ]);
    }

    /** Scale very large images down to the configured maximum (GD). */
    private function downscale(string $file, string $ext, int $w, int $h): array
    {
        $max = config('cms.media.max_image_px');
        if (! extension_loaded('gd') || max($w, $h) <= $max) {
            return [$w, $h];
        }
        $src = match ($ext) { 'jpg', 'jpeg' => @imagecreatefromjpeg($file), 'png' => @imagecreatefrompng($file), 'webp' => @imagecreatefromwebp($file), default => false };
        if (! $src) {
            return [$w, $h];
        }
        $ratio = $max / max($w, $h);
        $nw = (int) round($w * $ratio); $nh = (int) round($h * $ratio);
        $dst = imagecreatetruecolor($nw, $nh);
        if ($ext !== 'jpg' && $ext !== 'jpeg') {
            imagealphablending($dst, false); imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        match ($ext) { 'jpg', 'jpeg' => imagejpeg($dst, $file, 88), 'png' => imagepng($dst, $file, 8), 'webp' => imagewebp($dst, $file, 88) };

        return [$nw, $nh];
    }

    /** Replace the file of an existing item (same record, new file; URL changes only if the extension does). */
    public function replace(Media $media, UploadedFile $file, ?int $userId = null): Media
    {
        $new = $this->store($file, $userId);
        $old = $media->only(['disk', 'path']);
        $media->update($new->only(['disk', 'path', 'filename', 'mime_type', 'extension', 'kind', 'size', 'width', 'height']) + ['original_name' => $new->original_name]);
        $new->delete();                                   // metadata row only; the file now belongs to $media
        Storage::disk($old['disk'])->delete($old['path']);

        return $media->refresh();
    }

    public function delete(Media $media): void
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();
    }
}
