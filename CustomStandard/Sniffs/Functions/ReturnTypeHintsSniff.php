<?php
/**
 * This sniff prohibits the use of user-defined functions with no return type hints
 * this can only be used in php7 because return type hints started with php7
 *
 */
class CustomStandard_Sniffs_Functions_ReturnTypeHintsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        //T_STRING returns method names
        return array(T_STRING, T_FUNCTION, T_RETURN_TYPE, T_OPEN_CURLY_BRACKET);
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
       
        $currentToken = $tokens[$stackPtr];

        $isFunction = $currentToken['type'] === 'T_FUNCTION';
        if ($isFunction) {
            $hasReturnTypeHint = $this->hasReturnTypeHint($phpcsFile, $stackPtr);
            if ($hasReturnTypeHint === false) {
                $error = 'missing return type hint for  '. $tokens[$stackPtr +2]['content'].'()';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }
        
    }//end process()

    private function hasReturnTypeHint(PHP_CodeSniffer_File $phpcsFile, $pointerOfMethod)
    {
        $tokens = $phpcsFile->getTokens();
        $pointerOfOpeningCurlybracket = $this->getOpeningCurlyBracketPointerOfFunction($phpcsFile, $pointerOfMethod);
        for ($i = $pointerOfOpeningCurlybracket; $i >= $pointerOfMethod; $i--) {
            if ($tokens[$i]['type'] === 'T_RETURN_TYPE') {
                return true;
            }
        }


        return false;
    }

    private function getOpeningCurlyBracketPointerOfFunction(PHP_CodeSniffer_File $phpcsFile, $pointerOfFunctionUnderCheck) 
    {
        $tokens = $phpcsFile->getTokens();
        for ($i=1;100;$i++) {
            if ($tokens[$pointerOfFunctionUnderCheck + $i]['type'] === 'T_OPEN_CURLY_BRACKET') {
                return ($pointerOfFunctionUnderCheck + $i);
            }
        }
    }

    

}//end class

?>