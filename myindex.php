<!DOCTYPE html>
<html lang="en">
  <head> 
    <title> KNOWLEDGE ENGINE </title> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  </head> 

  <body>
    <div class="absolute-center">
        <span id="close"><i class="fa fa-fw fa-times-circle-o"></i></span>
    </div>

    <script>
      /*VARIABLES*/

      canvas = document.getElementsByTagName('canvas')[0];
      canvas.width = document.body.clientWidth;
      canvas.height = document.body.clientHeight;

      var ctx = canvas.getContext('2d');
      /*Modify options here*/

      //possible characters that will appear
      var characterList = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

      //stocks possible character attributes
      var layers = {
          n: 5, //number of layers
          letters: [100, 40, 30, 20, 10], //letters per layer (starting from the deepest layer)
          coef: [0.1, 0.2, 0.4, 0.6, 0.8], //how much the letters move from the mouse (starting from the deepest layer)
          size: [16, 22, 36, 40, 46], //font size of the letters (starting from the deepest layer)
          color: ['#fff', '#eee', '#ccc', '#bbb', '#aaa'], //color of the letters (starting from the deepest layer)
          font: 'Courier' //font family (of every layer)
      };

      /*End of options*/

      var characters = [];
      var mouseX = document.body.clientWidth/2;
      var mouseY = document.body.clientHeight/2;

      var rnd = {
          btwn: function(min, max) {
              return Math.floor(Math.random() * (max - min) + min);
          },
          choose: function(list) {
              return list[rnd.btwn(0, list.length)];
          }
      };

      /*LETTER DRAWING*/

      function drawLetter(char) {
          ctx.font = char.size + 'px ' + char.font;
          ctx.fillStyle = char.color;
          
          var x = char.posX + (mouseX-canvas.width/2)*char.coef;
          var y = char.posY + (mouseY-canvas.height/2)*char.coef;

          ctx.fillText(char.char, x, y);
      }

      /*ANIMATION*/

      document.onmousemove = function(ev) {
          mouseX = ev.pageX - canvas.offsetLeft;
          mouseY = ev.pageY - canvas.offsetTop;

          if (window.requestAnimationFrame) {
              requestAnimationFrame(update);
          } else {
              update();
          }
      };

      function update() {
          clear();
          render();
      }

      function clear() {
          ctx.clearRect(0, 0, canvas.width, canvas.height);
      }

      function render() {
          for (var i = 0; i < characters.length; i++) {
              drawLetter(characters[i]);
          }
      }

      /*INITIALIZE*/

      function createLetters() {
          for (var i = 0; i < layers.n; i++) {
              for (var j = 0; j < layers.letters[i]; j++) {

                  var character = rnd.choose(characterList);
                  var x = rnd.btwn(0, canvas.width);
                  var y = rnd.btwn(0, canvas.height);

                  characters.push({
                      char: character,
                      font: layers.font,
                      size: layers.size[i],
                      color: layers.color[i],
                      layer: i,
                      coef: layers.coef[i],
                      posX: x,
                      posY: y
                  });

              }
          }
      }

      createLetters();
      update();

      /*REAJUST CANVAS AFTER RESIZE*/

      window.onresize = function() {
          location.reload();
      };

      document.getElementById('close').onclick = function() {
          this.parentElement.style.visibility = 'hidden';
          this.parentElement.style.opacity = '0';
      }
    </script>
    
    <form action = 'myquery.php' method = 'get' > <!-- change -->  
      <center id="content">
          <h1><b><font face="Bradley Hand ITC" color="White"> KNOWLEDGE ENGINE </font></b></h1 > 
          <h2><font face="Charcoal" color="White"> Science Search For Kids </font></h2 >

        <div class='comment'>
          <span>
            <input type="text" id="q"  name="q" placeholder="So what's cooking ?"/>
          </span>
        </div>

        <br></br>

        <button class="button">GO!!</button>
      
        <br></br><br></br>
        
        <img class="img-responsive" src="Used_Images/science.png" align="middle" style="width:500px;height:220px;">

      </center > 
    </form >
  </body > 
</html>

<style>
@import 'https://fonts.googleapis.com/css?family=Open+Sans:300|Roboto:400';

body {
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    margin: 0;
    padding: 0;
    
    background: #1f263b;
    background: -moz-linear-gradient(top, #1f263b 0%, #2c3654 100%);
    background: -webkit-linear-gradient(top, #1f263b 0%,#2c3654 100%);
    background: linear-gradient(to bottom, #1f263b 0%,#2c3654 100%);
}

div.absolute-center {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    
    opacity: 1;
    visibility: visible;
    
    transition: all .5s;
}

h3, h5 {
    padding: 10px 30px 10px 30px;
    box-sizing: border-box;
    
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 5px 0 black;
    
    font-family: 'Open Sans';
    font-weight: 300;
    text-align: center;
    letter-spacing: 3px;
}

span#close {
    position: absolute;
    top: -15px;
    left: -30px;
    
    color: white;
    font-size: 30px;
    text-shadow: 0 0 5px black;
    
    cursor: pointer;
}

canvas {
    //border: 2px solid;
}


.button {
  padding: 15px 25px;
  font-size: 24px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #fff;
  background-color: blue;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button:hover {background-color: lightblue}

.button:active {
  background-color: lightblue;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
} 

.comment{
  margin:0 auto;
  width:400px;
  height: 45px;
  text-align:center;
}
#q {
    border: 2px solid rgb(173, 204, 204);
    height: 45px;
    width: 400px;
    box-shadow: 0 0 27px rgb(204, 204, 204) inset;
    transition: 500ms all ease;
    padding: 3px 3px 3px 3px;
}
#q:hover,
#q:focus {
    width: 300px;
    transition: 500ms all ease;
    background-size: 25px 25px;
    background-position: 96% 62%;
    padding: 3px 32px 3px 3px;
}

body,html{margin:0;padding:0;height:100%;width:100%;}
#content {position:absolute;left:0;top:0;z-index:1;height:100%;width:100%;}

</style>