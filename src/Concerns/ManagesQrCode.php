<?php

declare(strict_types=1);

namespace Faust\CharacterBuilder\Concerns;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Faust\CharacterBuilder\Path;

trait ManagesQrCode
{
    private function createQrCode(): void
    {
        (new PngWriter())->write(
            QrCode::create($this->identifier)
                ->setSize(90)
                ->setMargin(4)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->setForegroundColor(new Color(0, 0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255))
        )->saveToFile(Path::characters("{$this->identifier}/qr.png"));
    }
}
