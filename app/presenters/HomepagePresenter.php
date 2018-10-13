<?php declare(strict_types = 1);

namespace App\Presenters;


final class HomepagePresenter extends BasePresenter
{

	/**
	 * @var \App\HttpClient
	 * @inject
	 */
	public $httpClient;


	public function actionSuccess(): void
	{
		$apiResponse = $this->httpClient->sendRequest(
			'/api/success'
		);

		$this->getTemplate()
			->add('apiResponse', $apiResponse)
		;
	}


	public function actionBadRequest(): void
	{
		$randomParams = [
			'content' => \Nette\Utils\Random::generate(300),
			'user_id' => \rand(100, 500),
		];

		$this->httpClient->sendRequest(
			'/api/bad-request',
			$randomParams
		);
	}


}
