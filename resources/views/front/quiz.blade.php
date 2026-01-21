<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4">
            <div class="mb-8 flex justify-between">
                <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" class="text-gray-500">Batal</a>
            </div>

            <form action="{{ route('front.quiz.submit', [$course->slug, $lesson->id]) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    @foreach($questions as $index => $q)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                            <div class="flex gap-4 mb-4">
                                <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">{{ $index + 1 }}</span>
                                <div class="text-lg font-medium">{{ $q->question_text }}</div>
                            </div>
                            <div class="ml-12">
                                @if($q->type == 'multiple_choice')
                                    <div class="space-y-3">
                                        @foreach($q->options as $key => $val)
                                            <label class="flex items-center p-3 border rounded-xl hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" class="w-4 h-4 text-indigo-600" required>
                                                <span class="ml-3 font-bold mr-2">{{ $key }}.</span> {{ $val }}
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <textarea name="answers[{{ $q->id }}]" rows="4" class="w-full border-gray-300 rounded-xl" required></textarea>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700" onclick="return confirm('Kirim jawaban?')">Kirim Jawaban</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>