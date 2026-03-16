<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Ticket: {{ $ticket->ticket_number }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" value="{{ old('title', $ticket->title) }}" class="mt-1 block w-full rounded-md border-gray-300" required>
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $ticket->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority</label>
                                <select name="priority" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    @foreach(['Low', 'Medium', 'High', 'Critical'] as $p)
                                        <option value="{{ $p }}" {{ old('priority', $ticket->priority) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300">
                                    @foreach(['Open', 'In Progress', 'Resolved', 'Closed'] as $s)
                                        <option value="{{ $s }}" {{ old('status', $ticket->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Assign To</label>
                                <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Unassigned</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}" {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Resolution Note</label>
                            <textarea name="resolution_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('resolution_note', $ticket->resolution_note) }}</textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('tickets.show', $ticket) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                            <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Update Ticket</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
