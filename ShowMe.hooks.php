<?php
/**
 * Hooks for ShowMe extension
 *
 * @file
 * @ingroup Extensions
 */

class ShowMeHooks {

	/**
	 *
	 * @param Parser &$parser
	 * @return bool
	 */
	public static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'showme', [ __CLASS__, 'showMeRender' ] );
		return true;
	}

	/**
	 *
	 * @staticvar int $num
	 * @param string $input
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string Output HTML
	 */
	public static function showMeRender( $input, array $args, Parser $parser, PPFrame $frame ) {
		$parser->getOutput()->addModuleScripts( 'ext.showMe' );

		$options = [];
		$lines = StringUtils::explode( "\n", $input );
		foreach ( $lines as $line ) {
			$bits = StringUtils::explodeMarkup( '|', $line );
			if ( count( $bits ) < 2 ) {
				continue;
				// throw error instead?
			}
			$options[$bits[0]] = $bits[1];
		}

		// If the user supplied an ID, use it. Otherwise, generate one.
		if ( isset( $args['name'] ) ) {
			$name = $args['name'];
		} else {
			static $num = 0;
			// Add a num to the HTML ID in case there is more than one ShowMe field on this page.
			// Iterate after getting num.
			$name = 'showme-dropdown' . '-' . $num++;
		}

		$showMe = new ShowMe( 'dropdown', $name, $options, $parser->getOutput() );
		return $showMe->getHTML();
	}
}
