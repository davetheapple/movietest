<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- jQuery Datatables -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/jquery.dataTables.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script>

        <!-- AngularJS 1.x -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>

        <!-- Bootstrap -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- Angular-Datatables -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.2/angular-datatables.min.js"></script>
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.2/css/angular-datatables.min.css" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.2/plugins/bootstrap/datatables.bootstrap.min.css" />

        <!-- Custom files -->
        <link rel="stylesheet" href="css/main.css">
        <script src="js/app.js"></script>

    </head>
    <body>
        <div ng-app="MovieApp">
        	<div ng-controller="movieController as ctrl">
        		<table datatable="" dt-options="ctrl.dtOptions" dt-columns="ctrl.dtColumns" class="row-border hover" ></table>
        		<!-- Modal -->
				<div id="detail" class="modal fade" role="dialog" >
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content" style="background-image: url({{selected.backdrop_path}});">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">{{selected.title_reg}}</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4">
										<img ng-src="{{selected.poster_path}}" class="poster">
									</div>
									<div class="col-md-8 col-sm-8"><p class="desc">{{selected.overview}}</p></div>
								</div>
								
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>

					</div>
				</div>
        	</div>
        </div>
    </body>
</html>