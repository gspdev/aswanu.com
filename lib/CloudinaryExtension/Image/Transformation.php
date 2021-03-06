<?php

namespace CloudinaryExtension\Image;

use CloudinaryExtension\Image\Transformation\Dimensions;
use CloudinaryExtension\Image\Transformation\Dpr;
use CloudinaryExtension\Image\Transformation\FetchFormat;
use CloudinaryExtension\Image\Transformation\Format;
use CloudinaryExtension\Image\Transformation\Gravity;
use CloudinaryExtension\Image\Transformation\Quality;
use CloudinaryExtension\Image\Transformation\Crop;
use CloudinaryExtension\Image\Transformation\Freeform;

class Transformation
{
    private $gravity;
    private $dimensions;
    private $crop;
    private $fetchFormat;
    private $quality;
    private $dpr;
    private $flags;
    private $freeform;

    public function __construct()
    {
        $this->crop = 'pad';
        $this->flags = [];
    }

    public function withGravity(Gravity $gravity)
    {
        $this->gravity = $gravity;
        $this->crop = ((string)$gravity) ? 'crop' : 'pad';
        return $this;
    }

    public function withDimensions(Dimensions $dimensions)
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function withCrop(Crop $crop)
    {
        $this->crop = $crop;
        return $this;
    }

    public function withFetchFormat(FetchFormat $fetchFormat)
    {
        $this->fetchFormat = $fetchFormat;
        return $this;
    }

    public function withQuality(Quality $quality)
    {
        $this->quality = $quality;
        return $this;
    }

    public function withDpr(Dpr $dpr)
    {
        $this->dpr = $dpr;
        return $this;
    }

    public function withFreeform(Freeform $freeform)
    {
        $this->freeform = $freeform;
        return $this;
    }

    public function addFlags(array $flags = [])
    {
        $this->flags += $flags;
        return $this;
    }

    public static function builder()
    {
        return new Transformation();
    }

    public function build()
    {
        return [
            ['raw_transformation' => (string)$this->freeform],
            [
                'fetch_format' => (string)$this->fetchFormat,
                'quality' => (string)$this->quality,
                'crop' => (string)$this->crop,
                'gravity' => (string)$this->gravity ?: null,
                'width' => $this->dimensions ? $this->dimensions->getWidth() : null,
                'height' => $this->dimensions ? $this->dimensions->getHeight() : null,
                'dpr' => (string)$this->dpr,
                'flags' => $this->flags
            ]
        ];
    }
}
