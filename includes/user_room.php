<?php
$db->query("SELECT room_id FROM users WHERE id = :id");
$db->bind(":id", $user);

if (!empty($rooms_row = $db->single())) { ?>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div style='position: inherit;'><table border='1px solid black' class='table table-bordered table-hover grid subTable' style='color: black;'>
                <thead>
                    <tr>
                        <th></th>
                        <th>Ing</th>
                        <th>Family</th>
                        <th>Status</th>
                        <th>On</th>
                        <th>Rank</th>
                        <th>Villa protection</th>
                        <th>Last known City</th>
                        <th>Comments</th>
                        <th>Hospital time left</th>
                        <th>Travel time left (Estimated)</th>
                        <th>Shooter1</th>
                        <th>Shooter2</th>
                        <th>Shooter3</th>
                        <th>Shooter4</th>
                        <th>Backup1</th>
                        <th>Backup2</th>
                        <th>Backup3</th>
                        <th>Backup4</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $db->query("SELECT * FROM targets WHERE room_id = :id ORDER BY sort_order ASC");
                $db->bind(":id", $rooms_row["room_id"]);

                if (!empty($target_row = $db->resultset())) {
                    $last_online =  htmlspecialchars($target_row["name"]);
                    $online_status = "";
                    ?>
                     <tr style="cursor: pointer;">
                        <td><?php echo htmlspecialchars($target_row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($target_row["family"]); ?></td>
                        <td><?php echo htmlspecialchars($target_row["status"]); ?></td>
                        <td><?php echo $online_status; ?></td>
                        <td><?php echo htmlspecialchars($target_row["rank"]); ?></td>
                        <td>100 def</td>
                        <td><?php if (isset($target_row["last_loc"])) { echo htmlspecialchars($target_row["last_loc"]); }; ?></td>
                        <td><?php if (isset($target_row["comments"])) { echo htmlspecialchars($target_row["comments"]); }; ?></td>
                        <td><?php if (isset($target_row["h_time"])) { echo htmlspecialchars($target_row["h_time"]); }; ?></td>
                        <td><?php if (isset($target_row["t_time"])) { echo htmlspecialchars($target_row["t_time"]); }; ?></td>
                        <td><?php if (isset($target_row["last_loc"])) { echo htmlspecialchars($target_row["last_loc"]); }; ?></td>
                        <td><?php if (isset($target_row["s1"])) { echo htmlspecialchars($target_row["s1"]); }; ?></td>
                        <td><?php if (isset($target_row["s2"])) { echo htmlspecialchars($target_row["s2"]); }; ?></td>
                        <td><?php if (isset($target_row["s3"])) { echo htmlspecialchars($target_row["s3"]); }; ?></td>
                        <td><?php if (isset($target_row["s4"])) { echo htmlspecialchars($target_row["s4"]); }; ?></td>
                        <td><?php if (isset($target_row["b1"])) { echo htmlspecialchars($target_row["b1"]); }; ?></td>
                        <td><?php if (isset($target_row["b2"])) { echo htmlspecialchars($target_row["b2"]); }; ?></td>
                        <td><?php if (isset($target_row["b3"])) { echo htmlspecialchars($target_row["b3"]); }; ?></td>
                        <td><?php if (isset($target_row["b4"])) { echo htmlspecialchars($target_row["b4"]); }; ?></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
            </div>
        <div class="col-lg-1"></div>
    </div>
    <?php }