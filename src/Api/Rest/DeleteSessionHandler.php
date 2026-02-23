<?php

namespace MediaWiki\Extension\CollabPads\Api\Rest;

use InvalidArgumentException;
use MediaWiki\Rest\Response;
use Wikimedia\ParamValidator\ParamValidator;

class DeleteSessionHandler extends CollabSessionHandlerBase {

	/**
	 * @return Response
	 * @throws InvalidArgumentException
	 */
	public function run() {
		$request = $this->getRequest();

		$pageNamespaceRaw = $request->getPathParam( "pageNamespace" );
		$pageTitleRaw = $request->getPathParam( "pageTitle" );

		if ( !$pageNamespaceRaw || !$pageTitleRaw ) {
			throw new InvalidArgumentException( 'Missing required path parameters' );
		}

		if ( !ctype_digit( $pageNamespaceRaw ) ) {
			throw new InvalidArgumentException( 'Invalid namespace parameter' );
		}

		$pageNamespace = (int)$pageNamespaceRaw;
		$pageTitle = $this->unmaskPageTitle( $pageTitleRaw );

		$this->collabSessionManager->deleteSession( $pageNamespace, $pageTitle );

		$output = [
			'success' => true,
			'processName' => "session delete",
		];

		return $this->getResponseFactory()->createJson( $output );
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'pageNamespace' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string'
			],
			'pageTitle' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string'
			]
		];
	}

}
