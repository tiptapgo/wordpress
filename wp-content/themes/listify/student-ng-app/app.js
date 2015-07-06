var flag = true;
$baseurl = "http://tiptapgo.co/wp-content/themes/listify/student-ng-app";

angular.module('formApp', ['ngAnimate', 'ui.router'])

.config(function($stateProvider, $urlRouterProvider, $locationProvider) {

$stateProvider

    // route to show our basic form (/form)
        .state('form', {
        url: '/form',
        templateUrl: $baseurl + '/form.html',
        controller: 'formController'
    })

    // nested states 
    // each of these sections will have their own view
    // url will be nested (/form/q1)
    .state('form.q1', {
        url: '/q1',
        templateUrl: $baseurl + '/q1.html'
    })

    // url will be nested (/form/q2)f
    .state('form.q2', {
        url: '/q2',
        templateUrl: $baseurl + '/q2.html'
    })

    // url will be  (/form/q3)
    .state('form.q3', {
        url: '/q3',
        templateUrl: $baseurl + '/q3.html'
    })

    // url will be  (/form/q4)
    .state('form.q4', {
            url: '/q4',
            templateUrl: $baseurl + '/q4.html'
        })
        // url will be  (/form/q5)
        .state('form.q5', {
            url: '/q5',
            templateUrl: $baseurl + '/q5.html'
        })
        // url will be  (/form/q6)
        .state('form.q6', {
            url: '/q6',
            templateUrl: $baseurl + '/q6.html'
        })
        .state('form.q7', {
            url: '/q7',
            templateUrl: $baseurl + '/q7.html'
        })
        .state('form.q8', {
            url: '/q8',
            templateUrl: $baseurl + '/q8.html'
        })
        // url will be  (/form/good)
        .state('form.good', {
            url: '/good',
            templateUrl: $baseurl + '/good.html'
        })
    //$locationProvider.html5Mode(true);
    // catch all route
    // send users to the form page 
    $urlRouterProvider.otherwise('/form/q1');

})

.controller('formController', function($scope, $rootScope, $http) {

    // we will store all of our form data in this object
    $scope.formData = {};
	var join = '';
    $scope.$watch("formData.q6",function( newValue, oldValue ) {if ( newValue === oldValue ) return;  else jQuery('.next').click();});
    $scope.$watch("formData.q7",function( newValue, oldValue ) {if ( newValue === oldValue ) return;  else jQuery('.next').click();});
    $scope.$watch("formData.q2cat",function( newValue, oldValue ) {
        if ( newValue === oldValue || typeof oldValue === 'undefined' || join == newValue)
            return;
        else {
		join = oldValue + ', ' + newValue;            
		$scope.formData.q2cat = join;	 
	}   
    });

    // function to process the form
    $scope.processForm = function() {
    $http({
	method  : 'POST',
	url     : $baseurl+'/ng-process.php',
	data    : jQuery.param($scope.formData),
	headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .success(function(data) {
	console.log(data);
    });
    };

    $scope.checkdata = function() {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var re1 = /^[789]\d{9}$/i;

        if (document.getElementById('name').value == "") {
            document.getElementById('nameerror').style.display = "block";
            flag = false;
        }
        if (document.getElementById('phone').value == "") {
            document.getElementById('phoneerror').style.display = "block";
            flag = false;
        }
        if (document.getElementById('phone').value != "" && re1.test(document.getElementById('phone').value) == false) {
            document.getElementById('phoneerror1').style.display = "block";
            flag = false;
        }
        if (document.getElementById('area').value == "") {
            document.getElementById('areaerror').style.display = "block";
            flag = false;
        }
        if (document.getElementById('email').value == "") {
            document.getElementById('emailerror').style.display = "block";
            flag = false;
        }
        if (document.getElementById('email').value != "" && re.test(document.getElementById('email').value) == false) {
            document.getElementById('emailerror1').style.display = "block";
            flag = false;
        }
        if (document.getElementById('name').value != "") {
            document.getElementById('nameerror').style.display = "none";
        }
        if (document.getElementById('phone').value != "") {
            document.getElementById('phoneerror').style.display = "none";
        }
        if (document.getElementById('area').value != "") {
            document.getElementById('areaerror').style.display = "none";
        }
        if (document.getElementById('email').value != "") {
            document.getElementById('emailerror').style.display = "none";
        }
        if (document.getElementById('email').value != "" && re.test(document.getElementById('email').value) != false) {
            document.getElementById('emailerror1').style.display = "none";
        }
        if (document.getElementById('name').value != "" && document.getElementById('phone').value != "" && document.getElementById('area').value != "" && document.getElementById('email').value != "" && re.test(document.getElementById('email').value) != false && re1.test(document.getElementById('phone').value) != false) {
            flag = true;
        }

    };

    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
        if(flag == false)
		event.preventDefault();
    });

});