<?php
/**
 * HCSB Verse of the Day (Plus) - Main Logic.
 * 
 * This contains the VOTD_HCSB class, which contains the main logic for getting
 * the verse, storing it, and displaying it.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 */

/**
 * This class contains the guts of this plug-in.  No editing should be
 * necessary; if you feel you need to change this to do what you want to do,
 * e-mail me.  I may be able to add an option so that you won't have to redo
 * work whenever I release updates to the plug-in.
 * 
 * Note to pedants - the code below tends to follow Zend coding standards as
 * opposed to WordPress standards, although it does use the extra spaces
 * between parameters. 
 */
class VOTD_HCSB {
	
	/** Version. */
	const VOTD_VERSION = 3;
	
	/** Options. */
	const VOTD_OPTIONS = 'votd_hcsb';
	
	/** Constants for options. */
	const DATE           = 'date';
	const HEADING        = 'heading';
	const TEXT           = 'text';
	const REFERENCE      = 'reference';
	const FOOTER         = 'footer';
	const TRANSLATION    = 'translation';
	const PLUGIN_VERSION = 'version';
	
	/** @var mixed $_options Option Array. */
	private $_options = '';

	/**
	 * Gets or sets the date for the current verse of the day.
	 * 
	 * @param string $date The date to set (optional).
	 * @return string The date for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Date( $date = '--' ) {
		if ( '--' == $date ) return $this->_options[ VOTD_HCSB::DATE ];
		$this->_options[ VOTD_HCSB::DATE ] = $date; return $this;
	}
	
	/**
	 * Gets or sets the heading for the current verse.
	 * 
	 * @param string $heading The heading to set (optional).
	 * @return string The heading for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Heading( $heading = '--' ) {
		if ( '--' == $heading ) return $this->_options[ VOTD_HCSB::HEADING ];
		$this->_options[ VOTD_HCSB::HEADING ] = $heading; return $this;
	}
	
	/**
	 * Gets or sets the verse text for the current verse.
	 * 
	 * @param string $text The text to set (optional).
	 * @return string The text for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Text( $text = '--' ) {
		if ( '--' == $text ) return $this->_options[ VOTD_HCSB::TEXT ];
		$this->_options[ VOTD_HCSB::TEXT ] = $text; return $this;
	}
	
	/**
	 * Gets or sets the reference for the current verse.
	 * 
	 * @param string $reference The reference to set (optional).
	 * @return string The reference for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Reference( $reference = '--' ) {
		if ( '--' == $reference ) return $this->_options[ VOTD_HCSB::REFERENCE ];
		$this->_options[ VOTD_HCSB::REFERENCE ] = $reference; return $this;
	}
	
	/**
	 * Get the reference as a link to the current verse.
	 * 
	 * @return string The reference as a link to the current verse.
	 */
	public function LinkedReference() {
		return '<a href="http://www.biblegateway.com/passage/?search='
			. str_replace ( ' ', '+', $this->Reference() ) 
			. '&amp;version=' . $this->Translation() . '" title="View ' 
			. $this->Reference() . ' at BibleGateway.com">' . $this->Reference() 
			. '</a> (' . $this->Translation() . ')';
	}
	
	/**
	 * Get the footer for the current verse.
	 * 
	 * @param string $footer The footer to set (optional).
	 * @return string The footers for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Footer( $footer = '--' ) {
		if ( '--' == $footer ) return $this->_options[ VOTD_HCSB::FOOTER ];
		$this->_options[ VOTD_HCSB::FOOTER ] = $footer; return $this;
	}
	
	/**
	 * Get or set the translation for the current verse.
	 * 
	 * @param string $translation The translation to set (optional).
	 * @return string The translation for the current verse (if no parameters specified).
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	public function Translation( $translation = '--' ) {
		if ( '--' == $translation ) return $this->_options[ VOTD_HCSB::TRANSLATION ];
		$this->_options[ VOTD_HCSB::TRANSLATION ] = $translation; return $this;
	}
	
	/**
	 * Get or set the plugin version for the options.
	 * 
	 * @param int $version The version to set (optional).
	 * @return int The version of the options (if no parameters specified)
	 * @return VOTD_HCSB The current instance (if parameters specified).
	 */
	private function Version( $version = '--' ) {
		if ( '--' == $version ) return $this->_options[ VOTD_HCSB::PLUGIN_VERSION ];
		$this->_options[ VOTD_HCSB::PLUGIN_VERSION ] = $version; return $this;
	}
	
