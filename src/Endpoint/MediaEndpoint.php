<?php


namespace Themecraft\WordPressApiClient\Endpoint;


use GuzzleHttp\Exception\ClientException;
use Themecraft\WordPressApiClient\Entity\Media;

class MediaEndpoint extends PaginatedEndpoint
{
    const COLLECTION_ROUTE = '/wp/v2/media';
    const ENTITY_ROUTE = '/wp/v2/media/%d';

    public function upload(Media $media)
    {
        if (null !== $media->getId())
            throw new \InvalidArgumentException(sprintf('Cannot create a media with the id property set'));

        if (!$media->getContents())
            throw new \InvalidArgumentException(sprintf('Cannot create media without contents'));


        $this->setRequestParameter('id', null);  // Needed for prepareQueryString()
        $query = $this->prepareQueryString();

        $resp = $this->httpClient->post('/', [
            'query' => $query,
            'multipart' => [[ 'name' => 'file', 'contents' => $media->getContents() ]]
        ]);
        $media->loadJsonObject(json_decode($resp->getBody()));

        return $media;
    }

    public function save(Media $media)
    {
        $this->setRequestParameter('id', $media->getId());   // Needed for prepareQueryString()
        $query = $this->prepareQueryString();

        $jsonObject = $media->toJsonObject();
        $resp = $this->httpClient->patch('/', [
            'query' => $query,
            'json' => $jsonObject
        ]);
        $media->loadJsonObject(json_decode($resp->getBody()));

        return $media;
    }

    public function delete(Media $media = null)
    {
        if ($media)
            $this->setRequestParameter('id', $media->getId());   // Needed for prepareQueryString()

        $query = ['force' => true] + $this->prepareQueryString();

        try {
            $this->httpClient->delete( '/', [ 'query' => $query ] );
        } catch (ClientException $e) {
            if ($e->getCode() !== 404)
                throw $e;
        }
    }

    /**
     * @param \stdClass $object
     *
     * @return mixed
     */
    protected function createEntityFromJsonObject( \stdClass $object ) {
        return new Media($object);
    }

    public function getRoute(): string
    {
        if (null === $this->getRequestParameter('id'))
            return static::COLLECTION_ROUTE;

        return sprintf( static::ENTITY_ROUTE, $this->getRequestParameter('id'));
    }
}
