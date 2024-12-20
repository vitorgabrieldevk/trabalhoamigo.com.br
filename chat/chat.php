<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          $user_id        = mysqli_real_escape_string($conn, $_GET['user_id']);
          $proposta_id    = mysqli_real_escape_string($conn, $_GET['proposta_id']);

          if (!isset($proposta_id)) {
            header("location: ../");  
          }

          $sql = mysqli_query($conn, "SELECT * FROM usuarios WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: ../");
          }
        ?>
        <div class="details">
          <span><?php echo $row['primeiro_nome']. " " . $row['ultimo_nome'] ?></span>
        </div>
      </header>
      <div class="chat-box">
        <div class="background-loading">
          <div class="dot-spinner">
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
              <div class="dot-spinner__dot"></div>
          </div>
        </div>
      </div>
      <form class="typing-area">
          <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
          <input type="text" name="message" class="input-field" placeholder="Digite sua mensagem aqui..." autocomplete="off">
          <button type="submit"><i class="fab fa-telegram-plane"></i></button> <!-- Lembre-se de adicionar type="submit" -->
      </form>


    </section>
  </div>

  <script src="javascript/chat.js"></script>

</body>
</html>
