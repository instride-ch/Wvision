<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 w-vision AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Api\ImprintApi;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImprintApi implements ImprintApiInterface
{
    /**
     * @var string
     */
    private $baseUri = 'https://www.w-vision.ch';

    /**
     * {@inheritdoc}
     */
    public function getData(array $addresses = []): ?array
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => ['ACCEPT' => 'application/json'],
        ]);

        $uri = '/api/internal/imprint';

        if (!empty($addresses)) {
            $uri .= sprintf('/%s', implode('/', $addresses));
        }

        $response = $client->get($uri);

        return $this->parseResponse($response);
    }

    /**
     * Parses the API JSON response
     *
     * @param ResponseInterface $response
     * @return array|null
     */
    private function parseResponse(ResponseInterface $response): ?array
    {
        if ($response->getStatusCode() < 300) {
            $result = (string) $response->getBody();
            $data = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new BadRequestHttpException('Invalid json body: ' . json_last_error_msg());
            }

            return is_array($data) ? $data : [];
        }

        return null;
    }
}
