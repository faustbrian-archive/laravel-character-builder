<?php

declare(strict_types=1);

namespace Faust\CharacterBuilder\Concerns;

use Faust\CharacterBuilder\Path;
use Intervention\Image\Facades\Image;

trait ManagesGradient
{
    private function createGradient(): void
    {
        $tempFile = Path::characters("{$this->identifier}/temp.png");

        Image::canvas($this->config['width'], $this->config['height'])->save($tempFile);
        $this->getRandomColors();
        $this->makeGradient($image = imagecreatefrompng($tempFile));

        unlink($tempFile);

        imagepng($image, Path::characters("{$this->identifier}/background.png"));
    }

    private function generateRandomSeededIndexes(array $colors): array
    {
        $values = [];

        while (2 !== \count($values)) {
            $number = mt_rand(0, \count($colors) - 1);

            if (! \in_array($number, $values, true)) {
                array_push($values, $number);
            }
        }

        return $values;
    }

    private function getRandomColors(): void
    {
        $indexes = $this->generateRandomSeededIndexes($this->config['colors']);

        $this->withColors([
            substr($this->config['colors'][$indexes[0]], 1),
            substr($this->config['colors'][$indexes[1]], 1),
        ]);
    }

    private function makeGradient($image, int $x = 0, int $y = 0): bool
    {
        $x1 = $this->config['width'];
        $y1 = $this->config['height'];

        if ($x > $x1 || $y > $y1) {
            return false;
        }

        $s = [
            hexdec(substr($this->config['colors'][0], 0, 2)),
            hexdec(substr($this->config['colors'][0], 2, 2)),
            hexdec(substr($this->config['colors'][0], 4, 2)),
        ];

        $e = [
            hexdec(substr($this->config['colors'][1], 0, 2)),
            hexdec(substr($this->config['colors'][1], 2, 2)),
            hexdec(substr($this->config['colors'][1], 4, 2)),
        ];

        $steps = $this->config['gradient'] === 'horizontal' ? $y1 - $y : $x1 - $x;

        for ($i = 0; $i < $steps; $i++) {
            $r = intval($s[0] - ((($s[0] - $e[0]) / $steps) * $i));
            $g = intval($s[1] - ((($s[1] - $e[1]) / $steps) * $i));
            $b = intval($s[2] - ((($s[2] - $e[2]) / $steps) * $i));

            $color = imagecolorallocate($image, $r, $g, $b);

            if ($this->config['gradient'] === 'vertical') {
                imagefilledrectangle($image, $x + $i, $y, $x1 + $i + 1, $y1, $color);
            }

            imagefilledrectangle($image, $x, $y + $i, $x1, $y + $i + 1, $color);
        }

        return true;
    }
}
