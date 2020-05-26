<?php

/**
 * Maurice Mol
 * mauricemol@hotmail.nl
 */

//TODO:
// BG recommendations?

if (isset($_POST["token"]) && isset($_POST["action"])) {
    require ("includes/rq.php");
    exit();
}

require('includes/setup.php');

if ($url->getPage() == "logout") {
    if (isset($_SESSION["a"])) {
        unset($_SESSION["a"]);
    } else {
        header("Location: index.php");
        exit();
    }
}

//Login admin
if (isset($_POST["username"]) && isset($_POST["pass"])) {
    if (!empty($_POST["username"]) && !empty($_POST["pass"])) {
        $username = htmlspecialchars($_POST["username"]);
        $pass = htmlspecialchars($_POST["pass"]);

        if (!$core->login($username, $pass)) {
            $loginError = "Something went wrong, please try again.";
        }
    }
}

//Post from sorting targets in room
if (isset($_POST["sort"]) && isset($_POST["token"])) {
    if ($_POST["token"] === $a_token_row["token"]) {
        $sort = htmlspecialchars($_POST["sort"]);
        $sort = explode(",", $sort);
        $sort = array_filter($sort);
        $sort = array_values($sort);

        $db->beginTransaction();
        $db->query('UPDATE targets SET sort_order = :sort WHERE id = :id');

        for ($i = 0; $i < count($sort); $i++) {
            $db->bind(":id", $sort[$i]);
            $db->bind(":sort", $i);

            echo "ID: " . $sort[$i];
            echo "Index: " . $i;

            $db->execute();
        }
        $db->endTransaction();
    }
}

?>
<div class="container-fluid" id="wrapper" style="padding-left: 0;padding-right: 0;">

    <?php
    require('includes/header.php');

    echo "<div data-sticky-wrap style='overflow: hidden;margin-left: 15px;margin-right: 15px;'>";

    if (isset($_SESSION["u"]) && !isset($_SESSION["a"])) {
        $user = htmlspecialchars($_SESSION["u"]);

        if ($url->getPage() == "room") {
            require("includes/user_room.php");
            exit();
        }
    } elseif (!isset($_SESSION["u"]) && isset($_SESSION["a"])) {
        $admin_id = htmlspecialchars($_SESSION["a"]);

        if ($url->getPage() == "room") {
            require("includes/admin_room.php");
            exit();
        } else {
             ?>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <h3>Welcome, please select one of the options from the menu.</h3>
                </div>
                <div class="col-lg-2"></div>
            </div> <?php
        }
    } else {
        //Login
        if ($url->getPage() == "login") {
            ?>
            <div class="row">
                <br><br>
                <div class="col-lg-4"></div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <form action="#" method="post">
                            <label for="username">Login:</label>
                            <input type="text" placeholder="Username" class="form-control"
                                   name="username" id="username"
                                   value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" required>
                            <br>
                            <label for="pass">Wachtwoord:</label>
                            <input type="password" placeholder="Wachtwoord"
                                   class="form-control" name="pass" id="pass" required><br>
                            <button type="submit" class="form-control" name="login_submit">Inloggen</button>
                            <?php
                            if (isset($loginError)) {
                                echo $loginError;
                            }
                            ?>
                        </form>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-4"></div>
            </div> <?php
        } else { ?>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <h3>Not in session, please use !login in a whatsapp group with an i-bot and active war
                        mode.</h3>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <?php
        }
    }
    ?>

    <div class="push"></div>
    </div>
    <?php

    require('includes/footer.php');

?>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

</body>
</html>