	/**
	 * VOTD_HCSB Constructor.
	 * 
	 * @return A VOTD_HCSB object with the reference text ready for display.
	 */
	public function __construct() {

		// Determine if we've already obtained today's date.
		$this->_options = get_option( VOTD_HCSB::VOTD_OPTIONS );
	
		if ( $this->_options == '' ) {
			// New installation - add the options
			$this->_options = array();
			$this->Date        ( '' )
			     ->Heading     ( '' )
			     ->Text        ( '' )
			     ->Reference   ( '' )
			     ->Footer      ( '' )
			     ->Translation ( 'HCSB' )
			     ->Version     ( VOTD_HCSB::VOTD_VERSION );
			
			add_option( VOTD_HCSB::VOTD_OPTIONS, $this->_options );
		}
		
		// Check for upgrade.
		if ( VOTD_HCSB::VOTD_VERSION != $this->Version() ) {
			$this->UpgradeOptions();
		}
		
		if ( date( 'Y-m-d' ) != $this->Date() ) {
			// Obtain the next day's verse of the day.
			$this->ObtainNewVerse();
			
//			if ( 'Error' == $this->Heading()) {
				// Clear the date so we'll try again next load.
				$this->Date( '' );
//			}
//			else {
//				$this->Date( date( 'Y-m-d' ) );
//			}
			
			update_option( VOTD_HCSB::VOTD_OPTIONS, $this->_options );
		}
	}
	
	/**
	 * Upgrade options among versions.
	 */
	private function UpgradeOptions() {
		
		// Version 3 is the first version where we stored the version in the
		// options.  The option difference between version 2 and 3 is the
		// addition of the Translation and Version parameters.
		if ( null ==  $this->Version() ) {
			$this->Translation( 'HCSB' )->Version( VOTD_HCSB::VOTD_VERSION );
			update_option( VOTD_HCSB::VOTD_OPTIONS, $this->_options );
		}
	}

	/**
	 * Obtain New Verse.
	 * 
	 * Obtain the verse reference from BibleGateway.com's Verse of the Day
	 * service, then obtain the text from their regular user interface.  Then,
	 * update the WordPress database so future displays will be quicker.
	 *
	 * @access private
	 */
	private function ObtainNewVerse ( ) {
		
		$url = 'http://www.biblegateway.com/votd/get/?format=atom&version=';
		$url .= ( $this->ScrapeTranslation() ) ? 'NIV' : $this->Translation();
		
		$rss = fetch_feed( $url );
				
		if ( is_wp_error( $rss ) ) {
			$this->Heading( 'Error' )->Text( $rss->get_error_message() . '<br/>' . $url );
			return;
		}
		
		$items = $rss->get_items( 0, 1 );
		$item  = $items[0];
			
		// The reference is the title.
		$this->Reference( $item->get_title() );
		
		if ( $this->ScrapeTranslation() ) {
			// Get the reference and screen-scrape.
			$this->ScreenScrape();
		} else {
			// Make "LORD" use small-caps instead.
			$this->Text( str_replace( 'LORD',
					'<span style="font-variant:small-caps;">Lord</span>',
					$item->get_content() ) );
		}
		
		// Footer contains copyright information.
		$footer = '';
		if ( 'HCSB' == $this->Translation() ) {
			$footer = 'Copyright &copy; 1999, 2000, 2002, 2003 Holman Bible Publishers, Nashville, TN. All Rights Reserved.';
		} elseif ( 'NKJV' == $this->Translation() ) {
			$footer = 'Copyright &copy; 1982 by Thomas Nelson, Inc.';
		} else {
			$footer = $item->get_copyright();
		}
		$footer .= '<br />';
		
		// Complete the header and footer.
		$header = '';
		if ( strpos( $this->Reference(), '-' ) ) {
			$header = 'Passage';
			$footer .= 'Passage Reference';
		} else {
			$header = 'Verse';
			$footer .= 'Verse Reference';
			if ( strpos( $this->Reference(), ',' ) ) {
				$header .= 's';
				$footer .= 's';
			}
		}
		
		$header .= ' of the Day';
		$footer .= ' Provided by <a href="http://www.biblegateway.com" '
			. 'title="Visit BibleGateway.com">BibleGateway.com</a>';
			
		$this->Heading( $header )->Footer( $footer ); 
	}
	
	/**
	 * Is this a translation we have to screen-scrape?
	 * 
	 * @return boolean True if we should scrape, false if not.
	 */
	private function ScrapeTranslation() {
		return ( ( 'HCSB' == $this->Translation() ) || ( 'NKJV' == $this->Translation() ) );
	}
	
