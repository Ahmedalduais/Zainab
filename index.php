<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'users_db';

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التعامل مع طلب الحذف
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id='$id'";
    $conn->query($sql);
}

// التعامل مع طلب الإضافة
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $sql = "INSERT INTO users (username, phone, email) VALUES ('$username', '$phone', '$email')";
    $conn->query($sql);
}

// التعامل مع طلب التعديل
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $sql = "UPDATE users SET username='$username', phone='$phone', email='$email' WHERE id='$id'";
    $conn->query($sql);
}

// استرجاع بيانات المستخدمين
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة حسابات المستخدمين</title>
    <link rel="stylesheet" href="nax.css">
    <style>
        /* أنماط النافذة المنبثقة */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="app" class="container">
    <h1>نظام إدارة حسابات المستخدمين</h1>

    <!-- زر لفتح مودال إضافة مستخدم جديد -->
    <button id="addUserBtn" class="btn btn-add">إضافة مستخدم جديد</button>

    <!-- عرض بيانات المستخدمين -->
    <h2>قائمة المستخدمين</h2>
    <table>
        <thead>
            <tr>
                <th>اسم المستخدم</th>
                <th>رقم الهاتف</th>
                <th>البريد الإلكتروني</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['username']; ?>', '<?php echo $row['phone']; ?>', '<?php echo $row['email']; ?>')">تعديل</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-delete">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- مودال إضافة مستخدم -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>إضافة مستخدم جديد</h2>
        <form method="POST">
            <label for="username">اسم المستخدم:</label>
            <input type="text" id="username" name="username" required>
            <label for="phone">رقم الهاتف:</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="email">البريد الإلكتروني:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" name="add" class="btn btn-add">إضافة</button>
        </form>
    </div>
</div>

<!-- مودال تعديل مستخدم -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>تعديل مستخدم</h2>
        <form method="POST" id="editUserForm">
            <input type="hidden" name="id" id="editUserId">
            <label for="editUsername">اسم المستخدم:</label>
            <input type="text" id="editUsername" name="username" required>
            <label for="editPhone">رقم الهاتف:</label>
            <input type="tel" id="editPhone" name="phone" required>
            <label for="editEmail">البريد الإلكتروني:</label>
            <input type="email" id="editEmail" name="email" required>
            <button type="submit" name="edit" class="btn btn-edit">تعديل</button>
        </form>
    </div>
</div>

<script>
    // فتح مودال إضافة المستخدم
    document.getElementById('addUserBtn').onclick = function() {
        document.getElementById('addUserModal').style.display = 'block';
    }

    // إغلاق مودال إضافة المستخدم
    function closeAddModal() {
        document.getElementById('addUserModal').style.display = 'none';
    }

    // فتح مودال تعديل المستخدم
    function openEditModal(id, username, phone, email) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editEmail').value = email;
        document.getElementById('editUserModal').style.display = 'block';
    }

    // إغلاق مودال تعديل المستخدم
    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }

    // إغلاق المودال عند النقر خارج المحتوى
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            closeAddModal();
            closeEditModal();
        }
    }
</script>

</body>
</html>

<?php
// إغلاق الاتصال
$conn->close();
?>
