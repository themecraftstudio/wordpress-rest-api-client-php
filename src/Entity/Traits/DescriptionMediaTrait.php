<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait DescriptionMediaTrait
{
    /** @var string */
    public $description;

    private function getDescriptionMappings(): array
    {
        return [['name' => 'description', 'setter' => 'setDescriptionFromApi']];
    }

    protected function setDescriptionFromApi($data)
    {
        if (isset($data->raw)) $this->caption = $data->raw;
    }
}
