<!DOCTYPE html>
<html lang="en" ng-app="getWeather">
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Weather In Paradise</title>
		<meta name="description" content="Get the weather for select cities, project for StraighterLine">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		
		<link rel="stylesheet" href="css/input2.css" media="all" />

	</head>
	<body data-spy="scroll">
		<div class="container" ng-controller="weatherCtrl">
			<div class="row projectHeader">
				<div class="headerTitle col-xs-12 col-sm-6 col-md-6"><h1>Find A Warmer Climate!</h1></div>
				<div class="headerMenu col-xs-12 col-sm-6 col-md-6">
					<h3>Select 1 or More cities:</h3>
					
					<form id="citySelectForm" action="" method="post" onsubmit="return false;">
						<span class="formOption"><input class="cityOption" type="checkbox" name="city[]" value="{{96801}}">{{city1}}</span>
						<span class="formOption"><input class="cityOption" type="checkbox" name="city[]" value="{{73301}}">{{city2}}</span>
						<span class="formOption"><input class="cityOption" type="checkbox" name="city[]" value="{{92101}}">{{city3}}</span>
						<span class="formOption"><input type="submit" name="submit" class="btn-info" id="citySelectSubmit"  value="Submit"/></span>
					
					</form>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
			
			
			
					<nav class="navbar navbar-inverse displayNone" id="navbarList">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#weatherNavBar">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>                        
								</button>
								<a class="navbar-brand" href="#">Selectecd Weather</a>
							</div>
							<div>
								<div class="collapse navbar-collapse" id="weatherNavBar">
									<ul class="nav navbar-nav" id="navbarLinks">

									</ul>
								</div>
							</div>
						</div>
					</nav>
							
				</div>
			
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="row" id="weatherContent">
					
					</div>
				</div>
				
			</div>
			<div class="row projectFooter">
				<div class="footer col-xs-12 col-sm-12 col-md-12">
					<h4>Thank you for using this service to find a warmer climate. Get out of the snow and enjoy!</h4>
				</div>
				
			</div>
		</div>
		<script>
			var app = angular.module('getWeather', []);
			app.controller('weatherCtrl', function($scope) {
				$scope.city1 = "Honolulu, HI";
				$scope.city2 = "Austin, TX";
				$scope.city3 = "San Diego, CA";
				$scope.zip1 = "96801";
				$scope.zip2 = "73301";
				$scope.zip3 = "92101";
			});
		</script>
	</body>
</html>