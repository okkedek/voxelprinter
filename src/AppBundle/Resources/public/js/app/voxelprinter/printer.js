angular.module('voxelprinter')

    .service('printerService', function ($http, PREFIX_PRINTER) {
        var self = this;
        this.projectors = {};
        this.visited = [];
        this.currentLayer = 0;
        this.nozzleState = "?";

        this.loadVoxelModel = function () {
            return $http({
                method: 'GET',
                url: PREFIX_PRINTER + '/load'
            }).then(function successCallback(response) {
                updatePrinterState(response.data);
            });
        };

        this.addVoxel = function (x, y) {
            this.visit(x, y);
            return $http({
                method: "POST",
                url: PREFIX_PRINTER + "/move",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({coord: [x, y]})
            }).then(function (response) {
                self.unVisit.call(self, x, y);
                updatePrinterState(response.data);
            });
        };

        this.sendCommand = function (command) {
            return $http({
                method: "POST",
                url: PREFIX_PRINTER + "/command",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({command: command})
            }).then(function (response) {
                updatePrinterState(response.data);
            });
        };


        this.visit = function (x, y) {
            this.visited[x + "," + y] = true;
            setTimeout(function () {
                self.unVisit.call(self, x, y);
            }, 3000);
        }

        this.unVisit = function (x, y) {
            this.visited[x + "," + y] = undefined;
        }

        this.clearVisited = function () {
            this.visited = [];
        }

        this.hasVisited = function (x, y) {
            return (this.visited[x + "," + y] != undefined);
        }

        function updatePrinterState(printer) {
            self.currentLayer = printer.currentLayer;
            self.nozzleState = printer.nozzleState;
            self.projectors.top   = new Projector(printer.voxels, {x: 0, y: 1, z: 0});
            self.projectors.front = new Projector(printer.voxels, {x: 0, y: 0, z: 1});
            self.projectors.left  = new Projector(printer.voxels, {x: 1, z: 0, y: 0});
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
        $scope.nozzlestate = "open";
        this.init = function () {
            printerService.loadVoxelModel().then(function () {
                $scope.projectors = printerService.projectors;
                $scope.visited = printerService.visited;
            });
        };

        $scope.add = function (x, y) {
            printerService.addVoxel(x, y);
        };

        $scope.next = function () {
            printerService.clearVisited();
            printerService.sendCommand('nextLayer');
        };

        $scope.nozzle = function () {
            printerService.sendCommand('toggleNozzle');
        };

        $scope.nozzleState = function() {
            return printerService.nozzleState ? "open" : "closed";
        }

        $scope.clear = function () {
            printerService.clearVisited();
            printerService.sendCommand('clear');
        };

        $scope.snapshot = function () {
            // send message to external stlviewer iframe to take a snapshot and return the image
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
            if (printerService.hasVisited(x, y)) {
                classes.push("visit");
            }
            return classes;
        };

        $scope.keypress = function (keycode) {
            switch (keycode) {
                case 76: //l
                    $scope.next();
                    break;
                case 67: //c
                    $scope.clear();
                    break;
                case 78: //n
                    $scope.nozzle();
                    break;
            }
        };

        this.init();
    });