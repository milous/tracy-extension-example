<?php declare(strict_types = 1);

namespace App\RemoteApiModule\Presenters;


class DataPresenter extends \Nette\Application\UI\Presenter
{


	public function actionSuccess(): void
	{
		$successResponse = new \Nette\Application\Responses\JsonResponse([
			'status' => 'OK',
		]);

		$this->sendResponse($successResponse);
	}


	public function actionBadRequest(): void
	{
		$responseCallback = function (\Nette\Http\IRequest $httpRequest, \Nette\Http\IResponse $httpResponse): void {
			$httpResponse->setCode(400);
			$httpResponse->setContentType('application/json', 'utf-8');

			$errorCode = \rand(1, 1000);

			echo \Nette\Utils\Json::encode([
				'status' => 'FAILED',
				'reason' => \sprintf('Random error message #%d %s', $errorCode, \Nette\Utils\Random::generate(150)),
				'error' => $errorCode,
			], \Nette\Utils\Json::PRETTY);
		};

		$this->sendResponse(
			new \Nette\Application\Responses\CallbackResponse($responseCallback)
		);
	}

}
