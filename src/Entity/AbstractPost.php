<?php


namespace Themecraft\WordPressApiClient\Entity;


/**
 * Class AbstractPost
 * @package Themecraft\WordPressApiClient\Entity
 */
abstract class AbstractPost
{
    const DATE_WORDPRESS = 'Y-m-d\TH:i:s';
    const TYPE = 'post';
    const FIELD_MAPPINGS = [
        // NOTE: setters and getter are given the spec as second argument
        ['name' => 'id', 'readonly' => true],
        ['name' => 'slug'],
        ['name' => 'link', 'readonly' => true],
    ];

    /** @var int */
    protected $id; // read only

    /** @var string */
    public $slug;

    /** @var \DateTime UTC publication time. */
    public $date;

    /** @var \DateTime UTC modified time. */
    protected $modified; // read only

    /** @var  string */
    protected $link; // readonly

    public function getId(): ?int { return $this->id; }
    public function getLink(): string { return $this->link; }
    public function getModificationDate(): \DateTime { return $this->modified; }
    public function getPublicationDate(): \DateTime { return $this->date; }
    public function setPublicationDate(\DateTime $date) { $this->date = $date; return $this; }
    public function setModificationDate(\DateTime $date) { $this->modified = $date; return $this; }


    public function __construct(\stdClass $object = null)
    {
        if ($object)
            $this->loadJsonObject($object);
    }

    /**
     * Override this class to control how API fields are translated to instance fields.
     * @param \stdClass $object
     */
    public function loadJsonObject(\stdClass $object)
    {
        if ($object->type !== static::TYPE)
            throw new \RuntimeException(sprintf('Trying to populate an object of class %s with data from WordPress object type %s', static::class, $object->type));

        $this->loadMappedFieldsFromJsonObject($object);

        // Publication date
        if (isset($object->date_gmt)) $this->date = new \DateTime($object->date_gmt, new \DateTimeZone('UTC'));
        // Modified
        if (isset($object->modified_gmt)) $this->modified = new \DateTime($object->modified_gmt, new \DateTimeZone('UTC'));
    }

    /**
     * @return \stdClass
     */
    public function toJsonObject()
    {
        $o = new \stdClass();

        $this->applyMappedFieldsToJsonObject($o);

        // Publication date
        if (isset($this->date)) $o->date_gmt = $this->date->format(self::DATE_WORDPRESS);

        return $o;
    }

    protected function loadMappedFieldsFromJsonObject(\stdClass $object)
    {
        $mappings = $this->getFieldMappings();
        foreach ($mappings as $spec) {
            if (!isset($spec['name']))
                throw new \RuntimeException(sprintf('Missing name in spec %s', var_export($spec, true)));

            $field = $spec['name'];
            $apiField = $spec['api'] ?? $spec['name'];

            if (isset($object->{$apiField})) {
                if (isset($spec['setter']) && method_exists($this, $spec['setter']))
                    $this->{$spec['setter']}($object->{$apiField});
                else
                    $this->{$field} = $object->{$apiField};
            }
        }
    }

    protected function applyMappedFieldsToJsonObject(\stdClass $object)
    {
        foreach ($this->getFieldMappings() as $spec) {
            if (!empty($spec['readonly']))
                continue;

            $field = $spec['name'];
            $apiField = $spec['api'] ?? $spec['name'];

            if (isset($this->{$field})) {
                if (isset($spec['getter']) && method_exists($this, $spec['getter']))
                    $object->{$apiField} = $this->{$spec['getter']}();
                else
                    $object->{$apiField} = $this->{$field};
            }
        }
    }

    protected function getFieldMappings(): array
    {
        return self::FIELD_MAPPINGS;
    }
}
