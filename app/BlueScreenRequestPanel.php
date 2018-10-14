<?php declare(strict_types = 1);

namespace App;


class BlueScreenRequestPanel
{

	public function __invoke(?\Throwable $e): ?array
	{
		if ( ! $e instanceof \App\RequestException) {
			return NULL;
		}

		$request = $e->getRequest();
		$response = $e->getResponse();

		$copyScript = '
			<script>
				function copyToClipboard(text) {
	                var tempInput = document.createElement("textarea");
	                var body = document.getElementsByTagName("BODY")[0];
	                body.append(tempInput);
	                tempInput.value = text;
	                tempInput.select();
	                document.execCommand("copy");
	                tempInput.remove();
				}
			</script>
		';

		$body = (string) $request->getBody();

		try {
			$tmp = \Nette\Utils\Json::decode($body);
			$body = \Nette\Utils\Json::encode($tmp, \Nette\Utils\Json::PRETTY);
		} catch (\Nette\Utils\JsonException $e) {
		}

		$responseBody = $response
			? (string) $response->getBody()
			: ''
		;

		try {
			$tmpResponse = \Nette\Utils\Json::decode($responseBody);
			$responseBody = \Nette\Utils\Json::encode($tmpResponse, \Nette\Utils\Json::PRETTY);
		} catch (\Nette\Utils\JsonException $e) {
		}

		$htmlId = 'guzzle-http-exception-client-exception-body';

		$uri = '<b>' . $request->getUri() . '</b> ' . $this->createSvg("'" . (string) $request->getUri() . "'");

		$content = $this->createBody(
			$htmlId . '-request',
			$body
		);

		$contentResponse = $this->createBody(
			$htmlId . '-response',
			$responseBody
		);

		return [
			'tab' => 'Request' . ($response ? ' & response' : ''),
			'panel' => \sprintf('%s %s %s %s', $uri, $copyScript, $content, $contentResponse),
		];
	}


	private function createBody(
		string $htmlId,
		string $body
	): string
	{
		return $body
			? '<pre id="' . $htmlId . '" class="tracy-dump">' . $body . '</pre>' . $this->createSvg("document.getElementById('$htmlId').innerText")
			: ''
		;
	}


	private function createSvg(
		string $value
	): string
	{
		return '
			<svg onclick="copyToClipboard(' . $value . ')" class="octicon octicon-clippy" viewBox="0 0 14 16" version="1.1" width="14" height="16" aria-hidden="true">
				<path fill-rule="evenodd" d="M2 13h4v1H2v-1zm5-6H2v1h5V7zm2 3V8l-3 3 3 3v-2h5v-2H9zM4.5 9H2v1h2.5V9zM2 12h2.5v-1H2v1zm9 1h1v2c-.02.28-.11.52-.3.7-.19.18-.42.28-.7.3H1c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1h3c0-1.11.89-2 2-2 1.11 0 2 .89 2 2h3c.55 0 1 .45 1 1v5h-1V6H1v9h10v-2zM2 5h8c0-.55-.45-1-1-1H8c-.55 0-1-.45-1-1s-.45-1-1-1-1 .45-1 1-.45 1-1 1H3c-.55 0-1 .45-1 1z"></path>
			</svg>
		';
	}

}
