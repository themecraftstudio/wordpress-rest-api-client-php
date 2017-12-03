<?php


namespace Themecraft\WordPressApiClient\Endpoint;


/**
 * Class PaginatedEndpoint
 * @package Tiphys\AccademiaEtrusca\ObjectsReplication\Endpoint
 */
abstract class AbstractPaginatedEndpoint extends AbstractEndpoint
{
    const MAX_RESULTS_PER_PAGE = 100; // capped by WP REST API

    /** @var integer */
    protected $resultsPerPage;

    /** @var integer */
    protected $resultsOffset;

    /** @var  integer */
    protected $page = 1;

    /** @var int total pages for the query according to X-WP-TotalPages header */
    protected $totalPages = 1;

    /** @var int total objects matching the query according to X-WP-Total header */
    protected $totalObjects = 1;

    public function fetch()
    {
        $oldResultsPerPage = $this->resultsPerPage;
        $this->resultsPerPage = 1;

        $entities = $this->fetchPage();

        $this->resultsPerPage = $oldResultsPerPage;

        return $entities ? $entities[0] : null; // [] evaluates to false
    }

    /**
     * @return array
     *
     * Returns objects from the specified page of the result set.
     */
    public function fetchPage(): array
    {
        $query = $this->prepareQueryString();

        // Send request
        $response = $this->httpClient->get('/', ['query' => $query]);

        // Update metadata about the result set
        if ($response->hasHeader('X-WP-Total'))
            $this->totalObjects = intval($response->getHeader('X-WP-Total')[0]);
        if ($response->hasHeader('X-WP-TotalPages'))
            $this->totalPages = intval($response->getHeader('X-WP-TotalPages')[0]);

        // Fetch objects
        $objects = $this->fetchResponseArray($response);
        $posts = array_map(function ($o) {
            return $this->createEntityFromJsonObject($o);
        }, $objects);

        return $posts;
    }

    /**
     * @return \Generator
     *
     * Fetch all objects up to the last page of the result set.
     */
    public function fetchAll(): \Generator
    {
        $oldLimit = $this->resultsPerPage;

        $this->limit(static::MAX_RESULTS_PER_PAGE);
        $page = 1;

        do {
            $results = $this
                ->page($page++)
                ->fetchPage();

            foreach ($results as $result)
                yield $result;

        } while ($results && $page <= $this->getTotalPages());

        $this->resultsPerPage = $oldLimit;
    }

    /**
     * @param int $resultsPerPage
     *
     * @return $this
     */
    public function limit(int $resultsPerPage)
    {
        if ($resultsPerPage > static::MAX_RESULTS_PER_PAGE)
            $resultsPerPage = static::MAX_RESULTS_PER_PAGE;

        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function page(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $resultsOffset
     *
     * @return $this
     */
    public function offset(int $resultsOffset)
    {
        $this->resultsOffset = $resultsOffset;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getTotalObjects(): int
    {
        return $this->totalObjects;
    }

    protected function prepareQueryString($params = []): array
    {
        // Results page
        $p = ['page' => $this->page];

        // Number of results per page
        if ($this->resultsPerPage > 0) $p['per_page'] = $this->resultsPerPage;

        // Results offset
        if ($this->resultsOffset > 0) $p['offset'] = $this->resultsOffset;

        return parent::prepareQueryString($params) + $p;
    }

    /**
     * @param \stdClass $object
     *
     * @return mixed
     */
    abstract protected function createEntityFromJsonObject(\stdClass $object);
}
