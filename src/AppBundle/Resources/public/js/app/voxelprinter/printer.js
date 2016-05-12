angular.module('voxelprinter')

    .service('printerService', function ($http, PREFIX_PRINTER) {
        var self = this;
        this.projectors = {};
        this.currentLayer = 0;

        this.loadVoxelModel = function () {
            return $http({
                method: 'GET',
                url: PREFIX_PRINTER + '/load'
            }).then(function successCallback(response) {
                updateVoxelModel(response.data);
            });
        };

        this.addVoxel = function (x, y) {
            return $http({
                method: "POST",
                url: PREFIX_PRINTER + "/move",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({coord: [x, y]})
            }).then(function (response) {
                updateVoxelModel(response.data);
            });
        };

        this.sendCommand = function (command) {
            return $http({
                method: "POST",
                url: PREFIX_PRINTER + "/command",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({command: command})
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
    })

    .directive('keyboardShortcuts', ['$document', function ($document) {
        return {
            restrict: 'A',
            link: function ($scope) {
                $document.bind('keydown', function (event) {
                    $scope.keypress(event.which);
                });
                $scope.$on('$destroy', function () {
                    $document.unbind('keydown');
                });
            }
        };
    }])

    .controller('printerController', function ($scope, $route, printerService) {
        $scope.init = function () {
            printerService.loadVoxelModel().then(function () {
                $scope.projectors = printerService.projectors;
            });
            $(".voxel-grid-container:first").on("mouseenter", "div div", function () {
                var el = $(this);
                el.addClass("visit");
                setTimeout(function () {
                    el.removeClass("visit");
                }, 2000);
            });
        };

        $scope.add = function (x, y) {
            printerService.addVoxel(x, y);
        };

        $scope.next = function () {
            printerService.sendCommand('nextLayer');
        };

        $scope.nozzle = function () {
            printerService.sendCommand('toggleNozzle');
        };

        $scope.clear = function () {
            printerService.sendCommand('clear');
        };

        $scope.snapshot = function () {
            document.getElementById('vs_iframe').contentWindow.postMessage({msg_type: 'get_photo'}, '*');
        };

        $scope.classMap = function (projector, x, y) {
            if (projector == undefined) return [];
            var classes = [];
            var value = projector.valueAt(x, y);
            if (value != undefined) {
                classes.push("voxel");
            }
            var distance = printerService.currentLayer - projector.distanceOf(x, y);
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
                    printerService.sendCommand('nextLayer');
                    break;
                case 67: //c
                    printerService.sendCommand('clear');
                    break;
                case 78: //n
                    printerService.sendCommand('toggleNozzle');
                    break;
            }
        };

        $scope.init();
    });