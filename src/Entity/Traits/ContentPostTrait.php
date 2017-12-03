<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait ContentPostTrait
{
    /** @var string */
    public $content = '';

    private function getContentMappings(): array
    {
        return [['name' => 'content', 'setter'=> 'setContentFromApi']];
    }

    protected function setContentFromApi($data)
    {
        if (isset($data->rendered)) $this->content = $data->rendered;
    }
}
