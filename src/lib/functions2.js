
// Creates a filled image rectangle which changes colour of its border when hovered over
var imageRect = (function () {

    // constructor
    function imageRect(canvas, img, x, y, width, height, fillColour, stroke, strokewidth) {
        this.canvas = canvas;
        this.c = canvas.getContext('2d');
        this.x = x;
        this.y = y;
        this.img = img;
        this.width = width;
        this.height = height;
        this.fillColour = fillColour || "grey"
        this.stroke = stroke || "skyblue";
        this.strokewidth = strokewidth || 3;
        this.redraw(this.x, this.y);
        return (this);
    }
    imageRect.prototype.redraw = function (x, y) {
        this.x = x || this.x;
        this.y = y || this.y;
        this.draw(this.stroke);
        return (this);
    }
    //
    imageRect.prototype.highlight = function (x, y) {
        this.x = x || this.x;
        this.y = y || this.y;
        this.draw("orange");
        return (this);
    }
    //
    imageRect.prototype.draw = function (stroke) {
        this.c.save();
        this.c.beginPath();
        this.c.strokeStyle = stroke;
        this.c.lineWidth = this.strokewidth;
        this.c.rect(this.x, this.y, this.width, this.height);
        this.c.stroke();
        this.c.fillStyle = this.fillColour
        this.c.fillRect(this.x, this.y, this.width, this.height);
        this.c.drawImage(this.img, this.x, this.y, this.width, this.height)
        this.c.restore();
    }
    //
    imageRect.prototype.isPointInside = function (x, y) {
        var rect = this.canvas.getBoundingClientRect();
        // calculate mouse positions relative to canvas
        var xRel = x - rect.left; var yRel = y - rect.top;
        return (xRel >= this.x && xRel <= this.x + this.width && yRel >= this.y && yRel <= this.y + this.height);
    }

    return imageRect;
})();

// Draws an arrow
function drawArrow(c, fromx, fromy, tox, toy){
  c.beginPath()
  var headlen = 10;   // length of head in pixels
  var angle = Math.atan2(toy-fromy,tox-fromx);
  c.moveTo(fromx, fromy);
  c.lineTo(tox, toy);
  c.lineTo(tox-headlen*Math.cos(angle-Math.PI/6),toy-headlen*Math.sin(angle-Math.PI/6));
  c.moveTo(tox, toy);
  c.lineTo(tox-headlen*Math.cos(angle+Math.PI/6),toy-headlen*Math.sin(angle+Math.PI/6));
  c.stroke()
}
