<?php
namespace AppBundle\Service\Api\Legacy;

use AppBundle\Service\Http\Client\ClientInterface;
use AppBundle\Concern\WebsiteConcern;
use Symfony\Component\HttpFoundation\Response;
use Psr7\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ServerException;

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
    protected $params;

    /**
     * @param ClientInterface $client
     * @param string          $apiUrl
     */
    public function __construct(ClientInterface $client, WebsiteConcern $website)
    {
        $this->client   = $client;
        $this->website  = $website;
        $this->uri      = $website->getConfig(WebsiteConcern::WEBSITE_LEGACY_API_URI);
        $this->params   = [];
    }

    /**
     * @param string $param
     * @param mixed  $value
     *
     * @return self
     */
    protected function setParams(array $params)
    {
        $this->params = array_merge(['endpoint' => $this->endpoint], $params);

        return $this;
    }

    /**
     * @param integer $method
     * @param array   $params
     *
     * @return array
     * @throws LegacyApiException
     */
    public function get($method, array $params = [])
    {
        $params['method'] = $method;
        $this->setParams($params);

        try {

            $response = $this->client->get($this->uri, [
                'query' => $this->params,
            ]);

        } catch (ServerException $e) {
            return $this->throwServerErrorException($e->getResponse());
        }

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param integer $method
     * @param array   $params
     *
     * @return array
     * @throws LegacyApiException
     */
    public function json($method, array $data = [])
    {
        $this->setParams(['method' => $method]);

        try {

            $response = $this->client->post($this->uri, [

                'query' => $this->params,
                'json'  => $data,
            ]);
        } catch (ServerException $e) {
            return $this->throwServerErrorException($e->getResponse());
        }

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    protected function throwServerErrorException($response)
    {
        $result = json_decode((string)$response->getBody(), true);
        $message = 'unknown error';

        if (is_array($result)) {
            $message = $result['message'];
        }

        throw new LegacyApiException(sprintf('Legacy API did not respond correctly. This is the legacy api response: %s', $message));
    }
}