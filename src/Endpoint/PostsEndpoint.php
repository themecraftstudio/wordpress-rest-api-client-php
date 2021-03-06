<?php


namespace Themecraft\WordPressApiClient\Endpoint;

use GuzzleHttp\Exception\ClientException;
use Themecraft\WordPressApiClient\Entity\Post;


/**
 * REST API call for Post-like endpoints (e.g. news or events)
 */
class PostsEndpoint extends PaginatedEndpoint
{
    const COLLECTION_ROUTE = '/wp/v2/posts';
    const ENTITY_ROUTE = '/wp/v2/posts/%d';

    /**
     * @var array
     *
     * Each key represents the 'rest_base' of the taxonomy (e.g. 'category'),
     * while each value is an array of term slugs.
     */
//    protected $terms = [];

//    public function term(string $taxonomyRestBase, string $termSlug): self
//    {
//        if (!array_key_exists($taxonomyRestBase, $this->terms))
//            $this->terms[$taxonomyRestBase] = [];
//
//        $this->terms[$taxonomyRestBase][] = $termSlug;
//
//        return $this;
//    }

    /**
     * @param Post|null $post
     *
     * @return PostsEndpoint
     */
    public function delete(Post $post = null)
    {
        if ($post)
            $this->setRequestParameter('id', $post->getId()); // Needed for prepareQueryString()

        $query = ['force' => true] + $this->prepareQueryString();
        try {
            $this->httpClient->delete( '/', [ 'query' => $query ] );
        } catch (ClientException $e) {
            if ($e->getCode() !== 404)
                throw $e;
        }

        return $this;
    }

    protected function prepareQueryString($params = []): array
    {
        $p = [];

        // Taxonomy terms
//        $this->resolveTermSlugs();
//        $query = array_merge($query, $this->terms);

        return parent::prepareQueryString($params) + $p;
    }

    public function getRoute(): string
    {
        if (null === $this->getRequestParameter('id'))
            return static::COLLECTION_ROUTE;

        return sprintf( static::ENTITY_ROUTE, $this->getRequestParameter('id'));
    }

//    protected function resolveTermSlugs()
//    {
//        foreach ($this->terms as $taxonomyRestBase => &$terms) {
//            foreach ($terms as $key => $id) {
//                // Resolve only if we have an actual slug
//                if (is_integer($id))
//                    continue;
//
//                $uri = sprintf('%s', $taxonomyRestBase);
//
//                // Fetch JSON response
//                $response = $this->getHttpClient()->get($uri, [
//                    'query' => ['slug' => $id]
//                ]);
//
//                $objects = json_decode($response->getBody());
//                if (count($objects) > 1)
//                    throw new \RuntimeException(sprintf('Got multiple terms for the slug %s', $slug));
//
//                $term = $objects[0];
//                $terms[$key] = $term->id;
//            }
//        }
//    }

    /**
     * Returns an instance of an entity associated with this endpoint.
     *
     * @param \stdClass $object
     *
     * @return mixed single entity
     */
    protected function createEntityFromJsonObject( \stdClass $object )
    {
        return new Post($object);
    }
}
