<?php
namespace AppBundle\Service\Api\Legacy;

use AppBundle\Service\Http\Client\ClientInterface;
use AppBundle\Concern\WebsiteConcern;
use Psr7\Http\Message\ResponseInterface;

/**
 * LegacyService
 *
 * A http based api to bridge between legacy and new
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
abstract class LegacyService
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var WebsiteConcern
     */
    protected $website;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $params;

    /**
     * @param ClientInterface $client
     * @param string          $apiUrl
     */
    public function __construct(ClientInterface $client, WebsiteConcern $website, $redis)
    {
        $this->client   = $client;
        $this->website  = $website;
        $this->redis    = $redis;
        $this->uri      = $website->getConfig(WebsiteConcern::WEBSITE_LEGACY_API_URI);
        $this->params   = [];
    }

    /**
     * @param string $param
     * @param mixed  $value
     *
     * @return self
     */
    public function setParams(array $params)
    {
        $this->params = array_merge(['endpoint' => $this->endpoint, 'token' => $this->redis->get('api:legacy:token')], $params);

        return $this;
    }
}