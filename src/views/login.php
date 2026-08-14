<?php
    require_once "../db.inc.php";
    require_once "../helpers/loginValidation.php";
    $formFields = [
    [
        'label'       => 'Email Address',
        'type'        => 'email',
        'id'          => 'email',
        'name'        => 'email',
        'placeholder' => 'kalin@gmail.com',
        'required'    => 'required',
        'minlength'   => 3,
    ],
    [
        'label'       => 'Password',
        'type'        => 'password',
        'id'          => 'password',
        'name'        => 'password',
        'placeholder' => '••••••••',
        'required'    => 'required',
        'minlength'   => 8,
    ],
    ];

    $email = $password = $errorPasswordOrEmail = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    $inputsCheck = validateLoginInputs($email, $password);

    if (empty($inputsCheck)) {

        try {
            $query   = "SELECT email,password,id From users where email = :email ";
            $getData = $conn->prepare($query);
            $getData->bindParam(":email", $email);
            $getData->execute();
            $existedEmailDb = $getData->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            $errorPasswordOrEmail = "Something went wrong. Please try again later.";
        }
        if (! empty($existedEmailDb)) {
            $encodePasswordHash = password_verify($password, $existedEmailDb->password);
            if ($encodePasswordHash) {
                header("Location: index.php");
                exit();
            } else {
                $errorPasswordOrEmail = "your email or password is not correct";
            }
        } else {
            $errorPasswordOrEmail = "your email or password is not correct";
        }
    } else {
        if (is_array($inputsCheck)) {
            $errorPasswordOrEmail = $inputsCheck['emailAndPassword'];
        }
    }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Login - FocusFlow</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-hsl(227, 100%, 98%) px-4">
    <main class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-primary">
                FocusFlow</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 italic">Clarity in every task.</p>
        </div>

        <form method="post" class="bg-white shadow-xl shadow-blue-500 rounded-md p-8 border-gray-100 dark:border-gray-700 space-y-6">
            <?php foreach ($formFields as $field): ?>


                <div class="flex relative flex-col space-y-2">
                    <label for="<?php echo $field['id'] ?>" class="text-sm font-semibold  ">
                        <?php echo $field['label'] ?>
                    </label>
                    <input
                        type="<?php echo $field['type'] ?>"
                        name="<?php echo $field['name'] ?>"
                        id="<?php echo trim($field['id']) ?>"
                        placeholder="<?php echo $field['placeholder'] ?>"
                        minlength="<?php echo $field['minlength'] ?>"
                        <?php echo $field['required'] ? 'required' : '' ?>
                        class="w-full px-4 py-3 rounded-lg border">

                    <?php if ($field['name'] === "password"): ?>
                        <span class="absolute cursor-pointer right-4 top-11" id="eyeContainer"><img id="eye" class="w-5 h-fit" src="../assets/Eye.svg" alt=""></span>
                    <?php endif?>
                </div>
                <?php endforeach?>
                <?php if (! empty($errorPasswordOrEmail)): ?>
                    <p  class="text-red-500 errorMsg"><?php echo $errorPasswordOrEmail ?></p>
                <?php endif?>



            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out transform active:scale-[0.98]">
                Login
            </button>
            <div class="text-center pt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center space-x-1 rtl:space-x-reverse">
                <span>Don't have an account?</span>
                <a href="./signup.php" class="text-primary font-semibold hover:underline">Sign up for free</a>
            </div>
        </form>
    </main>

    <script src="../js/login.js" defer></script>
</body>

</html>