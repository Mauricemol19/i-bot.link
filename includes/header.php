<div class="row" id="head_row">
	<div class="col-md-12" id="head_container" style="text-align:left;">
		<div id="head_title">
            <h2 style="margin-top: 10px;margin-bottom:10px;margin-left: 5px;color:black;">Inori's (War)bot</h2>
		</div>
		<div class="sidebar-nav">
			<div class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle m_header_nav" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse sidebar-navbar-collapse">
					<ul class="nav navbar-nav a_hover" style="text-align: right">
                        <?php if (isset($_SESSION["a"])) { ?>
                            <li><a href="/index.php" style="color: white;">HOME</a></li>
                            <li><a href="/room" style="color: white;">ROOM</a></li>
                            <li><a href="/spots" style="color: white;">SPOTS</a></li>
                            <li><a href="/stats" style="color: white;">STATS</a></li>
                            <li><a href="/logout" style="color: white;">LOGOUT</a></li>
                            <?php
                        } else { ?>
						    <li><a href="/index.php" style="color: white;">HOME</a></li>
						    <li><a href="/login" style="color: white;">LOGIN</a></li>
                        <?php } ?>
					</ul>
				</div>
			</div>
		</div>		
	</div>
</div>
