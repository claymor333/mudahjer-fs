<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <label for="create-quiz-modal" class="btn btn-primary">Create New Quiz</label>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 shadow-xl rounded-box">
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
                            @foreach($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->title }}</td>
                                <td>{{ Str::limit($quiz->description, 50) }}</td>
                                <td>{{ $quiz->questions->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm">Edit</button>
                                        <button class="btn btn-sm btn-error">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $quizzes->links() }}
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
</x-app-layout>