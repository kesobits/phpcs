<?php
/**
 * This sniff prohibits the use assignments inside If statements
 * such code is not detected as syntax error and is usually a typing mistake
 */
class CustomStandard_Sniffs_Conditions_AssignmentAtConditionsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        //T_STRING returns method names
        return array(T_FUNCTION, T_EQUAL, T_ELSEIF);
    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $assignmentOperator = $phpcsFile->findNext(
	        array(T_EQUAL, T_ELSEIF),
	        $stackPtr,
	        $tokens[$stackPtr]['parenthesis_opener']
	    );

	    if (!empty($assignmentOperator)) {
       		$error = 'Assignment Operator found within an if/elseif Statement';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
	    }
    }//end process()

}//end class

?>