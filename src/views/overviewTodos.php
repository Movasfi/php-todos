<?php

    session_start();
    require_once "../db.inc.php";
    include_once "../middleware/auth.php";
    $userId   = $_SESSION["userId"];
    $userData = [];
    try {
    $query   = "SELECT status from todos where user_id = :id";
    $getData = $conn->prepare($query);
    $getData->bindParam(":id", $userId, PDO::PARAM_STR);
    $getData->execute();
    $userData = $getData->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    echo $e;
    }

    try {
    $getTodosquery = "
    SELECT * FROM todos
    WHERE user_id = :id
    ORDER BY created_at DESC
";
    $getQuery = $stmtRecent = $conn->prepare($getTodosquery);
    $getQuery->bindParam(":id", $userId);
    $getQuery->execute();
    $allTasks = $getQuery->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    echo $e;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $todoId = $_POST['task_id'] ?? null;
    $action = $_POST['action'];

    if ($action === 'update_status' && isset($_POST['status'])) {
        $status = $_POST['status'];

        try {
            $updateStatusQuery = "
                UPDATE todos
                SET status = :status
                WHERE id = :id AND user_id = :user_id
            ";
            $updateQuery = $conn->prepare($updateStatusQuery);
            $updateQuery->bindParam(":status", $status);
            $updateQuery->bindParam(":id", $todoId);
            $updateQuery->bindParam(":user_id", $userId);
            $updateQuery->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    if ($action === 'delete_task') {
        try {
            $deleteTodoQuery = "
                DELETE FROM todos
                WHERE id = :id AND user_id = :user_id
            ";
            $deleteQuery = $conn->prepare($deleteTodoQuery);
            $deleteQuery->bindParam(":id", $todoId);
            $deleteQuery->bindParam(":user_id", $userId);
            $deleteQuery->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            echo $e;
        }
    }
    }

    $totalTasks = count($userData);
    function calculateTasksStatus(array $data, string $cate): int
    {
    $amount = 0;
    foreach ($data as $key) {
        if ($key["status"] === "$cate") {
            $amount++;
        }
    }
    return $amount;
    }
    $inProgress     = calculateTasksStatus($userData, "in_progress");
    $completedTasks = calculateTasksStatus($userData, "completed");
    $pendingTasks   = calculateTasksStatus($userData, "pending");

    $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

    $dashOffset = 100 - $completionRate;

    $dashboardData = [
    'title'    => 'Productivity Overview',
    'subtitle' => 'Your week at a glance. Keep up the good work.',
    'stats'    => [
        [
            'title'     => 'TOTAL TASKS',
            'value'     => $totalTasks,
            'color'     => 'blue',
            "icon"      => "../assets/total.svg",
            "iconClass" => "w-15",
        ],
        [
            'title'     => 'PENDING',
            'value'     => $pendingTasks,
            'color'     => 'amber',
            "icon"      => "../assets/pending.svg",
            "iconClass" => "w-15",
        ],
        [
            'title'     => 'COMPLETED',
            'value'     => $completedTasks,
            'color'     => 'emerald',
            "icon"      => "../assets/done.svg",
            "iconClass" => "w-15",
        ],
        [
            'title'     => 'IN PROGRESS',
            'value'     => $inProgress,
            'color'     => 'red',
            "icon"      => "../assets/progress.svg",
            "iconClass" => "w-15",
        ],
    ],
    ];

    $colorStyles = [
    'blue'    => [
        'badge' => 'bg-blue-100 text-blue-700 ',
        'hover' => 'hover:border-blue-300 cursor-pointer',
    ],
    'amber'   => [
        'badge' => 'bg-amber-100 text-amber-700 ',
        'hover' => 'hover:border-amber-300 cursor-pointer',
    ],
    'emerald' => [
        'badge' => 'bg-emerald-100 text-emerald-700 ',
        'hover' => 'hover:border-emerald-300 cursor-pointer',
    ],
    'red'     => [
        'badge' => 'bg-red-100 text-red-700 ',
        'hover' => 'hover:border-red-300 cursor-pointer',
    ],
    ];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Overview todos</title>
</head>

<body class="bg-slate-50 min-h-screen w-full flex  flex-col justify-between font-sans antialiased">

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col min-h-screen">

        <?php require_once "../partials/navbar.php"?>

        <main class="my-8 w-full bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-8">

            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    <?php echo htmlspecialchars($dashboardData['title']); ?>
                </h1>
                <p class="text-slate-500 mt-1 text-sm sm:text-base">
                    <?php echo htmlspecialchars($dashboardData['subtitle']); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-6">
                    <?php foreach ($dashboardData['stats'] as $stat):
                            $style = $colorStyles[$stat['color']] ?? $colorStyles['blue'];
                    ?>
                        <div class="relative bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md <?php echo $style['hover']; ?> flex flex-col items-center text-center justify-center">

                            <div class="custom-icon-container mb-2">
                                <img class="<?php echo $stat['iconClass'] ?>" src="<?php echo $stat['icon'] ?>" alt="">
                            </div>

                            <span class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-1">
                                <?php echo htmlspecialchars($stat['title']); ?>
                            </span>

                            <span class="text-3xl font-extrabold text-slate-800 my-1">
                                <?php echo number_format($stat['value']); ?>
                            </span>

                            <?php if (! empty($stat['badge'])): ?>
                                <span class="mt-3 inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full <?php echo $style['badge']; ?>">
                                    <?php echo htmlspecialchars($stat['badge']); ?>
                                </span>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>


            </div>
            <div class="lg:col-span-1 bg-slate-50 rounded-xl border border-slate-200/80 p-6 flex flex-col items-center justify-center text-center shadow-sm">
                <span class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4">
                    Completion Rate
                </span>

                <div class="relative size-36">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-200" stroke-width="3"></circle>
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-indigo-600 transition-all duration-1000 ease-out" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="<?php echo $dashOffset; ?>" stroke-linecap="round"></circle>
                    </svg>

                    <div class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 flex flex-col items-center">
                        <span class="text-2xl font-extrabold text-slate-800">
                            <?php echo htmlspecialchars($completionRate); ?>%
                        </span>
                    </div>
                </div>
            </div>
            <section>
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50/80 text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Task</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                    <th class="px-6 py-3 font-semibold">Created At</th>
                                    <th class="px-6 py-3 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (! empty($allTasks)): ?>
                                    <?php foreach ($allTasks as $task): ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900"><?php echo htmlspecialchars($task['title']) ?></div>
                                                <?php if (! empty($task['description'])): ?>
                                                    <div class="text-xs text-slate-400 mt-0.5"><?php echo htmlspecialchars($task['description']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if ($task['status'] === 'completed'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                        Completed
                                                    </span>
                                                <?php elseif ($task['status'] === 'in_progress'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                                        In Progress
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                        Pending
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                                <?php echo date('M d, Y H:i', strtotime($task['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">

                                                    <form action="" method="POST" class="inline-block">
                                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                        <input type="hidden" name="action" value="update_status">
                                                        <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                                                            <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                                            <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        </select>
                                                    </form>

                                                    <form action="" method="POST" class="inline-block">
                                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                        <input type="hidden" name="action" value="delete_task">
                                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Task">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">
                                            No tasks found yet. Start by adding one!
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

        <?php require_once "../partials/footer.php"?>

    </div>
</div>

    <script src="../js/index.js"></script>
</body>

</html>