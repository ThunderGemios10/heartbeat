<?php session_start();?>
	<div sidebar-nav active="anyTV" col="2"></div>
	<div class="col-md-10 pull-left boxed-left has-padding-sm" ng-show="showNewGroup">
		<form action="" ng-submit="save(newgroup)" class="col-md-7">
			<table class="table table-bordered">
				<thead>
					<tr>
					  <th colspan="3"><i class="glyphicon glyphicon-plus"></i> Create new group
					  	<button type="button" class="pull-right close" aria-hidden="true" ng-click="hide()">&times;</button>
					  </th>
					</tr>
				</thead>
				<tbody>
					<tr>
					  	<td class="col-md-1">							  		
							<span class="pull-right">
								Group Id							
							</span>
					 	</td>
					 	<td class="col-md-3 no-padding" ng-class="{'highlight-red':exist}">
							<input type="text" name="id" ng-model="newgroup.id" required class="form-control no-border clean-form-control" placeholder="anyTV">
					 	</td>
					</tr>
					<tr>
					  	<td class="col-md-1">							  		
							<span class="pull-right">
								Name
							</span>
					 	</td>
					 	<td class="col-md-3 no-padding">
							<input type="text" name="name" ng-model="newgroup.name" required class="form-control no-border clean-form-control" placeholder="any.TV group, freedom group">
					 	</td>
					</tr>
					<tr>
					  	<td class="col-md-1">
							<span class="pull-right">
								Description
							</span>
					 	</td>
					 	<td class="col-md-3 no-padding">
							<textarea type="text" name="description" ng-model="newgroup.description" class="form-control verticalResize no-border clean-form-control" placeholder="Contains cool stuff!"></textarea>
					 	</td>
					</tr>
					<tr>
					  	<td class="col-md-1">
							<span class="pull-right">Type</span>
					 	</td>
					 	<td class="col-md-3 no-padding">
							<select type="password" name="type" ng-model="newgroup.grouptype" required ng-options="type.id as type.name for type in groupType" class="form-control no-border clean-form-control">
						    </select>			
					 	</td>
					</tr>
				</tbody>
			</table>		
			<div class="alert alert-danger" ng-show="exist">Oh wait! Group Id is already taken.</div>
			<div class="pull-right">
				<!-- <button type="button" class="btn btn-default no-border-radius" ng-click="save('resetview')">Close</button> -->
	       		<button type="submit" class="btn btn-primary no-border-radius">Save group</button>
       		</div>
   		</form>		
	</div>	
	<div class="col-md-8 pull-left boxed-left">
		<div class="col-md-12 pull-left boxed no-padding">
			<table class="table table-striped no-margin">
				<thead>
					<tr>
					  <th colspan="4">
						<span class="pull-left"><h4>Groups</h4></span>
						<div class="pull-right col-lg-11">
							<div class="pull-right col-lg-9">
								<button type="button" class="btn btn-default" popover="Add a group" popover-trigger="mouseenter" popover-placement="right" ng-click="show()">
								    <span class="glyphicon glyphicon-plus"></span>
								</button>
								<div class="col-lg-10">
								    <div class="input-group">
								      <input type="text" class="form-control" ng-model="keyword.groupId">
								      <div class="input-group-btn">
								        <button type="button" class="btn btn-default"> <span class="glyphicon glyphicon-search"></span></button>							        
								      </div>
								    </div>
								</div>
							</div>
						</div>		
					  </th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="group in createdGroup | filter:keyword | orderBy:'dateModified':true">
					  	<td class="col-md-2" ng-init="group.hovered=false">							  		
							<div ng-mouseover="group.hovered=true" ng-mouseleave="group.hovered=false" class="with-close-btn" style="width:250px;height:100px;background-image: url(uploads/groupBanner/{{group.bannerLink}}); background-size:cover; background-position:center center;">
								<!-- <a class="close-btn form-control close" href="#"></a> -->
								<!-- <button type="button" ng-show="group.hovered"  class="close-btn btn btn-warning" aria-hidden="true">
									<span class="glyphicon glyphicon-upload"> </span>
								</button> -->
								<input ng-show="group.hovered" type="file" ng-file-select="onFileSelect($files,group.groupId)" >							
							</div>
					 	</td>
					  	<td class="col-md-2">							  		
							<span class="bold">
								{{group.groupId}}
							</span>
					 	</td>
					 	<td class="col-md-6">
							<span class="color-grey-2">
								{{getGroupTypeById(group.groupType)}}
							</span>
					 	</td>
					 	<td class="col-md-2">
					 		<a href="#!managegroup/{{group.groupId}}" type="button" class="btn btn-default" popover="Add channel" popover-trigger="mouseenter" popover-placement="right">
							    <span class="glyphicon glyphicon-plus"></span>
							</a>
							<!-- <span class="glyphicon glyphicon-trash pointer-cursor" ng-click=""></span> -->
					 	</td>
					</tr>					
				</tbody>
			</table>
		</div>



  <!-- <input type="text" ng-model="id"> -->
  
  <!-- <input type="file" ng-file-select="onFileSelect($files)" multiple> -->
  <!-- <div class="drop-box" ng-file-drop="onFileSelect($files);" ng-show="ddSupported">drop files here</div> -->
  <!-- <div ng-file-drop-available="dropSupported=true" ng-show="!ddSupported">HTML5 Drop File is not supported!</div> -->


	</div>
