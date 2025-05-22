<body>
    <div class="preloader" id="preloader">
            <div class="middle" id="middle">
                <i class="fi fa-solid fa-seedling"></i>
                <i class="fi fa-solid fa-seedling"></i>
                <i class="fi fa-solid fa-seedling"></i>
                <i class="fi fa-solid fa-seedling"></i>
            </div>
        </div>
        <script src="assets/js/loading/loading.js"></script>
  <div class="container">
    <nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Agri<span style="color:#5AAB2F;">Pure</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=product">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=chatbot">AgriPure AI</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kiểm định
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Chỉ nông dân mới có chức năng đăng ký kiểm định, quản lý bán hàng và xem phiếu
                        kiểm định -->
                        <?php 
                            if($_SESSION['role'] == 2)
                            {
                                echo '<li class="dropdown-item">
                                        <a class="nav-link" aria-current="page" href="index.php?page=dangkykiemdinh">Đăng ký kiểm định</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="nav-link" href="index.php?page=xemphieukiemdinh">Xem phiếu kiểm định</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="nav-link" href="index.php?page=xemchungchi&id_nongtrai='.$_SESSION['id_nongtrai'].'">Xem chứng chỉ</a>
                                    </li>';
                            }
                           
                            // Chỉ kiểm định viên mới có chức năng kiểm định
                            if ($_SESSION['role'] == 1) {
                            echo ' <li class="dropdown-item">
                                        <a class="nav-link" href="index.php?page=kiemdinh">Kiểm định nông trại</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="nav-link" href="index.php?page=xemchungchi&id_kdv='.$_SESSION['id_kiemdinhvien'].'">Xem chứng chỉ</a>
                                    </li>';
                            }
                        ?>
                    </ul>
                </li>
                <?php
                    // Chat dành cho khách hàng và nông dân
                    if($_SESSION['role'] == 2 || $_SESSION['role'] == 4)
                    {
                        echo ' <li class="nav-item">
                                <a class="nav-link" href="index.php?page=chat-search">Chat</a>
                            </li>';
                    }
                    if($_SESSION['role'] == 2)
                    {
                        echo ' <li class="nav-item">
                                <a class="nav-link" href="index.php?page=quanlysanpham">Quản lý sản phẩm</a>
                            </li>';
                    }
                ?>
            </ul>
            <!-- Dropdown user -->
            <div class="nav-item dropdown position-relative">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user fa-2xl py-2" style="color:#1da1f2;"></i>
                </a>
                <!-- Phần dropdown: avatar, tên người dùng, nút đăng xuất -->
                <div class="dropdown-menu p-0 my-2 custom-dropdown" style="border: none;">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php 
                                include_once("models/config.php");

                                if (isset($_SESSION['unique_id'])) {
                                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                                    if (mysqli_num_rows($sql) > 0) {
                                        $row = mysqli_fetch_assoc($sql);
                                        $statusColor = ($row['status'] == 'Online') ? 'green' : 'red';
                            ?>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img class="user-avatar rounded-circle" src="assets/images/upload/<?= $row['img'] ?>" alt="avatar"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="text-start">
                                    <p class="mb-0 fw-bold"><?= $row['lname'] ?></p>
                                    <small style="color: <?= $statusColor ?>;"><?= $row['status'] ?></small>
                                </div>
                            </div>
                            <a href="controllers/logout/logout.php?logout_id=<?= $row['unique_id'] ?>" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                            </a>
                            <?php
                                        }
                                    } else {
                            ?>
                            <!-- Trường hợp chưa đăng nhập thì drop sẽ hiện đăng nhập và đăng ký -->
                            <div class="d-grid gap-2">
                                <a href="index.php?page=login" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-user"></i> Đăng nhập
                                </a>
                                <a href="index.php?page=register" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-user"></i> Đăng ký
                                </a>
                            </div>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
      </div>
    </nav>