<?php
//Token::constant field
class Token
{
    // Change constants to array
    
    const LPAREN = 0;
    const RPAREN = 1;
    const LBRACKET = 2;
    const RBRACKET = 3;
    const LESS = 4;
    const EQUAL = 5;
    const COLON = 6;
    const ID = 7;
    const VALUE = 8;
    const IF_TAG = 9;
    const ELSE_TAG = 10;
    const EOF = 11;
    const INVALID = 12;
    
    var $type, $str, $val;
    
    function __construct()
    {
        $args = func_num_args();
        //echo "Num of args: $args";
        switch($args)
        {
            case 1:
                self::__construct1(func_get_arg(0));
                break;
            case 2:
                self::__construct2(func_get_arg(0), func_get_arg(1));
                break;
            case 3:
                self::__construct3(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                break;
        }
    }
    
    function __construct1($theType)
    {
        $this->type = $theType;
    }
    
    function __construct2($theType, $theString)
    {
        //echo "Construct 2";
        $this->type = $theType;
        $this->str = $theString;
    }
    
    function __construct3($theType, $theString, $theVal)
    {
        $this->type = $theType;
        $this->str = $theString;
        $this->val = $theVal;
    }
}
?>