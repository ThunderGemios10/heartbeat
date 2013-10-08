angular.module('ui-filter',[]).
	filter('unique', function () {

	  return function (items, filterOn, isDeep) {

		if (filterOn === false) {
		  return items;
		}

		if ((filterOn || angular.isUndefined(filterOn)) && angular.isArray(items)) {
		  var hashCheck = {}, newItems = [];

		  var extractValueToCompare = function (item) {
			if (angular.isObject(item) && angular.isString(filterOn)) {
			  return item[filterOn];
			} else {
			  return item;
			}
		  };

		  angular.forEach(items, function (item) {
			var valueToCheck, isDuplicate = false;

			for (var i = 0; i < newItems.length; i++) {
				if(isDeep) {
					if (angular.equals(extractValueToCompare(newItems[i]), extractValueToCompare(item))) {
						isDuplicate = true;
						break;
					}
				}
				else {
					// console.log(extractValueToCompare(newItems[i]));
					// console.log(extractValueToCompare(item));
					// console.log(extractValueToCompare(item).toLowerCase());
					if (extractValueToCompare(newItems[i]).toLowerCase() == extractValueToCompare(item).toLowerCase()) {
						isDuplicate = true;
						break;
				  }
				}
			}
			if (!isDuplicate) {
			  newItems.push(item);
			}

		  });
		  items = newItems;
		}
		return items;
	  };
	});
angular.module('trim-filter', []).
    filter('truncate', function () {
        return function (text, length, end, key) {
			if(key){
				if (isNaN(length))
					length = 10;

				if (end === undefined)
					end = "...";

				if (text.length <= length || text.length - end.length <= length) {
					return text;
				}
				else {
					return String(text).substring(0, length-end.length) + end;
				}
			}
			else{
				return text;
			}			
        };
    });
angular.module('text-module', []).
    filter('exceed', function () {
        return function (text, length) {
			if(text.length>length)
			{
				return true;
			}
			else {
				return false;
			}
        };
    });