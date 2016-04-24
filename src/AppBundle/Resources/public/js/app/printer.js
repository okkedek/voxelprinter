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

printer.service('printerService', function ($http, URL_PREFIX) {

    var self = this;
    this.projectors = {};
    this.currentLayer = 0;

    this.loadVoxelModel = function () {
        return $http({
            method: 'GET',
            url: URL_PREFIX + '/load'
        }).then(function successCallback(response) {
            updateVoxelModel(response.data);
        });
    };

    this.addVoxel = function (x, y) {
        return $http({
            method: "POST",
            url: URL_PREFIX + "/move",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param({coord: [x, y]}),
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.nextLayer = function () {
        return $http({
            method: "POST",
            url: URL_PREFIX + "/next"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.toggleNozzle = function () {
        return $http({
            method: "POST",
            url: URL_PREFIX + "/nozzle"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    this.clear = function () {
        return $http({
            method: "POST",
            url: URL_PREFIX + "/clear"
        }).then(function (response) {
            updateVoxelModel(response.data);
        });
    };

    function updateVoxelModel(voxelModel) {
        self.currentLayer = voxelModel.currentLayer;
        self.projectors.top = new Projector(voxelModel.voxels, {x: 0, y: 1, z: 0});
        self.projectors.front = new Projector(voxelModel.voxels, {x: 0, y: 0, z: 1});
        self.projectors.left = new Projector(voxelModel.voxels, {x: 1, z: 0, y: 0});
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
            $scope.projectors = printerService.projectors;
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

    $scope.classMap = function(projector, x, y) {
        if (projector == undefined) return [];
        var classes = [];
        var value = projector.valueAt(x, y);
        if (value != undefined) {
            classes.push("voxel");
        }
        var distance = printerService.currentLayer - projector.layerOf(x, y);
        if (distance == 0) {
            classes.push("current");
        } else if (distance == 1) {
            classes.push("previous");
        }
        return classes;
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
    };

    $scope.init();
});