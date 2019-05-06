
// Creates a filled image rectangle which changes colour of its border when hovered over
var imageRect = (function () {

    // constructor
    function imageRect(img, x, y, width, height, fillColour, stroke, strokewidth) {
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
        c.save();
        c.beginPath();
        c.strokeStyle = stroke;
        c.lineWidth = this.strokewidth;
        c.rect(this.x, this.y, this.width, this.height);
        c.stroke();
        c.fillStyle = this.fillColour
        c.fillRect(this.x, this.y, this.width, this.height);
        c.drawImage(this.img, this.x, this.y, this.width, this.height)
        c.restore();
    }
    //
    imageRect.prototype.isPointInside = function (x, y) {
        return (x >= this.x && x <= this.x + this.width && y >= this.y && y <= this.y + this.height);
    }

    return imageRect;
})();

// Draws an arrow
function drawArrow(fromx, fromy, tox, toy){
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