	/**
	 * Get the text by screen-scraping the BibleGateway.com website.
	 */
	private function ScreenScrape() {
		
		// Accrue the text in this variable.
		$text = '';
		
		// Retrieve the page from BibleGateway.com as an array.
		$page = file( 'http://www.biblegateway.com/passage/?search='
			. str_replace( ' ', '+', $this->Reference() ) . '&version=HCSB' );
		
		if ( strpos( $this->Reference(), ',' ) ) {
			/** Multiple non-contiguous verse */
			
			// Find the table with the output.
			for ( $key = 0; $key < count( $page ); $key++ ) {
				$line = $page[$key];
				if ( strpos( $line, 'multipassage-box' ) ) {
					// The second next line is the actual text.
					++$key;
					$line = $page[++$key];
					
					// Fix the text.
					if ( $text > '' ) {
						$text .= ' ';
					}
					
					$text .= $this->ReformatText( $line );
				}
				
				if ( strpos( $line, '<!-- SIGNATURE -->' ) ) {
					// Remove the extra space.
					$text = substr( $text, 0, strlen( $text ) - 1 );
					break;
				}
			}
		} else {
			/** Single verse or continguous passage */
			
			// Find the line with the text.
			foreach ( $page as $key => $line ) {
				if ( strpos( $line, 'result-text-style-normal' ) ) {
					break;
				}
			}
			
			// The next line is the actual text.
			$line = $page[++$key];
			$text = $this->ReformatText( $line );
		}
		
		// Put nice quotes around the whole thing.
		$this->Text( '&ldquo;' . $text . '&rdquo;' );
	}
	
	/**
	 * This will take the web page returned by the HTML get, and remove
	 * footnote/cross-reference links,  It will also remove other HTML tags,
	 * while preserving the small-caps "Lord" found in the Old Testament.
	 * Finally, it will strip out the reference, as the passage has the book
	 * and chapter number in it if it's the first verse in a chapter.
	 * 
	 * @param string $text The text line with the verse/passage to reformat.
	 * @return The properly formatted text.
	 */
	private function ReformatText( $text ) {

		$line = $text;

		// Mask off footnotes and cross-references.
		$xref = '<strong>Cross references:</strong>';
		$foot = '<strong>Footnotes:</strong>';

		if ( ( strpos( $line, $xref ) ) && ( strpos( $line, $foot ) ) ) {
			if ( strpos( $line, $foot ) > strpos ( $line, $xref ) ) {
				$line = substr( $line, 0, strpos( $line, $xref ) );
			} else {
				$line = substr( $line, 0, strpos( $line, $foot) );
			}
		} elseif ( strpos( $line, $xref ) ) {
			$line = substr( $line, 0, strpos( $line, $xref ) );
		} elseif ( strpos( $line, $foot ) ) {
			$line = substr( $line, 0, strpos( $line, $foot ) );
		}

		// Save off all capital lettered LORD.
		$line = str_replace(
			'L<span style="font-variant:small-caps">ORD</span>', 'LORD',
			$line );

		// Get rid of some tags including their contents (strip tags won't do that).
		$tags = array( 'sup', 'span', 'h5' );

		foreach ( $tags as $tag ) {
			while ( strpos( $line, "<{$tag}" ) ) {
				$work_line = $line;
				$start     = strpos( $line, "<{$tag}" );
				$end       = strpos( $line, "</{$tag}>" ) + strlen( $tag ) + 3;
				$line      = substr( $work_line, 0, $start ) . substr( $work_line, $end );
			}
		}

		// Eliminate tags and non-breaking spaces
		$line = trim( str_replace( '&nbsp;', '', strip_tags( $line ) ) );

		// Eliminate quotes if they're the first or last character in the text
		if ( substr( $line, 0, 1 ) == '"' ) {
			$line = substr( $line, 1 );
		}

		if ( substr( $line, -1 ) == '"') {
			$line = substr( $line, 0, strlen( $line ) - 1 );
		}

		// Put special formatting back for all-caps LORD
		$line = str_replace('LORD', '<span style="font-variant:small-caps">Lord</span>', $line );

		// Replace the book and chapter if it's found in the passage, and
		// eliminate extra spaces
		$line = trim( str_replace( substr( $this->Reference(), 0,
				strpos( $this->Reference(), ':' ) ), '', $line ) );
		
		// Return the finished product
		return $line;
	}
}
