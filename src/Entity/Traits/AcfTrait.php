<?php


namespace Themecraft\WordPressApiClient\Entity\Traits;


/**
 * ACFs are read only!
 *
 * Trait AcfTrait
 * @package Themecraft\WordPressApiClient\Entity\Traits
 */
trait AcfTrait
{
    /** @var  array */
    protected $acf;

    /**
     * @param string $name
     *
     * @return bool|null
     */
    public function getAcfField(string $name)
    {
        if (!isset($this->acf->{$name}))
            return null;

        return $this->acf->{$name};

    }

    protected function getAcfMappings(): array
    {
        return [['name' => 'acf']];
    }
}
