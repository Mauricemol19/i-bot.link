<?php
/**
 * Class Session
 *
 * Class for storing and managing sessions in a database using PDO
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

class Session {
    private $db;

    public function __construct() {
        $this->db = new Database;

        session_set_save_handler(
            array($this, "_open"),
            array($this, "_close"),
            array($this, "_read"),
            array($this, "_write"),
            array($this, "_destroy"),
            array($this, "_gc")
        );

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * open()
     *
     * @return boolean
     */
    public function _open() {
        if ($this->db) {
            return true;
        }
        return false;
    }

    /**
     * _close()
     *
     * @return boolean
     */
    public function _close() {
        if ($this->db = null) {
            return true;
        }
        return false;
    }

    /**
     * _read()
     *
     * @param int $id
     *
     * @return string
     */
    public function _read($id) {
        $this->db->query('SELECT data FROM sessions WHERE id = :id');
        $this->db->bind(':id', $id);

        if (!empty($row = $this->db->single())) {
            return $row['data'];
        } else {
            return '';
        }
    }

    /**
     * _write()
     *
     * @param int $id
     * @param string $data
     *
     * @return bool
     */
    public function _write($id, $data) {
        $access = time();
        $ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
        $page = htmlspecialchars($_SERVER['REQUEST_URI']);
		$protocol = htmlspecialchars($_SERVER["REQUEST_METHOD"]);
		$method = htmlspecialchars($_SERVER["REQUEST_METHOD"]);

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_a = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
        } else {
            $user_a = "none";
        }

        $t = time();
        $current_date = date('Y-m-d H:i', $t);

        //Set query
        $this->db->query('REPLACE INTO sessions VALUES (:id, :access, :data, :ip, :req, :date, :user)');

        //Bind data
        $this->db->bind(':id', $id);
        $this->db->bind(':access', $access);
        $this->db->bind(':data', $data);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':req', $page);
        $this->db->bind(':date', $current_date);
        $this->db->bind(':user', $user_a);

        if ($this->db->execute()) {
            return true;
        }

        return false;
    }

    /**
     * _destroy()
     *
     * @param int $id
     *
     * @return boolean
     */
    public function _destroy($id) {
        //Set query
        $this->db->query('DELETE FROM sessions WHERE id = :id');

        //Bind data
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        }

        return false;
    }

    /**
     * _gc()
     *
     * @param int $max
     *
     * @return boolean
     */
    public function _gc($max) {
        $old = time() - $max;

        $this->db->query('DELETE FROM sessions WHERE access < :old');

        $this->db->bind(':old', $old);

        if ($this->db->execute()) {
            return true;
        }

        return false;
    }
}
?>