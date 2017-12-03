<?php


namespace Themecraft\WordPressApiClient\Endpoint;


interface EndpointInterface
{

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getRequestParameter(string $name);

    /**
     * @param string $name
     * @param $value
     *
     * @return mixed
     */
    public function setRequestParameter(string $name, $value);

    /**
     * @return string
     */
    public function getRoute(): string;

    // fetch may not be supported
    // delete neither
    // create / update neither
    // but at least one of them has to
}
