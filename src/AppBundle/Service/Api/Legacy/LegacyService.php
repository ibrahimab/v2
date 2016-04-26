<?php
namespace AppBundle\Service\Api\Legacy;

use AppBundle\Service\Http\Client\ClientInterface;
use AppBundle\Concern\WebsiteConcern;
use AppBundle\Service\Legacy\CmsUser\CmsUserService;
use Symfony\Component\HttpFoundation\Response;
use Psr\Http\Message\ResponseInterface;
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
     * @var CmsUserService
     */
    protected $legacyCmsUserService;

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
     * @param WebsiteConcern  $website
     * @param CmsUserService  $legacyCmsUserService
     */
    public function __construct(ClientInterface $client, WebsiteConcern $website, CmsUserService $legacyCmsUserService)
    {
        $this->client               = $client;
        $this->website              = $website;
        $this->uri                  = $website->getConfig(WebsiteConcern::WEBSITE_LEGACY_API_URI);
        $this->legacyCmsUserService = $legacyCmsUserService;
        $this->params               = [];
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

            $json = $this->parseJson($response);

        } catch (ServerException $e) {
            return $this->throwServerErrorException($e->getResponse());
        }

        return $json;
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

            $json = $this->parseJson($response);

        } catch (ServerException $e) {
            return $this->throwServerErrorException($e->getResponse());
        }

        return $json;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string
     * @throws ServerException
     */
    protected function throwServerErrorException(ResponseInterface $response)
    {
        $result = json_decode((string)$response->getBody(), true);
        $message = 'unknown error';

        if (is_array($result)) {
            $message = $result['message'];
        }

        throw new LegacyApiException(sprintf('Legacy API did not respond correctly. This is the legacy api response: %s', $message));
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     * @throws LegacyApiException
     */
    protected function parseJson(ResponseInterface $response)
    {
        $json = json_decode((string)$response->getBody(), true);

        if (null === $json) {
            throw new LegacyApiException(sprintf('Invalid response from legacy API (%s), message from server: %s', (string)$this->uri . '?' . http_build_query($this->params), (string)$response->getBody()));
        }

        return $json;
    }
}
