<?php

class PipeEscape {

	private static $parserFunctions = array('!' => 'pipeChar');

	public static function setup( &$parser ) {
		// register each hook
		foreach( self::$parserFunctions as $hook => $function )
			$parser->setFunctionHook( $hook,
				array( __CLASS__, $function ), SFH_OBJECT_ARGS );
		return true;
	}

	public static function pipeChar( &$parser, $frame, $args ) {

		// shift the first argument off the front of the array
		$output = array_shift( $args );

		// If there was nothing to shift, users entered nothing into parser
		// function: give them nothing back
		if ( ! isset( $output ) ) {
			return '';
		}

		// expand the first argument
		$output = $frame->expand( $output );

		// get the rest of the arguments, expand each one, prefix each expansion
		// with a pipe character, and append it to the output string.
		for ( $arg = array_shift( $args );
			isset( $arg );
			$arg = array_shift( $args ) )
		{
			$output .= '|' . $frame->expand( $arg );
		}

		return trim( $output );
	}
}
