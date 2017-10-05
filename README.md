# PHP_Program_Interpreter
This program is an assignment for my CS 4339 Secure Web-Based Systems class.  We were given a Java program that will take a URL
that contains a document of a program that has assignments and conditonal statements.  My task was to create a PHP program that will 
print out the same output as the Java code.  The following is the BNF for the language the program will accept:

\<program>      ::= \<statement>\* \<results>

\<statement>    ::= \<assignment> \| \<conditional>

\<assignment>   ::= ID '=' \<expression>

\<expression>   ::= ID \| VALUE

\<conditional>  ::= 'if' \<condition> '\{' \<statement>\* '\}' \[ 'else' '\{' \<statement>\* '\}' \]

\<condition>    ::= '\(' \<expression> '<' \<expression> '\)'

\<results>      ::= ':' ID\*

ID is [a-z] (one lower case letter)

VALUE is [0-9] (one digit)
