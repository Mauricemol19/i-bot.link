<?php
/**
 * Class URL
 *
 * Retrieves user input url and stores it in different global vars.
 * Also accepts get parameters, accessible via $url->$get["name"]["param"]
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

Class Url {
    private $base;
    private $page;
    private $get = array();
    private $vars_array = array();

    public function __construct() {
        $elements = $this->getURL();
		
        $count = count($elements);

        if ($count !== 0) {
            if (isset($elements[$count - 1])) {
                $last = $elements[$count - 1];
                if (strpos($last, '?') !== false) {
                    $elements[$count - 1] = $this->stripGet($last, $count);
                }
            }
        }

        if (empty($elements)) {
            return;
        }
		
        if (!$this->checkList($count, $elements) || $count > 3) {
			//$path = ltrim($_SERVER['REQUEST_URI'], '/');
			//$path = htmlspecialchars($path);
			
            include ("404.php");
		    die();
        }
    }

    /**
     * checklist()
     *
     * @param integer $count
     * @param array[string] $elements
     *
     * @return boolean
     */
    private function checklist($count, $elements) {
		$mainPages = [
			"index.php",
			"room",
			"stats",
			"spots",
			"rq",
			"logout",
			"login"
		];
        
		if (in_array($elements[0], $mainPages) && $count < 2) {
			if ($count == 0 || $elements[0] == "") {
				$this->page = "index.php";
			} else {
				$page = str_replace('%20', ' ', $elements[0]);
				$this->page = $page;
			}
			return true;
		}

        return false;
    }

    /**
     * getURL()
     * Retrieves server url
     *
     * @return array[string]
     */
    private function getURL() {
        $path = ltrim($_SERVER['REQUEST_URI'], '/');
        $path = htmlspecialchars($path);
        $elements = explode('/', $path);

        for ($i = count($elements) - 1; $i > 0; $i--) {
            $elements[$i] = rawurldecode($elements[$i]);
        }

        return array_filter($elements);
    }

    /**
     * stripGet()
     * Strips and stores all GET vars in $url->$get
     *
     * @param string $last
     * @param integer $count
     *
     * @return string
     */
    private function stripGet($last, $count) {
        $vars = explode("?", $last);
        $first = $vars[0];

        if ($count > 0) {
            $last = array_pop($vars);
        }

        if (strpos($last, '&') !== false) {
            //Multiple get vars
            $Gvars = explode("&", $last);

            foreach ($Gvars as $v) {
                $temp = explode("=", $v);
                $temp[0] = str_replace("&", " ", $temp[0]);
                $temp[0] = str_replace("amp;", " ", $temp[0]);

                if (empty($temp[0]) || empty($temp[1])) {
                    include ("404.php");
		            die();
                }

                $tempArray = array("name" => $temp[0], "param" => $temp[1]);
                array_push($this->vars_array, $tempArray);
            }

            //print_r($this->vars_array);
            $this->get = $this->vars_array;
        } else {
            //One get var
            $vars = explode("=", $last);
            $vars = array_filter($vars);

            if (count($vars) == 1 || count($vars) == 0) {
                include ("404.php");
		        die();
            }

            $vars = array("0" => array("name" => $vars[0], "param" => $vars[1]));
            $this->get = $vars;
        }

        return $first;
    }

    /**
     * getBase()
     *
     * @return string
     */
    public function getBase() {
        return $this->base;
    }

    /**
     * getPage()
     *
     * @return string
     */
    public function getPage() {
        return htmlspecialchars($this->page);
    }

    /**
s     * getGET()
     *
     * @return array[string]
     */
    public function getGET() {
        return $this->get;
    }

}