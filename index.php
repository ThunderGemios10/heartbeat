

<?php


// session_start();
if(!(isset($_SESSION["valid"]))) {
		// header("location: error.php");
	// echo '1'; exit;
	// require "index-notsignedin.php";
	 include 'header.php' ;
	 include 'navigation.php';
	 // echo '<div ng-view></gmp_div() >';

	 echo '
	 	<div class="content">
			<section id="coa">
				<div class="container">
					<div class="row">
						<div class="col-lg-3"></div>
						<div class="col-lg-6">
							<div class="message">
								<div class="message-header">
									<p><img src="images/hb-logo-coa.png"> Heart<span>beat</span></p>
								</div>
								<div class="message-subtitle">
									<p>Dating for YouTubers!</p>
								</div>
								<div class="message-footer">							
									<a id="explore2" href="" scrollto="#lt-pulse" class="btn btn-default btn-lg btn-lrn">Learn more</a>					
									<a href="login.php" class="btn btn-default btn-lg btn-gplus">Sign in with Google+</a> 
									<a href="sneakpeek.php" class="btn btn-default btn-lg btn-lrn">Sneak peek</a>			
								</div>
							</div>
							<div class="col-lg-3"></div>
						</div>

					</div>
				</section>

				<section id="lt-pulse">
					<div class="container">
						<div class="row">
							<div class="latest-pulse">
								<div class="title">
									<h1>Latest Heartbeats</h1>

								</div>
								<div class="body">
									<div class="col-lg-3"><img src="images/pulse-video-1.jpg"><p><h3>Video Title</h3><span>/username</span></p></div>
									<div class="col-lg-3"><img src="images/pulse-video-2.jpg"><p><h3>Video Title</h3><span>/username</span></p></div>
									<div class="col-lg-3"><img src="images/pulse-video-3.jpg"><p><h3>Video Title</h3><span>/username</span></p></div>
									<div class="col-lg-3"><img src="images/pulse-video-4.jpg"><p><h3>Video Title</h3><span>/username</span></p></div>
								</div>
							</div>
						</div>
					</div>
				</section>

				<section id="hb-feat">
					<div class="container">
						<div class="row">
							<div class="feat">
								<div class="title"></div>
								<div class="body">
									<div class="col-lg-4">
										<i class="fa fa-youtube-play fa-6x"></i>
										<h1>Watch</h1>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
									<div class="col-lg-4">
										<i class="fa fa-level-up fa-6x"></i>
										<h1>Rank</h1>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua.</p>								
									</div>
									<div class="col-lg-4">
										<i class="fa fa-group fa-6x"></i>
										<h1>Connect</h1>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

				<section id="about">
					<div class="container">
						<div class="row">
						<div class="ab-hb">
						<div class="col-lg-6">
							<div class="title">
								<h1>Heart<span>beat</span> is in development.</h1>
								<h2>Help us grow!</h2>
								<a href="login.php" class="btn btn-default btn-lg">Spread it like peanut butter!</a>
							</div>					
						</div>
						<div class="col-lg-6">
							<div class="body">
							  <div class="stage">
							  <div class="circle"></div>
							    <div class="heart">
							      <div class="left"></div>
							      <div class="right"></div>
							    </div>  
							</div>							
							</div>					
						</div>

						</div>
						</div>
					</div>
				</section>

		<!-- 		<section id="slide5">
					
				</section> -->
			</div>
	 ';
	 include 'footer.php';
	// require 'index-signedin.php';
}
else {
	// echo '2'; exit;
	// $_SESSION["guest"]=true;
	include 'index-signedin.php';
	 // include 'header.php' ;
	 // include 'navigation.php';
	 // echo '<div ng-view></div>';
	 // include 'footer.php';
}

 ?>

