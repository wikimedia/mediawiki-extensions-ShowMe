<?php
/**
 * @author Ike Hecht <tosfos@yahoo.com>
 */
class ShowMe {

	/** @var string */
	private $type;

	/** @var string */
	private $name;

	/** @var string[] */
	private $options;

	/**
	 * @param string $type The type of input field, currently may be 'dropdown' or 'ul'
	 * @param string $name The name and ID to be assigned to the input field
	 * @param string[] $options Use the form label => value
	 * @param ParserOutput $out
	 */
	public function __construct( $type, $name, array $options, ParserOutput $out ) {
		$this->type = $type;
		$this->name = $name;
		$this->options = $options;

		// Add this $name to the array of ShowMe field IDs that exist on this page
		if ( $type == 'dropdown' ) {
			$this->addVar( 'wgShowMeDropdownIDs', $name, $out );
		} elseif ( $type == 'ul' ) {
			$this->addVar( 'wgShowMeUnorderedListIDs', $name, $out );
		}
	}

	/**
	 * Get the output HTML
	 *
	 * @return string
	 */
	public function getHTML() {
		// Theoretically, other types may be added in the future, such as radio buttons.
		switch ( $this->type ) {
			case 'dropdown':
				return $this->getDropdownHTML();
			case 'ul':
				return $this->getUnorderedListHTML();
		}
		// invalid type
		// Throw error?
		return '';
	}

	/**
	 * Get the output HTML for a dropdown
	 *
	 * @return string
	 */
	protected function getDropdownHTML() {
		$select = new XmlSelect( $this->name, $this->name );
		$select->addOptions( $this->options );
		return $select->getHTML();
	}

	/**
	 * Get the output HTML for a ul
	 *
	 * @return string
	 */
	protected function getUnorderedListHTML() {
		$html = Html::openElement( 'ul', [ 'id' => $this->name, 'class' => 'showme-ul' ] );
		foreach ( $this->options as $label => $value ) {
			$anchor = Html::rawElement( 'a', [ 'href' => '#' ], $label );
			$html .= Html::rawElement( 'li', [ 'id' => $value ], $anchor );
		}
		$html .= Html::closeElement( 'ul' );

		return $html;
	}

	/**
	 * Add the name of an element to the JS vars
	 *
	 * @param string $varName
	 * @param string $name
	 * @param ParserOutput $out
	 */
	private function addVar( $varName, $name, ParserOutput $out ) {
		$configVars = $out->getJsConfigVars();
		$configVars[$varName][] = $name;
		$out->addJsConfigVars( $varName, $configVars[$varName] );
	}
}
