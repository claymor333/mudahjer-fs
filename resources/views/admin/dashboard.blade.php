<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">Create New Quiz</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-200 shadow-xl rounded-box border border-base-300">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Questions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quizzes as $quiz)
                            <form id="delete-quiz-form-{{ $quiz->id }}" 
                                action="{{ route('admin.quizzes.delete', $quiz->id) }}" 
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <tr class="hover:bg-base-200 dark:hover:bg-base-300">
                                <td>{{ $quiz->title }}</td>
                                <td>{{ Str::limit($quiz->description, 50) }}</td>
                                <td>{{ $quiz->questions->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-warning" href="{{ route('admin.quizzes.edit', $quiz->id) }}">Edit</a>
                                        <a class="btn btn-sm btn-error" href="#" onclick="confirmDelete( {{ $quiz->id }} )">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500">
                                    No quizzes found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    <x-daisy-pagination :paginator="$quizzes" />
                </div>
            </div>
        </div>
    </div>

    <!-- Create Quiz Modal -->
    <input type="checkbox" id="create-quiz-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Create New Quiz</h3>
            <form action="{{ route('admin.quizzes.store') }}" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Quiz Title</span>
                    </label>
                    <input type="text" name="title" class="input input-bordered" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered" required></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <label for="create-quiz-modal" class="btn">Cancel</label>
                </div>
            </form>
        </div>
    </div>
        
    @push('scripts')
        <script>
            function confirmDelete(quizId) {
                if (confirm('Are you sure you want to delete this quiz? This action cannot be undone.')) {
                    document.getElementById(`delete-quiz-form-${quizId}`).submit();
                }
            }
        </script>
    @endpush

</x-app-layout>