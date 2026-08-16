<?php
    include_once "../db.inc.php";
    include_once "../helpers/profileVaildation.php";
    require_once "../middleware/auth.php";
    session_start();
    $userId          = $_SESSION["userId"];
    $checkPassowrds  = [];
    $currentPassword = $newPassword = $errorPassword = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["current-password"])) {
        $currentPassword = $_POST["current-password"];
    }
    if (isset($_POST["new-password"])) {
        $newPassword = $_POST["new-password"];
    }
    $checkPassowrds = VaildatePassword($newPassword, $currentPassword);

    if (empty($checkPassowrds)) {
        try {
            $query   = "SELECT password,email From users where id = :id";
            $getUser = $conn->prepare($query);
            $getUser->bindParam(":id", $userId, PDO::PARAM_INT);
            $getUser->execute();
            $getUserData = $getUser->fetch(PDO::FETCH_OBJ);
            if ($getUserData && password_verify($currentPassword, $getUserData->password)) {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

                $query          = "UPDATE users SET password = :password WHERE id = :id";
                $updatePassword = $conn->prepare($query);
                $updatePassword->bindParam(":id", $userId, PDO::PARAM_INT);
                $updatePassword->bindParam(":password", $newHash, PDO::PARAM_STR);
                $updatePassword->execute();

                header("Location: profile.php");
                exit();
            } else {
                $_SESSION["incorrect-password"] = "password is not correct";
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }
    $_SESSION["new-password"]     = $checkPassowrds["new-password"];
    $_SESSION["current-password"] = $checkPassowrds["current-password"];
    }

    try {
    $query   = "SELECT username,email From users where id = :id";
    $getInfo = $conn->prepare($query);
    $getInfo->bindParam(":id", $userId, PDO::PARAM_INT);
    $getInfo->execute();
    $getUserInfo = $getInfo->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
    echo $e;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Profile</title>
</head>

<body class="bg-slate-50 min-h-screen  flex flex-col">

    <?php include_once "../partials/navbar.php"?>


    <main class="flex flex-col justify-between gap-5 pt-7 m-7">
        <section class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2 text-blue-600 font-bold text-xl mb-6">
                <h3 class="text-gray-800 font-bold">Account Settings</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Full Name</label>
                    <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 bg-gray-50 text-sm font-medium select-none">
                        <?php echo htmlspecialchars($getUserInfo->username); ?>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Email Address</label>
                    <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 bg-gray-50 text-sm font-medium select-none">
                        <?php echo htmlspecialchars($getUserInfo->email); ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2 text-blue-600 font-bold text-xl mb-6">
                <h3 class="text-gray-800 font-bold">Security</h3>
            </div>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold text-sm mb-2">Current Password</label>
                        <input type="password"
                            name="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:border-blue-500 text-gray-700 bg-white shadow-sm text-sm">
                        <?php if (isset($_SESSION["current-password"])): ?>
                            <p class="text-red-500 errors" data-current-password="<?php echo $_SESSION["current-password"] ?>"></p>
                        <?php endif?>
                        <?php
                            unset($_SESSION["current-password"]);
                        ?>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold text-sm mb-2">New Password</label>
                        <input type="password"
                            name="new-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:border-blue-500 text-gray-700 bg-white shadow-sm text-sm">
                        <?php if (isset($_SESSION["new-password"])): ?>
                            <p class="text-red-500 errors" data-new-password="<?php echo $_SESSION["new-password"] ?>"></p>
                        <?php endif?>
                        <?php
                            unset($_SESSION["new-password"]);
                        ?>
                    </div>
                </div>
                <?php if (isset($_SESSION["incorrect-password"])): ?>
                    <p class="text-red-500 errors" data-incorrect-password="<?php echo $_SESSION["incorrect-password"] ?>"></p>
                <?php endif?>
                <?php
                    unset($_SESSION["incorrect-password"]);
                ?>
                <div>
                    <button type="submit" name="update_password" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl transition text-sm shadow-sm">
                        Update Password
                    </button>
                </div>
            </form>
        </section>

    </main>


    <?php include_once "../partials/footer.php"?>
    <script src="../js/profile.js" defer></script>
</body>

</html>