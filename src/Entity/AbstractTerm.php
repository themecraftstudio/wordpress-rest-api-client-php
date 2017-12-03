<?php

namespace Themecraft\WordPressApiClient\Entity;

use Themecraft\WordPressApiClient\Entity\Traits\CustomFieldsTrait;

/**
 * TODO
 *
 * Class AbstractTerm
 * @package Themecraft\WordPressApiClient\Entity
 */
abstract class AbstractTerm
{
    use CustomFieldsTrait;

    /** @var  int */
    public $id;

    /** @var  string */
    public $description;

    /** @var  string */
    public $link;

    /** @var  string */
    public $name;

    /** @var  string */
    public $slug;

    /** @var  int */
    public $count;

    public function __construct(\stdClass $object = null)
    {
        if ($object)
            $this->loadJsonObject($object);
    }

    /**
     * @param \stdClass $o
     */
    protected function loadJsonObject(\stdClass $o)
    {
        $maps = [
            'id' => 'id',
            'slug' => 'slug',
            'name' => 'name',
            'description' => 'description',
            'count' => 'count',
            'link' => 'link'
        ];

        foreach ($maps as $jsonProperty => $property) {
            if (isset($object->{$jsonProperty}))
                $this->{$property} = $object->{$jsonProperty};
        }
    }
}
