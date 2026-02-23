<?php

namespace MediaWiki\Extension\CollabPads;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ManifestRegistry\ManifestAttributeBasedRegistry;

class InsertCollabpadsConfig {
	/**
	 * @return array
	 */
	public static function makeConfig() {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$backendServiceURL = $mainConfig->get( 'CollabPadsBackendServiceURL' );
		return [
			'backendServiceURL' => $backendServiceURL
		];
	}

	/**
	 * @return array
	 */
	public static function getPluginModules() {
		// @phan-suppress-next-line PhanUndeclaredClassMethod
		$registry = new ManifestAttributeBasedRegistry(
			'CollabPadsPluginModules'
		);

		$pluginModules = [];
		// @phan-suppress-next-line PhanUndeclaredClassMethod
		foreach ( $registry->getAllKeys() as $key ) {
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$moduleName = $registry->getValue( $key );
			$pluginModules[] = $moduleName;
		}

		return $pluginModules;
	}
}
