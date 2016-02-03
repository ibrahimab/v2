<?php
namespace AppBundle\Service\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Every client needs to implement this interface to allow for
 * quick swapping of clients if the need arises.
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
interface ClientInterface
{
    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, array $options = []);

    /**
     * @param string $method
     * @param string $uri
     *
     * @return RequestInterface
     */
    public function request($method, $uri);

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return
     */
    public function get($uri, array $options = []);

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return
     */
    public function put($uri, array $options = []);

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return
     */
    public function post($uri, array $options = []);

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return
     */
    public function patch($uri, array $options = []);

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return
     */
    public function delete($uri, array $options = []);
}