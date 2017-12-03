<?php

namespace Themecraft\WordPressApiClient\Entity\Traits;

trait TitlePostTrait
{
    /** @var  string */
    public $title = '';


    private function getTitleMappings(): array
    {
        return [['name' => 'title', 'setter' => 'setTitleFromApi']];
    }

    protected function setTitleFromApi($data)
    {
        if (isset($data->rendered)) $this->title = $data->rendered;
    }
}
