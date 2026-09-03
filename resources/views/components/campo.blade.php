@props(['label', 'name', 'type' => 'text', 'value' => ''])

<label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
    {{ $label }}
</label>

@if ($type === 'textarea')
    <textarea id="{{ $name }}" name="{{ $name }}" rows="4"
              class="w-full rounded-lg border px-3 py-2 outline-none @error($name) border-red-500 focus:ring-red-200 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @enderror">{{ old($name, $value) }}</textarea>
@else
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
           class="w-full rounded-lg border px-3 py-2 outline-none @error($name) border-red-500 focus:ring-red-200 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @enderror">
@endif

@error($name)
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
@enderror
