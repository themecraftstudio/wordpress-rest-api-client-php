<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


trait CustomFieldsTrait
{
    /** @var  array */
    protected $meta;

    /**
     * @param string $name
     *
     * @return bool|null
     */
    public function getCustomField(string $name)
    {
        if (!isset($this->meta->{$name}))
            return null;

        return $this->meta->{$name};
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return self
     */
    public function setCustomField(string $name, $value): self
    {
        $this->meta->{$name} = $value;

        return $this;
    }

    protected function getCustomFieldsMappings(): array
    {
        return [['name' => 'meta', 'setter' => 'setCustomFieldsFromApi']];
    }

    /**
     * @param $data array
     */
    protected function setCustomFieldsFromApi($data)
    {
        $this->meta = $data;
    }

}
