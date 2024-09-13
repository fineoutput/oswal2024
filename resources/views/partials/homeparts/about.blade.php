<style>
    .effect {
    position: relative;
  height: 100%;
  width:100%;
  margin: 0;
  padding: 0;
  font-size: 10vw;
  background-color: #ddf0e9;
}



#confetti{
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
}
</style>
<!-- <div class="renwew_sect section-padding mb-5"

    style="background-image: url(https://www.bikaji.com/pub/media/wysiwyg/about_bg.jpg); margin-top: -10px;">

    <div class="container">

        <div class="row">

            <div class="col-lg-12 col-sm-12 col-md-12 renwer_upper">

                <div class="renew_head">

                    <h2 class="module-title text-center">Oswal</h2>

                    <div class="renew_head_img">

                        <img src="{{ asset('images/roya.') }}png" alt="" />

                    </div>

                    <div class="module-subtitle">

                        <br />
                        OSWAL SOAP GROUP With Over <span class="red">65 Years Of Experience</span> And More Than <span class="red">150 Million Satisfied Customers</span>,
    Oswal Soap Group Became One Of The <span class="red">Largest Manufacturers</span> Of The Daily-Use Commodities. We
    Provide <span class="red">Superior Quality Products</span> To The Consumers

                    </div>

                </div>

            </div>

        </div>

    </div>

    <img class="coach" src="{{ asset('images/small.png') }}" class="image-top" alt="Backgroung Image" />

    <section class="module module_aboutus active"

        style="background-color: #51d5b0; padding-top: 72px; margin-top: 250px;">

        class removed hidden-xs

        <img src="{{ asset('images/b.png') }}" class="floating balloon_left" alt="Balloon" />

        <div class="row">

            <div class="col-lg-12 col-sm-12 col-md-12">

                <div class="abt_time d-flex justify-content-center">

                    <div class="abt_time_img animate__animated animate__fadeInUp">

                        <img class="celeb_img" src="{{ asset('images/68.png') }}" alt="" />

                        <div class="insider_time">

                            <div class="insider_time_cust">

                                <h4>15cr+</h4>

                                <p>Customers</p>

                            </div>

                            <div class="insider_time_city">

                                <h4>200+</h4>

                                <p>Cities</p>

                            </div>

                            <div class="insider_time_retail">

                                <h4>2.5 Lac+</h4>

                                <p>Retailers</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div> -->

<div class="container-fluid effect" style="margin-bottom:7rem;">
   <img src="{{ asset('images/oswal_banner.') }}jpg" alt="" width="100%" />
    <canvas id="confetti"></canvas>
</div>
<section class="module"

    style="background-image: url(https://www.bikaji.com/pub/media/wysiwyg/3.png); margin-top: -160px; z-index: 9; background-size: cover; background-position: 30% 50%; text-align: center;">


    <img src="{{ asset('images/b.png') }}" class="floating balloon_right" alt="Balloon" />


    <div class="home-orangepatch">

        PRESERVING CULTURE<br />

        THROUGH ETHNIC-CLEANING

    </div>

    <div class="home-orangepatch-yellow">FOR OVER 68 YEARS.</div>
    <img src="http://127.0.0.1:8000/images/bucket.png" alt="" style="
    width: 9%;
    position: absolute;
    top: 40%;
    left: 0;
">

</section>


<section class="module active"

    style="background-image: url('{{ asset('images/4-Recovered.jpg') }}'); margin-top: -100px; background-position: center bottom; background-size: cover;">

    <div class="container">

        <div class="row">

            <div class="col-sm-6">

                <h5 class="font-alt"

                    style="font-size: 30px; font-weight: bold; font-family: 'Cormorant', serif !important;">ABOUT US -

                    Oswal Soap</h5>

                <br />

                <p style="line-height: 26px; font-size: 14px;">

                    Oswal Soap Group was established by Late Shri Uttamchand Deshraj Jain in 1956 in Johari Bazaar,

                    Jaipur with his hard work and efforts. The soap’s high quality was the key to attract people and

                    made it their

                    first choice. Later on, Late Shri Uttamchand Deshraj Jain’s sons took a step forward and got the

                    business registered and copyright as “Oswal Soap Group” and started handling his whole business.

                </p>

            </div>

        </div>

    </div>

