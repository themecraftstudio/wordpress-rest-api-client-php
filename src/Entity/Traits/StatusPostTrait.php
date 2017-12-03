<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait StatusPostTrait
{
    /** @var  string */
    public $status = 'inherit';

    private function getCaptionMappings(): array
    {
        return [['name' => 'status', 'getter' => 'getStatusForApi']];
    }

    protected function getStatusForApi($data)
    {
        if ($data === 'inherit')
            throw new \InvalidArgumentException('Invalid status. This exception should be captured');

        if (isset($data->r)) $this->status = $data->raw;
    }
}
