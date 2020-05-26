<?php
$db->query("SELECT token FROM admin WHERE id = :id");
$db->bind(":id", $admin_id);

$a_token_row = $db->single();

//Post add room
if (isset($_POST["s_room"]) && isset($_POST["token"])) {
    $room_token = $_POST["token"];

    if ($room_token == $a_token_row["token"]) {
        $db->query("SELECT worker_id FROM admin WHERE id = :id");
        $db->bind(":id", $admin_id);

        if (!empty($admin_w_row = $db->single())) {
            $db->query("INSERT INTO rooms (admin_id, worker_id) VALUES (:a_id, :w_id)");
            $db->bind(":a_id", $admin_id);
            $db->bind(":w_id", $admin_w_row["worker_id"]);

            $db->execute();
        }
    }
}

//Post add target
if (isset($_POST["ing"]) && isset($_POST["token"]) && isset($_POST["add_t"])) {

    if (isset($_POST["ing"])) {
        $token = $_POST["token"];

        if ($a_token_row["token"] == $token) {

            if (!empty($_POST["ing"])) {
                $add_ing = htmlspecialchars($_POST["ing"]);
                $room = htmlspecialchars($_POST["room"]);

                $s1 = (!empty($_POST["s1"])) ? htmlspecialchars($_POST["s1"]) : "";
                $s2 = (!empty($_POST["s2"])) ? htmlspecialchars($_POST["s2"]) : "";
                $s3 = (!empty($_POST["s3"])) ? htmlspecialchars($_POST["s3"]) : "";
                $s4 = (!empty($_POST["s4"])) ? htmlspecialchars($_POST["s4"]) : "";

                $b1 = (!empty($_POST["b1"])) ? htmlspecialchars($_POST["b1"]) : "";
                $b2 = (!empty($_POST["b2"])) ? htmlspecialchars($_POST["b2"]) : "";
                $b3 = (!empty($_POST["b3"])) ? htmlspecialchars($_POST["b3"]) : "";
                $b4 = (!empty($_POST["b4"])) ? htmlspecialchars($_POST["b4"]) : "";

                $comments = (!empty($_POST["comments"])) ? $_POST["comments"] : "";

                $cURLConnection = curl_init();

                curl_setopt($cURLConnection, CURLOPT_URL, 'https://d-bot.net/statistics/nl/ingame/?ingame=' . $add_ing);
                //curl_setopt($cURLConnection, CURLOPT_URL, 'https://d-bot.net/statistics/nl/ingame/?ingame=monster');
                curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

                $phoneList = curl_exec($cURLConnection);
                curl_close($cURLConnection);

                $stat_array = array();

                //TODO: check if target actually exists

                foreach (str_get_html($phoneList)->find('#userinfo > div:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(3)') as $l_tr) {
                    array_push($stat_array, html_entity_decode($l_tr));
                };

                for ($i = 0;$i < count($stat_array);$i++) {
                    switch ($i) {
                        case 0:
                            /*
                            $status = substr(strip_tags($stat_array[$i]), 6, strlen($stat_array[$i]) - 6);

                            if ($status === "Alive") {
                                $status = "OFFLINE";
                            }
                            if ($status === "Alive and online") {
                                $status = "ONLINE";
                            }
                            if ($status === "Died") {
                                $status = "DEAD";
                            } */

                            break;
                        case 2:
                            $rank = substr(strip_tags($stat_array[$i]), 4, strlen($stat_array[$i]) - 4);

                            break;
                        case 3:
                            $family = substr(strip_tags($stat_array[$i]), 6, strlen($stat_array[$i]) - 6);

                            break;
                        case 8:
                            $last_online = substr(strip_tags($stat_array[$i]), 15, strlen($stat_array[$i]) - 15);

                            break;
                        case 13:
                            if (strlen(strip_tags($stat_array[$i + 1])) > 0) {
                                //14 exists
                                $pos = substr(strip_tags($stat_array[$i + 1]), 10, strlen($stat_array[$i]) - 10);
                            } else {
                                $pos = substr(strip_tags($stat_array[$i]), 17, strlen($stat_array[$i]) - 17);
                            }

                            break;
                    }
                }

                $db->query("INSERT INTO targets (room_id, name, pos, rank, family, last_online, s1, s2, s3, s4, b1, b2, b3, b4, comments) 
                                VALUES (:r_id, :name, :pos, :rank, :family, :last_online, :s1, :s2, :s3, :s4, :b1, :b2, :b3, :b4, :comments)");
                $db->bind(":r_id", $room);
                $db->bind(":name", $add_ing);
                $db->bind(":pos", $pos);
                $db->bind(":rank", $rank);
                $db->bind(":family", $family);
                //$db->bind(":status", $status);
                $db->bind(":last_online", $last_online);
                $db->bind(":s1", $s1);
                $db->bind(":s2", $s2);
                $db->bind(":s3", $s3);
                $db->bind(":s4", $s4);
                $db->bind(":b1", $b1);
                $db->bind(":b2", $b2);
                $db->bind(":b3", $b3);
                $db->bind(":b4", $b4);
                $db->bind(":comments", $comments);

                $db->execute();
            }
        }
    }

    exit();
}

$db->query("SELECT id FROM rooms WHERE admin_id = :id");
$db->bind(":id", $admin_id);

if (!empty($rooms_row = $db->single())) {
    $db->query("SELECT value FROM settings WHERE name = 'version'");

    $version_row = $db->single();
    ?>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <h3 style="margin-top: 0;">Version: <?php echo $version_row["value"]; ?></h3>
            <div class="table-responsive" style='position: inherit;'>
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
                    $db->bind(":id", $rooms_row["id"]);

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
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <br>
        <hr style="border-color: #555151;"/>
    <br>

    <div class="row" style="margin-left: 25px;">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <h3>Add new target</h3><br>
            <form method="post" action="#" id="a_add_target">
                <label for="ing">Ingame</label>
                <input type="text" name="ing" id="ing" class="form-control" style="width: 15%;" required>

                <label for="s1">Shooter 1</label>
                <input type="text" name="s1" id="s1" class="form-control" style="width: 15%;">
                <label for="s2">Shooter 2</label>
                <input type="text" name="s2" id="s2" class="form-control" style="width: 15%;">
                <label for="s3">Shooter 3</label>
                <input type="text" name="s3" id="s3" class="form-control" style="width: 15%;">
                <label for="s4">Shooter 4</label>
                <input type="text" name="s4" id="s4" class="form-control" style="width: 15%;">

                <label for="b1">Backup 1</label>
                <input type="text" name="b1" id="b1" class="form-control" style="width: 15%;">
                <label for="b2">Backup 2</label>
                <input type="text" name="b2" id="b2" class="form-control" style="width: 15%;">
                <label for="b3">Backup 3</label>
                <input type="text" name="b3" id="b3" class="form-control" style="width: 15%;">
                <label for="b4">Backup 4</label>
                <input type="text" name="b4" id="b4" class="form-control" style="width: 15%;">

                <label for="comments">Comments</label>
                <textarea name="comments" id="comments" class="form-control"></textarea>

                <br>

                <input type="hidden" name="room" value="<?php echo $rooms_row["id"]; ?>">
                <input type="hidden" name="add_t" value="">
                <input type="hidden" name="token" value="<?php echo $a_token_row["token"]; ?>">
                <button type="submit" name="s_a_t" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Add Target</button>
            </form>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <br>
    <br>

    <?php
} else {
    ?>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <h3>No room active</h3>
            <form method="post" action="/room">
                <input type="hidden" name="token" value="<?php echo $a_token_row["token"]; ?>">
                <button type="submit" name="s_room" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Start Room</button>
            </form>
        </div>
        <div class="col-lg-2"></div>
    </div>
    <?php
}
?>

<div class="push"></div>
</div>
<?php

require('includes/footer.php');

if (empty($rooms_row["id"])) {
    $room_id = 0;
} else {
    $room_id = $rooms_row["id"];
}

?>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

<script>
    $(function() {
        $(".subTable tbody").sortable({
            scroll: false,
            helper: fixHelperModified,
            stop: updateIndex,
            sort: function(event, ui) {
                var $target = $(event.target);
                if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                    var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) / 2);
                    ui.helper.css({'top' : top + 'px'});
                }},
            update: function(event, ui) {
                saveOrderClick();
            }
        }).disableSelection();

        $("#a_add_target").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url : $(this).attr('action') || window.location.pathname,
                type: "POST",
                data: $(this).serialize(),
                success: function (data) {
                    $("#form_output").html(data);

                    up_timer();
                },
                error: function (jXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });

        let update_timer = setInterval(function () {
            up_timer();
        }, 5000);

        function up_timer() {
            $.ajax({
                url: "/rq",
                type: "POST",
                data: { room_id: <?php echo $room_id; ?>, action: "get_t_table", token: "<?php echo $a_token_row["token"]; ?>"},
                success: function (data) {
                    $(".table-responsive").html(data);
                },
                error: function (jXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
});

var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
updateIndex = function(e, ui) {
    $('td.index', ui.item.parent()).each(function (i) {
        $(this).html(i + 1);
    });
};

function saveOrderClick() {
    // ----- Retrieve the li items inside our sortable list
    var i = 1;

    $(".subTable").each(function () {
        var items = $("#sort" + i + " tbody td");

        var sort = [];
        var index = 0;

        // ----- Iterate through each li, extracting the ID embedded as an attribute
        items.each( function(intIndex) {
            if ($(this).attr("id")) {
                sort[index] = $(this).attr("id");
                index++;
            }
        });

        var room = "<?php if (isset($rooms_row["id"])) { echo $rooms_row["id"]; }; ?>";

        $.post( "/room", { sort: sort.join(","), token: "<?php echo $a_token_row["token"]; ?>", room: room } ).done(function( data ) {});

        i++;
    });
}
</script>

</body>
</html>

