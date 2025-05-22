<?php 
    if(!isset($_SESSION['unique_id']))
    {
       header("Location: index.php?page=login");
    }
?>


    <div class="content my-2" >
        <h2 class="text-center my-3">Chat</h2>
      <div class="row chat">
        <div class="search">
            <!-- <span class="text">Chọn người cần chat</span> -->
            <div class="input-group my-2">
                <input class="form-control" type="text" name="searchTerm" placeholder="Nhập tên người cần chat" aria-describedby="basic-search">
                <button class="input-group-text" id="basic-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="users-list">

            </div>
            <script type="text/javascript" src="assets/js/chat-search/chat-search.js"></script>
        </div>
      </div>
    </div>

