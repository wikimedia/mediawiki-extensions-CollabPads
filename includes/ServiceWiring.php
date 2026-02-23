<?php

use MediaWiki\Extension\CollabPads\CollabRevisionManager;
use MediaWiki\Extension\CollabPads\CollabSessionManager;
use MediaWiki\MediaWikiServices;

/** @phpcs-require-sorted-array */
return [
	'CollabPadsCollabRevisionManager' => static function (
		MediaWikiServices $services,
	): CollabRevisionManager {
		return new CollabRevisionManager( $services->getDBLoadBalancer() );
	},
	'CollabPadsCollabSessionManager' => static function (
		MediaWikiServices $services,
	): CollabSessionManager {
		return new CollabSessionManager(
			$services->getConnectionProvider(),
			$services->getObjectCacheFactory()
		);
	},
];
