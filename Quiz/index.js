let currentQuestionIndex = 0;

document.querySelectorAll(".choice .ch").forEach((button) => {
  button.addEventListener("click", () => {
    document
      .querySelectorAll(".choice .ch")
      .forEach((btn) => btn.classList.remove("selected"));
    button.classList.add("selected");
  });

  button.addEventListener("dblclick", () => {
    document
      .querySelectorAll(".choice .ch")
      .forEach((btn) => btn.classList.remove("selected"));
  });
});

const questions = [
  //1
  {
    question: "What is the Jersey number of Indian cricketer Virat Kholi",
    choice: ["45", "18", "7", "10"],
    answer: "18",
  },

  //2
  {
    question: "How many bones are there in Human Body",
    choice: ["206", "208", "210", "212"],
    answer: "206",
  },

  //3
  {
    question: "What is the capital of India",
    choice: ["Mumbai", "New Delhi", "Kolkata", "Chennai"],
    answer: "New Delhi",
  },

  //4
  {
    question: "Find the odd one out",
    choice: ["Franklin", "Micheal", "Trevor", "Lamar"],
    answer: "Lamar",
  },

  //5
  {
    question: "What is the largest lake in the world",
    choice: ["Caspian Sea", "Lake Superior", "Lake Victoria", "Lake Baikal"],
    answer: "Caspian Sea",
  },

  //6
  {
    question: "What is the capital of Australia",
    choice: ["Sydney", "Melbourne", "Canberra", "Brisbane"],
    answer: "Canberra",
  },

  //7
  {
    question: "Which planet in the solar system is known as the 'Red Planet'",
    choice: ["Venus", "Earth", "Mars", "Jupiter"],
    answer: "Mars",
  },

  //8
  {
    question: "Who is the protaginest of the game Red Dead Redemption 2",
    choice: [
      "Arthur Morgan",
      "John Marston",
      "Dutch Van der Linde",
      "Bill Williamson",
    ],
    answer: "Arthur Morgan",
  },

  //9
  {
    question: "What is the capital of Japan?",
    choice: ["Beijing", "Tokyo", "Seoul", "Bangkok"],
    answer: "Tokyo",
  },

  //10
  {
    question:
      "What is the name of the process by which plants convert sunlight into energy?",
    choice: ["Respiration", "Photosynthesis", "Oxidation", "Evolution"],
    answer: "Photosynthesis",
  },

  //11
  {
    question: "Hitler's party is known as",
    choice: ["Labour Party", "Nazi Party", "Ku-Klux-Klan", "Democratic Party"],
    answer: "Nazi Party",
  },

  //12
  {
    question: "Doremon is a cartoon based on the city",
    choice: ["Tokyo", "Osaka", "Yokohama", "Hiroshima"],
    answer: "Tokyo",
  },

  //13
  {
    question: "Which is the most sold Rockstar games?:",
    choice: ["Red Dead Redemption", "Bully", "GTA V", "The Warriors"],
    answer: "GTA V",
  },

  //14
  {
    question: "What is the official currency of Japan?",
    choice: ["Won", "Yuan", "Yen", "Dollars"],
    answer: "Yen",
  },

  //15
  {
    question:
      "Which organ in the human body is responsible for the secretion of bile?",
    choice: ["Liver", "Kidneys", "Spleen", "Stomach"],
    answer: "Liver",
  },

  //16
  {
    question: "Which is an open-source Operating System",
    choice: ["Windows", "Linux", "MacOS", "iOS"],
    answer: "Linux",
  },

  //17
  {
    question: "Indian cricket team won Champions Trophy in the year",
    choice: ["2024", "2011", "2013", "1983"],
    answer: "2013",
  },

  //18
  {
    question: "What is the capital of Pakistan",
    choice: ["Karachi", "Islamabad", "Lahore", "Quetta"],
    answer: "Islamabad",
  },

  //19
  {
    question: "Which is the largest mammal in the world",
    choice: ["Elephant", "Blue Whale", "Hippopotamus", "Giraffe"],
    answer: "Blue Whale",
  },

  //20
  {
    question: "Which is the largest desert in the world",
    choice: ["Sahara", "Arabian", "Gobi", "Kalahari"],
    answer: "Sahara",
  },
];

function change_question() {
  let question_no = currentQuestionIndex;
  let answers = [];

  var question_container = document.querySelector(".question");
  var choices = document.querySelectorAll(".choice .ch");

  question_container.innerHTML = `<h2>${questions[question_no].question}</h2>`;
  choices.forEach((choice, index) => {
    choice.innerHTML = questions[question_no].choice[index];
    choice.classList.remove("selected");
  });
}
var score = 0;
function next_question() {
  let selected_choice = document.querySelector(".selected");

  if (selected_choice) {
    let question_no = currentQuestionIndex;
    let selected_answer = selected_choice.innerHTML;
    let correct_answer = questions[question_no].answer;
    console.log(selected_answer, correct_answer);

    if (selected_answer === correct_answer) {
      score++;
    }

    // if (selected_answer === correct_answer) {
    //   selected_choice.style.backgroundColor = "green";
    // } else {
    //   selected_choice.style.backgroundColor = "red";
    // }

    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
      change_question();
    } else {
      alert("Quiz Completed!! Your score is " + score + " /20");
    }
  } else {
    alert("Please select an answer");
  }
}

document.querySelector(".next").addEventListener("click", next_question);

change_question();
