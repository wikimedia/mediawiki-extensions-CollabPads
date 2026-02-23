<?php

namespace MediaWiki\Extension\CollabPads\Api\Rest;

use InvalidArgumentException;
use MediaWiki\Rest\Response;
use Wikimedia\ParamValidator\ParamValidator;

class GetSessionHandler extends CollabSessionHandlerBase {

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

		return $this->collabSessionManager->getSessionByName( $pageNamespace, $pageTitle );
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
