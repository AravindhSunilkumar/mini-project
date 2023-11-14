<?php

include('connection.php');

function fetchTableData($conn, $tableName)
{
  $sql = "SELECT * FROM $tableName";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}

$questions = fetchTableData($conn, 'tbl_prebuild_questions');
if(isset($_POST['add_question'])){
  $question=$_POST['question'];
  $sql = "";
  $result = $conn->query($sql);

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
   

    #chatbot-icon {
      position: fixed;
      bottom: 430px;
      right: 20px;
      cursor: pointer;
      z-index: 999;
    }

    #chatbot-box {
      position: fixed;
      bottom: 100px;
      right: -394px;
      /* Initially hidden off-screen */
      width: 344px;
      height: 400px;
      background-color: #2196F3;
      border: 1px solid #ccc;
      transition: right 0.3s ease-in-out;
      z-index: 998;
      box-sizing: border-box;
      overflow-y: auto;
      /* Enable vertical scrolling */
    }

    #close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }

    #user-input {
      width: calc(100% - 20px);
      padding: 10px;
      margin: 0 10px 10px;
      box-sizing: border-box;
    }

    .answer {
      display: none;
      margin: 10px;
    }
    @keyframes headShake {
      0%, 100% {
        transform: translateX(0);
      }
      10%, 30%, 50%, 70%, 90% {
        transform: translateX(-10px);
      }
      20%, 40%, 60%, 80% {
        transform: translateX(10px);
      }
    }

    #chatbot-icon img {
      animation: headShake 5s ease infinite;
    }
  </style>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div id="chatbot-icon" onclick="toggleChatbox()">
  <img src="img/chatbot.png" alt="Chatbot Icon" width="50">
</div>

  <div id="chatbot-box">
    <div id="close-btn" onclick="toggleChatbox2()">X</div>
    <p>Hello! I'm a Enamal.</p>
    <?php foreach ($questions as $index => $question) : ?>



      <button onclick="showAnswer('<?php echo $question['prequestion_id']; ?>')"><br>
        <?php echo $question['question']; ?>
      </button>
      <div id="answer_<?php echo $question['prequestion_id']; ?>" class="answer">
        <?php echo $question['answer']; ?>
      </div>

    <?php endforeach; ?>

    <!-- Input box for user questions -->
    <div class="row">
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" id="user-input" style="margin-top:225px;" name="question" placeholder="Type your question...">
        <input type="submit" value="Submit" name="add_question">
      </form>
    </div>
  </div>

  <script>
    function toggleChatbox() {
      var chatbox = document.getElementById("chatbot-box");
      var chaticon = document.getElementById("chatbot-icon");
      chaticon.style.display = 'none';
      var currentRight = parseInt(window.getComputedStyle(chatbox).right);

      if (currentRight === 0) {
        chatbox.style.right = "-394px";
      } else {
        chatbox.style.right = "0";
      }
    }

    function toggleChatbox2() {
      var chatbox = document.getElementById("chatbot-box");
      var chaticon = document.getElementById("chatbot-icon");
      chaticon.style.display = ''; // Corrected from 'display' to 'none'
      var currentRight = parseInt(window.getComputedStyle(chatbox).right);

      if (currentRight === 0) {
        chatbox.style.right = "-394px";
      } else {
        chatbox.style.right = "0";
      }
    }

    function showAnswer(questionId) {
      var answerDiv = document.getElementById("answer_" + questionId);
      // Toggle the display property
      answerDiv.style.display = answerDiv.style.display === 'none' ? 'block' : 'none';
    }
  </script>



</body>

</html>