/**
 * Projector: projects a voxelmodel onto an origin intersecting plane
 * specified by the given vector.
 *
 * @param voxels
 * @param vector
 * @constructor
 */
function Projector(voxels, vector) {

    var axes       = determineAxes(vector);
    var projection = project(voxels, axes);

    /**
     * Projects a voxel at (v0,v1,v2) onto the (v0,v1) plane
     *
     * v1
     * |
     * |
     * \----- v0
     *  \
     *   v2
     *
     * @param voxels
     * @param axes
     * @returns {Array}
     */
    function project(voxels, axes) {
        var projection = [];
        for (var i = 0; i < voxels.length; i++) {
            var voxel = voxels[i];
            var v0 = voxel[axes[0]];
            var v1 = voxel[axes[1]];
            var v2 = voxel[axes[2]];

            if (typeof projection[v0] == "undefined") {
                projection[v0] = [];
            }
            projection[v0][v1] = v2;
        }

        return projection;
    }

    function determineAxes(vector) {
        var axes = [];
        var projectionAxis;
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

    /**
     * Returns the projected value at the given coordinate
     *
     * @param x
     * @param y
     * @returns {undefined}
     */
    this.valueAt = function (x, y) {

        return (projection[x] != undefined && projection[x][y] != undefined ? projection[x][y] : undefined);
    };

    /**
     * Determines the distance of the original voxel to the projection plane
     *
     * @param x
     * @param y
     * @returns {*}
     */
    this.distanceOf = function (x, y) {
        switch (axes[2]) {
            case "y":

                return this.valueAt(x, y);
            case "x":
            case "z":

                return y;
        }
    }

}

