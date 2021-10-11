<?php

declare(strict_types=1);

namespace Faust\CharacterBuilder\Concerns;

trait ManagesConfiguration
{
    private array $config = [
        'background'      => 'dominant_color',
        'flip'            => false,
        'gradient'        => 'horizontal',
        'gradient_colors' => 'horizontal',
        'greyscale'       => false,
        'height'          => 512,
        'orientation'     => 'horizontal',
        'qrcode'          => false,
        'width'           => 512,
    ];

    public function withColors(array $colors): self
    {
        $this->config['colors'] = $colors;

        return $this;
    }

    public function withSize(int $size): self
    {
        $this->config['width']  = $size;
        $this->config['height'] = $size;

        return $this;
    }

    public function withWidth(int $width): self
    {
        $this->config['width'] = $width;

        return $this;
    }

    public function withHeight(int $height): self
    {
        $this->config['height'] = $height;

        return $this;
    }

    public function withQrCode(): self
    {
        $this->config['qrcode'] = true;

        return $this;
    }

    public function withHorizontalGradient(): self
    {
        $this->config['gradient'] = 'horizontal';

        return $this;
    }

    public function withVerticalGradient(): self
    {
        $this->config['gradient'] = 'vertical';

        return $this;
    }

    public function withGradientBackground(): self
    {
        $this->config['background'] = 'gradient';

        return $this;
    }

    public function withTransparentBackground(): self
    {
        $this->config['background'] = 'transparent';

        return $this;
    }

    public function withRandomColorBackground(): self
    {
        $this->config['background'] = 'random_color';

        return $this;
    }

    public function withDominantColorBackground(): self
    {
        $this->config['background'] = 'dominant_color';

        return $this;
    }

    public function withFlip(): self
    {
        $this->config['flip'] = true;

        return $this;
    }

    public function withGreyscale(): self
    {
        $this->config['greyscale'] = true;

        return $this;
    }
}
