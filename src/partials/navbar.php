<header class="flex w-full justify-between items-center p-3 gap-4 bg-navbar border-b ">

    <div class="text-headline-sm font-headline-sm text-blue-500">
        FocusFlow
    </div>


    <div class="hidden  w-full sm:flex sm:justify-center sm:items-center">
        <nav>
            <ul class="list-none flex space-x-3">
                <li class="cursor-pointer hover:underline hover:underline-offset-2"><a href="../views/index.php">Tasks</a></li>
                <li class="cursor-pointer hover:underline hover:underline-offset-2"><a href="../views/overviewTodos.php">Overview</a></li>
                <li class="cursor-pointer hover:underline hover:underline-offset-2"><a href="../views/logout.php">Log out</a></li>
            </ul>
        </nav>

    </div>

    <div class="hidden  w-fit sm:flex sm:justify-center sm:items-center">
        <button>
            <span class="">
                <a class="" href="../views/profile.php">
                    <img class="w-11 h-fit" src="../assets/profile.svg" alt="">
                </a>
            </span>
        </button>
    </div>

    <span class="cursor-pointer menu sm:hidden ">
        <img src="../assets/menu.svg" alt="" class="w-8 h-fit">
    </span>
</header>

<div id="menu-container" class="w-full sm:hidden bg-navbar ">
    <ul class=" navbar-list flex-col p-4 space-y-3 transition-all duration-300 ease-in-out max-h-0 opacity-0 -translate-y-2 pointer-events-none">
        <li>
            <a href="../views/index.php" class=" font-medium hover:text-blue-500 transition-colors">Tasks</a>
        </li>
        <li>
            <a href="../views/overviewTodos.php" class=" font-medium hover:text-blue-500 transition-colors">Overview</a>
        </li>
        <li class=" font-medium hover:text-blue-500 transition-colors"><a href="../views/logout.php">Log out</a></li>
        <li class="pt-2 border-t border-slate-200 dark:border-slate-700">
            <a class="flex items-center gap-3 hover:text-blue-500 transition-colors" href="../views/profile.php">
                <img class="w-9 h-9 rounded-full object-cover" src="../assets/profile.svg" alt="Profile">
                <span class="font-medium">Profile</span>
            </a>
        </li>
    </ul>
</div>
