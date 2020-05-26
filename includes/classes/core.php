<?php
/**
 * Class Core
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

class Core {
    function login($username, $pass) {
        global $db;

        $db->query("SELECT id, password_hash, attempts FROM admin WHERE name = :name");
        $db->bind(":name", $username);

        if (!empty($row = $db->single())) {
            if (!empty($row["password_hash"]) && $row["attempts"] <= 7) {
                $pass_hash = $row["password_hash"];

                if (password_verify($pass, $pass_hash)) {
                    $_SESSION["a"] = $row["id"];

                    $db->query("UPDATE admin SET attempts = 0 WHERE name = :name");
                    $db->bind(":name", $username);
                    $db->execute();

                    return true;
                }
            }
        }

        $db->query("UPDATE admin SET attempts = attempts + 1 WHERE name = :name");
        $db->bind(":name", $username);
        $db->execute();

        return false;
    }

	/**
	 * er_log()
	 *
     * @param string $message
	 */
	function er_log($message) {
		global $url;
		
		$path = ERROR_LOG_PATH;
		$message = $url->getPage() . "| [" . date("Y-m-d H:i:s") . "] " . URL . " : " . $message . "\n";
		
		error_log($message, 3, $path . "error_log-" . URL_NAME . ".log");
	}

    /**
     * errorRegister()
     *
     * @return string
     */
    function errorRegister() {
        return "<b>Één of meer velden zijn niet volledig/goed ingevuld, probeer het opnieuw.</b>";
    }

    /**
     * error404()
     *
     * @return string
     */
	function error404() {
		include ("404.php");
		die();
	}
}

?>