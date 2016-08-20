<?php
/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Your Name <you@domain.net>
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   SVN: $Id: coding-standard-tutorial.xml,v 1.9 2008-10-09 15:16:47 cweiske Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * An example of a hash comment is:
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 * 
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Your Name <you@domain.net>
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MyStandard_Sniffs_Commenting_TraceSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        //T_STRING returns method names
        return array(T_STRING, T_VARIABLE,T_COMMA,T_TRUE,T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS);

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
        
        $error = 'trace --->'. var_export($tokens[$stackPtr],true) .'<---'; //['content']
        $data  = array(trim($tokens[$stackPtr]['content']));
        //$phpcsFile->addError($error, $stackPtr, 'Found', $data);   

    }//end process()

    private function getBlacklistedFunctions()
    {
        return array('dd','var_dump','var_export','print_r');
    }


}//end class

?>