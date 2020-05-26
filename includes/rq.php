<?php
require("includes/settings.php");

require("classes/database.php");
require("classes/session.php");
require("classes/core.php");
require("classes/url.php");

$db = new Database;
$session = new Session;
$core = new Core;
$url = new Url;

$get = $url->getGET();

/**
 <form method="post" action="#">
    <input type="text" name="token"></input>
    <input type="text" name="status"></input>
    <input type="text" name="action"></input>
    <input type="submit" />
 </form>
 */

if ($url->getPage() == "rq") {
    $action = htmlspecialchars($_POST["action"]);
    $token = htmlspecialchars($_POST["token"]);

    $db->query("SELECT id FROM workers WHERE token = :token");
    $db->bind(":token", $token);

    if (!empty($token_row = $db->single())) {
        $worker_id = htmlspecialchars($token_row["id"]);

        switch ($action) {
            case "status":
                $status = htmlspecialchars($_POST["status"]);

                $db->query("UPDATE workers SET status = :status WHERE id = :id");
                $db->bind(":status", $status);
                $db->bind(":id", $worker_id);

                $db->execute();

                break;
            case "get_t_table": ?>
                <table border='1px solid black' class='table table-bordered table-hover grid subTable' style='color: black;'>
                    <thead>
                        <tr>
                            <th>Ing</th>
                            <th>Family</th>
                            <th>Rank</th>
                            <th>Pos</th>
                            <th>Status</th>
                            <th>(If owned) Villa protection</th>
                            <th>Kills</th>
                            <th>Bullets Shot</th>
                            <th>Last known City</th>
                            <th>Hospital time left</th>
                            <th>Travel time left (Estimated)</th>
                            <th>Comments</th>
                            <th>Shooter 1</th>
                            <th>Shooter 2</th>
                            <th>Shooter 3</th>
                            <th>Shooter 4</th>
                            <th>Backup 1</th>
                            <th>Backup 2</th>
                            <th>Backup 3</th>
                            <th>Backup 4</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $db->query("SELECT * FROM targets WHERE room_id = :id ORDER BY sort_order ASC");
                    $db->bind(":id", htmlspecialchars($_POST["room_id"]));

                    if (!empty($target_row = $db->resultset())) {
                        for ($x = 0;$x < count($target_row);$x++) {

                            if (substr($target_row[$x]["status"], 0, 5) === "Alive" && strlen($target_row[$x]["status"]) === 5) {
                                $status = "Offline" . "<br>[" . ltrim(htmlspecialchars($target_row[$x]["last_online"])) . "]";
                            } else {
                                $status = htmlspecialchars($target_row[$x]["status"]);
                            }

                            if ($target_row[$x]["kills"] == -1) {
                                $target_row[$x]["kills"] = "";
                            }
                            if ($target_row[$x]["bullets_shot"] == -1) {
                                $target_row[$x]["bullets_shot"] = "";
                            }
                        ?>
                         <tr style="cursor: pointer;">
                            <td><?php echo ucfirst(htmlspecialchars($target_row[$x]["name"])); ?></td>
                            <td><?php echo htmlspecialchars($target_row[$x]["family"]); ?></td>
                            <td><?php echo htmlspecialchars($target_row[$x]["rank"]); ?></td>
                            <td><?php echo htmlspecialchars($target_row[$x]["pos"]); ?></td>
                            <td><?php echo $status ?></td>
                            <td>100 def</td>
                            <td><?php echo htmlspecialchars($target_row[$x]["kills"]); ?></td>
                            <td><?php echo htmlspecialchars($target_row[$x]["bullets_shot"]); ?></td>
                            <td><?php if (isset($target_row[$x]["last_loc"])) { echo htmlspecialchars($target_row[$x]["last_loc"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["h_time"])) { echo htmlspecialchars($target_row[$x]["h_time"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["t_time"])) { echo htmlspecialchars($target_row[$x]["t_time"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["comments"])) { echo htmlspecialchars($target_row[$x]["comments"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["s1"])) { echo htmlspecialchars($target_row[$x]["s1"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["s2"])) { echo htmlspecialchars($target_row[$x]["s2"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["s3"])) { echo htmlspecialchars($target_row[$x]["s3"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["s4"])) { echo htmlspecialchars($target_row[$x]["s4"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["b1"])) { echo htmlspecialchars($target_row[$x]["b1"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["b2"])) { echo htmlspecialchars($target_row[$x]["b2"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["b3"])) { echo htmlspecialchars($target_row[$x]["b3"]); }; ?></td>
                            <td><?php if (isset($target_row[$x]["b4"])) { echo htmlspecialchars($target_row[$x]["b4"]); }; ?></td>
                        </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <?php

                break;
            case "get_version":
                $db->query("SELECT value FROM settings WHERE name = 'version'");
                $version_row = $db->single();

                echo htmlspecialchars($version_row["value"]);

                break;
            case "get_targets":
                $db->query("SELECT name FROM targets WHERE room_id = :id");
                $db->bind(":id", $_POST["room"]);

                $target_row = $db->resultset();

                if (!empty($target_row)) {
                    echo json_encode($target_row);
                } else {
                    echo "NTS";
                }

                break;
            case "update_targets":
                $room = htmlspecialchars($_POST["room"]);
                $name = htmlspecialchars($_POST["name"]);
                $family = htmlspecialchars($_POST["family"]);
                $status = htmlspecialchars($_POST["status"]);
                $villa = htmlspecialchars($_POST["villa"]);
                $rank = htmlspecialchars($_POST["rank"]);
                $pos = htmlspecialchars($_POST["pos"]);
                $kills = htmlspecialchars($_POST["kills"]);
                $bullets_shot = htmlspecialchars($_POST["bullets_shot"]);
                $last_online = htmlspecialchars($_POST["last_online"]);

                $db->query("SELECT status FROM targets WHERE name = :name");
                $db->bind(":name", htmlspecialchars($name));

                if (!empty($old_status_row = $db->single())) {
                    if (!empty($old_status_row["status"])) {
                        if ($old_status_row["status"] !== $status) {
                            if ($status === "Alive") {
                                $status = "OFFLINE";
                            } elseif ($status === "Alive and online") {
                                $status = "ONLINE";
                            } elseif ($status === "Died") {
                                $status = "DEAD";
                            }

                            echo $status;
                        }
                    }
                }

                /*
                if ($kills == -1) {
                    $kills = null;
                }
                if ($bullets_shot == -1) {
                    $bullets_shot = null;
                } */

                if ($villa === null) {
                    $villa = "";
                }

                //$db->query("UPDATE targets SET pos = ':pos', rank = ':rank', kills = ':kills', bullets_shot = ':bullets', family = ':family', status = ':status', villa_strength = ':villa', last_online = ':last_online' WHERE name = ':name'");
                $db->query("UPDATE targets SET pos = :pos, rank = :rank, kills = :kills, bullets_shot = :bullets, family = :family, status = :status, villa_strength = :villa, last_online = :last_online WHERE name = :name");
                $db->bind(":pos", $pos);
                $db->bind(":rank", $rank);
                $db->bind(":kills", $kills);
                $db->bind(":bullets", $bullets_shot);
                $db->bind(":family", $family);
                $db->bind(":status", $status);
                $db->bind(":villa", $villa);
                $db->bind(":last_online", $last_online);
                $db->bind(":name", $name);

                //echo "UPDATE targets SET pos = '" . $pos . "', rank = '" . $rank . "', kills = " . $kills . ", bullets_shot = '" . $bullets_shot . "', family = '" . $family . "', status = '" . $status . "', villa_strength = " . $villa . ", last_online = '" . $last_online . "' WHERE name = '" . $name . "'";

                $db->execute();

                break;
            case "get_room":
                $db->query("SELECT id FROM rooms WHERE worker_id = :id");
                $db->bind(":id", $worker_id);

                if (!empty($room_row = $db->single())) {
                     echo $room_row["id"];
                }

                break;
            default:
                exit();

                break;
        }
    } else {
        echo "Token Error";
        exit();
    }
}
