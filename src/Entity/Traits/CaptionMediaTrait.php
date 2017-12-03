<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait CaptionMediaTrait
{
    /** @var  string */
    public $caption = '';

    private function getCaptionMappings(): array
    {
        return [['name' => 'caption', 'setter' => 'setCaptionFromApi']];
    }

    protected function setCaptionFromApi($data)
    {
        if (isset($data->raw)) $this->caption = $data->raw;
    }
}
