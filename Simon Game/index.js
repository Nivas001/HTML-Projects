var $buttonColors = ["red","blue","green","yellow"];
var $gamePattern = [];
var $userChosenColor =[];

$(".btn").click(function(e){
    var clicked = e.target.id;
    console.log(clicked);
    $userChosenColor.push(clicked);
    console.log($userChosenColor);
    Blink_Animate(clicked);
    Sounds(clicked);
})

function Sounds($randomChosenColor) {
    var audio = new Audio("sounds/" + $randomChosenColor + ".mp3");
    audio.play();
    $(".btn").classList.add(".pressed");
}

function Blink_Animate($randomChosenColor) {
    $("#" + $randomChosenColor).fadeIn(100).fadeOut(100).fadeIn(100);
}

function nextSequence(){
    var $randomNumber = Math.round(Math.random()*3);
    var $randomChosenColor = $buttonColors[$randomNumber];
    $gamePattern.push[$randomChosenColor];
    Blink_Animate($randomChosenColor);
    Sounds($randomChosenColor);
}





