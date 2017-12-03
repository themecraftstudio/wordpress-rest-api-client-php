<?php

namespace Themecraft\WordPressApiClient\Entity;

use Themecraft\WordPressApiClient\Entity\Traits\ContentPostTrait;
use Themecraft\WordPressApiClient\Entity\Traits\CustomFieldsTrait;
use Themecraft\WordPressApiClient\Entity\Traits\TitlePostTrait;

/**
 * Class WP_Post
 * @package Tiphys\News\WordPress\Entity
 *
 * Loosely mimics the properties of a WordPress post.
 */
class Post extends AbstractPost
{
    use TitlePostTrait;
    use ContentPostTrait;
    use CustomFieldsTrait;

    const TYPE = 'post';
    const FIELD_MAPPINGS = [
        ['name' => 'format'],
        ['name' => 'commentStatus', 'api' => 'comment_status'],
        ['name' => 'pingStatus', 'api' => 'ping_status'],
    ];

    /** @var string */
    public $format;

    /** @var string */
    public $commentStatus;

    /** @var string */
    public $pingStatus;

    protected function getFieldMappings(): array {
        return array_merge(
            parent::getFieldMappings(),
            self::FIELD_MAPPINGS,
            $this->getTitleMappings(),
            $this->getContentMappings(),
            $this->getCustomFieldsMappings()
        );
    }
}
