<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'GSF_Inc_Content_Inject' ) ) {
	class GSF_Inc_Content_Inject {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		private $injections = array();

		public function init() {
			add_filter( 'the_content', array( $this, 'the_content' ), 999 );
		}

		public function inject( $item = array() ) {
			$this->injections[] = $item;
		}

		public function get_injection_content( $injection ) {
			if ( isset( $injection['callback'] ) ) {
				return call_user_func( $injection['callback'], $injection );
			} elseif ( isset( $injection['content'] ) ) {
				return $injection['content'];
			}

			return '';
		}

		public function is_valid_html($string) {
			if ( preg_match_all( '@\<(/?[^<>&/\<\>\x00-\x20=]++)@', $string, $matches ) ) {
				$tags = array_count_values( $matches[1] );

				$self_close = array(
					'command' => '',
					'keygen'  => '',
					'source'  => '',
					'embed'   => '',
					'area'    => '',
					'base'    => '',
					'br'      => '',
					'col'     => '',
					'hr'      => '',
					'wbr'     => '',
					'img'     => '',
					'link'    => '',
					'meta'    => '',
					'input'   => '',
					'param'   => '',
					'track'   => '',
				);

				foreach ( array_diff_key( $tags, $self_close ) as $tag => $count ) {

					if ( $tag[0] === '/' || $tag[0] === '!' ) {
						continue;
					}

					$close_tag = '/' . $tag;

					if ( ! isset( $tags[ $close_tag ] ) || $tags[ $close_tag ] !== $count ) {
						return false;
					}
				}
				return true;
			}
			return false;
		}

		public function get_html_blocks( $html ) {
			$block_level_elements = 'address|article|aside|blockquote|canvas|dd|div|dl|fieldset|figcaption|figure|footer|form|h1|h2|h3|h4|h5|h6|header|hgroup|hr|li|main|nav|ol|output|p|pre|section|table|tfoot|ul|video';
			preg_match_all( '/
			( # Capture Whole HTML or Text
				\s* ( < \s* (' . $block_level_elements . ')  (?=.*\>) )? # Select HTML Open Tag

				(?(2)  # IF Open Tag Exists
					(.*?)  # accept innerHTML
		             <\s*\/\s*(?:\\3) \s* > \s* # Select HTML Close Tag
				|  # Else
				[^\n]+  # Capture pain text
				)  #END Condition
			)
		 /six', trim( $html ), $match );

			if ( empty( $match ) ) {
				return false;
			}

			$empty_check = array(
				'<p>&amp;nbsp;</p>' => '',
				'&nbsp;'            => '',
				'<p>&nbsp;</p>'     => '',
			);

			$block_valid_html = array();

			if ( array_filter( $match[3] ) ) {
				$html_blocks                  = array( 0 => array( 'content' => '' ) );
				$last_index                   = - 1;
				$mark_plain_text_as_new_block = true;
				foreach ( $match[3] as $index => $tag ) {
					if ( ! trim( $match[1][ $index ] ) ) {
						continue;
					}

					if ( $tag ) {
						$text = trim( $match[1][ $index ] );
						if ( ! isset( $block_valid_html[ $index ] ) ) {
							$block_valid_html[ $index ] = $this->is_valid_html( $match[0][ $index ] );
						}

						if (isset($empty_check[$text])
							|| (isset( $html_blocks[ $last_index ]['content'] ) &&
							    ! $this->is_valid_html( $html_blocks[ $last_index ]['content'] ))
							|| (isset( $block_valid_html[ $index - 1 ] ) && ! $block_valid_html[ $index - 1 ])
						) {
							if ( $last_index === - 1 ) {
								$last_index ++;
							}
							$html_blocks[ $last_index ]['content'] .= $match[0][ $index ];

						} else {
							$last_index ++;
							$html_blocks[ $last_index ]['content'] = $match[0][ $index ];
							$html_blocks[ $last_index ]['tag']     = $tag;

							$mark_plain_text_as_new_block = true;
						}

					} else {
						$is_plain_text = ! strstr( $match[1][ $index ], '<' );
						if ( $is_plain_text && $mark_plain_text_as_new_block ) {

							$last_index ++;
							$mark_plain_text_as_new_block = false;

							$html_blocks[ $last_index ]['content'] = $match[0][ $index ];
							$html_blocks[ $last_index ]['tag']     = $tag;

						} else {

							if ( $last_index === - 1 ) {
								$last_index ++;
							}

							$html_blocks[ $last_index ]['content'] .= $match[0][ $index ];
						}
					}

				}
			} else {
				$html_blocks = array();

				$i = 0;
				foreach ( $match[0] as $text ) {

					if ( trim( $text ) === '' ) {
						$i ++;
						continue;
					}

					if ( ! isset( $html_blocks[ $i ]['content'] ) ) {
						$html_blocks[ $i ]['content'] = '';
						$html_blocks[ $i ]['tag']     = '';
					}

					$html_blocks[ $i ]['content'] .= "\n";
					$html_blocks[ $i ]['content'] .= $text;

				}
			}

			return $html_blocks;


		}

		public function inject_after(&$blocks, $injection, $position) {
			$position --;
			if ( isset( $blocks[ $position ]['content'] ) ) {
				$blocks[ $position ]['content'] .= $this->get_injection_content( $injection );
			}
		}

		public function the_content( $content ) {
			$before = $after = '';
			$paragraph_changed = false;

			foreach ($this->injections as $_inject) {
				if ($_inject['position'] === 'before') {
					$before .= $this->get_injection_content($_inject);
				} elseif ($_inject['position'] === 'after') {
					$after .= $this->get_injection_content($_inject);
				} else {
					if (!isset($html_blocks)) {
						$html_blocks       = $this->get_html_blocks( $content );
						$html_blocks_count = count( $html_blocks );
					}

					if ($_inject['position'] === 'middle') {
						$position = floor( $html_blocks_count / 2 );
					} else {
						$position = intval($_inject['paragraph']);
					}
					$this->inject_after($html_blocks,$_inject,$position);
					$paragraph_changed = true;
				}
			}

			if ($paragraph_changed) {
				$content = '';
				foreach ( $html_blocks as $block ) {
					$content .= $block['content'];
					$content .= ' ';
				}
			}
			return $before . $content . $after;
		}
	}
}
