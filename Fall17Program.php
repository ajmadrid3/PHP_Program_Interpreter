<?php

include 'Lexer.php';
include 'Token.php';

class Fall17Program {
    static $letters = "abcdefghijklmnopqrstuvwxyz";
    static $digits = "0123456789";
    static $values = [];
    static $currentToken;
    static $lex;
    static $oneIndent = "   ";
     
    function main() {
        $header = "<html>\n" . "  <head>\n" . "    <title>Program Evaluator</title>\n" . "  </head>\n" . "  <body>\n" . "  <pre>";
        echo $header . "\n";    
        $programsUrl = fopen("http://cs5339.cs.utep.edu/longpre/assignment2/programs.txt", "r") or die("Unable to open site.");
        while(!feof($programsUrl))
        {
            $programsInputLine = fgets($programsUrl);
            $programsInputLine = trim($programsInputLine);
            echo $programsInputLine . "\n";
            $inputUrl = fopen($programsInputLine, "r") or die("Fuck PHP");
            $program = "";
            while(!feof($inputUrl))
            {
                $program = $program . "\n" . fgets($inputUrl);
            }
            self::$lex = new Lexer($program);
            self::$currentToken = self::$lex->next();
            try {
                self::execProg(self::$oneIndent);
                if(self::$currentToken->type != Token::EOF)
                {
                    echo "Unexpected characters at the end of the program\n";
                    throw new Exception();
                }
            } catch (Exception $ex) {
                echo "<br/>Program parsing aborted";
            }
            echo "\n";
        }
        $footer = "  </pre>\n" . "  </body>\n" . "</html>";
        echo $footer;
    }
        
    function execProg($indent)
    {
        while(self::$currentToken->type == Token::ID || self::$currentToken->type == Token::IF_TAG)
        {
            self::execStatement($indent, true);
        }
        echo "\n";
        self::execResults($indent);
    }
    
    function execStatement($indent, $executing)
    {
        if(self::$currentToken->type == Token::ID)
        {
            self::execAssign($indent, $executing);
        }
        else 
        {
            self::execConditional($indent, $executing);
        }
    }
        
    function execAssign($indent, $executing)
    {
        $c = substr(self::$currentToken->str, 0);
        self::$currentToken = self::$lex->next();
        if(self::$currentToken->type != Token::EQUAL)
        {
            echo "/n equal sign expected\n";
            throw new Exception();
        }
        self::$currentToken = self::$lex->next();
        echo $indent . $c . " = ";
        $value = self::execExpr($indent);
        echo "\n";
        if ($executing)
        {
            self::$values[$c] = $value;
        }
    }
    
    function execConditional($indent, $executing)
    {
        echo $indent . "if ";
        self::$currentToken = self::$lex->next();
        $condResult = self::execCond($indent);
        echo " {\n";
        if(self::$currentToken->type != Token::LBRACKET)
        {
            echo "Left bracket expected\n";
            throw new Exception();
        }
        self::$currentToken = self::$lex->next();
        while(self::$currentToken->type == Token::ID || self::$currentToken->type == Token::IF_TAG)
        {
            self::execStatement($indent . self::$oneIndent, $condResult);
        }
        if(self::$currentToken->type != Token::RBRACKET)
        {
            echo "Right bracket or statement expected\n";
            throw new Exception();
        }
        echo $indent . "}";
        self::$currentToken = self::$lex->next();
        if(self::$currentToken->type == Token::ELSE_TAG)
        {
            self::$currentToken = self::$lex->next();
            if(self::$currentToken->type != Token::LBRACKET)
            {
                echo "Left bracket expected\n";
                throw new Exception();
            }
            self::$currentToken = self::$lex->next();
            echo " else {\n";
            while(self::$currentToken->type == Token::ID || self::$currentToken->type == Token::IF_TAG)
            {
                self::execStatement($indent . self::$oneIndent, !$condResult);
            }
            if(self::$currentToken->type != Token::RBRACKET)
            {
                echo "Right bracket or statement expected\n";
                throw new Exception();
            }
            echo $indent . "}";
            self::$currentToken = self::$lex->next();
        }
        echo "\n";
    }
    
    function execCond($indent)
    {
        if(self::$currentToken->type != Token::LPAREN)
        {
            echo "Left parenthesis expected\n";
            throw new Exception();
        }
        echo "(";
        self::$currentToken = self::$lex->next();
        $v1 = self::execExpr($indent);
        if(self::$currentToken->type != Token::LESS)
        {
            echo "LESS THAN expected\n";
            throw new Exception();
        }
        echo "&lt;";
        self::$currentToken = self::$lex->next();
        $v2 = self::execExpr($indent);
        if(self::$currentToken->type != Token::RPAREN)
        {
            echo "Right parenthesis expected\n";
            throw new Exception();
        }
        echo ")";
        self::$currentToken = self::$lex->next();
        return $v1 < $v2;
    }
    
    function execExpr($indent)
    {
           if(self::$currentToken->type == Token::VALUE)
           {
               $val = self::$currentToken->val;
               echo $val;
               self::$currentToken = self::$lex->next();
               return $val;
           }
           if(self::$currentToken->type == Token::ID)
           {
               $c = substr(self::$currentToken->str, 0);
               echo "$c";
               if(array_key_exists($c, self::$values))
               {
                   self::$currentToken = self::$lex->next();
                   return (int) self::$values[$c];
               }
               else
               {
                   echo "Reference to an undefined variable\n";
                   throw new Exception();
               }
           }
           echo "An expression should be either a digit or letter\n";
           throw new Exception();
    }
    
    function execResults($indent)
    {
        if(self::$currentToken->type != Token::COLON)
        {
            echo "COLOR or statement expected\n";
            throw new Exception();
        }
        self::$currentToken = self::$lex->next();
        while(self::$currentToken->type == Token::ID)
        {
            $c = substr(self::$currentToken->str, 0);
            self::$currentToken = self::$lex->next();
            if(array_key_exists($c, self::$values))
            {
                echo "The value of $c is " . self::$values[$c] . "\n";
            }
            else 
            {
                echo "The value of $c is undefined\n";
            }
        }
    }

}
Fall17Program::main();
?>