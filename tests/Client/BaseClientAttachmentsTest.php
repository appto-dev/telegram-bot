<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Client;

use Appto\TelegramBot\Client\TelegramClient;
use Appto\TelegramBot\Client\Upload;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Type\InputMediaPhoto;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

final class BaseClientAttachmentsTest extends TestCase
{
    public function test_a_top_level_upload_is_sent_as_a_multipart_file(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'result' => true])]);

        $client = new TelegramClient(UpdateFactory::bot(), []);
        $client->call('sendPhoto', ['chat_id' => 111, 'photo' => Upload::content('cat.jpg', 'jpeg-bytes')]);

        Http::assertSent(function (Request $request): bool {
            $parts = $this->partsByName($request);

            return $request->isMultipart()
                && $parts['photo']['contents'] === 'attach://cat.jpg'
                && $parts['cat.jpg']['filename'] === 'cat.jpg'
                && (string) $parts['cat.jpg']['contents'] === 'jpeg-bytes';
        });
    }

    /**
     * sendMediaGroup nests uploads inside InputMedia objects — the files themselves still have to
     * reach the top level of the multipart body, with only "attach://" references left in "media".
     */
    public function test_nested_uploads_are_lifted_to_the_top_level_of_the_multipart_body(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'result' => true])]);

        $client = new TelegramClient(UpdateFactory::bot(), []);
        $client->call('sendMediaGroup', [
            'chat_id' => 111,
            'media' => [
                $this->photo(Upload::content('first.jpg', 'first-bytes')),
                $this->photo(Upload::content('second.jpg', 'second-bytes')),
            ],
        ]);

        Http::assertSent(function (Request $request): bool {
            $parts = $this->partsByName($request);
            $media = json_decode($parts['media']['contents'], true);

            return $request->isMultipart()
                && $media[0]['media'] === 'attach://first.jpg'
                && $media[1]['media'] === 'attach://second.jpg'
                && (string) $parts['first.jpg']['contents'] === 'first-bytes'
                && (string) $parts['second.jpg']['contents'] === 'second-bytes';
        });
    }

    private function photo(Upload $upload): InputMediaPhoto
    {
        return new InputMediaPhoto(
            type: 'photo',
            media: $upload,
            caption: null,
            parse_mode: null,
            caption_entities: null,
            show_caption_above_media: null,
            has_spoiler: null,
        );
    }

    /**
     * @return array<string, array{name: string, contents: mixed, filename?: string}>
     */
    private function partsByName(Request $request): array
    {
        return collect($request->data())->keyBy('name')->all();
    }
}
