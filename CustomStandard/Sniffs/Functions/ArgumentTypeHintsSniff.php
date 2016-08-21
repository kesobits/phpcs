<?php
/**
 * This sniff prohibits the use of user-defined functions with incomplete argument typehints for PHP7
 * this sniff can only be used with php7 because scalar type hints started with PHP7
 *
 */
class CustomStandard_Sniffs_Functions_ArgumentTypeHintsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        //T_STRING returns method names
        return array(T_STRING,T_VARIABLE,T_COLON, T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS, T_FUNCTION);
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
            $additionalPointersToGetOpenParenthesis = 2;
            if (!$this->isArgumentTypeHintCountAndArgumentsCountEqual($phpcsFile, $stackPtr + $additionalPointersToGetOpenParenthesis)) {
                $error = 'incomplete argument typehints for '. $tokens[$stackPtr+2]['content'].'()';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }

        
        
    }//end process()

    private function isArgumentTypeHintCountAndArgumentsCountEqual(PHP_CodeSniffer_File $phpcsFile, $pointerOfMethod)
    {
        $tokens = $phpcsFile->getTokens();
        $openParenthesisStackPtr = $tokens[$pointerOfMethod +1]['parenthesis_opener'];
        $closeParenthesisStackPtr = $tokens[$pointerOfMethod +1]['parenthesis_closer']; 
       
        $typehintCount = 0;
        $variableCount = 0;
        for ($i = $openParenthesisStackPtr; $i <= $closeParenthesisStackPtr; $i++) {
            if ($tokens[$i]['type'] === 'T_STRING') {
                $typehintCount++;
            } 

            if ($tokens[$i]['type'] === 'T_VARIABLE') {
                $variableCount++;
            }
        }
        return $typehintCount === $variableCount;
    }

    

}//end class

?>