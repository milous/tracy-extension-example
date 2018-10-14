<?php declare(strict_types = 1);

namespace App;


class RequestException extends \RuntimeException
{

	/**
	 * @var \Psr\Http\Message\RequestInterface
	 */
	private $request;

	/**
	 * @var \Psr\Http\Message\ResponseInterface|null
	 */
	private $response;


	public function __construct(
		string $message = "",
		\Psr\Http\Message\RequestInterface $request,
		?\Psr\Http\Message\ResponseInterface $response = NULL,
		int $code = 0,
		\Throwable $previous = NULL
	)
	{
		parent::__construct($message, $code, $previous);
		$this->request = $request;
		$this->response = $response;
	}


	public function getRequest(): \Psr\Http\Message\RequestInterface
	{
		return $this->request;
	}


	public function getResponse(): ?\Psr\Http\Message\ResponseInterface
	{
		return $this->response;
	}

}
