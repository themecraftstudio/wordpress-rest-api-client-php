<?php


namespace Themecraft\WordPressApiClient\Entity;


use Themecraft\WordPressApiClient\Entity\Traits\CaptionMediaTrait;
use Themecraft\WordPressApiClient\Entity\Traits\CustomFieldsTrait;
use Themecraft\WordPressApiClient\Entity\Traits\DescriptionMediaTrait;
use Themecraft\WordPressApiClient\Entity\Traits\TitlePostTrait;

class Media extends AbstractPost
{
    use TitlePostTrait;
    use CaptionMediaTrait;
    use DescriptionMediaTrait;
    use CustomFieldsTrait;

    const TYPE = 'attachment';
    const FIELD_MAPPINGS = [
        ['name' => 'alternativeText', 'api' => 'alt_text'],
        ['name' => 'authorId', 'api' => 'author'],
        ['name' => 'mediaType', 'api' => 'media_type', 'readonly' => true],
        ['name' => 'mimeType', 'api' => 'mime_type', 'readonly' => true],
        ['name' => 'postId', 'api' => 'post'],
        ['name' => 'sourceUrl', 'api' => 'source_url', 'readonly' => true],
        ['name' => 'mediaDetails', 'api' => 'media_details', 'readonly' => true]
    ];

    public $alternativeText;

    protected $mediaType; // read only

    protected $mimeType; // read only

    public $postId;

    protected $sourceUrl; // read only

    public $authorId;

    protected $mediaDetails; // read only

    protected $contents; // may require special handling

    public function getMediaType(): string { return $this->mediaType; }
    public function getMimeType(): string { return $this->mimeType; }
    public function getSourceUrl(): string { return $this->sourceUrl; }
    public function getMediaDetails(): array { return $this->mediaDetails; }

    /**
     * @param $resource mixed Whatever is accepted by GuzzleHttp\Psr7\stream_for()
     */
    public function setContents($resource) { $this->contents = $resource; }
    public function getContents() { return $this->contents; }

    protected function getFieldMappings(): array {
        return array_merge(
            parent::getFieldMappings(),
            self::FIELD_MAPPINGS,
            $this->getCaptionMappings(),
            $this->getDescriptionMappings(),
            $this->getCustomFieldsMappings()
        );
    }
}
