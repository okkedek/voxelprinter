/**
 *
 * Angular voxel printer module
 *
 * (c) 2016 Okke De Koninck
 * i-Help Networks
 * www.ihelp.nl
 *
 */
var printer = angular.module('printer', ['ngRoute']);

printer.constant('URL_PREFIX', '/printer');

printer.config(function ($routeProvider, URL_PREFIX) {
    $routeProvider
        .when('/', {
            controller: 'printerController',
            templateUrl: URL_PREFIX + '/grid'
        })
        .when('/result', {
            controller: 'printerController',
            templateUrl: URL_PREFIX + '/result'
        })
        .otherwise({redirectTo: '/'});
});

printer.service('printerService', function ($http) {

    var prefix = "/printer";
    var self = this;
    this.projections = {};

    this.loadVoxelModel = function () {
        return $http({
            method: 'GET',
            url: prefix + '/load'
        }).then(function successCallback(response) {
            updateVoxelModel(response.data);
        });
    };

    this.addVoxel = function (x, y) {
        return $http({
            method: "POST",
            url: prefix + "/move",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param({coord: [x, y]}),
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.nextLayer = function () {
        return $http({
            method: "POST",
            url: prefix + "/next"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.toggleNozzle = function () {
        return $http({
            method: "POST",
            url: prefix + "/nozzle"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.clear = function () {
        return $http({
            method: "POST",
            url: prefix + "/clear"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    function updateVoxelModel(voxels) {
        self.projections.top = new Projector(voxels, {x: 0, y: 1, z: 0});
        self.projections.front = new Projector(voxels, {x: 0, y: 0, z: 1});
        self.projections.left = new Projector(voxels, {x: 1, z: 0, y: 0});
    }
});

printer.directive('keyboardShortcuts', ['$document', function ($document) {
    return {
        restrict: 'A',
        link: function ($scope) {
            $document.bind('keydown', function (event) {
                $scope.keypress(event.which);
            });
            $scope.$on('$destroy', function (event) {
                $document.unbind('keydown');
            });
        }
    };
}]);

printer.controller('printerController', function ($scope, printerService) {
    $scope.init = function () {
        printerService.loadVoxelModel().then(function () {
            $scope.projections = printerService.projections;
        });
    };

    $scope.add = function (x, y) {
        printerService.addVoxel(x, y);
    };

    $scope.next = function () {
        printerService.nextLayer();
    };

    $scope.nozzle = function () {
        printerService.toggleNozzle();
    };

    $scope.clear = function () {
        printerService.clear();
    };

    $scope.keypress = function (keycode) {
        switch (keycode) {
            case 76: //l
                printerService.nextLayer();
                break;
            case 67: //c
                printerService.clear();
                break;
            case 78: //n
                printerService.toggleNozzle();
                break;
        }
    }

    $scope.init();
});