<?php

/**
 * Class Args
 *
 * Schema description: # is for integer, * is for string, ## for double, [*] for varargs, default is boolean
 *
 * So a valid schema is: "a,b#,c*".
 *
 * With can then we retrieved:
 * $args = new Args("a,b#,c*", arguments); // arguments = '-a true -b 1 -c "Hello"'
 * $args.getB("a"); // Returns boolean
 * $args.getI("b"); // Returns integer
 * $args.getS("c"); // Returns String
 *
 * TODO:
 * Refactor this code so it is easier to implement the getInteger functionality.
 */
class Args {

    /**
     * The format that needs to be checked
     */
    private $array_fmt = array();

    /**
     * The arguments that were given
     */
    private $array_args = array();

    /**
     * The variable in which the booleans get stored
     */
    private $array_bools = array();

    /**
     * The variable in which the strings get stored
     */
    private $array_strs = array();

    /**
     * Construct a new instance of the Args class
     * @param $fmt The format to use
     * @param $args The arguments to extract
     * @throws ParseException A parse exception
     */
    public function __construct($fmt, $args)
    {
        $this->array_fmt = array_filter(explode(",", $fmt));
        $this->prsArgs($args);

        if(count($this->array_args) / 2 != count($this->array_fmt)) {
            throw new ParseException();
        }

        for($index = 0; $index < count($this->array_fmt); $index++) {
            $typeIndicator = substr($this->array_fmt[$index], -1);
            if($typeIndicator === "*") {
                $v = $this->array_args[($index * 2)+ 1 ]; // Value from arguments

                //$exploded = explode('"', $val, 2);
                $exploded = explode('"', $v, 3);

                if(count($exploded) != 3) {
                    throw new ParseException();
                }

                $val = $exploded[1];
                $indexFmt = trim(trim($this->array_fmt[$index], '*'));
                $this->array_strs[$indexFmt] = $val;
            } else {
                $v = $this->array_args[($index * 2)+ 1 ]; // Value from arguments

                if($v != 'true' && $v != 'false') {
                    throw new ParseException();
                }

                $val = filter_var($v, FILTER_VALIDATE_BOOLEAN);
                $this->array_bools[trim($this->array_fmt[$index])] = $val;
            }
        }
    }

    /**
     * Parse the arguments
     * @var $args the arguments
     */
    private function prsArgs($args) {
        $tmp = array_filter(explode("-", $args));
        foreach($tmp as $arg) {
            $exploded = explode(' ', $arg, 2);
            array_push($this->array_args, trim($exploded[0]));
            array_push($this->array_args, trim($exploded[1]));
        }
    }

//    private function rmQts($string) {
//        return explode('"', $string, 3)[1];
//    }

    /**
     * Get a boolean
     * @param $k The key
     * @return null is no entry found, else the boolean
     */
    public function getB($k) {
        return $this->array_bools[$k];
    }

    /**
     * Get a String
     * @param $k The key
     * @return null is no entry found, else the string
     */
    public function getS($k) {
        return $this->array_strs[$k];
    }
}

/**
 * Class ParseException
 *
 * Exception that gets thrown when something fails to parse properly
 */
class ParseException extends Exception {

}