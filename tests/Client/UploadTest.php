<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Client;

use Appto\TelegramBot\Client\Upload;
use Appto\TelegramBot\Exceptions\InvalidInputFileException;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

final class UploadTest extends TestCase
{
    public function test_content_wraps_a_raw_string(): void
    {
        $upload = Upload::content('note.txt', 'hello world');

        $this->assertSame('note.txt', $upload->getFilename());
        $this->assertSame('attach://note.txt', $upload->getAttachName());
        $this->assertSame('hello world', stream_get_contents($upload->toResource()));
    }

    public function test_resource_wraps_an_open_stream(): void
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, 'binary');
        rewind($stream);

        $upload = Upload::resource('blob.bin', $stream);

        $this->assertSame('blob.bin', $upload->getFilename());
        $this->assertSame('binary', stream_get_contents($upload->toResource()));
    }

    public function test_resource_rejects_a_non_resource(): void
    {
        $this->expectException(InvalidInputFileException::class);
        $this->expectExceptionMessage('Unable to open file for reading: blob.bin');

        Upload::resource('blob.bin', 'not-a-resource');
    }

    public function test_file_reads_from_the_default_disk(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('promo.jpg', 'jpeg-bytes');

        $upload = Upload::file('promo.jpg');

        $this->assertSame('promo.jpg', $upload->getFilename());
        $this->assertSame('jpeg-bytes', stream_get_contents($upload->toResource()));
    }

    public function test_file_reads_from_a_named_disk(): void
    {
        Storage::fake('s3');
        Storage::disk('s3')->put('nested/promo.jpg', 'jpeg-bytes');

        $upload = Upload::file('nested/promo.jpg', disk: 's3');

        $this->assertSame('promo.jpg', $upload->getFilename());
        $this->assertSame('jpeg-bytes', stream_get_contents($upload->toResource()));
    }

    public function test_file_rejects_a_missing_path(): void
    {
        Storage::fake('local');

        $this->expectException(InvalidInputFileException::class);
        $this->expectExceptionMessage('File not found: missing.jpg');

        Upload::file('missing.jpg');
    }

    public function test_file_rejects_an_empty_file(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('empty.jpg', '');

        $this->expectException(InvalidInputFileException::class);
        $this->expectExceptionMessage('File is empty: empty.jpg');

        Upload::file('empty.jpg');
    }

    public function test_image_encodes_a_gd_image_by_extension(): void
    {
        $gdImage = imagecreatetruecolor(2, 2);

        $upload = Upload::image('avatar.png', $gdImage);

        $this->assertSame('avatar.png', $upload->getFilename());
        $bytes = stream_get_contents($upload->toResource());
        $this->assertSame("\x89PNG", substr($bytes, 0, 4));
    }

    public function test_image_rejects_an_unsupported_extension(): void
    {
        $gdImage = imagecreatetruecolor(2, 2);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported image extension: bmp');

        Upload::image('avatar.bmp', $gdImage);
    }
}
