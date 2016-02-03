<?php
namespace AppBundle\Service\Http\Client;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class Guzzle implements ClientInterface
{
    /** @var string */
    const METHOD_GET    = 'GET';

    /** @var string */
    const METHOD_PUT    = 'PUT';

    /** @var string */
    const METHOD_POST   = 'POST';

    /** @var string */
    const METHOD_PATCH  = 'PATCH';

    /** @var string */
    const METHOD_DELETE = 'DELETE';

    /**
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * @param GuzzleClientInterface $client
     */
    public function __construct(GuzzleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return RequestInterface
     */
    public function request($method, $uri)
    {
        return new Request($method, $uri);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function get($uri, array $options = [])
    {
        return $this->send($this->request(self::METHOD_GET, $uri), $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function put($uri, array $options = [])
    {
        return $this->send($this->request(self::METHOD_PUT, $uri), $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function post($uri, array $options = [])
    {
        return $this->send($this->request(self::METHOD_POST, $uri), $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function patch($uri, array $options = [])
    {
        return $this->send($this->request(self::METHOD_PATCH, $uri), $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function delete($uri, array $options = [])
    {
        return $this->send($this->request(self::METHOD_DELETE, $uri), $options);
    }
}