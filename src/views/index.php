<?php

    session_start();
    include '../db.inc.php';
    require_once '../helpers/todosValidation.php';
    $userId     = $_SESSION["userId"] ?? 20;
    $formFields = [
    [
        'name'      => 'title',
        'id'        => "title",
        'for'       => "title",
        'label'     => 'Title',
        'type'      => 'text',
        'maxlength' => 255,
        'required'  => "required",
    ],
    [
        'name'      => 'content',
        'id'        => "content",
        'for'       => "content",
        'label'     => 'Content',
        'type'      => 'textarea',
        'maxlength' => 1000,
        'required'  => "required",
    ],
    [
        'name'     => 'date',
        'id'       => "date",
        'for'      => "date",
        'label'    => 'Date',
        'type'     => 'date',
        'required' => "required",
    ],
    [
        'name'     => 'status',
        'id'       => "status",
        'for'      => "status",
        'label'    => 'Status',
        'type'     => 'select',
        'options'  => [
            'pending'     => 'Pending',
            'in_progress' => 'In Progress',
            'completed'   => 'Completed',
        ],
        'default'  => 'pending',
        'required' => "required",
    ],
    ];
    $title = $dueDate = $content = $status = $errors = $DbError = "";

    $todos             = [];
    $notCompletedTasks = 0;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["title"])) {
        $title = $_POST["title"];
    }
    if (isset($_POST["content"])) {
        $content = $_POST["content"];
    }
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    }
    if (isset($_POST["date"])) {
        $dueDate = $_POST["date"];
    }
    $inputsErrors = todosInputsValidation($title, $content, $status, $dueDate);
    if (empty($inputsErrors)) {
        try {
            $query = "INSERT INTO todos (title,content,status,user_id,due_date)  VALUES (:title,:content,:status,:userId,:date)
";

            $insertTodo = $conn->prepare($query);
            $insertTodo->bindParam(":title", $title, PDO::PARAM_STR);
            $insertTodo->bindParam(":content", $content, PDO::PARAM_STR);
            $insertTodo->bindParam(":status", $status, PDO::PARAM_STR);
            $insertTodo->bindParam(":date", $dueDate, PDO::PARAM_STR);
            $insertTodo->bindParam(":userId", $userId, PDO::PARAM_INT);
            $insertTodo->execute();
            $_SESSION['success'] = "Todo was created successfully!";
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error-database'] = "Something went wrong, please try later again";
        }
    }
    }

    date_default_timezone_set('Europe/Amsterdam');
    $hour = intval(date("H"));
    if ($hour >= 5 && $hour < 12) {
    $greeting = "Good morning";
    } elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good afternoon";
    } else {
    $greeting = "Good evening";
    }

    function notCompletedTasks(array $data): int
    {

    $amount = 0;
    foreach ($data as $task) {
        if ($task["status"] !== "completed") {
            $amount++;
        }
    }
    return $amount;
    }

    try {
    $query       = "SELECT due_date,created_at,title,status FROM todos where user_id =  :userId";
    $getUserTodo = $conn->prepare($query);
    $getUserTodo->bindParam(":userId", $userId, PDO::PARAM_INT);
    $getUserTodo->execute();
    $todos             = $getUserTodo->fetchAll(PDO::FETCH_ASSOC);
    $notCompletedTasks = notCompletedTasks($todos);
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Todos</title>
</head>

<body>
    <?php require_once "../partials/navbar.php"?>
    <main class="pt-10">
        <section>
            <div class="max-w-lg mx-auto mb-6 px-1">
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight"><?php echo $greeting ?> 👋</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">
                    You have <span class="font-semibold text-indigo-600"><?php echo htmlspecialchars($notCompletedTasks) ?> tasks</span> to complete
                </p>
            </div>
            <div class="max-w-lg mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-slate-100">
                <form method="POST" class="space-y-5">

                    <?php foreach ($formFields as $field):
                            $fieldsNames = $field;
                    ?>
                        <div>
                            <label for="<?php echo $field['for'] ?>" class=" text-sm font-semibold text-slate-700 mb-1">
                                <?php echo $field['label'] ?>
                            </label>

                            <?php if ($field['type'] === 'textarea'): ?>
                                <textarea
                                    name="<?php echo $field['name'] ?>"
                                    id="<?php echo $field['id'] ?>"
                                    rows="4"
                                    maxlength="<?php echo $field['maxlength'] ?? '' ?>"
                                    <?php echo isset($field['required']) ? 'required' : '' ?>
                                    class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-y"></textarea>
                                <?php if (! empty($inputsErrors[$field['name']])): ?>
                                    <p class="mt-1 text-xs font-medium text-rose-500">
                                        <?php echo htmlspecialchars($inputsErrors[$field['name']]) ?>
                                    </p>
                                <?php endif; ?>
                            <?php elseif ($field['type'] === 'select'): ?>
                                <select
                                    name="<?php echo $field['name'] ?>"
                                    id="<?php echo $field['id'] ?>"
                                    <?php echo isset($field['required']) ? 'required' : '' ?>
                                    class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                                    <?php foreach ($field['options'] as $value => $label): ?>
                                        <option
                                            value="<?php echo $value ?>"
                                            <?php echo(isset($field['default']) && $field['default'] === $value) ? 'selected' : '' ?>>
                                            <?php echo $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (! empty($inputsErrors[$field['name']]) && isset($inputsErrors[$field['name']])): ?>
                                    <p class="mt-1 text-xs font-medium text-rose-500">
                                        <?php echo htmlspecialchars($inputsErrors[$field['name']]) ?>
                                    </p>
                                <?php endif; ?>
                            <?php else: ?>
                                <input
                                    type="<?php echo $field['type'] ?>"
                                    name="<?php echo $field['name'] ?>"
                                    id="<?php echo $field['id'] ?>"
                                    maxlength="<?php echo $field['maxlength'] ?? '' ?>"
                                    <?php echo isset($field['required']) ? 'required' : '' ?>
                                    class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <?php if (! empty($inputsErrors[$field['name']]) && isset($inputsErrors[$field['name']])): ?>
                                    <p class="mt-1 text-xs font-medium text-rose-500">
                                        <?php echo htmlspecialchars($inputsErrors[$field['name']]) ?>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <p id="successMessage" data-message="<?php echo htmlspecialchars($_SESSION['success']); ?>" class="hidden"></p>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error-database'])): ?>
                        <p id="dbError" data-message="<?php echo htmlspecialchars($_SESSION['error-database']); ?>" class="hidden"></p>
                        <?php unset($_SESSION['error-database']); ?>
                    <?php endif; ?>
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Creaete a Task
                    </button>

                </form>
            </div>
        </section>
        <section>
            <div class="max-w-xl mx-auto my-6 font-sans">

                <h3 class="text-lg font-bold text-slate-800">Today</h3>
                <hr class="my-2">

                <?php foreach ($todos as $todo):
                        if ($todo['due_date'] === date("Y-m-d")):
                            $formattedStatus = ucwords(str_replace('_', ' ', $todo['status']));

                            $statusClasses = match ($todo['status']) {
                                'completed'   => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                'in_progress' => 'bg-amber-50 text-amber-600 border-amber-200',
                                default       => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                ?>
                        <div class="flex items-center justify-between p-4 mb-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 transition-colors">

                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <p class="text-slate-800 text-sm font-medium select-none">
                                    <?php echo htmlspecialchars($todo['title']) ?>
                                </p>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?php echo $statusClasses; ?>">
                                <?php echo htmlspecialchars($formattedStatus) ?>
                            </span>

                        </div>
                    <?php endif?>
                <?php endforeach; ?>

            </div>
        </section>
    </main>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../js/index.js" defer></script>
</body>

</html>