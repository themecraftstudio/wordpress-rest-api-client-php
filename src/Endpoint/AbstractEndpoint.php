<?php


namespace Themecraft\WordPressApiClient\Endpoint;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Themecraft\WordPressApiClient\WordPressApiClient;

abstract class AbstractEndpoint implements EndpointInterface
{
    /** @var WordPressApiClient  */
    protected $api;

    /** @var Client */
    protected $httpClient;

    /** @var array */
    protected $requestParams = [];

    /**
     * AbstractEndpoint constructor.
     *
     * @param WordPressApiClient $api
     */
    public function __construct(WordPressApiClient $api)
    {
        $this->api = $api;
        $this->httpClient = $api['http'];
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function id(int $id)
    {
        $this->setRequestParameter('id', $id);
        return $this;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function prepareQueryString($params = []): array
    {
        return $params + ['rest_route' => $this->getRoute()];
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     *
     * Normalizes response data to an array.
     */
    protected function fetchResponseArray(ResponseInterface $response): array
    {
        $objects = json_decode($response->getBody());

        if (!($objects instanceof \stdClass) && !is_array($objects))
            return [];

        if (!is_array($objects))
            $objects = [$objects];

        return $objects;
    }

    protected function fetchResponse(ResponseInterface $response): \stdClass
    {
        return \GuzzleHttp\json_decode($response->getBody());
    }

    public function getRequestParameter(string $name)
    {
        return $this->requestParams[$name];
    }

    public function setRequestParameter(string $name, $value)
    {
        $this->requestParams[$name] = $value;
        return $this;
    }

    abstract public function getRoute(): string;
}
