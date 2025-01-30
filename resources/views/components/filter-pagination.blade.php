@props(['perPage'])

<select name="perPage" 
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        onchange="this.form.submit()">
    @foreach ([10, 20, 50, 100] as $value)
        <option value="{{ $value }}" {{ $perPage == $value ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select> 