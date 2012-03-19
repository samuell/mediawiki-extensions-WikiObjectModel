<?php
/**
 * Created on 22.11.2010
 *
 * Author: ning
 */
if ( !defined( 'MEDIAWIKI' ) ) die;

define( 'WOM_VERSION', '1.0.2 alpha' );

$wgOMIP = $IP . '/extensions/WikiObjectModel';
$wgOMScriptPath = $wgScriptPath . '/extensions/WikiObjectModel';

$wgExtensionFunctions[] = 'wfWOMSetupExtension';
$wgExtensionMessagesFiles['WikiObjectModel'] = $wgOMIP . '/languages/Messages.php';

require_once( $wgOMIP . '/includes/WOM_Setup.php' );

function wfWOMInitLanguageObject( $langcode, $fallback = null ) {
	global $wgOMIP;

	$langClass = 'WOMLanguage' . str_replace( '-', '_', ucfirst( $langcode ) );

	if ( file_exists( $wgOMIP . '/languages/' . $langClass . '.php' ) ) {
		include_once( $wgOMIP . '/languages/' . $langClass . '.php' );
	}

	// fallback if language not supported
	if ( !class_exists( $langClass ) ) {
		if ( $fallback ) {
			return $fallback;
		}
		include_once( $wgOMIP . '/languages/WOMLanguageEn.php' );
		$langClass = 'WOMLanguageEn';
	}
	return new $langClass();
}

function wfWOMInitLanguage() {
	global $wgLanguageCode, $wgLang;
	global $wgOMContLang, $wgOMLang;

	$wgOMContLang = wfWOMInitLanguageObject( $wgLanguageCode );
	$wgOMLang = wfWOMInitLanguageObject( $wgLang->getCode(), $wgOMContLang );
}

function wfWOMRegisterParserFunctionParsers( &$parsers ) {
	global $wgOMPFParsers;
	$parsers += $wgOMPFParsers;
	return true;
}

function wfWOMSetupExtension() {
	global $wgHooks, $wgExtensionCredits;

	wfWOMInitLanguage();

	$wgHooks['womRegisterParserFunctionParsers'][] = 'wfWOMRegisterParserFunctionParsers';

	$wgExtensionCredits['parserhook'][] = array(
		'path' => __FILE__,
		'name' => 'Wiki ObjectModel Extension',
		'version' => WOM_VERSION,
		'author' => "Ning Hu, Justin Zhang, [http://smwforum.ontoprise.com/smwforum/index.php/Jesse_Wang Jesse Wang], sponsored by [http://projecthalo.com Project Halo], [http://www.vulcan.com Vulcan Inc.]",
		'url' => 'http://wiking.vulcan.com/dev',
		'descriptionmsg' => 'wom-desc'
	);
	return true;
}