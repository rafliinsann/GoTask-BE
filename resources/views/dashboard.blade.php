<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div class="flex">
        <aside class="w-1/4 bg-gray-200 p-4">
            <h2>Sidebar</h2>
            <input type="text" placeholder="Search..." class="mb-4">
            <ul>
                <li>Boards</li>
                <li>Members</li>
                <li>Workspace Settings</li>
            </ul>
            <button class="bg-blue-500 text-white p-2">Create</button>
        </aside>
        <main class="w-3/4 p-4">
            <h1>Dashboard</h1>
            <div>
                <h2>Create New Board</h2>
                <form id="create-board-form">
                    <input type="text" name="name" placeholder="Board Name" required>
                    <button type="submit">Create Board</button>
                </form>
            </div>
            <div class="grid grid-cols-5 gap-4">

                <div id="project-resources" class="bg-gray-300 p-4">
                    <h2>Project Resources</h2>
                    <div class="item" data-id="1">Data 1</div>
                    <div class="item" data-id="2">Data 2</div>
                    <div class="item" data-id="3">Data 3</div>
                    <form class="create-card-form">
                        <input type="text" name="title" placeholder="Card Title" required>
                        <input type="hidden" name="board_id" value="1"> <!-- Ganti dengan ID board yang sesuai -->
                        <button type="submit">Add Card</button>
                    </form>
                </div>
                <div id="to-do" class="bg-gray-300 p-4">
                    <h2>To Do</h2>
                    <!-- Card content here -->
                </div>
                <div id="pending" class="bg-gray-300 p-4">
                    <h2>Pending</h2>
                    <!-- Card content here -->
                </div>
                <div id="blocked" class="bg-gray-300 p-4">
                    <h2>Blocked</h2>
                    <!-- Card content here -->
                </div>
                <div id="done" class="bg-gray-300 p-4">
                    <h2>Done</h2>
                    <!-- Card content here -->
                </div>
            </div>
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = {
                group: 'shared',
                animation: 150,
                onEnd: function (evt) {
                    console.log('Item moved from ' + evt.from.id + ' to ' + evt.to.id);
                }
            };

            const projectResources = document.getElementById('project-resources');
            const toDo = document.getElementById('to-do');
            const pending = document.getElementById('pending');
            const blocked = document.getElementById('blocked');
            const done = document.getElementById('done');

            new Sortable(projectResources, options);
            new Sortable(toDo, options);
            new Sortable(pending, options);
            new Sortable(blocked, options);
            new Sortable(done, options);
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            group: 'shared',
            animation: 150,
            onEnd: function (evt) {
                const cardId = evt.item.dataset.id; // Ambil ID dari data-id
                const newStatus = evt.to.id; // ID dari card tujuan

                // Kirim permintaan AJAX untuk memperbarui status
                fetch('{{ route('update.card') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        card_id: cardId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Card updated successfully');
                    } else {
                        console.error('Failed to update card');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        };

        const projectResources = document.getElementById('project-resources');
        const toDo = document.getElementById('to-do');
        const pending = document.getElementById('pending');
        const blocked = document.getElementById('blocked');
        const done = document.getElementById('done');

        new Sortable(projectResources, options);
        new Sortable(toDo, options);
        new Sortable(pending, options);
        new Sortable(blocked, options);
        new Sortable(done, options);
    });
</script>
<script>
    document.getElementById('create-board-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        fetch('{{ route('create.board') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tambahkan board baru ke tampilan
                const newBoard = document.createElement('div');
                newBoard.className = 'bg-gray-300 p-4';
                newBoard.innerHTML = `<h2>${data.board.name}</h2>`;
                document.querySelector('.grid').appendChild(newBoard);
                console.log('Board created successfully');
            } else {
                console.error('Failed to create board');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</body>
</html>
