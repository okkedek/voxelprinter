
    function Projector(voxels, vector ) {

        var projectionAxis;
        var axes = determineAxes(vector);
        var projection = project(voxels, axes);

        function project(voxels, axes) {
            var projection = [];
            for (var i=0; i < voxels.length; i++) {
                var voxel = voxels[i];
                if (typeof projection[voxel[axes[0]]] == "undefined") {
                    projection[voxel[axes[0]]] = [];
                }
                projection[voxel[axes[0]]] [voxel[axes[1]]] = voxel[axes[2]];
            }

            return projection;
        }

        function determineAxes(vector) {
            var axes = [];
            for (var axis in vector) {
                if (vector[axis] != 0) {
                    projectionAxis = axis;
                } else {
                    axes.push(axis);
                }
            }
            axes.push(projectionAxis);

            return axes;
        }

        this.valueAt = function (x,y) {
            return (projection[x] != undefined && projection[x][y] != undefined ? projection[x][y] : undefined);
        }

        this.layerOf = function(x,y) {
            switch (projectionAxis) {
                case "y":
                    return this.valueAt(x,y);
                case "x":
                case "z":
                    return y;
            }
        }

    }

