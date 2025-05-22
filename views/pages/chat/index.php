<?php 
    include_once("models/config.php");
    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

?>

<div class="content my-2" >
  <section class="chat-area border border-info p-2">
    <header class="row d-flex align-items-center">
      <?php 
        if (!isset($_GET['user_id']) || empty($_GET['user_id']) || !isset($_SESSION['unique_id'])) {
            header("Location: index.php?page=chat-search");
            exit();
        }
    
        $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id='{$user_id}'");
        if ($row = mysqli_fetch_assoc($sql)) { 
      ?>
      <div class="col-2">
        <a href="index.php?page=chat-search" class="black-icon"><i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="col-10 d-flex flex-row-reverse ">
        <img class="user-avatar rounded-circle mx-2" src="assets/images/upload/<?php echo $row['img']; ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;">
        <div class="details">
          <span><?php echo $row['lname']; ?></span>
          <p id="status-text" class="<?php echo ($row['status'] == 'Online') ? 'text-success' : 'text-danger'?>"><?php echo $row['status']; ?></p>
        </div>
      </div>
      <?php } ?>
    </header>
    <div class="chat-box my-2 rounded p-2 bg-light border overflow-auto" style="height: 70vh; max-height:70vh; background-color:#e8f2f7;"></div>
    <form action="#" class="typing-area">
      <input type="text" name="incoming_id" class="incoming_id" value="<?php echo $user_id; ?>" hidden>
      <div class="input-group">
        <input type="text" name="message" class="input-field form-control" placeholder="Nhập tin nhắn tại đây..." autocomplete="off">
        <button class="input-group-text" id="basic-chat"><i class="fa-brands fa-telegram"></i></button>
      </div>
    </form>
  </section>
</div>
<script src="assets/js/chat/chat.js"></script>
<script src="assets/js/chat/check-status.js"></script>