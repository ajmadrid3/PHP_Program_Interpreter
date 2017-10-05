<?php
class Lexer
{
    static $letters = "abcdefghijklmnopqrstuvwxyz";
    static $digits = "0123456789";
    
    private $prog = [];
    private $i;
    
    function __construct($s)
    {
        $this->prog = str_split($s);
        $this->i = 0;
    }
    
    function next()
    {
        //echo "i: $this->i";
        while($this->i < count($this->prog) && (ctype_space($this->prog[$this->i]) || $this->prog[$this->i] == "\n"))
        {
            $this->i++;
        }
        //echo "Does the while loop $this->i times";
        if($this->i >= count($this->prog))
        {
            //echo "Goes to the first if";
            return new Token(Token::EOF);
        }
        switch($this->prog[$this->i])
        {
            case '(':
                $this->i++;
                return new Token(Token::LPAREN, "(");
            case ')':
                $this->i++;
                return new Token(Token::RPAREN, ")");
            case '{':
                $this->i++;
                return new Token(Token::LBRACKET, "{");
            case '}':
                $this->i++;
                return new Token(Token::RBRACKET, "}");
            case '<':
                $this->i++;
                return new Token(Token::LESS, "<");
            case '=':
                $this->i++;
                return new Token(Token::EQUAL, "=");
            case ':':
                $this->i++;
                return new Token(Token::COLON, ":");
        }
        if(strpos(self::$digits, $this->prog[$this->i]) !== false)
        {
            $digit = $this->prog[$this->i];
            $this->i++;
            return new Token(Token::VALUE, "" . $digit, intval($digit));
        }
        if(strpos(self::$letters, $this->prog[$this->i]) !== false)
        {
            //echo "Goes to third if";
            $id = "";
            while($this->i < count($this->prog) && strpos(self::$letters, $this->prog[$this->i]) !== false)
            {
                //echo "Enters the while loop";
                $id = $id . $this->prog[$this->i];
                $this->i++;
            }
            
            if(strcmp("if", $id) == 0)
            {
                //echo "Is if";
                return new Token(Token::IF_TAG, $id);
            }
            if(strcmp("else", $id) == 0)
            {
                //echo "Is else";
                return new Token(Token::ELSE_TAG, $id);
            }
            if(strlen($id) == 1)
            {
                //echo "Is id";
                $value =  new Token(Token::ID, $id);
                //echo print_r($value);
                return $value;
            }
            return new Token(Token::INVALID, "");
        }
        return new Token(Token::INVALID, "");
    }
}
?>