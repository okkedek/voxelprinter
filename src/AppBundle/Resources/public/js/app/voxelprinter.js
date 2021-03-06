/**
 * Angular voxel printer module
 *
 */
angular.module('voxelprinter', ['ngRoute'])
    .constant('PREFIX_PRINTER' , '/printer')
    .constant('PREFIX_SNAPSHOT', '/snapshot')
    .config(function ($routeProvider, PREFIX_PRINTER) {
        $routeProvider
            .when('/', {
                controller: 'printerController',
                templateUrl: PREFIX_PRINTER + '/view/grid'
            })
            .when('/result', {
                controller: 'printerController',
                templateUrl: PREFIX_PRINTER + '/view/result'
            })
            .otherwise({redirectTo: '/'});
    });
