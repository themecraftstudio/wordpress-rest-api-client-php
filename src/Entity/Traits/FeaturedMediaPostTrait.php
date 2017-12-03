<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait FeaturedMediaPostTrait
{
    /** @var  int */
    public $featuredMediaId;

    protected function getFeaturedMediaMappings(): array
    {
        return [['name' => 'featuredMediaId', 'api' => 'featured_media']];
    }
}