</section>
<div class="container-fluid effect" style="margin-bottom:7rem;">
   <img src="{{ asset('images/oswal_banner.') }}jpg" alt="" width="100%" />
    <canvas id="confetti"></canvas>
</div>
<div class="splide_secound_set mb-5">
    
    <div class="splide" id="splide3">
        
        <div class="splide__track">
            
            <ul class="splide__list">
                
                @foreach (App\Models\Offer::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get() as $key => $offerslider)

                    <li class="splide__slide">
                        
                        <div class="slide-content">
                            
                            <img src="{{ asset($offerslider->image) }}" alt="Slide 1 Image 1" />
                            
                        </div>
                        
                    </li>

                @endforeach
                
                {{-- <li class="splide__slide">
                    
                    <div class="slide-content">
                        
                        <img src="{{ asset('images/banner_sect.png') }}" alt="Slide 2 Image 2" />

                    </div>
                    
                </li>
                
                <li class="splide__slide">
                    
                    <div class="slide-content">
                        
                        <img src="{{ asset('images/banner_sect2.png') }}" alt="Slide 2 Image 3" />
                        
                    </div>
                    
                </li> --}}
                
                <!-- Add more slides as needed -->
                
            </ul>
            
        </div>
        
    </div>
    
</div>

<div class="totalcontainer" data-aos="fade-up" data-aos-duration="1000"> 
        <div class="laya-please layer-1"></div>
        <div class="laya-please layer-2"></div>
        <div class="container1">
        <div class="laya-please layer-3" 
     style="background-image: url('{{ asset('images/lay3.png') }}');">
</div>

<div class="laya-please layer-4"
 style="background-image: url('{{ asset('images/lay4.png') }}');
 ">
</div>
            <div class="laya-please layer-5"
            style="background-image: url('{{ asset('images/lay5.png') }}');
 "></div>
            <div class="laya-please layer-6"></div>
        </div>
        <div class="container2">
            <div class="laya-please layer-7" style="background-image: url('{{ asset('images/lay7.png') }}');   "></div>
            <div class="laya-please layer-8"></div>
        </div>
    </div>

    <script>
        const elems = document.querySelectorAll('.laya-please');
        const layer2 = document.querySelector('.layer-2');
        const layer3 = document.querySelector('.layer-3');
        const layer4 = document.querySelector('.layer-4');
        const layer5 = document.querySelector('.layer-5');
        const layer6 = document.querySelector('.layer-6');
        const layer7 = document.querySelector('.layer-7');
        const layer8 = document.querySelector('.layer-8');

        setTimeout(function () {
            elems.forEach(function (elem) {
                elem.style.animation = "none";
            });
        }, 1500);

        document.body.addEventListener('mousemove', function (e) {
            if (!e.currentTarget.dataset.triggered) {
                elems.forEach(function (elem) {
                    if (elem.getAttribute('style')) {
                        elem.style.transition = "all .5s";
                        elem.style.transform = "none";
                    }
                });
            }
            e.currentTarget.dataset.triggered = true;

            let width = window.innerWidth / 2;
            let mouseMoved2 = ((width - e.pageX) / 50);
            let mouseMoved3 = ((width - e.pageX) / 40);
            let mouseMoved4 = ((width - e.pageX) / 30);
            let mouseMoved5 = ((width - e.pageX) / 20);
            let mouseMoved6 = ((width - e.pageX) / 10);
            let mouseMoved7 = ((width - e.pageX) / 5);

            layer3.style.transform = "translateX(" + mouseMoved2 + "px)";
            layer4.style.transform = "translateX(" + mouseMoved3 + "px)";
            layer5.style.transform = "translateX(" + mouseMoved4 + "px)";
            layer6.style.transform = "translateX(" + mouseMoved5 + "px)";
            layer7.style.transform = "translateX(" + mouseMoved6 + "px)";
            layer8.style.transform = "translateX(" + mouseMoved7 + "px)";
        });

        document.body.addEventListener('mouseleave', function () {
            elems.forEach(function (elem) {
                elem.style.transition = "all .5s";
                elem.style.transform = "none";
            });
        });

        document.body.addEventListener('mouseenter', function () {
            elems.forEach(function (elem) {
                setTimeout(function () {
                    elem.style.transition = "none";
                }, 500);
            });
        });
    </script>
    <script>
    var retina = window.devicePixelRatio,

