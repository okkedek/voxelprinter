
    function Projector(voxelModel, vector ) {

        var axes = determineAxes(vector);
        var projection = project(voxelModel.voxels, axes);

        function project(voxels, axes) {
            var projection = [];
            for (var i=0; i < voxels.length; i++) {
                var voxel = voxels[i];
                if (typeof projection[voxel[axes[0]]] == "undefined") {
                    projection[voxel[axes[0]]] = [];
                }
                projection[voxel[axes[0]]] [voxel[axes[1]]] = voxel[axes[2]]+1;
            }

            return projection;
        }

        function determineAxes(vector) {
            var axes = [];
            for (var axis in vector) {
                if (vector[axis] != 0) {
                    var projectionAxis = axis;
                } else {
                    axes.push(axis);
                }
            }
            axes.push(projectionAxis);

            return axes;
        }

        this.coord = function (x,y) {

            return (projection[x] != undefined && projection[x][y] != undefined ? "voxel" : "");
        }

    }

