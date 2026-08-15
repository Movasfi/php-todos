<?php

    session_start();
    require_once "../db.inc.php";
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

        </main>

        <?php require_once "../partials/footer.php"?>

    </div>
</body>

</html>