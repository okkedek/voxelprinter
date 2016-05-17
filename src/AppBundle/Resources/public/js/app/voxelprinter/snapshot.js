angular.module('voxelprinter')

    .service('snapshotService', function ($http, PREFIX_SNAPSHOT) {
        this.add = function (data) {
            return $http({
                method: "POST",
                url: PREFIX_SNAPSHOT + "/add",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({img: data})
            });
        };
        this.load = function () {
            return $http({
                method: 'GET',
                url: PREFIX_SNAPSHOT + '/load'
            });
        }
    })

    .controller('snapshotController', function ($scope, $route, snapshotService) {
        $scope.snapshots = [];
        $scope.init = function () {
            // load snapshots
            snapshotService.load().then(function (data) {
                updateSnapshots(data);
            });
            // install handler for incoming snapshots sent by viewstl.com
            window.onmessage = function (e) {
                if ((e.origin == "http://www.viewstl.com") && (e.data.msg_type)) {
                    if (e.data.msg_type == 'photo') {
                        snapshotService.add(e.data.img_data).then(function (data) {
                            updateSnapshots(data);
                        });
                    }
                }
            };
        };
        function updateSnapshots(data) {
            $scope.snapshots = data.data.images;
        }
        $scope.init();
    });