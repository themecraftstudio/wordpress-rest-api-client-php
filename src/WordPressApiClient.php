<?php

namespace Themecraft\WordPressApiClient;

use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Themecraft\WordPressApiClient\Endpoint\MediaEndpoint;
use Themecraft\WordPressApiClient\Endpoint\PostsEndpointAbstract;

class WordPressApiClient extends Container
{
    /** @var  LoggerInterface */
    protected $logger;

    public function __construct( array $config = array(), LoggerInterface $logger = null)
    {
        // Check settings
        if (empty($config['settings.url']))
            throw new \InvalidArgumentException('settings.url');

        parent::__construct($config + [
            'settings.timezone' => 'UTC',
            'debug' => false,
        ]);

        // Logger
        if ($logger)
            $this['logger'] = $logger;

        // Http client
        $this['http'] = function ($api) {
            $httpSettings = [
                'base_uri' => trim($api['settings.url'])
            ];
            if (!empty($this['settings.auth']))
                $httpSettings['auth'] = $this['settings.auth'];

            return new Client($httpSettings);
        };

        // Posts endpoint
        $this['posts'] = $this->factory(function ($api) {
            return new PostsEndpointAbstract($api);
        });

        // Media endpoint
        $this['media'] = $this->factory( function ( $api ) {
            return new MediaEndpoint( $api );
        } );
    }
}
