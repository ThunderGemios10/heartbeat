<?php
	session_start();
	if(isset($_SESSION["userlevel"])) {
		if($_SESSION["userlevel"]!='admin') {
			//header("location: error.php");
		}		
	}
	else{
		//header("location: error.php");
	}
?>
<div class="row">
	<div class="col-xs-12">	
		<div class="container col-lg-2">
			<input class="form-control" id="focusedInput" type="text" ng-model="searchTag.name" placeholder="Search Tag">			
			  <label>
			    <input type="checkbox" ng-model="statusOnly">
			   	Active Only
			  </label>
			<br/>
			<div id="scrollableDivForTags" class="boxed tagsContainerSettings has-padding-sm-more-left" ng-init="filteredData=[]">									
				<a ng-animate="'animate'" class="btn has-margin col-lg-12 has-padding-less btn-md btn-default has-margin no-border" ng-repeat="rating in filteredData = (ratingsData | filter:searchTag | filter:tagStatus() | orderBy:'name')" id="{{rating.tagId}}" href="" ng-class="{'active-yellow':activeRow.tagId==rating.tagId,'color-red':rating.status==0}" ng-click="changeSelected($index,rating)">{{rating.name}}</a>
			</div>			
			<hr/>
		</div>
		<div class="container col-lg-6">
			<div class="container">
				<h4 class="pull-left"><span class="text-info">Heartbeat</span> Tags:</h4>
				<div class="pull-right">
					<a href="" ng-click="show('add')" data-toggle="modal" class="btn btn-default" ng-class="{'active-yellow': showAdd}"><i class="glyphicon glyphicon-plus"></i></a>
					<a href="" ng-click="tempRow=activeRow;show('edit')" class="btn btn-default" ng-class="{'active-yellow': showEdit, disabled: activeRow.name==null}"><i class="glyphicon glyphicon-pencil"></i></a>
					<a href="" ng-show="activeRow.status==1" ng-click="deactivateTag(activeRow.tagId)" class="btn btn-default" ng-class="{'active':statusBtnLoading,'disabled':activeRow.name==null}"><i class="glyphicon glyphicon-trash"></i></a>
					<a href="" ng-hide="activeRow.status==1" ng-click="activateTag(activeRow.tagId)" class="btn btn-default" ng-class="{'active':statusBtnLoading,'disabled':activeRow.name==null}"><i class="glyphicon glyphicon-refresh"></i></a>
				</div>
			</div>
			<br/>
			<div class="container col-lg-12" ng-show="showView==true">
				<table class="table table-bordered" ng-show="activeRow.tagId">
					<thead>
						<tr>
						  <th colspan="3">Tag Details</th>
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
									{{activeRow.name}}
								</span>
						 	</td>
						</tr>
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">
									Description
								</span>
						 	</td>
						 	<td class="col-md-3">
								<span ng-dblclick="show('edit')">
									{{activeRow.description}}
								</span>
						 	</td>
						</tr>
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">Type</span>
						 	</td>
						 	<td class="col-md-3">
								<div ng-switch="activeRow.type">
									<span ng-switch-when="1">Primary</span>
									<span ng-switch-when="3">Secondary</span>
									<span ng-switch-when="2">Optional</span>									
									<span ng-switch-when="4">Tertiary</span>	
									<span ng-switch-default>UnTyped</span>
								</div>		
						 	</td>
						</tr>
						<tr>
						  	<td class="col-md-1">
								<span class="pull-right">
									Status
								</span>
						 	</td>
						 	<td class="col-md-3">
						 		<div ng-switch="activeRow.status">
						 			<span class="label label-success" ng-switch-when="1">Active</span>
									<span class="label label-danger" ng-switch-when="0">Inactive</span>
									<span ng-switch-default> - </span>						
								</div>
						 	</td>
						</tr>
					</tbody>
				</table>
				<!-- <pre>{{activeRow | json}}</pre> -->
				<table ng-show="activeRow.tagId" class="table table-hover table-bordered">
					<thead>
						<tr>
						  <th colspan="3">Levels</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="intensity in activeRow.intensity | orderBy:'level'" ng-init="rating.editEnable=false;rating.deleteEnable=false" ng-class="{true:'grayed'}[intensity.id==rating.tagId]">						
						  	<td class="col-md-1">
								<span class="pull-right">{{intensity.level}}</span>
						 	</td>
						 	<td class="col-md-3" ng-dblclick="show('edit')">
								<span>{{intensity.defaultName}}</span>										
						 	</td>
						 	<td class="col-md-8" ng-dblclick="show('edit')">
								<span>{{intensity.alternateName}}</span>								
						 	</td>
						</tr>
					</tbody>
				</table>
			
				<table class="table table-bordered" ng-hide="activeRow.tagId">
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
				<form action="" ng-submit="saveTag(newTag)" name="addTag">				
					<table class="table table-bordered no-margin">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-plus"></i> New Tag</th>
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
									<input type="text" name="name" ng-model="newTag.name" required class="form-control no-border clean-form-control" placeholder="Funny, Sad, Pro, High Quality">									
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Description
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<textarea type="text" name="description" ng-model="newTag.description" required class="form-control verticalResize no-border clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!"></textarea>
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">Type</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<select type="password" name="type" ng-model="newTag.type" required ng-options="type.tagTypeId as type.tagTypeName for type in tagType" class="form-control no-border clean-form-control" id="inputPassword1" placeholder="Password">
								    </select>						
							 	</td>
							</tr>
						</tbody>
					</table>
					<span class="container"><i class="glyphicon glyphicon-arrow-down container"></i></span>					
					<table class="table table-bordered">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-plus"></i> New Tag Levels</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="intensity in newTag.intensity">
							  	<td class="col-md-1">
									<span class="pull-right">{{intensity.level}}</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<input type="text" ng-model="intensity.defaultName" placeholder="Default Name" required class="form-control no-border clean-form-control"></input>										
							 	</td>
							 	<td class="col-md-8 no-padding">
									<input type="text" ng-model="intensity.alternateName" placeholder="Alternate Name" required class="form-control clean-form-control no-border"></input>
							 	</td>
							</tr>
						</tbody>
					</table>
					<div class="pull-right">
						<button type="button" class="btn btn-default no-border-radius" ng-click="show('resetview')">Close</button>
			       		<button type="submit" class="btn btn-primary no-border-radius">Add Tag</button>
		       		</div>
	       		</form>
	       		<!-- <pre>{{newTag | json}}</pre> -->
			</div>
			<div class="container col-lg-12" ng-show="showEdit==true">
				<form ng-submit="saveEditTag(editRow);">
					<table class="table table-bordered">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-pencil"></i> Tag Edit</th>
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
									<input type="text" dbl name="description" ng-model="editRow.name" required class="form-control color-red no-border clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!">							 		
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">
										Description
									</span>
							 	</td>
							 	<td class="col-md-3 no-padding">
									<textarea type="text" name="description" ng-model="editRow.description" required class="form-control color-red no-border verticalResize clean-form-control" placeholder="This tag is so awesome! We should all use it on our videos!"></textarea>
							 	</td>
							</tr>
							<tr>
							  	<td class="col-md-1">
									<span class="pull-right">Type {{editRow.type}}</span>
							 	</td>
							 	<td class="col-md-3">

									<div ng-switch="editRow.type">
										<span ng-switch-when="1">Primary</span>
										<span ng-switch-when="3">Secondary</span>
										<span ng-switch-when="2">Optional</span>
										<span ng-switch-when="4">Tertiary</span>
										<span ng-switch-default>UnTyped</span>
									</div>
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
					<!-- {{tempRow}} -->
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
							  <th colspan="3"><i class="glyphicon glyphicon-pencil"></i> Levels Edit</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="intensity in editRow.intensity | orderBy:'level'" ng-class="{true:'grayed'}[intensity.id==rating.tagId]">						
							  	<td class="col-md-1">
									<span class="pull-right">{{intensity.level}}</span>
							 	</td>
							 	<td class="col-md-3 no-padding border-red">								
									<input type="text" required class="form-control col-md-5 color-red clean-form-control no-border" ng-model="intensity.defaultName"></input>								
							 	</td>
							 	<td class="col-md-8 no-padding">								
									<input type="text" required class="form-control col-md-5 color-red clean-form-control no-border" ng-model="intensity.alternateName"></input>											
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

