<?php

namespace MediaWiki\Extension\CollabPads\HookHandler;

use BlueSpice\SaferEdit\Hook\BSSaferEditMessageDataHook;
use MediaWiki\Html\Html;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\LoadBalancer;

class SaferEditMessage implements BSSaferEditMessageDataHook {

	/** @var LoadBalancer */
	private $loadBalancer;

	/**
	 * @param LoadBalancer $loadBalancer
	 */
	public function __construct( $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @inheritDoc
	 */
	public function onBSSaferEditMessageData( Title $title, array &$data ): void {
		if ( $this->hasCollabPadsSession( $title ) ) {
			$data = [
				'message' => 'collabpads-bs-saferedit-editing',
				'params' => [ $this->makeCollabPadsAnchor( $title ) ],
				'hideForCurrentUser' => true,
			];
		}
	}

	/**
	 * @param Title $title
	 * @return bool
	 */
	private function hasCollabPadsSession( Title $title ): bool {
		$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
		$resRowCount = $dbr->newSelectQueryBuilder()
			->select( 's_id' )
			->from( 'collabpad_session' )
			->where( [
				"s_page_title" => $title->getDBkey(),
				"s_page_namespace" => $title->getNamespace(),
			] )
			->caller( __METHOD__ )
			->fetchRowCount();

		if ( $resRowCount ) {
			return true;
		}

		return false;
	}

	/**
	 * @param Title $title
	 * @return string
	 */
	private function makeCollabPadsAnchor( Title $title ): string {
		$collabPadsEditLink = $title->getFullURL( [ 'veaction' => 'collab-edit' ] );
		return Html::element( 'a',
			[ 'href' => $collabPadsEditLink ],
			wfMessage( 'collabpads-bs-saferedit-editing-link-text' )->text()
		);
	}
}
