<?php declare(strict_types = 1);

namespace App;


class HttpClient
{

	/**
	 * @var \Tracy\ILogger
	 */
	private $logger;

	/**
	 * @var \GuzzleHttp\Client
	 */
	private $client;


	public function __construct(
		\Tracy\ILogger $logger
	)
	{
		$this->logger = $logger;
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => 'http://localhost:8008',
		]);
	}


	public function sendRequest(
		string $uri,
		array $params = []
	): \Psr\Http\Message\StreamInterface
	{
		$method = 'POST';
		$body = \Nette\Utils\Json::encode($params);

		$this->logger->log(
			\sprintf('Request: %s (%s)', $uri, $body)
		);

		try {
			$apiResponse = $this->client->request($method, $uri, [
				'body' => $body,
			]);

			return $apiResponse->getBody();

		} catch (\GuzzleHttp\Exception\RequestException $e) {
			$this->logger->log(
				\sprintf('Response error: %s %s: %s', $method, $uri, $e->getMessage())
			);

			throw new RequestException(
				\sprintf('%s Request failed', $method),
				$e->getRequest(),
				$e->getResponse(),
				$e->getCode(),
				$e
			);
		}
	}

}
