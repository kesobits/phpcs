<?php
/**
 * This sniff prohibits the use of the ff methods
 * dd, var_dump
 * and checks if the ff: methods are safely used
 * print_r, var_export
 *
 */
class CustomStandard_Sniffs_Functions_DisallowDangerousFunctionsSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        //T_STRING returns method names
        return array(T_STRING, T_CLOSE_PARENTHESIS, T_TRUE,T_EVAL, T_EXIT);
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
        $this->checkForBlacklistedMethods($phpcsFile, $stackPtr);
        $this->checkForDumpersThatCanBeUsedSafely($phpcsFile, $stackPtr);
    }//end process()

    private function checkForBlacklistedMethods(PHP_CodeSniffer_File $phpcsFile, $stackPtr) 
    {
        $tokens = $phpcsFile->getTokens();
        if  (in_array(strtolower($tokens[$stackPtr]['content']), $this->getBlacklistedMethods())) {
            $error = 'please remove blacklisted function '. $tokens[$stackPtr]['content'] .'()';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }
    }

    private function checkForDumpersThatCanBeUsedSafely(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if  (in_array(strtolower($tokens[$stackPtr]['content']), $this->getDumpersThatCanBeUsedSafely())) {
            
            $openingParenthesisOfIllegalFunction = $tokens[$stackPtr+1];

            if ($openingParenthesisOfIllegalFunction['type'] === 'T_OPEN_PARENTHESIS') {
                $closingParenthesisMarker = $openingParenthesisOfIllegalFunction['parenthesis_closer'];
                $closingParenthesis = null;

                for ($i=1;empty($closingParenthesis); $i++) {
                    $tokenUnderCheck = $tokens[$stackPtr + $i]; //['content']
                    if ($tokenUnderCheck['type'] === 'T_CLOSE_PARENTHESIS' && $tokenUnderCheck['parenthesis_closer'] == $openingParenthesisOfIllegalFunction['parenthesis_closer']) {
                        $closingParenthesis = $tokenUnderCheck;
                        $closingParenthesisPointer = $stackPtr + $i;
                        $returnBehaviourParamPointer = $this->getNonWhiteSpaceToLeftPointer($tokens, $stackPtr + $i);//$tokens[$closingParenthesisPointer - 1];
                        
                        if (($returnBehaviourParamPointer - $stackPtr) === 5 && $tokens[$returnBehaviourParamPointer]['type'] === 'T_TRUE') {
                            return ;
                        }
                        $error = $tokens[$stackPtr]['content'] . '() is only allowed if 2nd parameter is TRUE ';
                        $data  = array(trim($tokens[$stackPtr]['content']));
                        $phpcsFile->addError($error, $stackPtr, 'Found', $data);
                    }
                }
            }
        }
    }

    private function getNonWhiteSpaceToLeftPointer($token, $currentPointer)
    {
        for ($i=1;true;$i++) {
            if ($token[$currentPointer - $i]['type'] != 'T_WHITESPACE') {
                return $currentPointer - $i;
            } 
        }
    }

    private function getBlacklistedMethods()
    {
        return array('dd','var_dump','die','eval','shell_exec');
    }

    
    private function getDumpersThatCanBeUsedSafely()
    {
        return array('var_export','print_r');
    }

}//end class

?>