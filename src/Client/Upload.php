<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client;

use Appto\TelegramBot\Exceptions\InvalidInputFileException;
use Appto\TelegramBot\Type\InputFile;
use Illuminate\Support\Facades\Storage;

final readonly class Upload implements InputFile
{
    /** @param resource|string $content */
    private function __construct(private string $filename, private mixed $content) {}

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getAttachName(): string
    {
        return 'attach://'.$this->filename;
    }

    public function toResource(): mixed
    {
        if (is_resource($this->content)) {
            return $this->content;
        }

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $this->content);
        rewind($stream);

        return $stream;
    }

    public static function content(string $filename, string $content): self
    {
        return new self($filename, $content);
    }

    public static function resource(string $filename, $resource): self
    {
        if (! is_resource($resource)) {
            throw InvalidInputFileException::unreadable($filename);
        }

        return new self($filename, $resource);
    }

    public static function file(string $path, ?string $disk = null): self
    {
        if (! Storage::disk($disk)->exists($path)) {
            throw InvalidInputFileException::notFound($path);
        }

        if (! Storage::disk($disk)->size($path)) {
            throw InvalidInputFileException::empty($path);
        }

        if (! $resource = Storage::disk($disk)->readStream($path)) {
            throw InvalidInputFileException::unreadable($path);
        }

        return new self(basename($path), $resource);
    }

    public static function image(string $filename, \GdImage $image, int $quality = -1, int $filters = -1, int $speed = -1): self
    {
        $stream = fopen('php://temp', 'r+');

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        match ($extension) {
            'png' => imagepng($image, $stream, $quality, $filters),
            'jpg', 'jpeg' => imagejpeg($image, $stream, $quality),
            'gif' => imagegif($image, $stream),
            'webp' => imagewebp($image, $stream, $quality),
            'avif' => imageavif($image, $stream, $quality, $speed),
            default => throw new \InvalidArgumentException("Unsupported image extension: {$extension}"),
        };

        rewind($stream);

        return new self($filename, $stream);
    }
}
