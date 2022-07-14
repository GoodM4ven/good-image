<?php

namespace GoodM4ven\GoodImage;

use Exception;

trait HasBlurredImages
{
    protected function thumbnailConversionName()
    {
        return config('good-image.conversion-name');
    }

    public function getGoodThumbnailImageUrl(string $collection): string
    {
        $this->initialChecks($collection);

        return $this->getFirstMediaUrl($collection, $this->thumbnailConversionName());
    }

    protected function initialChecks(string $collection): void
    {
        if (!($media = $this->getFirstMedia($collection))) {
            throw new Exception(
                "The provided media-library \"{$collection}\" collection does not exist on the model!"
            );
        } elseif (!$media->hasGeneratedConversion($this->thumbnailConversionName())) {
            throw new Exception(
                "The provided media model does not have a generated blur-thumbnail. Please regenerate media for it."
            );
        } elseif ($media->responsive_images) {
            throw new Exception(
                "The provided media does contain responsive images. There's no point of using the image component then!"
            );
        }
    }

    public function addGoodThumbnailConversion()
    {
        $this->addMediaConversion($this->thumbnailConversionName())
            ->width(208)
            ->height(117)
            ->sharpen(10);
    }
}
