<?php
	session_start();
	if(isset($_SESSION["userlevel"])) {
		if($_SESSION["userlevel"]!='admin') {
			//header("location: error.php");
		}		
	}
?>
<div class="row">
	<div class="col-xs-12">	
		<div class="container col-lg-2">
			<input class="form-control" id="focusedInput" type="text" ng-model="search" placeholder="Search Name">
			<br/>
			<div id="scrollableDivForTags" class="boxed tagsContainerSettings has-padding-sm-more-left">									
				<a ng-animate="'animate'" class="btn has-margin col-lg-12 has-padding-less btn-md btn-default has-margin no-border" ng-repeat="user in filteredData = (userlist | filter:search | orderBy:['authtype','authname'])" href="" ng-class="{'active-yellow':activeRow._id.$id==user._id.$id,'bg-red':user.authtype=='admin','bg-blue':user.authtype=='domain'}" ng-click="changeSelected(user)">
					<div ng-switch="user.authtype">
			 			<span ng-switch-when="domain">{{user.authemail}}</span>
						<span ng-switch-default>{{user.authname}}</span>
					</div>
				</a>
			</div>
			<hr/>
		</div>
		<div class="container col-lg-6">
			<div class="container">
				<h4 class="pull-left"><span class="text-info">Heartbeat</span> Users:</h4>
				<div class="pull-right">
					<a href="" ng-click="show('add')" data-toggle="modal" class="btn btn-default" ng-class="{'active-yellow': showAdd}"><i class="glyphicon glyphicon-plus"></i></a>
					<a href="" ng-click="show('edit')" class="btn btn-default" ng-class="{'active-yellow': showEdit, disabled: activeRow._id.$id==null}"><i class="glyphicon glyphicon-pencil"></i></a>
					<a href="" ng-show="activeRow.status==1" ng-click="deactivateTag(activeRow._id.$id)" class="btn btn-default" ng-class="{'active':statusBtnLoading,'disabled':activeRow._id.$id==null}"><i class="glyphicon glyphicon-trash"></i></a>
					<a href="" ng-hide="activeRow.status==1" ng-click="activateTag(activeRow._id.$id)" class="btn btn-default" ng-class="{'active':statusBtnLoading,'disabled':activeRow._id.$id==null}"><i class="glyphicon glyphicon-refresh"></i></a>
				</div>
			</div>
			<br/>
			<!-- <pre>{{activeRow | json}}</pre> -->
			<div class="container col-lg-12" ng-show="showView==true">
				<table class="table table-bordered" ng-show="activeRow._id.$id">
					<thead>
						<tr>
						  <th colspan="3">User Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						  	<td class="col-md-1">							  		
								<span class="pull-right">
									Name
								</span>
						 	</td>
						 	<td class="col-md-3">
								<span ng-dblclick="show('edit')">
									{{activeRow.authname}}
								</span>
						 	</td>
						</tr>
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">
									Authentication Type
								</span>
						 	</td>
						 	<td class="col-md-3">
								<a ng-dblclick="show('edit')">
									{{activeRow.authtype}}
								</a>
						 	</td>
						</tr>					
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">
									Project
								</span>
						 	</td>
						 	<td class="col-md-3">
								<span ng-dblclick="show('edit')">
									{{activeRow.project}}
								</span>
						 	</td>
						</tr>	
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">
									e-mail
								</span>
						 	</td>
						 	<td class="col-md-3">
								<span ng-dblclick="show('edit')">
									{{activeRow.authemail}}
								</span>
						 	</td>
						</tr>	
					</tbody>
				</table>			
				<table class="table table-bordered" ng-hide="activeRow._id.$id">
					<thead>
						<tr>
						  <th>Tag Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						  	<td class="col-md-1">							  		
								<span class="pull-left">
									None selected...
								</span>
						 	</td>						 	
						</tr>						
					</tbody>
				</table>				
			</div>		
			<div class="container col-lg-12" ng-show="showAdd==true">
				<form ng-submit="saveNewUser(newRow);">
					<table class="table table-bordered">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-pencil"></i> User Account Edit</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							  	<td class="col-md-1">							  		
									<span class="pull-right">
										Name
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="newRow.authname" required class="form-control color-red no-border clean-form-control" placeholder="Albert Einstein">							 		
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Authentication Type
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
							 		<select class="form-control no-border clean-form-control" ng-model="newRow.authtype" required ng-options="item for item in authtypes"></select>
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Project
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="newRow.project" required class="form-control color-red no-border clean-form-control" placeholder="heartbeat">
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										e-mail
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="newRow.authemail" required class="form-control color-red no-border clean-form-control" placeholder="e-mail">
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Status
									</span>
							 	</td>
							 	<td class="col-md-3">
							 		<span class="label label-success">Active</span>
							 	</td>
							</tr>
						</tbody>
					</table>				
					<div class="pull-right">
						<button type="button" class="btn btn-default no-border-radius" ng-click="show('cancelEdit');activeRow=tempRow">Cancel</button>
			       		<button type="submit" class="btn btn-primary no-border-radius">Save changes</button>
		       		</div>
	       		</form>
	       		<!-- <pre>{{newTag | json}}</pre> -->
			</div>
			<div class="container col-lg-12" ng-show="showEdit==true">
				<form ng-submit="saveEditUser(editRow);">
					<table class="table table-bordered">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-pencil"></i> User Account Edit</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							  	<td class="col-md-1">							  		
									<span class="pull-right">
										Name
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="editRow.authname" required class="form-control color-red no-border clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!">							 		
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Authentication Type
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
							 		<select class="form-control no-border clean-form-control" ng-model="editRow.authtype" ng-options="item for item in authtypes"></select>
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Project
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="editRow.project" required class="form-control color-red no-border clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!">
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										e-mail
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" dbl name="description" ng-model="editRow.authemail" required class="form-control color-red no-border clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!">
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Status
									</span>
							 	</td>
							 	<td class="col-md-3">
							 		<div ng-switch="editRow.status">
										<div ng-switch="activeRow.status">
								 			<span class="label label-success" ng-switch-when="1">Active</span>
											<span class="label label-Danger" ng-switch-when="0">Inactive</span>
											<span ng-switch-default> - </span>						
										</div>
									</div>
							 	</td>
							</tr>
						</tbody>
					</table>				
					<div class="pull-right">
						<button type="button" class="btn btn-default no-border-radius" ng-click="show('cancelEdit');">Cancel</button>
			       		<button type="submit" class="btn btn-primary no-border-radius">Save changes</button>
		       		</div>
	       		</form>
			</div>
		</div>	
	</div>	
</div>   

