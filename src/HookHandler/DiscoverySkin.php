<?php

namespace MediaWiki\Extension\CollabPads\HookHandler;

use BlueSpice\Discovery\Hook\BlueSpiceDiscoveryTemplateDataProviderAfterInit;
use BlueSpice\Discovery\ITemplateDataProvider;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\CollabPads\CollabSessionManager;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Title\Title;

class DiscoverySkin implements BlueSpiceDiscoveryTemplateDataProviderAfterInit {
// @phan-suppress-previous-line PhanUndeclaredInterface

	/** @var CollabSessionManager */
	private $collabSessionManager;

	/** @var HookContainer */
	private $hookContainer;

	/**
	 * @param CollabSessionManager $collabSessionManager
	 * @param HookContainer $hookContainer
	 */
	public function __construct( CollabSessionManager $collabSessionManager, HookContainer $hookContainer ) {
		$this->collabSessionManager = $collabSessionManager;
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @param ITemplateDataProvider $registry
	 * @return void
	 */
	public function onBlueSpiceDiscoveryTemplateDataProviderAfterInit( $registry ): void {
		// @phan-suppress-previous-line PhanUndeclaredTypeParameter
		$title = RequestContext::getMain()->getTitle();
		if ( !$title || !$title->isContentPage() ) {
			return;
		}

		// @phan-suppress-next-line PhanUndeclaredClassMethod
		$registry->register( 'panel/edit', 'ca-collabpad' );

		$session = $this->collabSessionManager->getSessionExistsByName(	$title->getNamespace(),	$title->getDBkey() );
		if ( $session ) {
			// Disable other edit actions when CollabPad session is active
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$registry->unregister( 'panel/edit', 'ca-new-section' );
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$registry->unregister( 'panel/edit', 'ca-ve-edit' );
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$registry->unregister( 'panel/edit', 'ca-edit' );
			return;
		}

		$this->maybeDisableContentAction( $title, $registry );
	}

	/**
	 * Allow extensions to unregister 'ca-collabpad'
	 *
	 * @param Title $title
	 * @param ITemplateDataProvider $registry
	 */
	protected function maybeDisableContentAction( Title $title, ITemplateDataProvider $registry ): void {
		// @phan-suppress-previous-line PhanUndeclaredTypeParameter
		$this->hookContainer->run(
			'CollabPadsAfterAddContentAction',
			[ $title, $registry ]
		);
	}
}
