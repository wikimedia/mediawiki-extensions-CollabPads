<?php

namespace MediaWiki\Extension\CollabPads\Api\Rest;

use MediaWiki\Context\RequestContext;

class SetSessionHandler extends CollabSessionHandlerBase {

	public function run() {
		$request = $this->getRequest();
		$user = RequestContext::getMain()->getUser();

		$args = json_decode( $request->getBody()->getContents() );

		if ( !is_array( $args ) ) {
			return $this->getResponseFactory()->createJson( [
				'success' => false,
				'error' => 'Invalid JSON body'
			] );
		}

		if ( !isset( $args['pageTitle'] ) || !isset( $args['pageNamespace'] ) ) {
			return $this->getResponseFactory()->createJson( [
				'success' => false,
				'error' => 'Missing required parameters'
			] );
		}

		$pageName = str_replace( " ", "_", $args['pageTitle'] );
		$session = $this->collabSessionManager->getSession( $args['pageNamespace'], $pageName );

		if ( $session['sessionId'] ) {
			$sessionParticipants = unserialize( $session[ 'participants' ] );
			$sessionParticipants[] = $user->getName();
			$sessionParticipants = array_unique( $sessionParticipants );
			$this->collabSessionManager->setParticipants( $sessionParticipants, $session[ 'sessionId' ] );
		} else {
			$this->collabSessionManager->insertSession(
				$args['pageNamespace'],
				$pageName,
				$user->getName(),
				[ $user->getName() ]
			);
		}

		$output = [
			'success' => true,
			'processId' => $request,
		];

		return $this->getResponseFactory()->createJson( $output );
	}
}
