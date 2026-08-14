<?php
    require_once "../helpers/signupValidation.php";
    require_once "../db.inc.php";
    $formFields = [
    [
        'label'       => 'Full Name',
        'type'        => 'text',
        'id'          => 'username',
        'name'        => 'username',
        'placeholder' => 'e.g. John Doe',
        'required'    => true,
        'minlength'   => 3,
    ],
    [
        'label'       => 'Email Address',
        'type'        => 'email',
        'id'          => 'email',
        'name'        => 'email',
        'placeholder' => 'name@example.com',
        'required'    => true,
        'minlength'   => 3,
    ],
    [
        'label'       => 'Password',
        'type'        => 'password',
        'id'          => 'password',
        'name'        => 'password',
        'placeholder' => '••••••••',
        'required'    => true,
        'minlength'   => 8,
    ],
    [
        'label'       => 'Confirm Password',
        'type'        => 'password',
        'id'          => 'confirm-password',
        'name'        => 'confirm-password',
        'placeholder' => '••••••••',
        'required'    => true,
        'minlength'   => 8,
    ],
    ];

    $email = $username = $password = $confirmPassword = $DbError = $errors = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username'])) {
        $username = $_POST["username"];
    }
    if (isset($_POST['password'])) {
        $password = $_POST["password"];
    }
    if (isset($_POST['email'])) {
        $email = $_POST["email"];
    }
    if (isset($_POST['confirm-password'])) {
        $confirmPassword = $_POST["confirm-password"];
    }
    $errors = validateRegistration($username, $email, $password, $confirmPassword);
    }
    if (empty(($errors))) {
    try {
        $query          = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $insertData     = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertData->bindParam(':username', $username, PDO::PARAM_STR);
        $insertData->bindParam(':email', $email, PDO::PARAM_STR);
        $insertData->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        $insertData->execute();
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $DbError = $e->getMessage();
    }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Sign up</title>
</head>

<body class="flex flex-col justify-center items-center h-screen w-full">

    <main>
        <section>
            <h1 class="text-3xl text-center font-semibold text-primary">todoly</h1>
            <p class="bg-gray text-center  my-4">Create your accountto start organizing</p>
        </section>
        <section class="border bg-main p-6 rounded-md shadow-md max-w-md w-80  sm:w-140 mx-auto">
            <form  method="POST" class="space-y-4">
                <?php foreach ($formFields as $field):
                        $fieldName  = $field['name'];
                        $fieldValue = ($field['type'] !== 'password') ? (${$fieldName} ?? '') : '';
                ?>
                    <div class="flex flex-col space-y-1.5">
                        <label for="<?php echo $field['id'] ?>" class="text-sm font-medium">
                            <?php echo $field['label'] ?>
                        </label>
                        <input
                            class="border border-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-md p-2.5 outline-none transition-all"
                            type="<?php echo $field['type'] ?>"
                            id="<?php echo $field['id'] ?>"
                            name="<?php echo $field['name'] ?>"
                            value="<?php echo htmlspecialchars($fieldValue) ?>"
                            placeholder="<?php echo $field['placeholder'] ?>"
                            <?php echo $field['required'] ? 'required' : '' ?>
                            <?php echo empty($field['minlength']) ? $field['minlength'] : 3 ?>>

                        <?php if (isset($errors[$fieldName])): ?>
                            <span class="text-red-500 text-xs font-medium">
                                <?php echo htmlspecialchars($errors[$fieldName]) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button class="bg-primary cursor-pointer hover:opacity-90 mt-6 w-full flex rounded-md justify-center items-center gap-2 p-3 text-white font-medium transition-all" type="submit">
                    <span>Create Account</span>
                    <img class="w-5 h-5" src="../assets/arrow right.svg" alt="">
                </button>

                <span class="flex justify-center items-center gap-3">
                    <p class="text-gray-500">Already have an account? </p>
                    <a class="text-primary hover:underline hover:underline-offset-2 font-semibold" href="../views/login.php">Sign in</a>
                </span>
            </form>
        </section>

    </main>
</body>

</html>