// Math shorthands
PI = Math.PI,
sqrt = Math.sqrt,
round = Math.round,
random = Math.random,
cos = Math.cos,
sin = Math.sin,

// Local WindowAnimationTiming interface
rAF = window.requestAnimationFrame,
cAF = window.cancelAnimationFrame || window.cancelRequestAnimationFrame,
_now = Date.now || function () {return new Date().getTime();};

// Local WindowAnimationTiming interface polyfill
(function (w) {
/**
            * Fallback implementation.
            */
var prev = _now();
function fallback(fn) {
var curr = _now();
var ms = Math.max(0, 16 - (curr - prev));
var req = setTimeout(fn, ms);
prev = curr;
return req;
}

/**
            * Cancel.
            */
var cancel = w.cancelAnimationFrame
|| w.webkitCancelAnimationFrame
|| w.clearTimeout;

rAF = w.requestAnimationFrame
|| w.webkitRequestAnimationFrame
|| fallback;

cAF = function(id){
cancel.call(w, id);
};
}(window));

document.addEventListener("DOMContentLoaded", function() {
var speed = 50,
  duration = (1.0 / speed),
  confettiRibbonCount = 11,
  ribbonPaperCount = 30,
  ribbonPaperDist = 8.0,
  ribbonPaperThick = 8.0,
  confettiPaperCount = 95,
  DEG_TO_RAD = PI / 180,
  RAD_TO_DEG = 180 / PI,
  colors = [
    ["#df0049", "#660671"],
    ["#00e857", "#005291"],
    ["#2bebbc", "#05798a"],
    ["#ffd200", "#b06c00"]
  ];

function Vector2(_x, _y) {
this.x = _x, this.y = _y;
this.Length = function() {
  return sqrt(this.SqrLength());
}
this.SqrLength = function() {
  return this.x * this.x + this.y * this.y;
}
this.Add = function(_vec) {
  this.x += _vec.x;
  this.y += _vec.y;
}
this.Sub = function(_vec) {
  this.x -= _vec.x;
  this.y -= _vec.y;
}
this.Div = function(_f) {
  this.x /= _f;
  this.y /= _f;
}
this.Mul = function(_f) {
  this.x *= _f;
  this.y *= _f;
}
this.Normalize = function() {
  var sqrLen = this.SqrLength();
  if (sqrLen != 0) {
    var factor = 1.0 / sqrt(sqrLen);
    this.x *= factor;
    this.y *= factor;
  }
}
this.Normalized = function() {
  var sqrLen = this.SqrLength();
  if (sqrLen != 0) {
    var factor = 1.0 / sqrt(sqrLen);
    return new Vector2(this.x * factor, this.y * factor);
  }
  return new Vector2(0, 0);
}
}
Vector2.Lerp = function(_vec0, _vec1, _t) {
return new Vector2((_vec1.x - _vec0.x) * _t + _vec0.x, (_vec1.y - _vec0.y) * _t + _vec0.y);
}
Vector2.Distance = function(_vec0, _vec1) {
return sqrt(Vector2.SqrDistance(_vec0, _vec1));
}
Vector2.SqrDistance = function(_vec0, _vec1) {
var x = _vec0.x - _vec1.x;
var y = _vec0.y - _vec1.y;
return (x * x + y * y + z * z);
}
Vector2.Scale = function(_vec0, _vec1) {
return new Vector2(_vec0.x * _vec1.x, _vec0.y * _vec1.y);
}
Vector2.Min = function(_vec0, _vec1) {
return new Vector2(Math.min(_vec0.x, _vec1.x), Math.min(_vec0.y, _vec1.y));
}
Vector2.Max = function(_vec0, _vec1) {
return new Vector2(Math.max(_vec0.x, _vec1.x), Math.max(_vec0.y, _vec1.y));
}
Vector2.ClampMagnitude = function(_vec0, _len) {
var vecNorm = _vec0.Normalized;
return new Vector2(vecNorm.x * _len, vecNorm.y * _len);
}
Vector2.Sub = function(_vec0, _vec1) {
return new Vector2(_vec0.x - _vec1.x, _vec0.y - _vec1.y, _vec0.z - _vec1.z);
}

function EulerMass(_x, _y, _mass, _drag) {
this.position = new Vector2(_x, _y);
this.mass = _mass;
this.drag = _drag;
this.force = new Vector2(0, 0);
this.velocity = new Vector2(0, 0);
this.AddForce = function(_f) {
  this.force.Add(_f);
}
this.Integrate = function(_dt) {
  var acc = this.CurrentForce(this.position);
  acc.Div(this.mass);
  var posDelta = new Vector2(this.velocity.x, this.velocity.y);
  posDelta.Mul(_dt);
  this.position.Add(posDelta);
  acc.Mul(_dt);
  this.velocity.Add(acc);
  this.force = new Vector2(0, 0);
}
this.CurrentForce = function(_pos, _vel) {
  var totalForce = new Vector2(this.force.x, this.force.y);
  var speed = this.velocity.Length();
  var dragVel = new Vector2(this.velocity.x, this.velocity.y);
  dragVel.Mul(this.drag * this.mass * speed);
  totalForce.Sub(dragVel);
  return totalForce;
}
}

function ConfettiPaper(_x, _y) {
this.pos = new Vector2(_x, _y);
this.rotationSpeed = (random() * 600 + 800);
this.angle = DEG_TO_RAD * random() * 360;
this.rotation = DEG_TO_RAD * random() * 360;
this.cosA = 1.0;
this.size = 5.0;
this.oscillationSpeed = (random() * 1.5 + 0.5);
this.xSpeed = 40.0;
this.ySpeed = (random() * 60 + 50.0);
this.corners = new Array();
this.time = random();
var ci = round(random() * (colors.length - 1));
this.frontColor = colors[ci][0];
this.backColor = colors[ci][1];
for (var i = 0; i < 4; i++) {
  var dx = cos(this.angle + DEG_TO_RAD * (i * 90 + 45));
  var dy = sin(this.angle + DEG_TO_RAD * (i * 90 + 45));
  this.corners[i] = new Vector2(dx, dy);
}
this.Update = function(_dt) {
  this.time += _dt;
  this.rotation += this.rotationSpeed * _dt;
  this.cosA = cos(DEG_TO_RAD * this.rotation);
  this.pos.x += cos(this.time * this.oscillationSpeed) * this.xSpeed * _dt
  this.pos.y += this.ySpeed * _dt;
  if (this.pos.y > ConfettiPaper.bounds.y) {
    this.pos.x = random() * ConfettiPaper.bounds.x;
    this.pos.y = 0;
  }
}
this.Draw = function(_g) {
  if (this.cosA > 0) {
    _g.fillStyle = this.frontColor;
  } else {
    _g.fillStyle = this.backColor;
  }
  _g.beginPath();
  _g.moveTo((this.pos.x + this.corners[0].x * this.size) * retina, (this.pos.y + this.corners[0].y * this.size * this.cosA) * retina);
  for (var i = 1; i < 4; i++) {
    _g.lineTo((this.pos.x + this.corners[i].x * this.size) * retina, (this.pos.y + this.corners[i].y * this.size * this.cosA) * retina);
  }
  _g.closePath();
  _g.fill();
}
}
ConfettiPaper.bounds = new Vector2(0, 0);

function ConfettiRibbon(_x, _y, _count, _dist, _thickness, _angle, _mass, _drag) {
this.particleDist = _dist;
this.particleCount = _count;
this.particleMass = _mass;
this.particleDrag = _drag;
this.particles = new Array();
var ci = round(random() * (colors.length - 1));
this.frontColor = colors[ci][0];
this.backColor = colors[ci][1];
this.xOff = (cos(DEG_TO_RAD * _angle) * _thickness);
this.yOff = (sin(DEG_TO_RAD * _angle) * _thickness);
this.position = new Vector2(_x, _y);
this.prevPosition = new Vector2(_x, _y);
this.velocityInherit = (random() * 2 + 4);
this.time = random() * 100;
this.oscillationSpeed = (random() * 2 + 2);
this.oscillationDistance = (random() * 40 + 40);
this.ySpeed = (random() * 40 + 80);
for (var i = 0; i < this.particleCount; i++) {
  this.particles[i] = new EulerMass(_x, _y - i * this.particleDist, this.particleMass, this.particleDrag);
}
this.Update = function(_dt) {
  var i = 0;
  this.time += _dt * this.oscillationSpeed;
  this.position.y += this.ySpeed * _dt;
  this.position.x += cos(this.time) * this.oscillationDistance * _dt;
  this.particles[0].position = this.position;
  var dX = this.prevPosition.x - this.position.x;
  var dY = this.prevPosition.y - this.position.y;
  var delta = sqrt(dX * dX + dY * dY);
  this.prevPosition = new Vector2(this.position.x, this.position.y);
  for (i = 1; i < this.particleCount; i++) {
    var dirP = Vector2.Sub(this.particles[i - 1].position, this.particles[i].position);
    dirP.Normalize();
    dirP.Mul((delta / _dt) * this.velocityInherit);
    this.particles[i].AddForce(dirP);
  }
  for (i = 1; i < this.particleCount; i++) {
    this.particles[i].Integrate(_dt);
  }
  for (i = 1; i < this.particleCount; i++) {
    var rp2 = new Vector2(this.particles[i].position.x, this.particles[i].position.y);
    rp2.Sub(this.particles[i - 1].position);
    rp2.Normalize();
    rp2.Mul(this.particleDist);
    rp2.Add(this.particles[i - 1].position);
    this.particles[i].position = rp2;
  }
  if (this.position.y > ConfettiRibbon.bounds.y + this.particleDist * this.particleCount) {
    this.Reset();
  }
}
this.Reset = function() {
  this.position.y = -random() * ConfettiRibbon.bounds.y;
  this.position.x = random() * ConfettiRibbon.bounds.x;
  this.prevPosition = new Vector2(this.position.x, this.position.y);
  this.velocityInherit = random() * 2 + 4;
  this.time = random() * 100;
  this.oscillationSpeed = random() * 2.0 + 1.5;
  this.oscillationDistance = (random() * 40 + 40);
  this.ySpeed = random() * 40 + 80;
  var ci = round(random() * (colors.length - 1));
  this.frontColor = colors[ci][0];
  this.backColor = colors[ci][1];
  this.particles = new Array();
  for (var i = 0; i < this.particleCount; i++) {
    this.particles[i] = new EulerMass(this.position.x, this.position.y - i * this.particleDist, this.particleMass, this.particleDrag);
  }
};
this.Draw = function(_g) {
  for (var i = 0; i < this.particleCount - 1; i++) {
    var p0 = new Vector2(this.particles[i].position.x + this.xOff, this.particles[i].position.y + this.yOff);
    var p1 = new Vector2(this.particles[i + 1].position.x + this.xOff, this.particles[i + 1].position.y + this.yOff);
    if (this.Side(this.particles[i].position.x, this.particles[i].position.y, this.particles[i + 1].position.x, this.particles[i + 1].position.y, p1.x, p1.y) < 0) {
      _g.fillStyle = this.frontColor;
      _g.strokeStyle = this.frontColor;
    } else {
      _g.fillStyle = this.backColor;
      _g.strokeStyle = this.backColor;
    }
    if (i == 0) {
      _g.beginPath();
      _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
      _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
      _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * retina);
      _g.closePath();
      _g.stroke();
      _g.fill();
      _g.beginPath();
      _g.moveTo(p1.x * retina, p1.y * retina);
      _g.lineTo(p0.x * retina, p0.y * retina);
      _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * retina);
      _g.closePath();
      _g.stroke();
      _g.fill();
    } else if (i == this.particleCount - 2) {
      _g.beginPath();
      _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
      _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
      _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * retina, ((this.particles[i].position.y + p0.y) * 0.5) * retina);
      _g.closePath();
      _g.stroke();
      _g.fill();
      _g.beginPath();
      _g.moveTo(p1.x * retina, p1.y * retina);
      _g.lineTo(p0.x * retina, p0.y * retina);
      _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * retina, ((this.particles[i].position.y + p0.y) * 0.5) * retina);
      _g.closePath();
      _g.stroke();
      _g.fill();
    } else {
      _g.beginPath();
      _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
      _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
      _g.lineTo(p1.x * retina, p1.y * retina);
      _g.lineTo(p0.x * retina, p0.y * retina);
      _g.closePath();
      _g.stroke();
      _g.fill();
    }
  }
}
this.Side = function(x1, y1, x2, y2, x3, y3) {
  return ((x1 - x2) * (y3 - y2) - (y1 - y2) * (x3 - x2));
}
}
ConfettiRibbon.bounds = new Vector2(0, 0);
confetti = {};
confetti.Context = function(id) {
var i = 0;
var canvas = document.getElementById(id);
var canvasParent = canvas.parentNode;
var canvasWidth = canvasParent.offsetWidth;
var canvasHeight = canvasParent.offsetHeight;
canvas.width = canvasWidth * retina;
canvas.height = canvasHeight * retina;
var context = canvas.getContext('2d');
var interval = null;
var confettiRibbons = new Array();
ConfettiRibbon.bounds = new Vector2(canvasWidth, canvasHeight);
for (i = 0; i < confettiRibbonCount; i++) {
  confettiRibbons[i] = new ConfettiRibbon(random() * canvasWidth, -random() * canvasHeight * 2, ribbonPaperCount, ribbonPaperDist, ribbonPaperThick, 45, 1, 0.05);
}
var confettiPapers = new Array();
ConfettiPaper.bounds = new Vector2(canvasWidth, canvasHeight);
for (i = 0; i < confettiPaperCount; i++) {
  confettiPapers[i] = new ConfettiPaper(random() * canvasWidth, random() * canvasHeight);
}
this.resize = function() {
  canvasWidth = canvasParent.offsetWidth;
  canvasHeight = canvasParent.offsetHeight;
  canvas.width = canvasWidth * retina;
  canvas.height = canvasHeight * retina;
  ConfettiPaper.bounds = new Vector2(canvasWidth, canvasHeight);
  ConfettiRibbon.bounds = new Vector2(canvasWidth, canvasHeight);
}
this.start = function() {
  this.stop()
  var context = this;
  this.update();
}
this.stop = function() {
  cAF(this.interval);
}
this.update = function() {
  var i = 0;
  context.clearRect(0, 0, canvas.width, canvas.height);
  for (i = 0; i < confettiPaperCount; i++) {
    confettiPapers[i].Update(duration);
    confettiPapers[i].Draw(context);
  }
  for (i = 0; i < confettiRibbonCount; i++) {
    confettiRibbons[i].Update(duration);
    confettiRibbons[i].Draw(context);
  }
  this.interval = rAF(function() {
    confetti.update();
  });
}
};
var confetti = new confetti.Context('confetti');
confetti.start();
window.addEventListener('resize', function(event){
confetti.resize();
});
});
</script>