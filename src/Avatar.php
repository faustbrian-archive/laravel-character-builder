<?php

declare(strict_types=1);

namespace Faust\CharacterBuilder;

use ColorThief\ColorThief;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class Avatar
{
    use Concerns\ManagesCharacter;
    use Concerns\ManagesConfiguration;
    use Concerns\ManagesGradient;
    use Concerns\ManagesQrCode;

    private $identifier;

    private $files = [];

    public function __construct(string $identifier)
    {
        mt_srand(crc32($identifier));

        $this->identifier = $identifier;

        $this->files = [
            'avatar'     => Path::characters("{$identifier}.png"),
            'qrcode'     => Path::characters("{$identifier}/qr.png"),
            'background' => Path::characters("{$identifier}/background.png"),
            'temporary'  => Path::characters("{$identifier}/temp.png"),
        ];

        File::ensureDirectoryExists(Path::characters("{$identifier}"), 0777);
    }

    public function create(): \Intervention\Image\Image
    {
        $this->createCharacter();

        $characterImage = Image::make($this->files['avatar']);

        if ($this->config['background'] === 'gradient') {
            $this->createGradient();

            $backgroundImage = Image::make($this->files['background']);
        }

        if ($this->config['background'] === 'transparent') {
            $backgroundImage = Image::canvas($this->config['width'], $this->config['height'])
                ->save(Path::characters("{$this->identifier}/background.png"));
        }

        if ($this->config['background'] === 'random_color') {
            $backgroundImage = Image::canvas($this->config['width'], $this->config['height'], Arr::random($this->config['colors']))
                ->save(Path::characters("{$this->identifier}/background.png"));
        }

        if ($this->config['background'] === 'dominant_color') {
            $backgroundColor = ColorThief::getColor($this->files['avatar']);
            $backgroundImage = Image::canvas($this->config['width'], $this->config['height'], $backgroundColor)
                ->save(Path::characters("{$this->identifier}/background.png"));
        }

        $backgroundImage->insert($characterImage, 'top-left', 0, 0);

        if ($this->config['flip']) {
            $backgroundImage->flip();
        }

        if ($this->config['qrcode']) {
            $this->createQrCode();

            $qrImage = Image::make($this->files['qrcode']);
            $backgroundImage->insert($qrImage, 'bottom-right', 4, 4);

            unlink($this->files['qrcode']);
        }

        if ($this->config['greyscale']) {
            $backgroundImage->greyscale();
        }

        unlink($this->files['background']);

        return $backgroundImage->save($this->files['avatar']);
    }
}
